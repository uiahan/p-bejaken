
<aside id="side" class="admin-side" aria-label="Menu admin">

    {{-- Logo dan tombol tutup --}}
    <div class="flex items-center justify-between px-6 pb-2 pt-7">
        <a href="{{ route('admin.dashboard') }}"
           aria-label="Dashboard Cap Jamu Raden">
            <img
                src="{{ asset('images/brand/logo-horizontal-light.png') }}"
                alt="Cap Jamu Raden"
                width="1000"
                height="183"
                class="h-9 w-auto"
            >
        </a>

        <button
            type="button"
            class="-mr-2 flex h-10 w-10 items-center justify-center
                   rounded-full transition hover:bg-daun/10 lg:hidden"
            data-side-close
            aria-label="Tutup menu"
        >
            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                aria-hidden="true"
            >
                <path d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>

    {{-- Navigasi --}}
    <nav class="mt-8 flex-1 overflow-y-auto px-4"
         aria-label="Navigasi admin">

        <p class="px-3 pb-3 text-xs font-medium uppercase
                  tracking-[0.14em] text-daun/50">
            Menu
        </p>

        <ul class="space-y-1">
            @foreach ($menu as $i => $m)
                <li class="side-in" style="--i: {{ $i }}">
                    @if ($m['route'])
                        <a
                            href="{{ route($m['route']) }}"
                            class="side-item"
                            @if (request()->routeIs($m['aktif'] ?? $m['route']))
                                aria-current="page"
                            @endif
                        >
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                {!! $m['icon'] !!}
                            </svg>

                            {{ $m['label'] }}
                        </a>
                    @else
                        <span class="side-item is-soon"
                              aria-disabled="true">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                {!! $m['icon'] !!}
                            </svg>

                            {{ $m['label'] }}

                            <em>Segera</em>
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- Footer sidebar --}}
    <div class="mt-4 px-4 pb-5">

        <a href="{{ route('toko.index') }}"
           class="side-item mb-3">
            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M5 8h14l-1 11.5a1.5 1.5 0 0 1-1.5 1.4h-9A1.5 1.5 0 0 1 6 19.5L5 8z"/>
                <path d="M9 8V7a3 3 0 0 1 6 0v1"/>
            </svg>

            Lihat toko
        </a>

        {{-- Profil admin --}}
        <div class="flex items-center gap-3 rounded-[20px] bg-daun/10 p-3">
            <span
                class="grid h-10 w-10 flex-none place-items-center
                       rounded-full bg-emas font-semibold text-hutan-deep"
                aria-hidden="true"
            >
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </span>

            <div class="min-w-0 flex-1">
                <p class="truncate text-[15px] font-medium leading-tight">
                    {{ $user->name }}
                </p>

                <p class="text-sm text-daun/60">
                    {{ $peran }}
                </p>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf

                <button
                    type="submit"
                    class="flex h-10 w-10 items-center justify-center
                           rounded-full transition hover:bg-daun/15"
                    aria-label="Keluar"
                    title="Keluar"
                >
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M10 4.5H6.5A2 2 0 0 0 4.5 6.5v11a2 2 0 0 0 2 2H10"/>
                        <path d="M15 8l4 4-4 4M19 12H9.5"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
