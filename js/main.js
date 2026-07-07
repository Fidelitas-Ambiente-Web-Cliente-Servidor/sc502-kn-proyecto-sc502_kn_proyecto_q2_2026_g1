document.addEventListener('DOMContentLoaded', () => {
    // ── Hamburger menu ──────────────────────────────────────────
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    hamburger?.addEventListener('click', () => {
        navMenu?.classList.toggle('open');
    });

    // ── Pet Carousel ─────────────────────────────────────────────
    const track    = document.getElementById('carouselTrack');
    const prevBtn  = document.getElementById('carouselPrev');
    const nextBtn  = document.getElementById('carouselNext');
    const dotsEl   = document.getElementById('carouselDots');

    if (track && prevBtn && nextBtn) {
        const cards        = Array.from(track.children);
        const totalCards   = cards.length;

        // How many cards are visible at once (mirrors CSS breakpoints)
        function visibleCount() {
            if (window.innerWidth <= 600)  return 1;
            if (window.innerWidth <= 1000) return 2;
            return 4;
        }

        // Card width = (viewport width - gaps) / visibleCount
        function cardWidth() {
            const viewport = track.parentElement.offsetWidth;
            const vis      = visibleCount();
            const gap      = 27; // 1.7rem ≈ 27px
            return (viewport - gap * (vis - 1)) / vis;
        }

        // Total number of "pages" (positions we can snap to)
        function totalPages() {
            return totalCards - visibleCount() + 1;
        }

        let current = 0;
        let autoTimer;

        // Build dot indicators
        function buildDots() {
            if (!dotsEl) return;
            dotsEl.innerHTML = '';
            const pages = totalPages();
            for (let i = 0; i < pages; i++) {
                const dot = document.createElement('button');
                dot.className = 'carousel-dot' + (i === current ? ' active' : '');
                dot.setAttribute('aria-label', `Ir a mascota ${i + 1}`);
                dot.addEventListener('click', () => goTo(i));
                dotsEl.appendChild(dot);
            }
        }

        function updateDots() {
            if (!dotsEl) return;
            dotsEl.querySelectorAll('.carousel-dot').forEach((d, i) => {
                d.classList.toggle('active', i === current);
            });
        }

        function goTo(index) {
            const pages = totalPages();
            current = Math.max(0, Math.min(index, pages - 1));
            const offset = current * (cardWidth() + 27);
            track.style.transform = `translateX(-${offset}px)`;
            updateDots();
        }

        function next() { goTo(current + 1 < totalPages() ? current + 1 : 0); }
        function prev() { goTo(current - 1 >= 0 ? current - 1 : totalPages() - 1); }

        function startAuto() {
            stopAuto();
            autoTimer = setInterval(next, 3500);
        }

        function stopAuto() {
            clearInterval(autoTimer);
        }

        nextBtn.addEventListener('click', () => { next(); stopAuto(); startAuto(); });
        prevBtn.addEventListener('click', () => { prev(); stopAuto(); startAuto(); });

        // Recalculate on resize
        window.addEventListener('resize', () => {
            buildDots();
            goTo(0);
        });

        // Touch / swipe support
        let touchStartX = 0;
        track.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend',   e => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) { diff > 0 ? next() : prev(); stopAuto(); startAuto(); }
        });

        buildDots();
        goTo(0);
        startAuto();
    }

    // ── Contact form ──────────────────────────────────────────────
    const contactForm    = document.getElementById('contactForm');
    const contactSuccess = document.getElementById('contactSuccess');
    if (contactForm && contactSuccess) {
        contactForm.addEventListener('submit', e => {
            e.preventDefault();
            contactForm.style.display = 'none';
            contactSuccess.style.display = 'block';
        });
    }
});
