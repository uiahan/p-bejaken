<div class="announce bg-hutan text-daun">
    <p class="mx-auto max-w-7xl px-6 py-2.5 text-center text-[13px] leading-snug">
        Diracik sesuai stok. Simpan di kulkas dan habiskan dalam 7 sampai 10 hari.
    </p>
</div>

<header class="nav sticky top-0 z-40 bg-daun" data-nav data-scrolled="false">
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-6 px-6 lg:h-20">
        <a href="{{ url('/toko') }}" class="flex shrink-0 items-center" aria-label="Cap Jamu Raden, ke beranda toko">
            <img src="{{ asset('images/brand/logo-horizontal.png') }}" alt="Cap Jamu Raden" width="1000" height="183"
                class="h-8 w-auto lg:h-10">
        </a>

        <nav class="hidden items-center gap-8 text-[15px] font-medium lg:flex" aria-label="Menu toko">
            <a href="{{ url('/toko') }}" class="nav-link">Toko</a>
            <a href="{{ url('/toko') }}#katalog" class="nav-link">Varian</a>
            <a href="{{ url('/chatbot') }}" class="nav-link">Chatbot</a>
            <a href="{{ url('/pesanan') }}" class="nav-link">Pesanan saya</a>
        </nav>

        <form action="{{ url('/produk') }}" method="get" role="search"
            class="search ml-auto hidden md:block lg:w-72">
            <label for="cari" class="sr-only">Cari varian</label>
            <div class="flex items-center gap-2.5 rounded-full px-4 py-2.5">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" />
                    <path d="M16 16l4.5 4.5" />
                </svg>
                <input id="cari" name="q" type="search" placeholder="Cari varian" autocomplete="off"
                    class="w-full bg-transparent text-[15px] outline-none placeholder:text-hutan/50">
            </div>
        </form>

        <div class="ml-auto flex items-center gap-1 md:ml-0">
            <button type="button"
                class="cart-btn relative mr-2 flex h-11 items-center gap-2.5 rounded-full bg-hutan px-3.5 text-[15px] sm:pl-4 sm:pr-5 font-medium text-daun transition hover:bg-hutan-deep"
                data-cart-open aria-haspopup="dialog" aria-controls="cart">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 8h14l-1 11.5a1.5 1.5 0 0 1-1.5 1.4h-9A1.5 1.5 0 0 1 6 19.5L5 8z" />
                    <path d="M9 8V7a3 3 0 0 1 6 0v1" />
                </svg>
                <span class="hidden sm:inline">Keranjang</span>
                <span class="cart-badge" data-cart-count hidden aria-live="polite">0</span>
            </button>
            @auth
                {{-- Dashboard khusus staf --}}
                @if (auth()->user()->isStaf())
                    <a href="{{ route('admin.dashboard') }}"
                        class="hidden rounded-full px-4 py-2.5 text-[15px] font-medium transition hover:bg-hutan/10 lg:block">
                        Dashboard
                    </a>
                @endif

                {{-- Dropdown profil pengguna --}}
                <div class="relative" data-profile-dropdown>
                    <button type="button" data-profile-toggle aria-expanded="false" aria-haspopup="true"
                        aria-label="Buka menu profil"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-hutan text-sm font-bold uppercase text-daun ring-2 ring-transparent transition hover:bg-hutan-deep focus:outline-none focus:ring-hutan/30">
                        {{ strtoupper(substr(trim(auth()->user()->name ?? 'U'), 0, 1)) }}
                    </button>

                    {{-- Isi dropdown --}}
                    <div data-profile-menu
                        class="absolute right-0 top-full z-50 mt-3 hidden w-60 overflow-hidden rounded-2xl border border-hutan/10 bg-white py-2 text-hutan shadow-xl">
                        {{-- Informasi pengguna --}}
                        <div class="border-b border-hutan/10 px-4 py-3">
                            <p class="truncate font-semibold">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="truncate text-sm text-hutan/60">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        {{-- Profile --}}
                        <a href="{{ url('/profile') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm transition hover:bg-daun">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M5 21v-2a7 7 0 0 1 14 0v2" />
                            </svg>
                            Profile
                        </a>

                        {{-- Setting --}}
                        <a href="{{ url('/settings') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm transition hover:bg-daun">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="m19.4 15 .1.1 1.4 1.1-1.5 2.6-1.7-.7a8 8 0 0 1-1.8 1l-.3 1.9h-3l-.3-1.9a8 8 0 0 1-1.8-1l-1.7.7-1.5-2.6 1.4-1.1a7 7 0 0 1 0-2l-1.4-1.1 1.5-2.6 1.7.7a8 8 0 0 1 1.8-1l.3-1.9h3l.3 1.9a8 8 0 0 1 1.8 1l1.7-.7 1.5 2.6-1.4 1.1a7 7 0 0 1 0 2Z"
                                    transform="translate(-1 -1) scale(1.08)" />
                            </svg>
                            Setting
                        </a>

                        {{-- Logout --}}
                        <div class="my-1 border-t border-hutan/10"></div>

                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf

                            <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-red-600 transition hover:bg-red-50">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M10 17l5-5-5-5" />
                                    <path d="M15 12H3" />
                                    <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('auth.login') }}"
                    class="hidden rounded-full px-4 py-2.5 text-[15px] font-medium transition hover:bg-hutan/10 lg:block">
                    Masuk
                </a>
            @endauth

            <button type="button" class="-mr-2 flex h-11 w-11 items-center justify-center lg:hidden" data-menu-toggle
                aria-expanded="false" aria-controls="menu-mobile" aria-label="Buka menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 8h16M4 16h16" />
                </svg>
            </button>
        </div>
    </div>

    <div id="menu-mobile" class="menu lg:hidden" data-menu>
        <div>
            <nav class="flex flex-col gap-1 px-6 pb-6 text-lg font-medium" aria-label="Menu toko seluler">
                <form action="{{ url('/produk') }}" method="get" role="search" class="search mb-3 md:hidden">
                    <label for="cari-m" class="sr-only">Cari varian</label>
                    <div class="flex items-center gap-2.5 rounded-full px-4 py-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="6.5" />
                            <path d="M16 16l4.5 4.5" />
                        </svg>
                        <input id="cari-m" name="q" type="search" placeholder="Cari varian"
                            autocomplete="off"
                            class="w-full bg-transparent text-base outline-none placeholder:text-hutan/50">
                    </div>
                </form>
                <a href="{{ url('/toko') }}" class="py-3">Toko</a>
                <a href="{{ url('/toko') }}#katalog" class="py-3">Varian</a>
                <a href="{{ url('/chatbot') }}" class="py-3">Chatbot</a>
                <a href="{{ url('/pesanan') }}" class="py-3">Pesanan saya</a>
                @auth
                    @if (auth()->user()->isStaf())
                        <a href="{{ route('admin.dashboard') }}" class="py-3">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-3 text-left">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="py-3">Masuk</a>
                @endauth
                <a href="{{ url('/') }}" class="py-3 text-hutan/65">Tentang Cap Jamu Raden</a>
            </nav>
        </div>
    </div>
</header>
