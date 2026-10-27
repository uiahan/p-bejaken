const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

/* ---------- Navigasi ---------- */
const nav = document.querySelector('[data-nav]');
if (nav) {
    const onScroll = () => nav.setAttribute('data-scrolled', String(window.scrollY > 8));
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const toggle = nav.querySelector('[data-menu-toggle]');
    const menu = nav.querySelector('[data-menu]');
    if (toggle && menu) {
        const setOpen = (open) => {
            menu.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        };
        toggle.addEventListener('click', () => setOpen(!menu.classList.contains('open')));
        menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => setOpen(false)));
    }
}

/* ---------- Hero: pilih varian, warna menyebar dari tombol ---------- */
const hero = document.querySelector('[data-hero]');
if (hero) {
    const variants = JSON.parse(hero.dataset.variants);
    const buttons = [...hero.querySelectorAll('[data-pick]')];
    const bottle = hero.querySelector('[data-bottle]');
    const labelName = hero.querySelector('[data-label-name]');
    const noteName = hero.querySelector('[data-note-name]');
    const noteText = hero.querySelector('[data-note-text]');

    let current = Number(hero.dataset.start || 0);
    let busy = false;

    // Botol terisi setelah halaman siap.
    requestAnimationFrame(() => {
        setTimeout(() => hero.classList.add('is-ready'), reduceMotion ? 0 : 700);
    });

    const paintBottle = (v) => {
        bottle.style.setProperty('--b', v.cairan);
        labelName.textContent = v.nama;
        noteName.textContent = v.nama;
        noteText.textContent = v.manfaat;
    };

    const setPressed = (index) =>
        buttons.forEach((b, i) => b.setAttribute('aria-pressed', String(i === index)));

    const choose = (index, button) => {
        if (index === current || busy) return;
        const v = variants[index];
        setPressed(index);

        if (reduceMotion) {
            hero.style.setProperty('--v', v.latar);
            hero.style.setProperty('--v-ink', v.teks);
            paintBottle(v);
            current = index;
            return;
        }

        busy = true;
        const h = hero.getBoundingClientRect();
        const b = button.getBoundingClientRect();
        const x = b.left + b.width / 2 - h.left;
        const y = b.top + b.height / 2 - h.top;
        const radius = Math.hypot(Math.max(x, h.width - x), Math.max(y, h.height - y));

        const flood = document.createElement('div');
        flood.className = 'hero-flood';
        flood.style.background = v.latar;
        hero.prepend(flood);

        hero.classList.add('is-draining');
        setTimeout(() => hero.style.setProperty('--v-ink', v.teks), 400);
        setTimeout(() => {
            paintBottle(v);
            hero.classList.remove('is-draining');
        }, 540);

        flood
            .animate(
                [
                    { clipPath: `circle(0px at ${x}px ${y}px)` },
                    { clipPath: `circle(${radius}px at ${x}px ${y}px)` },
                ],
                { duration: 850, easing: 'cubic-bezier(0.65, 0, 0.35, 1)', fill: 'forwards' }
            )
            .finished.then(() => {
                hero.style.setProperty('--v', v.latar);
                flood.remove();
                current = index;
                busy = false;
            });
    };

    buttons.forEach((button, i) => button.addEventListener('click', () => choose(i, button)));
    setPressed(current);
}

/* ---------- Tandai elemen yang sudah terlihat ---------- */
const onceVisible = (selector, callback, threshold = 0.35) => {
    const els = document.querySelectorAll(selector);
    if (!els.length) return;
    if (!('IntersectionObserver' in window)) {
        els.forEach(callback);
        return;
    }
    const io = new IntersectionObserver(
        (entries) =>
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    io.unobserve(entry.target);
                    callback(entry.target);
                }
            }),
        { threshold }
    );
    els.forEach((el) => io.observe(el));
};

// Batang masa simpan
onceVisible('[data-bars]', (el) => el.classList.add('in-view'), 0.5);

// Contoh percakapan chatbot
onceVisible('[data-chat]', async (chat) => {
    const steps = [...chat.querySelectorAll('[data-step]')];
    if (reduceMotion) {
        steps.forEach((s) => s.classList.add('show', 'done'));
        return;
    }
    for (const step of steps) {
        step.classList.add('show');
        if (step.dataset.step === 'bot') {
            await wait(1300);
            step.classList.add('done');
        }
        await wait(650);
    }
});

/* ---------- Indikator section aktif ---------- */

const sectionLinks = [
    ...document.querySelectorAll('[data-section-link]')
];

const sections = ['cerita', 'varian', 'segar', 'chatbot']
    .map((id) => document.getElementById(id))
    .filter(Boolean);

const setActiveSection = (activeId) => {
    sectionLinks.forEach((link) => {
        if (link.dataset.sectionLink === activeId) {
            link.setAttribute('aria-current', 'location');
        } else {
            link.removeAttribute('aria-current');
        }
    });
};

if (sections.length && 'IntersectionObserver' in window) {
    const visibleSections = new Map();

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    visibleSections.set(entry.target.id, entry.intersectionRatio);
                } else {
                    visibleSections.delete(entry.target.id);
                }
            });

            const activeSection = [...visibleSections.entries()]
                .sort((a, b) => b[1] - a[1])[0];

            if (activeSection) {
                setActiveSection(activeSection[0]);
            }
        },
        {
            rootMargin: '-20% 0px -55% 0px',
            threshold: [0, 0.1, 0.25, 0.5, 0.75, 1]
        }
    );

    sections.forEach((section) => observer.observe(section));
}