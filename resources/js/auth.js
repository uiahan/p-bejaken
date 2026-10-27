/* ---------- Tampilkan / sembunyikan password ---------- */
document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
    const input = document.querySelector(btn.dataset.togglePassword);
    if (!input) return;
    btn.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.textContent = show ? 'Sembunyikan' : 'Tampilkan';
        btn.setAttribute('aria-pressed', String(show));
        input.focus();
    });
});

/* ---------- Kekuatan password ---------- */
document.querySelectorAll('[data-strength]').forEach((box) => {
    const input = document.querySelector(box.dataset.strength);
    const bars = box.querySelectorAll('.strength-bar i');
    const text = box.querySelector('[data-strength-text]');
    if (!input) return;

    const labels = ['Minimal 8 karakter.', 'Lemah. Tambahkan huruf besar, angka, atau simbol.', 'Cukup. Tambahkan satu hal lagi supaya lebih kuat.', 'Kuat.'];

    const update = () => {
        const v = input.value;
        let level = 0;
        let filled = 0;
        if (v.length > 0) {
            const score =
                (v.length >= 8 ? 1 : 0) +
                (v.length >= 12 ? 1 : 0) +
                (/[a-z]/.test(v) && /[A-Z]/.test(v) ? 1 : 0) +
                (/\d/.test(v) ? 1 : 0) +
                (/[^A-Za-z0-9]/.test(v) ? 1 : 0);
            if (v.length < 8) {
                level = 0;
                filled = 1;
            } else {
                level = score <= 2 ? 1 : score === 3 ? 2 : 3;
                filled = Math.min(4, score);
            }
        }
        box.dataset.level = String(level);
        bars.forEach((b, i) => b.classList.toggle('on', i < filled));
        if (text) text.textContent = v.length === 0 ? labels[0] : v.length < 8 ? `Masih ${8 - v.length} karakter lagi.` : labels[level];
    };
    input.addEventListener('input', update);
    update();
});

/* ---------- Konfirmasi password ---------- */
document.querySelectorAll('[data-match]').forEach((out) => {
    const a = document.querySelector(out.dataset.match);
    const b = document.querySelector(out.dataset.with);
    if (!a || !b) return;
    const check = () => {
        if (!b.value) {
            out.textContent = '';
            b.removeAttribute('aria-invalid');
            return;
        }
        const same = a.value === b.value;
        out.textContent = same ? 'Password cocok.' : 'Password belum sama.';
        out.className = 'field-hint ' + (same ? '' : 'text-rosella! font-medium');
        if (same) b.removeAttribute('aria-invalid');
        else b.setAttribute('aria-invalid', 'true');
    };
    a.addEventListener('input', check);
    b.addEventListener('input', check);
});

/* ---------- Status tombol saat dikirim ---------- */
document.querySelectorAll('[data-auth-form]').forEach((form) => {
    const btn = form.querySelector('.btn-submit');
    form.addEventListener('submit', (e) => {
        const confirm = form.querySelector('[data-confirm]');
        const first = form.querySelector('[data-confirm-source]');
        if (confirm && first && confirm.value !== first.value) {
            e.preventDefault();
            confirm.setAttribute('aria-invalid', 'true');
            confirm.focus();
            return;
        }
        if (btn) {
            btn.setAttribute('aria-busy', 'true');
            const label = btn.querySelector('[data-label]');
            if (label) label.textContent = btn.dataset.busy || 'Memproses';
        }
    });
});

/* ---------- Fokus ke isian pertama yang salah ---------- */
const firstError = document.querySelector('.input[aria-invalid="true"]');
if (firstError) firstError.focus({ preventScroll: false });