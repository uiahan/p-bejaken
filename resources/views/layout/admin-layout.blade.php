@extends('layout.main-layout')

@section('title', 'Dashboard | Cap Jamu Raden')

@push('styles')
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@section('content')
    @php
    
        $menu = [
            [
                'label' => 'Dashboard',
                'route' => 'admin.dashboard',
                'aktif' => 'admin.dashboard',
                'icon' =>
                    '<rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>',
            ],
            [
                'label' => 'Produk',
                'route' => 'admin.produk.index',
                'aktif' => 'admin.produk.*',
                'icon' => '<path d="M4 8l8-4 8 4-8 4-8-4z"/><path d="M4 8v8l8 4 8-4V8"/><path d="M12 12v8"/>',
            ],
            [
                'label' => 'Mutasi Stok',
                'route' => 'admin.mutasi-stok.index',
                'aktif' => 'admin.mutasi-stok.*',
                'icon' => '<path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/>',
            ],
            [
                'label' => 'Pesanan',
                'route' => null,
                'icon' => '<path d="M6 3.5h12v17l-3-2-3 2-3-2-3 2v-17z"/><path d="M9.5 8.5h5M9.5 12h5"/>',
            ],
            [
                'label' => 'Transaksi',
                'route' => null,
                'icon' => '<rect x="3" y="6" width="18" height="12" rx="2.5"/><circle cx="12" cy="12" r="2.5"/>',
            ],
            [
                'label' => 'Ulasan',
                'route' => null,
                'icon' => '<path d="M12 4l2.4 5 5.4.7-4 3.7 1 5.4-4.8-2.7-4.8 2.7 1-5.4-4-3.7 5.4-.7L12 4z"/>',
            ],
            [
                'label' => 'Chatbot',
                'route' => null,
                'icon' =>
                    '<path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v7a2.5 2.5 0 0 1-2.5 2.5H11l-4.5 4v-4A2.5 2.5 0 0 1 4 13.5v-7z"/>',
            ],
            [
                'label' => 'Pengguna',
                'route' => 'admin.pengguna.index',
                'aktif' => 'admin.pengguna.*',
                'khusus' => 'superadmin',
                'icon' =>
                    '<path d="M12 3.5l7 2.5v5.5c0 4.2-2.8 7.4-7 9-4.2-1.6-7-4.8-7-9V6l7-2.5z"/><circle cx="12" cy="10" r="2.3"/><path d="M8.3 15.5c.6-1.7 2-2.6 3.7-2.6s3.1.9 3.7 2.6"/>',
            ],
        ];

        $user = auth()->user();

        $menu = array_values(array_filter($menu, fn($m) => empty($m['khusus']) || $user->punyaRole($m['khusus'])));

        $peran = $user->punyaRole('superadmin') ? 'Super Admin' : 'Admin';
    @endphp

    <a href="#isi"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4
               focus:z-[120] focus:rounded-full focus:bg-hutan
               focus:px-5 focus:py-3 focus:text-daun">
        Lompat ke konten
    </a>

    @include('components.admin.mobile-sidebar')

    @include('components.admin.sidebar')

    <div id="isi" tabindex="-1" class="min-h-svh lg:pl-[17rem]">
        <div class="mx-auto max-w-6xl px-5 pb-16 pt-6 lg:px-10 lg:pt-12">
            @yield('admin')
        </div>
    </div>
@endsection
