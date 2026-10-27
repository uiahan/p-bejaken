@extends('layout.main-layout')

@section('title', 'Cap Jamu Raden')

@push('styles')
    <meta name="robots" content="noindex">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
@php
    $panel = [
        'sweet-tamarind'    => ['nama' => 'Sweet Tamarind',    'latar' => '#F1C7A4', 'manfaat' => 'Menyegarkan dan membantu pencernaan.'],
        'blue-telang'       => ['nama' => 'Blue Telang',       'latar' => '#DCC7E3', 'manfaat' => 'Mengandung antioksidan alami (antosianin).'],
        'kunyit-asem-sirih' => ['nama' => 'Kunyit Asem Sirih', 'latar' => '#F4CD72', 'manfaat' => 'Menjaga kesehatan lambung dan kulit.'],
        'red-rosella'       => ['nama' => 'Red Rosella',       'latar' => '#F2B8B2', 'manfaat' => 'Kaya vitamin C dan antioksidan.'],
        'beras-kencur'      => ['nama' => 'Beras Kencur',      'latar' => '#E8DCC3', 'manfaat' => 'Membantu meredakan pegal dan menghangatkan badan.'],
        'sereh-jahe'        => ['nama' => 'Sereh Jahe',        'latar' => '#E6D08E', 'manfaat' => 'Menghangatkan badan.'],
    ];
    $slug = trim($__env->yieldContent('varian', 'kunyit-asem-sirih'));
    $p = $panel[$slug] ?? $panel['kunyit-asem-sirih'];
@endphp

<div class="min-h-svh lg:grid lg:grid-cols-12">
    {{-- Panel varian --}}
    <aside class="auth-panel" style="--panel: {{ $p['latar'] }}">
        <div class="auth-flood" aria-hidden="true"></div>

        <div class="relative z-10 mx-auto flex h-full w-full max-w-xl flex-col px-6 py-5 lg:max-w-none lg:px-14 lg:py-12">
            <a href="{{ url('/toko') }}" class="inline-flex w-fit items-center" aria-label="Cap Jamu Raden, ke toko">
                <img src="{{ asset('images/brand/logo-horizontal.png') }}" alt="Cap Jamu Raden" width="1000" height="183" class="h-8 w-auto lg:h-11">
            </a>

            <div class="mt-14 hidden lg:block">
                <h2 class="max-w-md text-[clamp(2.4rem,4vw,3.8rem)] font-semibold leading-[1.02] tracking-[-0.04em] text-balance">
                    <span class="line"><span style="--d: 500ms">@yield('panel-judul', 'Minuman para raden.')</span></span>
                </h2>
                <p class="fade-in mt-6 max-w-sm text-lg leading-relaxed" style="--d: 1000ms">@yield('panel-teks', 'Jamu dari bahan alami, diracik sesuai stok.')</p>
            </div>

            <div class="mt-auto hidden items-end justify-between gap-6 pt-10 lg:flex">
                <div class="fade-in max-w-[14rem] pb-2" style="--d: 1250ms">
                    <p class="text-lg font-semibold tracking-tight">{{ $p['nama'] }}</p>
                    <p class="mt-1 text-[15px] leading-relaxed">{{ $p['manfaat'] }}</p>
                </div>
                <img src="{{ asset('images/produk/' . $slug . '-potong.webp') }}" alt="Botol jamu {{ $p['nama'] }} 250 ml" width="360" height="800" class="bottle-in auth-bottle">
            </div>
            <div class="auth-rak hidden lg:block" aria-hidden="true"></div>
        </div>
    </aside>

    {{-- Form --}}
    <div class="flex min-h-[calc(100svh-4.5rem)] flex-col lg:col-span-7 lg:min-h-svh">
        <div class="flex justify-end px-6 pt-6 lg:px-14 lg:pt-10">
            <a href="{{ url('/toko') }}" class="back-link inline-flex items-center gap-2 rounded-full py-2 pl-2 pr-4 text-[15px] font-medium">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
                Kembali ke toko
            </a>
        </div>

        <div class="flex flex-1 items-center px-6 py-10 lg:px-14">
            <div class="mx-auto w-full max-w-md">
                @yield('auth')
            </div>
        </div>

        <p class="px-6 pb-8 text-center text-sm text-hutan/60 lg:px-14">&copy; {{ date('Y') }} Cap Jamu Raden</p>
    </div>
</div>
@endsection