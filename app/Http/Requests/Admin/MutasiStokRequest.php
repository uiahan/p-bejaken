<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MutasiStokRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->punyaRole(
            User::ROLE_ADMIN,
            User::ROLE_SUPERADMIN
        );
    }

    public function rules(): array
    {
        return [
            'produk_id' => [
                'required',
                'integer',
                Rule::exists('produks', 'id'),
            ],
            'jenis' => [
                'required',
                Rule::in(['masuk', 'keluar']),
            ],
            'jumlah' => ['required', 'integer', 'min:1', 'max:2147483647'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'produk_id.required' => 'Pilih produk terlebih dahulu.',
            'produk_id.exists' => 'Produk yang dipilih tidak ditemukan.',
            'jenis.required' => 'Pilih jenis mutasi.',
            'jenis.in' => 'Jenis mutasi harus masuk atau keluar.',
            'jumlah.required' => 'Jumlah mutasi wajib diisi.',
            'jumlah.min' => 'Jumlah mutasi minimal 1.',
            'tanggal.required' => 'Tanggal mutasi wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal mutasi tidak boleh melebihi hari ini.',
        ];
    }
}