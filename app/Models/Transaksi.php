<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'tgl_transaksi',
        'total_bayar',
        'metode_bayar',
        'bukti_bayar',
        'pengiriman',
        'status',
        'users_id',
    ];

    protected function casts(): array
    {
        return [
            'tgl_transaksi' => 'date',
            'total_bayar' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function pengirimanData(): HasOne
    {
        return $this->hasOne(Pengiriman::class, 'transaksi_id');
    }

    public function isKeranjang(): bool
    {
        return $this->status === 'keranjang';
    }
}