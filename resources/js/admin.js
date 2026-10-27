/* ---------- Sidebar seluler ---------- */
const body = document.body;
const opener = document.querySelector('[data-side-open]');
const side = document.getElementById('side');

if (opener && side) {
    const set = (open) => {
        body.classList.toggle('side-open', open);
        opener.setAttribute('aria-expanded', String(open));
        if (open) side.querySelector('a, button')?.focus({ preventScroll: true });
        else if (document.activeElement && side.contains(document.activeElement)) opener.focus();
    };
    opener.addEventListener('click', () => set(true));
    document.querySelectorAll('[data-side-close]').forEach((el) => el.addEventListener('click', () => set(false)));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && body.classList.contains('side-open')) set(false);
    });
    // kembali ke desktop: tutup state seluler
    window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => e.matches && set(false));
}

/* ---------- Hitung naik pada angka ringkasan ---------- */
const fmt = new Intl.NumberFormat('id-ID');
const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.querySelectorAll('[data-count]').forEach((el) => {
    const target = Number(el.dataset.count);
    const prefix = el.dataset.prefix || '';
    if (!Number.isFinite(target)) return;
    const tulis = (n) => (el.textContent = prefix + fmt.format(Math.round(n)));
    if (reduce || target === 0) return tulis(target);

    tulis(0);
    const mulai = performance.now() + 650; // tunggu kartu selesai muncul
    const durasi = 1200;
    const frame = (t) => {
        const p = Math.min(1, Math.max(0, (t - mulai) / durasi));
        tulis(target * (p === 1 ? 1 : 1 - Math.pow(2, -10 * p)));
        if (p < 1) requestAnimationFrame(frame);
    };
    requestAnimationFrame(frame);
});

/* ---------- Dialog konfirmasi hapus ---------- */
const dialog = document.getElementById('dialog-hapus');
if (dialog) {
    const form = dialog.querySelector('[data-hapus-form]');
    const nama = dialog.querySelector('[data-hapus-nama]');

    document.querySelectorAll('[data-hapus]').forEach((btn) => {
        btn.addEventListener('click', () => {
            form.action = btn.dataset.hapus;
            nama.textContent = btn.dataset.nama || '';
            dialog.showModal();
        });
    });
    dialog.querySelector('[data-batal]').addEventListener('click', () => dialog.close());
    // klik di luar kartu = batal
    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) dialog.close();
    });
    // cegah kirim ganda
    form.addEventListener('submit', () => form.querySelector('[type="submit"]').setAttribute('disabled', ''));
}