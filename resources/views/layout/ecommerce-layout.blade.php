@extends('layout.main-layout')

@section('title', 'Toko Cap Jamu Raden | Jamu segar siap kirim')

@push('styles')
    <meta name="description"
        content="Belanja minuman herbal Cap Jamu Raden: enam varian dari bahan alami, tanpa pengawet, diracik sesuai stok.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
    
    @include('components.ecommerce.navbar')

    @yield('ecommerce')

    @include('components.ecommerce.footer')

    @include('components.ecommerce.keranjang')
    
@endsection
