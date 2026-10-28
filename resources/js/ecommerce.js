const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const rupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
const wait = (ms) => new Promise((r) => setTimeout(r, ms));
const esc = (v) => String(v).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

/* ==========================================================================
   Keranjang
   Sementara disimpan di browser (localStorage). Saat checkout dibuat,
   pindahkan ke session/database dan pakai harga & stok dari server.
   ========================================================================== */
const KEY = 'cjr_cart_v1';
let memory = [];

const load = () => {
    try {
        const data = JSON.parse(localStorage.getItem(KEY) || '[]');
        return Array.isArray(data) ? data : [];
    } catch {
        return memory;
    }
};
const save = (items) => {
    memory = items;
    try {
        localStorage.setItem(KEY, JSON.stringify(items));
    } catch {
        /* penyimpanan tidak tersedia, pakai memori saja */
    }
};

const cartEl = document.querySelector('[data-cart]');
if (cartEl) {
    const overlay = document.querySelector('[data-cart-overlay]');
    const list = cartEl.querySelector('[data-cart-list]');
    const body = cartEl.querySelector('[data-cart-body]');
    const foot = cartEl.querySelector('[data-cart-foot]');
    const subtotalEl = cartEl.querySelector('[data-cart-subtotal]');
    const totalQtyEl = cartEl.querySelector('[data-cart-total-qty]');
    const badges = document.querySelectorAll('[data-cart-count]');
    const openers = document.querySelectorAll('[data-cart-open]');
    const cartButton = openers[0];

    let items = load();
    let lastOpener = null;

    /* ---------- tampilan ---------- */
    const render = (newSlug) => {
        const qty = items.reduce((s, i) => s + i.qty, 0);
        const subtotal = items.reduce((s, i) => s + i.qty * i.harga, 0);

        badges.forEach((b) => {
            b.textContent = qty;
            b.hidden = qty === 0;
        });
        totalQtyEl.textContent = qty ? `(${qty})` : '';
        subtotalEl.textContent = rupiah(subtotal);
        body.dataset.kosong = String(items.length === 0);
        foot.hidden = items.length === 0;

        list.innerHTML = '';
        items.forEach((item) => {
            const li = document.createElement('li');
            li.className = 'cart-item' + (item.slug === newSlug ? ' baru' : '');
            li.style.setProperty('--c', /^#[0-9a-f]{3,8}$/i.test(item.latar) ? item.latar : '#E3E8DA');
            li.innerHTML = `
                <div class="thumb"><img src="${esc(item.foto)}" alt="" width="40" height="88"></div>
                <div class="flex min-w-0 flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <p class="font-semibold leading-snug tracking-tight">${esc(item.nama)}</p>
                        <button type="button" class="-mr-1 shrink-0 rounded-full px-2 py-1 text-sm text-hutan/65 underline underline-offset-4 transition hover:text-hutan" data-remove="${item.slug}" aria-label="Hapus ${esc(item.nama)} dari keranjang">Hapus</button>
                    </div>
                    <p class="text-sm text-hutan/65">${rupiah(item.harga)} / botol 250 ml</p>
                    <div class="mt-auto flex items-center justify-between pt-3">
                        <div class="stepper" role="group" aria-label="Jumlah ${esc(item.nama)}">
                            <button type="button" data-dec="${item.slug}" aria-label="Kurangi" ${item.qty <= 1 ? 'disabled' : ''}>&minus;</button>
                            <output aria-live="polite">${item.qty}</output>
                            <button type="button" data-inc="${item.slug}" aria-label="Tambah" ${item.qty >= item.stok ? 'disabled' : ''}>+</button>
                        </div>
                        <p class="font-semibold">${rupiah(item.qty * item.harga)}</p>
                    </div>
                </div>`;
            list.appendChild(li);
        });
    };

    const bump = () => {
        [cartButton, ...badges].forEach((el) => {
            if (!el) return;
            el.classList.remove('bump');
            void el.offsetWidth;
            el.classList.add('bump');
        });
    };

    /* ---------- data ---------- */
    const add = (data) => {
        const found = items.find((i) => i.slug === data.slug);
        if (found) {
            if (found.qty >= found.stok) return false;
            found.qty += 1;
        } else {
            items.push({ ...data, qty: 1 });
        }
        save(items);
        render(data.slug);
        return true;
    };
    const setQty = (slug, qty) => {
        const item = items.find((i) => i.slug === slug);
        if (!item) return;
        item.qty = Math.max(1, Math.min(item.stok, qty));
        save(items);
        render();
    };
    const remove = (slug) => {
        items = items.filter((i) => i.slug !== slug);
        save(items);
        render();
    };

    list.addEventListener('click', (e) => {
        const t = e.target.closest('button');
        if (!t) return;
        const item = (slug) => items.find((i) => i.slug === slug);
        if (t.dataset.inc) setQty(t.dataset.inc, item(t.dataset.inc).qty + 1);
        if (t.dataset.dec) setQty(t.dataset.dec, item(t.dataset.dec).qty - 1);
        if (t.dataset.remove) remove(t.dataset.remove);
    });

    /* ---------- buka / tutup ---------- */
    const focusables = () =>
        [...cartEl.querySelectorAll('a[href], button:not([disabled])')].filter((el) => el.offsetParent !== null);

    const open = (opener) => {
        lastOpener = opener || document.activeElement;
        document.body.classList.add('cart-open');
        cartEl.setAttribute('aria-hidden', 'false');
        overlay.setAttribute('aria-hidden', 'false');
        setTimeout(() => cartEl.focus(), 30);
    };
    const close = () => {
        document.body.classList.remove('cart-open');
        cartEl.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('aria-hidden', 'true');
        if (lastOpener && lastOpener.focus) lastOpener.focus();
    };

    openers.forEach((b) => b.addEventListener('click', () => open(b)));
    cartEl.querySelectorAll('[data-cart-close]').forEach((b) => b.addEventListener('click', close));
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e) => {
        if (!document.body.classList.contains('cart-open')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'Tab') {
            const f = focusables();
            if (!f.length) return;
            const first = f[0];
            const last = f[f.length - 1];
            if (e.shiftKey && (document.activeElement === first || document.activeElement === cartEl)) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });

    /* ---------- toast ---------- */
    const toast = document.querySelector('[data-toast]');
    const toastText = toast.querySelector('[data-toast-text]');
    const toastAction = toast.querySelector('[data-toast-action]');
    let toastTimer;
    const showToast = (text, withAction = false) => {
        toastText.textContent = text;
        toastAction.hidden = !withAction;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 3800);
    };
    toastAction.addEventListener('click', () => {
        toast.classList.remove('show');
        open(toastAction);
    });

    /* ---------- foto terbang ke keranjang ---------- */
    const fly = (img) =>
        new Promise((resolve) => {
            if (reduceMotion || !img || !cartButton) return resolve();
            const from = img.getBoundingClientRect();
            const to = cartButton.getBoundingClientRect();
            const clone = document.createElement('img');
            clone.src = img.currentSrc || img.src;
            clone.alt = '';
            clone.className = 'terbang';
            Object.assign(clone.style, {
                left: from.left + 'px',
                top: from.top + 'px',
                width: from.width + 'px',
                height: from.height + 'px',
            });
            document.body.appendChild(clone);

            const dx = to.left + to.width / 2 - (from.left + from.width / 2);
            const dy = to.top + to.height / 2 - (from.top + from.height / 2);
            const anim = clone.animate(
                [
                    { transform: 'translate(0,0) scale(1) rotate(0deg)', opacity: 1, offset: 0 },
                    { transform: `translate(${dx * 0.3}px, ${dy * 0.3 - 70}px) scale(0.62) rotate(-8deg)`, opacity: 1, offset: 0.4 },
                    { transform: `translate(${dx}px, ${dy}px) scale(0.1) rotate(10deg)`, opacity: 0.2, offset: 1 },
                ],
                { duration: 800, easing: 'cubic-bezier(0.5, 0, 0.75, 0.4)' }
            );
            anim.finished.then(() => {
                clone.remove();
                resolve();
            });
        });

    /* ---------- tombol "Tambah" ---------- */
    document.querySelectorAll('[data-add]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const d = btn.dataset;
            const card = btn.closest('[data-produk]');
            const inCart = items.find((i) => i.slug === d.slug);

            if (inCart && inCart.qty >= Number(d.stok)) {
                showToast(`Stok ${d.nama} tinggal ${d.stok} botol.`);
                return;
            }

            const ok = add({
                id: Number(d.id),
                slug: d.slug,
                nama: d.nama,
                harga: Number(d.harga),
                stok: Number(d.stok),
                foto: d.foto,
                latar: d.latar,
            });
            if (!ok) return;

            const label = btn.querySelector('[data-label]');
            const before = label.textContent;
            btn.classList.add('ok');
            label.textContent = 'Ditambahkan';
            setTimeout(() => {
                btn.classList.remove('ok');
                label.textContent = before;
            }, 1400);

            await fly(card && card.querySelector('.foto img'));
            bump();
            showToast(`${d.nama} masuk keranjang.`, true);
        });
    });

    render();
}

