@extends('layout.admin-layout')

@section('title', 'Tambah pengguna | Cap Jamu Raden')

@section('admin')
    <a href="{{ route('admin.pengguna.index') }}" class="back-link fade-in -ml-3 inline-flex items-center gap-2 rounded-full py-2 pl-2 pr-4 text-[15px] font-medium" style="--d: 100ms">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        Kelola pengguna
    </a>

    <header class="mt-5 max-w-2xl">
        <h1 class="text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
            <span class="line"><span style="--d: 200ms">Tambah pengguna</span></span>
        </h1>
        <p class="fade-in mt-4 text-hutan/75" style="--d: 450ms">Buat akun baru untuk admin, superadmin, atau pelanggan.</p>
    </header>

    <div class="fade-in mt-10 max-w-2xl" style="--d: 550ms">
        @include('components.admin.pengguna.form')
    </div>
@endsection