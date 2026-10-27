<header class="nav sticky top-0 z-50 bg-daun"
        data-nav
        data-scrolled="false">

    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 lg:h-20">

        {{-- Logo --}}
        <a href="{{ url('/') }}"
           class="flex items-center"
           aria-label="Cap Jamu Raden, ke beranda">

            <img
                src="{{ asset('images/brand/logo-horizontal.png') }}"
                alt="Cap Jamu Raden"
                width="1000"
                height="183"
                class="h-8 w-auto lg:h-10">
        </a>

        {{-- Navigasi desktop --}}
        <nav
            class="hidden items-center gap-9 text-[15px] font-medium md:flex"
            aria-label="Menu utama"
            data-section-nav>

            <a href="#cerita" class="nav-link" data-section-link="cerita">
                Cerita
            </a>

            <a href="#varian" class="nav-link" data-section-link="varian">
                Varian
            </a>

            <a href="#segar" class="nav-link" data-section-link="segar">
                Kesegaran
            </a>

            <a href="#chatbot" class="nav-link" data-section-link="chatbot">
                Chatbot
            </a>

            <a href="{{ url('/toko') }}"
               class="rounded-full bg-hutan px-5 py-2.5 text-daun transition hover:bg-hutan-deep">
                Belanja
            </a>
        </nav>

        {{-- Tombol menu mobile --}}
        <button
            type="button"
            class="-mr-2 flex h-11 w-11 items-center justify-center md:hidden"
            data-menu-toggle
            aria-expanded="false"
            aria-controls="menu-mobile"
            aria-label="Buka menu">

            <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                aria-hidden="true">

                <path d="M4 8h16M4 16h16"/>
            </svg>
        </button>

    </div>

    {{-- Navigasi mobile --}}
    <div id="menu-mobile" class="menu md:hidden" data-menu>
        <div>
            <nav
                class="flex flex-col gap-1 px-6 pb-6 text-lg font-medium"
                aria-label="Menu seluler"
                data-section-nav>

                <a href="#cerita"
                   class="nav-link py-3"
                   data-section-link="cerita">
                    Cerita
                </a>

                <a href="#varian"
                   class="nav-link py-3"
                   data-section-link="varian">
                    Varian
                </a>

                <a href="#segar"
                   class="nav-link py-3"
                   data-section-link="segar">
                    Kesegaran
                </a>

                <a href="#chatbot"
                   class="nav-link py-3"
                   data-section-link="chatbot">
                    Chatbot
                </a>

                <a href="{{ url('/toko') }}"
                   class="mt-2 rounded-full bg-hutan px-5 py-3.5 text-center text-daun">
                    Belanja
                </a>

            </nav>
        </div>
    </div>

</header>