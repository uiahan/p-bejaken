<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MutasiStokRequest;
use App\Models\MutasiStok;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MutasiStokController extends Controller
{
    private const PER_HALAMAN = 10;

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $jenis = $request->query('jenis');

        if (! in_array($jenis, ['masuk', 'keluar'], true)) {
            $jenis = null;
        }

        $daftar = MutasiStok::query()
            ->with(['produk', 'user'])
            ->when($jenis, fn ($query) => $query->where('jenis', $jenis))
            ->when($q !== '', function ($query) use ($q) {
                $like = '%' . addcslashes($q, '%_\\') . '%';

                $query->whereHas('produk', fn ($produk) =>
                    $produk->where('nama_produk', 'like', $like)
                );
            })
            ->latest('tanggal')
            ->latest('id')
            ->paginate(self::PER_HALAMAN)
            ->withQueryString();

        return view('features.admin.mutasi-stok.index', compact(
            'daftar',
            'q',
            'jenis'
        ));
    }

    public function create(): View
    {
        return view('features.admin.mutasi-stok.create', [
            'mutasi' => new MutasiStok(),
            'produkList' => Produk::query()
                ->orderBy('nama_produk')
                ->get(),
        ]);
    }

    public function store(MutasiStokRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $produk = Produk::query()
                ->lockForUpdate()
                ->findOrFail($data['produk_id']);

            $stokBaru = $this->hitungStok(
                $produk->stok,
                $data['jenis'],
                (int) $data['jumlah']
            );

            $produk->update(['stok' => $stokBaru]);

            $produk->mutasiStoks()->create([
                'jenis' => $data['jenis'],
                'jumlah' => $data['jumlah'],
                'tanggal' => $data['tanggal'],
                'user_id' => $request->user()->id,
            ]);
        });

        return redirect()
            ->route('admin.mutasi-stok.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Mutasi stok berhasil ditambahkan.',
            ]);
    }

    public function show(MutasiStok $mutasi_stok): View
    {
        $mutasi_stok->load(['produk', 'user']);

        return view('features.admin.mutasi-stok.show', [
            'mutasi' => $mutasi_stok,
        ]);
    }

    public function edit(MutasiStok $mutasi_stok): View
    {
        return view('features.admin.mutasi-stok.edit', [
            'mutasi' => $mutasi_stok,
            'produkList' => Produk::query()
                ->orderBy('nama_produk')
                ->get(),
        ]);
    }

    public function update(
        MutasiStokRequest $request,
        MutasiStok $mutasi_stok
    ): RedirectResponse {
        $data = $request->validated();

        DB::transaction(function () use ($data, $mutasi_stok) {
            $mutasi = MutasiStok::query()
                ->lockForUpdate()
                ->findOrFail($mutasi_stok->id);

            $ids = collect([
                $mutasi->produk_id,
                (int) $data['produk_id'],
            ])->unique()->sort()->values();

            $produks = Produk::query()
                ->whereIn('id', $ids)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $produkLama = $produks->get($mutasi->produk_id);
            $produkBaru = $produks->get((int) $data['produk_id']);

            if (! $produkLama || ! $produkBaru) {
                throw ValidationException::withMessages([
                    'produk_id' => 'Produk tidak ditemukan.',
                ]);
            }

            // Batalkan efek mutasi lama.
            $stokLama = $this->hitungStok(
                $produkLama->stok,
                $mutasi->jenis === 'masuk' ? 'keluar' : 'masuk',
                (int) $mutasi->jumlah
            );

            $produkLama->stok = $stokLama;

            // Terapkan mutasi baru ke produk tujuan.
            $produkBaru->stok = $this->hitungStok(
                $produkBaru->stok,
                $data['jenis'],
                (int) $data['jumlah']
            );

            if ($produkLama->is($produkBaru)) {
                $produkLama->save();
            } else {
                $produkLama->save();
                $produkBaru->save();
            }

            $mutasi->update([
                'produk_id' => $produkBaru->id,
                'jenis' => $data['jenis'],
                'jumlah' => $data['jumlah'],
                'tanggal' => $data['tanggal'],
            ]);
        });

        return redirect()
            ->route('admin.mutasi-stok.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Mutasi stok berhasil diperbarui.',
            ]);
    }

    public function destroy(MutasiStok $mutasi_stok): RedirectResponse
    {
        DB::transaction(function () use ($mutasi_stok) {
            $mutasi = MutasiStok::query()
                ->lockForUpdate()
                ->findOrFail($mutasi_stok->id);

            $produk = Produk::query()
                ->lockForUpdate()
                ->findOrFail($mutasi->produk_id);

            // Menghapus mutasi berarti membalik efeknya.
            $produk->stok = $this->hitungStok(
                $produk->stok,
                $mutasi->jenis === 'masuk' ? 'keluar' : 'masuk',
                (int) $mutasi->jumlah
            );

            $produk->save();
            $mutasi->delete();
        });

        return redirect()
            ->route('admin.mutasi-stok.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Mutasi stok berhasil dihapus dan stok telah dikoreksi.',
            ]);
    }

    private function hitungStok(
        int $stokSaatIni,
        string $jenis,
        int $jumlah
    ): int {
        $stokBaru = $jenis === 'masuk'
            ? $stokSaatIni + $jumlah
            : $stokSaatIni - $jumlah;

        if ($stokBaru < 0) {
            throw ValidationException::withMessages([
                'jumlah' => 'Stok tidak mencukupi untuk mutasi keluar.',
            ]);
        }

        return $stokBaru;
    }
}