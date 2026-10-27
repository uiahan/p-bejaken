<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Support\NomorHp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Dipakai untuk tambah (POST) dan ubah (PUT) pengguna.
 * Hanya bisa dicapai lewat route yang sudah dijaga role:superadmin.
 */
class PenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->punyaRole(User::ROLE_SUPERADMIN);
    }

    /** Pengguna yang sedang diubah (null saat menambah). */
    private function target(): ?User
    {
        $target = $this->route('pengguna');

        return $target instanceof User ? $target : null;
    }

    private function akunSendiri(): bool
    {
        return $this->target()?->is($this->user()) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $alamat = trim((string) $this->input('alamat'));

        $this->merge([
            'name' => trim(preg_replace('/\s+/', ' ', (string) $this->input('name'))),
            'email' => Str::lower(trim((string) $this->input('email'))),
            'phone' => NomorHp::normalisasi((string) $this->input('phone')),
            'alamat' => $alamat === '' ? null : $alamat,
        ]);
    }

    public function rules(): array
    {
        $target = $this->target();
        $password = Password::min(8)->letters()->numbers();

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($target)],
            'phone' => ['required', 'string', 'regex:/^628[0-9]{8,12}$/'],
            'alamat' => ['nullable', 'string', 'max:255'],
            // Saat menambah, password wajib. Saat mengubah, kosong = tidak diganti.
            'password' => [$target ? 'nullable' : 'required', 'string', 'confirmed', $password],
        ];

        // Role akun sendiri dikunci supaya superadmin tidak bisa menurunkan dirinya
        // (dan sistem tidak pernah kehabisan superadmin).
        if (! $this->akunSendiri()) {
            $rules['role'] = ['required', Rule::in(array_keys(User::LABEL_ROLE))];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama terlalu pendek.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'email.unique' => 'Email ini sudah dipakai pengguna lain.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp tidak valid. Contoh: 081234567890.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
            'role.required' => 'Pilih role pengguna.',
            'role.in' => 'Role tidak dikenal.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password belum sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus memuat huruf.',
            'password.numbers' => 'Password harus memuat angka.',
        ];
    }
}