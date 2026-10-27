<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const ZONA = 'Asia/Jakarta';

    public function index(Request $request)
    {
        $sekarang = now(self::ZONA)->locale('id');
        $jam = $sekarang->hour;

        $sapaan = match (true) {
            $jam < 11 => 'Selamat pagi',
            $jam < 15 => 'Selamat siang',
            $jam < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };

        $pelanggan = User::where('role', User::ROLE_PELANGGAN);
        $totalPelanggan = (clone $pelanggan)->count();
        $pelangganBaru = (clone $pelanggan)->where('created_at', '>=', now()->subDays(7))->count();

        $terbaru = (clone $pelanggan)
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'no_hp', 'created_at'])
            ->map(fn (User $u) => [
                'nama' => $u->name,
                'email' => $u->email,
                'hp' => str_starts_with((string) $u->no_hp, '62') ? '0' . substr($u->no_hp, 2) : $u->no_hp,
                'daftar' => $u->created_at->timezone(self::ZONA)->locale('id')->translatedFormat('j M Y'),
            ]);

        $kartu = [
            ['label' => 'Pelanggan', 'nilai' => $totalPelanggan, 'prefix' => '', 'latar' => '#F4CD72',
                'ket' => $pelangganBaru > 0 ? "+{$pelangganBaru} dalam 7 hari terakhir" : 'Belum ada yang baru minggu ini'],
            ['label' => 'Pesanan masuk', 'nilai' => null, 'prefix' => '', 'latar' => '#F1C7A4',
                'ket' => 'Tampil setelah modul pesanan dibuat'],
            ['label' => 'Pendapatan bulan ini', 'nilai' => null, 'prefix' => 'Rp', 'latar' => '#E8DCC3',
                'ket' => 'Tampil setelah modul transaksi dibuat'],
            ['label' => 'Stok menipis', 'nilai' => null, 'prefix' => '', 'latar' => '#F2B8B2',
                'ket' => 'Tampil setelah modul stok dibuat'],
        ];

        return view('features.admin.dashboard.index', [
            'sapaan' => $sapaan,
            'namaDepan' => explode(' ', trim($request->user()->name))[0],
            'tanggal' => $sekarang->translatedFormat('l, j F Y'),
            'kartu' => $kartu,
            'pelangganTerbaru' => $terbaru,
        ]);
    }
}