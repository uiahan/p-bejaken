<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class TokoController extends Controller
{
    public function index()
    {
        // Hanya produk berstatus aktif. Yang stoknya habis tetap tampil (di bagian akhir) dengan label "Stok habis".
        $katalog = Produk::aktif()
            ->orderByRaw('stok <= 0')
            ->orderBy('id')
            ->get();

        // Chip filter hanya untuk kebutuhan yang memang dimiliki minimal satu produk.
        $filter = ['semua' => 'Semua'];
        foreach (Produk::LABEL_TAG as $kunci => $label) {
            if ($katalog->contains(fn (Produk $p) => in_array($kunci, $p->tags, true))) {
                $filter[$kunci] = $label;
            }
        }

        return view('features.pelanggan.toko.index', [
            'katalog' => $katalog,
            'filter' => $filter,
        ]);
    }
}