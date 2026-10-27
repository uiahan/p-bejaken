@extends('layout.auth-layout')

@section('title', 'Daftar | Cap Jamu Raden')
@section('varian', 'blue-telang')
@section('panel-judul', 'Pesan jamu segar dari rumah.')
@section('panel-teks', 'Buat akun sekali, lalu pantau pesanan dan beri ulasan setelah jamunya sampai.')

@section('auth')
    <h1 class="text-[clamp(2.2rem,4vw,3rem)] font-semibold leading-[1.05] tracking-[-0.035em]">Buat akun</h1>
    <p class="mt-3 text-hutan/75">
        Sudah punya akun?
        <a href="{{ route('auth.login') }}" class="tautan">Masuk</a>
    </p>

    @if ($errors->any())
        <div class="alert alert-error mt-7" role="alert">
            Ada isian yang perlu diperbaiki. Periksa kolom yang bertanda merah.
        </div>
    @endif

    <form method="POST" action="{{ route('auth.register.submit') }}" class="mt-8" data-auth-form>
        @csrf

        <div class="field">
            <label for="name" class="field-label">Nama lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nama sesuai penerima paket" autocomplete="name" required class="input" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
            @error('name')
                <p id="name-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="email" class="field-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required class="input" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')
                <p id="email-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="phone" class="field-label">Nomor WhatsApp</label>
            <input id="phone" name="phone" type="tel" inputmode="tel" value="{{ old('phone') }}" placeholder="081234567890" autocomplete="tel" required pattern="^(\+62|62|0)8[0-9]{8,12}$" title="Contoh: 081234567890" class="input" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
            @error('phone')
                <p id="phone-error" class="field-error">{{ $message }}</p>
            @else
                <p class="field-hint">Dipakai kurir dan kami untuk mengabari pesananmu.</p>
            @enderror
        </div>

        <div class="field">
            <label for="password" class="field-label">Password</label>
            <div class="relative">
                <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required minlength="8" class="input pr-32" data-confirm-source @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                <button type="button" class="pw-toggle" data-toggle-password="#password" aria-pressed="false">Tampilkan</button>
            </div>
            @error('password')
                <p id="password-error" class="field-error">{{ $message }}</p>
            @enderror
            <div class="mt-3" data-strength="#password" data-level="0">
                <div class="strength-bar" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                <p class="field-hint" data-strength-text aria-live="polite">Minimal 8 karakter.</p>
            </div>
        </div>

        <div class="field">
            <label for="password_confirmation" class="field-label">Ulangi password</label>
            <div class="relative">
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ketik ulang password" autocomplete="new-password" required class="input pr-32" data-confirm>
                <button type="button" class="pw-toggle" data-toggle-password="#password_confirmation" aria-pressed="false">Tampilkan</button>
            </div>
            <p class="field-hint" data-match="#password" data-with="#password_confirmation" aria-live="polite"></p>
        </div>

        <button type="submit" class="btn-submit mt-8" data-busy="Membuat akun">
            <span class="spin" aria-hidden="true"></span>
            <span data-label>Buat akun</span>
        </button>

        <p class="mt-5 text-center text-sm leading-relaxed text-hutan/65">
            Kami hanya memakai datamu untuk memproses pesanan.
        </p>
    </form>
@endsection