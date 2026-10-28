<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateTransaksiRequest;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function index(): View
    {
        $query = Transaksi::query()
            ->with('user')
            ->where('status', '!=', 'keranjang');

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('q')) {
            $q = trim(request('q'));

            $query->where(function ($builder) use ($q) {
                $builder->where('id', $q)
                    ->orWhereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $transaksis = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('features.admin.transaksi.index', compact('transaksis'));
    }

    public function show(Transaksi $transaksi): View
    {
        abort_if($transaksi->status === 'keranjang', 404);

        $transaksi->load([
            'user',
            'detailTransaksis.produk',
            'pengirimanData',
        ]);

        return view('features.admin.transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi): View
    {
        abort_if($transaksi->status === 'keranjang', 404);

        $transaksi->load([
            'user',
            'detailTransaksis.produk',
            'pengirimanData',
        ]);

        return view('features.admin.transaksi.edit', compact('transaksi'));
    }

    public function update(
        UpdateTransaksiRequest $request,
        Transaksi $transaksi
    ): RedirectResponse {
        abort_if($transaksi->status === 'keranjang', 404);

        $statusBaru = $request->validated('status');

        DB::transaction(function () use ($transaksi, $statusBaru) {
            $transaksi = Transaksi::query()
                ->whereKey($transaksi->id)
                ->lockForUpdate()
                ->with('detailTransaksis.produk')
                ->firstOrFail();

            $statusLama = $transaksi->status;

            $transisiDiizinkan = [
                'pesanan' => ['diantar', 'pengambilan'],
                'diantar' => ['selesai'],
                'pengambilan' => ['selesai'],
            ];

            if (! in_array(
                $statusBaru,
                $transisiDiizinkan[$statusLama] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status tersebut tidak diizinkan.',
                ]);
            }

            if ($statusBaru === 'diantar') {
                if ($transaksi->pengiriman !== 'diantar') {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan ini menggunakan pengambilan sendiri.',
                    ]);
                }

                $pengiriman = $transaksi->pengirimanData;

                if (! $pengiriman) {
                    throw ValidationException::withMessages([
                        'status' => 'Data pengiriman belum tersedia.',
                    ]);
                }

                if (
                    $pengiriman->metode === 'Belum ditentukan'
                    || $pengiriman->ongkir < 0
                    || ! $pengiriman->alamat_tujuan
                ) {
                    throw ValidationException::withMessages([
                        'status' => 'Lengkapi data pengiriman sebelum memproses pesanan.',
                    ]);
                }

                $pengiriman->update([
                    'status' => 'diproses',
                ]);
            }

            if ($statusBaru === 'pengambilan') {
                if ($transaksi->pengiriman !== 'ambil_sendiri') {
                    throw ValidationException::withMessages([
                        'status' => 'Pesanan ini menggunakan layanan pengiriman.',
                    ]);
                }
            }

            // QRIS wajib memiliki bukti sebelum pesanan diproses.
            // Konfirmasi keabsahan pembayaran tetap dilakukan oleh admin.
            if (
                in_array($statusBaru, ['diantar', 'pengambilan'], true)
                && $transaksi->metode_bayar === 'qris'
                && ! $transaksi->bukti_bayar
            ) {
                throw ValidationException::withMessages([
                    'status' => 'Bukti pembayaran QRIS belum tersedia.',
                ]);
            }

            // Kurangi stok hanya ketika pesanan pertama kali diproses.
            // Penguncian transaksi dan validasi transisi mencegah
            // pengurangan stok berulang pada transisi normal.
            if (in_array($statusBaru, ['diantar', 'pengambilan'], true)) {
                foreach ($transaksi->detailTransaksis as $detail) {
                    $produk = $detail->produk;

                    if (! $produk) {
                        throw ValidationException::withMessages([
                            'status' => 'Ada produk dalam transaksi yang tidak ditemukan.',
                        ]);
                    }

                    if ($produk->stok < $detail->jumlah) {
                        throw ValidationException::withMessages([
                            'status' => "Stok {$produk->nama_produk} tidak mencukupi.",
                        ]);
                    }
                }

                foreach ($transaksi->detailTransaksis as $detail) {
                    $produk = $detail->produk;

                    $produk->decrement('stok', $detail->jumlah);
                }
            }

            if ($statusBaru === 'selesai') {
                if (
                    $transaksi->metode_bayar === 'qris'
                    && ! $transaksi->bukti_bayar
                ) {
                    throw ValidationException::withMessages([
                        'status' => 'Bukti pembayaran QRIS belum tersedia.',
                    ]);
                }

                if ($transaksi->pengiriman === 'diantar') {
                    $pengiriman = $transaksi->pengirimanData;

                    if (! $pengiriman || $pengiriman->status !== 'diterima') {
                        throw ValidationException::withMessages([
                            'status' => 'Pengiriman harus ditandai diterima sebelum transaksi selesai.',
                        ]);
                    }
                }
            }

            $transaksi->update([
                'status' => $statusBaru,
            ]);
        });

        return redirect()
            ->route('admin.transaksi.show', $transaksi)
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        abort_if($transaksi->status === 'keranjang', 404);

        if (in_array($transaksi->status, ['diantar', 'pengambilan', 'selesai'], true)) {
            return back()->with(
                'error',
                'Transaksi yang sudah diproses tidak boleh dihapus agar riwayat stok tetap terjaga.'
            );
        }

        $transaksi->delete();

        return redirect()
            ->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}