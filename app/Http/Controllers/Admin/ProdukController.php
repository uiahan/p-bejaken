<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProdukRequest;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProdukController extends Controller
{
    private const PER_HALAMAN = 10;

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));
        $status = $request->query('status');

        if (! in_array($status, ['aktif', 'nonaktif'], true)) {
            $status = null;
        }

        $daftar = Produk::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $like = '%' . addcslashes($q, '%_\\') . '%';

                $query->where(function ($sub) use ($like) {
                    $sub->where('nama_produk', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate(self::PER_HALAMAN)
            ->withQueryString();

        return view('features.admin.produk.index', compact(
            'daftar',
            'q',
            'status'
        ));
    }

    public function create(): View
    {
        return view('features.admin.produk.create', [
            'produk' => new Produk(),
        ]);
    }

    public function store(ProdukRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Stok awal ditetapkan melalui mutasi agar riwayat stok konsisten.
        $stokAwal = (int) $data['stok'];
        unset($data['stok']);

        $gambarBaru = null;

        try {
            if ($request->hasFile('gambar')) {
                $gambarBaru = $request->file('gambar')
                    ->store('produk', 'public');

                $data['gambar'] = $gambarBaru;
            }

            $produk = DB::transaction(function () use (
                $data,
                $stokAwal
            ) {
                $produk = Produk::create([
                    ...$data,
                    'stok' => 0,
                ]);

                if ($stokAwal > 0) {
                    $produk->mutasiStoks()->create([
                        'jenis' => 'masuk',
                        'jumlah' => $stokAwal,
                        'tanggal' => now()->toDateString(),
                        'user_id' => auth()->id(),
                    ]);

                    $produk->stok = $stokAwal;
                    $produk->save();
                }

                return $produk;
            });
        } catch (\Throwable $e) {
            if ($gambarBaru) {
                Storage::disk('public')->delete($gambarBaru);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.produk.index')
            ->with('toast', [
                'type' => 'success',
                'message' => "Produk {$produk->nama_produk} berhasil ditambahkan.",
            ]);
    }

    public function show(Produk $produk): View
    {
        $riwayat = $produk->mutasiStoks()
            ->with('user')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10);

        return view('features.admin.produk.show', compact(
            'produk',
            'riwayat'
        ));
    }

    public function edit(Produk $produk): View
    {
        return view('features.admin.produk.edit', compact('produk'));
    }

    public function update(
        ProdukRequest $request,
        Produk $produk
    ): RedirectResponse {
        $data = $request->validated();

        // Stok tidak boleh diubah langsung lewat form edit.
        unset($data['stok']);

        $gambarLama = $produk->gambar;
        $gambarBaru = null;

        if ($request->hasFile('gambar')) {
            $gambarBaru = $request->file('gambar')
                ->store('produk', 'public');

            $data['gambar'] = $gambarBaru;
        } else {
            unset($data['gambar']);
        }

        try {
            $produk->update($data);
        } catch (\Throwable $e) {
            if ($gambarBaru) {
                Storage::disk('public')->delete($gambarBaru);
            }

            throw $e;
        }

        if ($gambarBaru && $gambarLama) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()
            ->route('admin.produk.index')
            ->with('toast', [
                'type' => 'success',
                'message' => "Produk {$produk->nama_produk} berhasil diperbarui.",
            ]);
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        try {
            DB::transaction(function () use ($produk) {
                $produk = Produk::query()
                    ->lockForUpdate()
                    ->findOrFail($produk->id);

                if ($produk->mutasiStoks()->exists()) {
                    throw ValidationException::withMessages([
                        'produk' => 'Produk yang memiliki riwayat mutasi tidak dapat dihapus.',
                    ]);
                }

                $produk->delete();
            });
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin.produk.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ]);
        }

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        return redirect()
            ->route('admin.produk.index')
            ->with('toast', [
                'type' => 'success',
                'message' => "Produk {$produk->nama_produk} berhasil dihapus.",
            ]);
    }
}