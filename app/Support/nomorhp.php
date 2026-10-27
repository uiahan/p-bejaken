<?php

namespace App\Support;

class NomorHp
{
    public static function normalisasi(string $nomor): string
    {
        $nomor = preg_replace('/[^\d+]/', '', $nomor) ?? '';

        if (str_starts_with($nomor, '+62')) {
            return '62' . substr($nomor, 3);
        }
        if (str_starts_with($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }

        return ltrim($nomor, '+');
    }

    public static function tampil(?string $nomor): string
    {
        $nomor = (string) $nomor;

        return str_starts_with($nomor, '62') ? '0' . substr($nomor, 2) : $nomor;
    }
}