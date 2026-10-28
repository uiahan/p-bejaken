<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && in_array(auth()->user()->role, ['admin', 'superadmin'], true);
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'pesanan',
                    'diantar',
                    'pengambilan',
                    'selesai',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status transaksi wajib dipilih.',
            'status.in' => 'Status transaksi tidak valid.',
        ];
    }
}