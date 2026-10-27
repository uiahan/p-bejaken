/* ==========================================================================
   Toaster global.
   - Flash dari server: <script data-toast-flash> (lihat partials/toaster.blade.php)
   - Dari JS: toast('pesan', { type, duration }) / toast.success() / .error() / .info()
   - Dari mana saja: document.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }))
   ========================================================================== */
const root = document.querySelector('[data-toaster]');
const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
const MAKS = 4;

const IKON = {
    success: '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
    error: '<path d="M7 7l10 10M17 7L7 17"/>',
    info: '<path d="M12 11v6M12 7.5v.01"/>',
};
const TIPE = ['success', 'error', 'info'];
const DURASI = { success: 4500, info: 5000, error: 7000 };

function tutup(el) {
    if (!el || el.classList.contains('keluar')) return;
    el.classList.add('keluar');
    setTimeout(() => el.remove(), reduce.matches ? 0 : 350);
}

function show(message, opts = {}) {
    if (!root || !message) return null;
    const type = TIPE.includes(opts.type) ? opts.type : 'success';
    const durasi = Number(opts.duration) > 0 ? Number(opts.duration) : DURASI[type];

    // batasi jumlah yang tampil
    const aktif = root.querySelectorAll('.notif:not(.keluar)');
    if (aktif.length >= MAKS) tutup(aktif[0]);

    const el = document.createElement('div');
    el.className = 'notif';
    el.dataset.type = type;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.style.setProperty('--dur', durasi + 'ms');
    el.innerHTML =
        '<span class="notif-ikon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">' +
        IKON[type] +
        '</svg></span>' +
        '<p class="notif-isi"></p>' +
        '<button type="button" class="notif-tutup" aria-label="Tutup notifikasi"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M7 7l10 10M17 7L7 17"/></svg></button>' +
        '<span class="notif-bar" aria-hidden="true"></span>';
    // textContent, bukan innerHTML, supaya pesan apa pun aman dari XSS
    el.querySelector('.notif-isi').textContent = String(message);
    el.querySelector('.notif-tutup').addEventListener('click', () => tutup(el));

    if (reduce.matches) setTimeout(() => tutup(el), durasi);
    else el.querySelector('.notif-bar').addEventListener('animationend', () => tutup(el));

    root.appendChild(el);
    return el;
}

const toast = (message, opts) => show(message, opts);
toast.success = (m, o) => show(m, { ...o, type: 'success' });
toast.error = (m, o) => show(m, { ...o, type: 'error' });
toast.info = (m, o) => show(m, { ...o, type: 'info' });
window.toast = toast;

document.addEventListener('toast', (e) => show(e.detail?.message, e.detail));

/* pesan dari server (session flash) */
document.querySelectorAll('script[data-toast-flash]').forEach((tag) => {
    try {
        const list = JSON.parse(tag.textContent);
        (Array.isArray(list) ? list : [list]).forEach((t, i) => setTimeout(() => show(t.message, t), 250 + i * 180));
    } catch {
        /* abaikan data rusak */
    }
    tag.remove();
});