{{-- resources/views/features/admin/mutasi-stok/edit.blade.php --}}
@extends('layout.admin-layout')

@section('title', 'Ubah mutasi stok | Cap Jamu Raden')

@section('admin')
    <a href="{{ route('admin.mutasi-stok.index') }}"
       class="back-link fade-in -ml-3 inline-flex items-center gap-2 rounded-full py-2 pl-2 pr-4 text-[15px] font-medium"
       style="--d: 100ms">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 6-6 6 6 6"/>
        </svg>
        Mutasi stok
    </a>

    <header class="mt-5 max-w-2xl">
        <h1 class="text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
            <span class="line"><span style="--d: 200ms">Ubah mutasi stok</span></span>
        </h1>
        <p class="fade-in mt-4 text-hutan/75" style="--d: 450ms">
            Perbarui catatan mutasi. Perubahan akan dihitung kembali terhadap stok produk.
        </p>
    </header>

    <div class="fade-in mt-10 max-w-2xl" style="--d: 550ms">
        @if ($produkList->isEmpty())
            <div class="alert alert-error" role="alert">
                Produk tidak tersedia. Periksa kembali data produk.
            </div>
        @else
            @include('features.admin.mutasi-stok.form')
        @endif
    </div>
@endsection