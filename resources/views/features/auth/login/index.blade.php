@extends('layout.auth-layout')

@section('title', 'Masuk | Cap Jamu Raden')
@section('varian', 'beras-kencur')
@section('panel-judul', 'Selamat datang kembali.')
@section('panel-teks', 'Masuk untuk melihat pesananmu dan belanja lebih cepat.')

@section('auth')
    <h1 class="text-[clamp(2.2rem,4vw,3rem)] font-semibold leading-[1.05] tracking-[-0.035em]">Masuk</h1>
    <p class="mt-3 text-hutan/75">
        Belum punya akun?
        <a href="{{ route('auth.register') }}" class="tautan">Daftar dulu</a>
    </p>

    @if (session('status'))
        <div class="alert alert-ok mt-7" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mt-7" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('auth.login.submit') }}" class="mt-8" data-auth-form>
        @csrf

        <div class="field">
            <label for="email" class="field-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required class="input" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
        </div>

        <div class="field">
            <div class="flex items-baseline justify-between gap-4">
                <label for="password" class="field-label">Password</label>
                <a href="{{ url('/lupa-password') }}" class="tautan mb-2 text-sm">Lupa password?</a>
            </div>
            <div class="relative">
                <input id="password" name="password" type="password" placeholder="Password kamu" autocomplete="current-password" required class="input pr-32" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                <button type="button" class="pw-toggle" data-toggle-password="#password" aria-pressed="false">Tampilkan</button>
            </div>
        </div>

        <label class="cek mt-5">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn-submit mt-8" data-busy="Memproses">
            <span class="spin" aria-hidden="true"></span>
            <span data-label>Masuk</span>
        </button>
    </form>
@endsection