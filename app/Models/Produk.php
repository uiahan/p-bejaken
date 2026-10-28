<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Produk extends Model
{
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'gambar',
        'stok',
        'status',
        'khasiat',
    ];

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';
 
    public const LABEL_STATUS = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_NONAKTIF => 'Nonaktif',
    ];
 
    /** Stok sebanyak ini atau kurang dianggap menipis. */
    public const AMBANG_MENIPIS = 5;
 
    /** Warna lembut di belakang foto di toko. Produk baru otomatis memakai salah satunya bergantian. */
    public const LATAR_VARIAN = [
        'sweet-tamarind' => '#F1C7A4',
        'blue-telang' => '#DCC7E3',
        'kunyit-asem-sirih' => '#F4CD72',
        'red-rosella' => '#F2B8B2',
        'beras-kencur' => '#E8DCC3',
        'sereh-jahe' => '#E6D08E',
    ];
 
    /**
     * Filter kebutuhan di toko. Tidak disimpan di database: dibaca dari kata kunci pada kolom khasiat,
     * jadi cukup menulis khasiat dengan jelas (mis. "membantu pencernaan") dan filternya ikut.
     */
    public const LABEL_TAG = [
        'pencernaan' => 'Pencernaan',
        'antioksidan' => 'Antioksidan',
        'lambung-kulit' => 'Lambung dan kulit',
        'hangat' => 'Menghangatkan',
    ];
 
    private const KATA_KUNCI_TAG = [
        'pencernaan' => ['cerna'],
        'antioksidan' => ['antioksidan', 'vitamin c'],
        'lambung-kulit' => ['lambung', 'kulit'],
        'hangat' => ['hangat', 'pegal'],
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok' => 'integer',
        ];
    }
 
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }
 
    public function scopeMenipis($query)
    {
        return $query->where('stok', '<=', self::AMBANG_MENIPIS);
    }
 
    /** $produk->gambar_url : URL publik foto, atau null kalau belum ada. Butuh `php artisan storage:link`. */
    protected function gambarUrl(): Attribute
    {
        return Attribute::get(fn () => $this->gambar ? Storage::disk('public')->url($this->gambar) : null);
    }
 
    /** $produk->harga_rp : Rp15.000 */
    protected function hargaRp(): Attribute
    {
        return Attribute::get(fn () => 'Rp' . number_format((float) $this->harga, 0, ',', '.'));
    }
 
    /** $produk->status_label : Aktif / Nonaktif */
    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => self::LABEL_STATUS[$this->status] ?? ucfirst((string) $this->status));
    }
 
    /** $produk->slug : "kunyit-asem-sirih" (dari nama, dipakai untuk id elemen dan kunci keranjang) */
    protected function slug(): Attribute
    {
        return Attribute::get(fn () => Str::slug((string) $this->nama_produk));
    }
 
    /** $produk->latar : warna pastel latar foto */
    protected function latar(): Attribute
    {
        return Attribute::get(function () {
            $palet = array_values(self::LATAR_VARIAN);
 
            return self::LATAR_VARIAN[$this->slug] ?? $palet[((int) $this->id) % count($palet)];
        });
    }
 
    /** $produk->tags : ['pencernaan', 'hangat', ...] */
    protected function tags(): Attribute
    {
        return Attribute::get(function () {
            $teks = Str::lower((string) $this->khasiat);
 
            return array_values(array_keys(array_filter(
                self::KATA_KUNCI_TAG,
                fn (array $kata) => Str::contains($teks, $kata)
            )));
        });
    }

    public function mutasiStoks(): HasMany
    {
        return $this->hasMany(MutasiStok::class);
    }

    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }
}