/* ==========================================================================
   Rak varian: sorot botol, ganti warna latar, lompat ke kartu produk
   ========================================================================== */
const band = document.querySelector('[data-rak-band]');
if (band) {
    const defaultTint = getComputedStyle(band).getPropertyValue('--rak').trim();
    const note = band.querySelector('[data-rak-note]');
    const defaultNote = note ? note.innerHTML : '';
    const items = band.querySelectorAll('[data-rak]');

    const sorot = (el) => {
        band.style.setProperty('--rak', el.dataset.latar);
        if (note) note.innerHTML = `<span><strong class="font-semibold">${el.dataset.nama}</strong> ${el.dataset.harga}</span>`;
    };
    const reset = () => {
        band.style.setProperty('--rak', defaultTint);
        if (note) note.innerHTML = defaultNote;
    };

    items.forEach((el) => {
        el.addEventListener('mouseenter', () => sorot(el));
        el.addEventListener('focus', () => sorot(el));
        el.addEventListener('mouseleave', reset);
        el.addEventListener('blur', reset);
        el.addEventListener('click', () => {
            sorot(el);
            const target = document.querySelector(el.dataset.target);
            if (!target) return;
            // pastikan kartu terlihat bila sedang difilter
            const semua = document.querySelector('[data-filter="semua"]');
            if (target.hidden && semua) semua.click();
            setTimeout(() => {
                target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
                target.classList.remove('ping');
                void target.offsetWidth;
                target.classList.add('ping');
            }, target.hidden ? 350 : 0);
        });
    });
}

