<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengiriman extends Model
{
    protected $table = 'pengirimen';

    protected $fillable = [
        'metode',
        'alamat_tujuan',
        'ongkir',
        'status',
        'transaksi_id',
    ];

    protected function casts(): array
    {
        return [
            'ongkir' => 'decimal:2',
        ];
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}