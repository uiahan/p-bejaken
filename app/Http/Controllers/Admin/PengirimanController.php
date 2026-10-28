<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePengirimanRequest;
use App\Models\Pengiriman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PengirimanController extends Controller
{
    public function index(): View
    {
        $query = Pengiriman::query()
            ->with('transaksi.user')
            ->whereHas('transaksi', function ($transaksiQuery) {
                $transaksiQuery->where('status', '!=', 'keranjang')
                    ->where('pengiriman', 'diantar');
            });

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('q')) {
            $q = trim(request('q'));

            $query->where(function ($builder) use ($q) {
                $builder->where('metode', 'like', "%{$q}%")
                    ->orWhere('alamat_tujuan', 'like', "%{$q}%")
                    ->orWhereHas('transaksi', function ($transaksiQuery) use ($q) {
                        $transaksiQuery->where('id', $q)
                            ->orWhereHas('user', function ($userQuery) use ($q) {
                                $userQuery->where('name', 'like', "%{$q}%");
                            });
                    });
            });
        }

        $pengirimen = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('features.admin.pengiriman.index', compact('pengirimen'));
    }

    public function show(Pengiriman $pengiriman): View
    {
        $pengiriman->load([
            'transaksi.user',
            'transaksi.detailTransaksis.produk',
        ]);

        abort_if(
            ! $pengiriman->transaksi
            || $pengiriman->transaksi->pengiriman !== 'diantar'
            || $pengiriman->transaksi->status === 'keranjang',
            404
        );

        return view('features.admin.pengiriman.show', compact('pengiriman'));
    }

    public function edit(Pengiriman $pengiriman): View
    {
        $pengiriman->load('transaksi.user');

        abort_if(
            ! $pengiriman->transaksi
            || $pengiriman->transaksi->pengiriman !== 'diantar'
            || $pengiriman->transaksi->status === 'keranjang',
            404
        );

        return view('features.admin.pengiriman.edit', compact('pengiriman'));
    }

    public function update(
        UpdatePengirimanRequest $request,
        Pengiriman $pengiriman
    ): RedirectResponse {
        $data = $request->validated();

        DB::transaction(function () use ($pengiriman, $data) {
            $pengiriman = Pengiriman::query()
                ->whereKey($pengiriman->id)
                ->lockForUpdate()
                ->with('transaksi')
                ->firstOrFail();

            $transaksi = $pengiriman->transaksi;

            if (
                ! $transaksi
                || $transaksi->pengiriman !== 'diantar'
                || $transaksi->status === 'keranjang'
            ) {
                throw ValidationException::withMessages([
                    'status' => 'Data pengiriman tidak valid.',
                ]);
            }

            $statusDiizinkan = [
                'menunggu' => ['menunggu', 'diproses'],
                'diproses' => ['diproses', 'dikirim'],
                'dikirim' => ['dikirim', 'diterima'],
                'diterima' => ['diterima'],
            ];

            if (! in_array(
                $data['status'],
                $statusDiizinkan[$pengiriman->status] ?? [],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status pengiriman tidak diizinkan.',
                ]);
            }

            $pengiriman->update([
                'metode' => $data['metode'],
                'alamat_tujuan' => $data['alamat_tujuan'],
                'ongkir' => $data['ongkir'],
                'status' => $data['status'],
            ]);

            // Total transaksi = jumlah seluruh subtotal + ongkir.
            $subtotalProduk = $transaksi->detailTransaksis()
                ->sum('subtotal');

            $transaksi->update([
                'total_bayar' => $subtotalProduk + $data['ongkir'],
            ]);
        });

        return redirect()
            ->route('admin.pengiriman.show', $pengiriman)
            ->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    public function destroy(Pengiriman $pengiriman): RedirectResponse
    {
        return back()->with(
            'error',
            'Data pengiriman tidak boleh dihapus langsung. Kelola pengiriman melalui transaksi terkait.'
        );
    }
}