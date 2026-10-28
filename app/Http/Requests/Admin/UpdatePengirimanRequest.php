<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePengirimanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && in_array(auth()->user()->role, ['admin', 'superadmin'], true);
    }

    public function rules(): array
    {
        return [
            'metode' => [
                'required',
                'string',
                'max:100',
                Rule::notIn(['Belum ditentukan']),
            ],

            'alamat_tujuan' => [
                'required',
                'string',
                'max:255',
            ],

            'ongkir' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'status' => [
                'required',
                Rule::in([
                    'menunggu',
                    'diproses',
                    'dikirim',
                    'diterima',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'metode.required' => 'Metode pengiriman wajib diisi.',
            'alamat_tujuan.required' => 'Alamat tujuan wajib diisi.',
            'ongkir.required' => 'Ongkir wajib diisi.',
            'ongkir.min' => 'Ongkir tidak boleh kurang dari nol.',
            'status.in' => 'Status pengiriman tidak valid.',
        ];
    }
}