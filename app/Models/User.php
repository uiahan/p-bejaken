<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Support\NomorHp;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'alamat', 'no_hp'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_PELANGGAN = 'pelanggan';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPERADMIN = 'superadmin';

    /** Role yang boleh masuk dashboard. */
    public const ROLE_STAF = [self::ROLE_ADMIN, self::ROLE_SUPERADMIN];

    /** Semua role yang dikenal sistem, beserta namanya untuk ditampilkan. */
    public const LABEL_ROLE = [
        self::ROLE_PELANGGAN => 'Pelanggan',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SUPERADMIN => 'Super Admin',
    ];

    public function mutasiStoks()
    {
        return $this->hasMany(MutasiStok::class);
    }

    /** Cocokkan role (tidak peka huruf besar/kecil). */
    public function punyaRole(string ...$roles): bool
    {
        return in_array(strtolower((string) $this->role), array_map('strtolower', $roles), true);
    }

    /** Admin atau superadmin. */
    public function isStaf(): bool
    {
        return $this->punyaRole(...self::ROLE_STAF);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'users_id');
    }

    /** $user->peran_label : "Super Admin" */
    protected function peranLabel(): Attribute
    {
        return Attribute::get(fn () => self::LABEL_ROLE[strtolower((string) $this->role)] ?? ucfirst((string) $this->role));
    }

    /** $user->hp_tampil : 0812... */
    protected function hpTampil(): Attribute
    {
        return Attribute::get(fn () => NomorHp::tampil($this->no_hp));
    }

    /** $user->tanggal_daftar : 26 Okt 2026 (waktu Jakarta) */
    protected function tanggalDaftar(): Attribute
    {
        return Attribute::get(fn () => $this->created_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j M Y'));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}