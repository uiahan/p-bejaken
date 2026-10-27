<div class="cart-overlay" data-cart-overlay aria-hidden="true"></div>
<aside id="cart" class="cart" role="dialog" aria-modal="true" aria-labelledby="cart-title" aria-hidden="true"
    tabindex="-1" data-cart>
    <div class="flex items-center justify-between px-6 py-5">
        <h2 id="cart-title" class="text-xl font-semibold tracking-tight">Keranjang <span class="text-hutan/55"
                data-cart-total-qty></span></h2>
        <button type="button"
            class="-mr-2 flex h-11 w-11 items-center justify-center rounded-full transition hover:bg-hutan/10"
            data-cart-close aria-label="Tutup keranjang">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    </div>

    <div class="cart-body" data-cart-body>
        <div class="cart-empty" data-cart-empty>
            <p class="text-lg font-semibold tracking-tight">Keranjangmu masih kosong.</p>
            <p class="mt-2 max-w-[16rem] leading-relaxed text-hutan/70">Pilih varian yang kamu suka, lalu tambahkan ke
                sini.</p>
            <button type="button"
                class="mt-7 rounded-full bg-hutan px-6 py-3 font-medium text-daun transition hover:bg-hutan-deep"
                data-cart-close>Lihat varian</button>
        </div>
        <ul class="cart-list" data-cart-list></ul>
    </div>

    <div class="cart-foot" data-cart-foot hidden>
        <div class="flex items-baseline justify-between">
            <p class="text-hutan/70">Subtotal</p>
            <p class="text-xl font-semibold tracking-tight" data-cart-subtotal>Rp 0</p>
        </div>
        <p class="mt-1 text-sm text-hutan/60">Ongkir dihitung saat checkout.</p>
        <a href="{{ url('/checkout') }}"
            class="mt-5 block rounded-full bg-hutan px-6 py-3.5 text-center font-medium text-daun transition hover:bg-hutan-deep">Lanjut
            ke checkout</a>
        <a href="{{ url('/keranjang') }}"
            class="mt-2 block rounded-full px-6 py-3 text-center font-medium transition hover:bg-hutan/10">Lihat
            halaman keranjang</a>
    </div>
</aside>

<div class="toast" data-toast role="status" aria-live="polite">
    <span data-toast-text></span>
    <button type="button" class="toast-aksi" data-toast-action hidden>Lihat keranjang</button>
</div>