/* ==========================================================================
   Filter katalog
   ========================================================================== */
const chips = [...document.querySelectorAll('[data-filter]')];
const cards = [...document.querySelectorAll('[data-produk]')];
if (chips.length && cards.length) {
    const hasil = document.querySelector('[data-hasil]');
    const kosong = document.querySelector('[data-filter-kosong]');
    let busy = false;

    const apply = async (key) => {
        if (busy) return;
        busy = true;
        chips.forEach((c) => c.setAttribute('aria-pressed', String(c.dataset.filter === key)));

        const match = (card) => key === 'semua' || card.dataset.tags.split(' ').includes(key);
        const keluar = cards.filter((c) => !c.hidden && !match(c));
        const masuk = cards.filter((c) => c.hidden && match(c));

        keluar.forEach((c) => c.classList.add('is-out'));
        if (keluar.length && !reduceMotion) await wait(260);
        keluar.forEach((c) => {
            c.hidden = true;
        });
        masuk.forEach((c, i) => {
            c.hidden = false;
            c.classList.remove('is-out', 'is-in');
            void c.offsetWidth;
            c.style.animationDelay = `${i * 70}ms`;
            c.classList.add('is-in');
        });

        const total = cards.filter((c) => !c.hidden).length;
        if (hasil) hasil.textContent = `Menampilkan ${total} dari ${cards.length} varian`;
        if (kosong) kosong.hidden = total !== 0;
        busy = false;
    };

    chips.forEach((c) => c.addEventListener('click', () => apply(c.dataset.filter)));
}