
{{-- Header mobile --}}
<header class="sticky top-0 z-40 flex h-16 items-center justify-between bg-daun px-5 lg:hidden">

    <a href="{{ route('admin.dashboard') }}"
       aria-label="Dashboard Cap Jamu Raden">
        <img
            src="{{ asset('images/brand/logo-horizontal.png') }}"
            alt="Cap Jamu Raden"
            width="1000"
            height="183"
            class="h-8 w-auto"
        >
    </a>

    <button
        type="button"
        class="-mr-2 flex h-11 w-11 items-center justify-center"
        data-side-open
        aria-controls="side"
        aria-expanded="false"
        aria-label="Buka menu"
    >
        <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            aria-hidden="true"
        >
            <path d="M4 8h16M4 16h16"/>
        </svg>
    </button>
</header>

{{-- Overlay ketika sidebar terbuka --}}
<div class="admin-scrim"
     data-side-close
     aria-hidden="true">
</div>
