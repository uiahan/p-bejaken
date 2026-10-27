@extends('layout.main-layout')

@section('title', 'Cap Jamu Raden | Minuman herbal segar, alami, tanpa pengawet')

@push('styles')
    <meta name="description"
        content="Cap Jamu Raden: minuman herbal dari bahan alami, tanpa pengawet dan tanpa bahan sintetis. Diracik sesuai stok, selalu segar.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')

    @include('components.landing.navbar')

    @yield('landing')
    
    @include('components.landing.footer')

@endsection
