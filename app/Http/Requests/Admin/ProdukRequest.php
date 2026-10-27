<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdukRequest extends FormRequest
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
            'nama_produk' => ['required', 'string', 'min:2', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'harga' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
           'stok' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'khasiat' => ['required', 'string', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'deskripsi.required' => 'Deskripsi produk wajib diisi.',
            'harga.required' => 'Harga produk wajib diisi.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'status.in' => 'Status produk tidak valid.',
            'khasiat.required' => 'Informasi khasiat wajib diisi.',
        ];
    }
}