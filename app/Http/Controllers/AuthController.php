<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function indexLogin()
    {
        return view('features.auth.login.index');
    }

    public function indexRegister()
    {
        return view('features.auth.register.index');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $tujuan = $user->isStaf() ? route('admin.dashboard') : route('toko.index');

        return redirect()
            ->intended($tujuan)
            ->with('toast', [
                'type' => 'success',
                'message' => 'Halo, ' . $this->namaDepan($user->name) . '! Kamu berhasil masuk.',
            ]);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_hp' => $data['phone'],
            'password' => $data['password'],
            'role' => User::ROLE_PELANGGAN,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('toko.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Akun berhasil dibuat. Selamat datang, ' . $this->namaDepan($user->name) . '!',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('toko.index')
            ->with('toast', ['type' => 'info', 'message' => 'Kamu sudah keluar.']);
    }

    private function namaDepan(string $nama): string
    {
        return explode(' ', trim($nama))[0];
    }
}