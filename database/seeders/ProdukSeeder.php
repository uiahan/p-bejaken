<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Enam varian Cap Jamu Raden.
 *  - Nama, bahan, dan khasiat dari data wawancara.
 *  - Harga & stok hanyalah CONTOH (belum ada di data wawancara). Ubah lewat dashboard admin.
 *  - Foto disalin dari public/images/produk/{slug}-potong.webp ke storage publik,
 *    jadi perlu `php artisan storage:link` agar foto tampil.
 *
 * Aman dijalankan berulang: produk yang sudah ada (berdasarkan nama) tidak ditimpa,
 * jadi perubahan dari dashboard tidak hilang.
 */
class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $varian = [
            ['slug' => 'sweet-tamarind', 'nama' => 'Sweet Tamarind', 'deskripsi' => 'Asam jawa · 250 ml', 'khasiat' => 'Menyegarkan dan membantu pencernaan.', 'stok' => 12],
            ['slug' => 'blue-telang', 'nama' => 'Blue Telang', 'deskripsi' => 'Bunga telang · 250 ml', 'khasiat' => 'Mengandung antioksidan alami (antosianin).', 'stok' => 4],
            ['slug' => 'kunyit-asem-sirih', 'nama' => 'Kunyit Asem Sirih', 'deskripsi' => 'Kunyit, asam jawa, sirih · 250 ml', 'khasiat' => 'Menjaga kesehatan lambung dan kulit. Sirih bersifat antiseptik.', 'stok' => 18],
            ['slug' => 'red-rosella', 'nama' => 'Red Rosella', 'deskripsi' => 'Rosela · 250 ml', 'khasiat' => 'Kaya vitamin C dan antioksidan.', 'stok' => 9],
            ['slug' => 'beras-kencur', 'nama' => 'Beras Kencur', 'deskripsi' => 'Beras, kencur · 250 ml', 'khasiat' => 'Membantu meredakan pegal dan menghangatkan badan.', 'stok' => 15],
            ['slug' => 'sereh-jahe', 'nama' => 'Sereh Jahe', 'deskripsi' => 'Sereh, jahe · 250 ml', 'khasiat' => 'Menghangatkan badan.', 'stok' => 0],
        ];

        foreach ($varian as $v) {
            Produk::firstOrCreate(
                ['nama_produk' => $v['nama']],
                [
                    'deskripsi' => $v['deskripsi'],
                    'khasiat' => $v['khasiat'],
                    'harga' => 15000,
                    'stok' => $v['stok'],
                    'status' => Produk::STATUS_AKTIF,
                    'gambar' => $this->salinFoto($v['slug']),
                ]
            );
        }
    }

    /** Salin foto bawaan ke storage publik. Mengembalikan path-nya, atau null kalau file sumber tidak ada. */
    private function salinFoto(string $slug): ?string
    {
        $sumber = public_path("images/produk/{$slug}-potong.webp");
        if (! is_file($sumber)) {
            return null;
        }

        $tujuan = "produk/{$slug}.webp";
        $disk = Storage::disk('public');
        if (! $disk->exists($tujuan)) {
            $disk->put($tujuan, file_get_contents($sumber));
        }

        return $tujuan;
    }
}