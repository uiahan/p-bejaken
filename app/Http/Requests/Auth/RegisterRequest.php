<?php

namespace App\Http\Requests\Auth;

use App\Support\NomorHp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rapikan input sebelum divalidasi.
     * Nomor HP disimpan dalam format 62xxxxxxxxxx (siap dipakai untuk link WhatsApp).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim(preg_replace('/\s+/', ' ', (string) $this->input('name'))),
            'email' => Str::lower(trim((string) $this->input('email'))),
            'phone' => NomorHp::normalisasi((string) $this->input('phone')),
        ]);
    }

    public function rules(): array
    {
        $password = Password::min(8)->letters()->numbers();
        if (app()->isProduction()) {
            // Tolak password yang pernah bocor (memanggil API haveibeenpwned).
            $password->uncompromised();
        }

        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^628[0-9]{8,12}$/'],
            'password' => ['required', 'string', 'confirmed', $password],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama terlalu pendek.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'email.unique' => 'Email ini sudah dipakai.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp tidak valid. Contoh: 081234567890.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password belum sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus memuat huruf.',
            'password.numbers' => 'Password harus memuat angka.',
            'password.uncompromised' => 'Password ini pernah bocor di internet. Pakai yang lain.',
        ];
    }
}