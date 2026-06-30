/* =============================================
   MAIN.JS — MI POLSRI v2.0
   FIX: Counter animasi, Lightbox, Dark Mode,
        Navbar, Fade-in, Form Validation
   ============================================= */

document.addEventListener('DOMContentLoaded', () => {

    /* ══════════════════════════════════════
       1. DARK / LIGHT MODE TOGGLE
    ══════════════════════════════════════ */
    const html        = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon   = document.getElementById('themeIcon');

    const savedTheme = localStorage.getItem('mi-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', (e) => {
            const current = html.getAttribute('data-theme');
            const next    = current === 'light' ? 'dark' : 'light';
            
            const rect = themeToggle.getBoundingClientRect();
            const x = e.clientX || (rect.left + rect.width / 2);
            const y = e.clientY || (rect.top + rect.height / 2);
            
            const overlay = document.createElement('div');
            overlay.className = 'theme-transition-overlay';
            overlay.style.setProperty('--click-x', x + 'px');
            overlay.style.setProperty('--click-y', y + 'px');
            overlay.style.background = next === 'dark' ? '#090D16' : '#FFFFFF';
            document.body.appendChild(overlay);
            
            if (themeIcon) {
                themeIcon.style.transform = 'rotate(360deg) scale(1.3)';
                themeIcon.style.transition = 'transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
            }
            
            requestAnimationFrame(() => {
                overlay.classList.add('active');
            });
            
            setTimeout(() => {
                html.setAttribute('data-theme', next);
                localStorage.setItem('mi-theme', next);
                updateThemeIcon(next);
            }, 400);
            
            setTimeout(() => {
                overlay.remove();
                if (themeIcon) {
                    themeIcon.style.transform = '';
                    themeIcon.style.transition = '';
                }
            }, 850);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeIcon) return;
        themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        if (themeToggle) themeToggle.title = theme === 'dark' ? 'Mode Terang' : 'Mode Gelap';
    }

    /* ══════════════════════════════════════
       2. NAVBAR SCROLL EFFECT
    ══════════════════════════════════════ */
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        const onScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 20);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // jalankan sekali saat load
    }

    /* ══════════════════════════════════════
       3. BACK TO TOP
    ══════════════════════════════════════ */
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            backToTopBtn.classList.toggle('visible', window.scrollY > 400);
        }, { passive: true });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ══════════════════════════════════════
       4. ANIMATED COUNTER — FIX UTAMA
       Menggunakan IntersectionObserver agar
       counter mulai saat elemen terlihat,
       bukan saat halaman load
    ══════════════════════════════════════ */
    const counters = document.querySelectorAll('.counter');

    if (counters.length > 0) {
        const counterObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.dataset.done) {
                        entry.target.dataset.done = '1';
                        animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );
        counters.forEach(c => counterObserver.observe(c));
    }

    function animateCounter(el) {
        const target   = parseInt(el.dataset.target || '0', 10);
        const duration = 2000;
        const fps      = 60;
        const steps    = Math.floor(duration / (1000 / fps));
        const increment = target / steps;
        let current    = 0;
        let frame      = 0;

        // Easing: ease-out quad
        function easeOut(t) { return 1 - (1 - t) * (1 - t); }

        const tick = () => {
            frame++;
            const progress = Math.min(frame / steps, 1);
            current = Math.round(easeOut(progress) * target);
            el.textContent = current.toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(tick);
            else el.textContent = target.toLocaleString('id-ID');
        };
        requestAnimationFrame(tick);
    }

    /* ══════════════════════════════════════
       5. FADE-UP ON SCROLL
    ══════════════════════════════════════ */
    const fadeEls = document.querySelectorAll('.fade-up');
    if (fadeEls.length > 0) {
        const fadeObserver = new IntersectionObserver(
            (entries) => {
                const intersecting = entries.filter(entry => entry.isIntersecting);
                intersecting.forEach((entry, index) => {
                    // Stagger delay berdasarkan urutan antrian yang muncul bersamaan
                    const delay = index * 100;
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, delay);
                    fadeObserver.unobserve(entry.target);
                });
            },
            { threshold: 0.1, rootMargin: '0px 0px -50px 0px' }
        );
        fadeEls.forEach(el => fadeObserver.observe(el));
    }

    /* ══════════════════════════════════════
       6. LIGHTBOX GALERI
    ══════════════════════════════════════ */
    initLightbox();

    function initLightbox() {
        const galeriItems = document.querySelectorAll('[data-lightbox]');
        if (!galeriItems.length) return;

        // Buat overlay sekali
        const overlay = document.createElement('div');
        overlay.className = 'lightbox-overlay';
        overlay.innerHTML = `
            <div class="lightbox-img-wrap">
                <button class="lightbox-close" id="lbClose" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
                <button class="lightbox-prev" id="lbPrev" aria-label="Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="lightbox-next" id="lbNext" aria-label="Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <img src="" alt="" id="lbImg" />
                <p class="lightbox-caption" id="lbCaption"></p>
            </div>`;
        document.body.appendChild(overlay);

        const lbImg     = document.getElementById('lbImg');
        const lbCaption = document.getElementById('lbCaption');
        const lbClose   = document.getElementById('lbClose');
        const lbPrev    = document.getElementById('lbPrev');
        const lbNext    = document.getElementById('lbNext');
        const images    = Array.from(galeriItems);
        let currentIdx  = 0;

        function openLightbox(idx) {
            currentIdx = (idx + images.length) % images.length;
            const item = images[currentIdx];
            lbImg.src  = item.dataset.lightbox;
            lbImg.alt  = item.dataset.caption || '';
            lbCaption.textContent = item.dataset.caption || '';
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        images.forEach((item, idx) => {
            item.style.cursor = 'pointer';
            item.addEventListener('click', () => openLightbox(idx));
        });

        lbClose.addEventListener('click', closeLightbox);
        lbPrev.addEventListener('click',  () => openLightbox(currentIdx - 1));
        lbNext.addEventListener('click',  () => openLightbox(currentIdx + 1));
        overlay.addEventListener('click', e => { if (e.target === overlay) closeLightbox(); });

        document.addEventListener('keydown', e => {
            if (!overlay.classList.contains('active')) return;
            if (e.key === 'Escape')     closeLightbox();
            if (e.key === 'ArrowLeft')  openLightbox(currentIdx - 1);
            if (e.key === 'ArrowRight') openLightbox(currentIdx + 1);
        });
    }

    /* ══════════════════════════════════════
       7. FLASH MESSAGE AUTO DISMISS
    ══════════════════════════════════════ */
    const flashMsg = document.getElementById('flashMsg');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.5s, transform 0.5s';
            flashMsg.style.opacity    = '0';
            flashMsg.style.transform  = 'translateX(120%)';
            setTimeout(() => flashMsg.remove(), 500);
        }, 4500);
    }

    /* ══════════════════════════════════════
       8. MOBILE NAV — tutup saat link diklik
    ══════════════════════════════════════ */
    document.querySelectorAll('#navMenu .nav-link:not(.dropdown-toggle)').forEach(link => {
        link.addEventListener('click', () => {
            const nav = document.getElementById('navMenu');
            if (nav?.classList.contains('show')) {
                const bsCollapse = window.bootstrap?.Collapse?.getInstance(nav);
                bsCollapse?.hide();
            }
        });
    });

    /* ══════════════════════════════════════
       9. CONTACT FORM — Real-time Validation
    ══════════════════════════════════════ */
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        const inputs = contactForm.querySelectorAll('input, textarea');

        inputs.forEach(input => input.addEventListener('blur', () => validateField(input)));

        contactForm.addEventListener('submit', e => {
            let allValid = true;
            inputs.forEach(input => { if (!validateField(input)) allValid = false; });
            if (!allValid) e.preventDefault();
        });

        function validateField(field) {
            const wrapper = field.closest('.field-wrap') || field.parentElement;
            let errEl = wrapper.querySelector('.field-error');
            if (!errEl) {
                errEl = document.createElement('span');
                errEl.className = 'field-error';
                errEl.style.cssText = 'color:#E11D48;font-size:0.78rem;display:block;margin-top:4px;';
                wrapper.appendChild(errEl);
            }

            const val = field.value.trim();

            if (!val) {
                setFieldState(field, errEl, false, 'Field ini wajib diisi.');
                return false;
            }
            if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                setFieldState(field, errEl, false, 'Format email tidak valid.');
                return false;
            }
            if (field.name === 'nama' && val.length < 3) {
                setFieldState(field, errEl, false, 'Nama minimal 3 karakter.');
                return false;
            }
            if (field.name === 'pesan' && val.length < 10) {
                setFieldState(field, errEl, false, 'Pesan minimal 10 karakter.');
                return false;
            }

            setFieldState(field, errEl, true, '');
            return true;
        }

        function setFieldState(field, errEl, valid, msg) {
            field.style.borderColor = valid ? 'var(--clr-primary)' : '#E11D48';
            errEl.textContent = msg;
        }
    }

    /* ══════════════════════════════════════
       10. SMOOTH SCROLL untuk anchor links
    ══════════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const top = target.getBoundingClientRect().top + window.scrollY - 80;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

    /* ══════════════════════════════════════
       11. CHARACTER COUNTER (kontak textarea)
    ══════════════════════════════════════ */
    const pesanField = document.getElementById('pesan');
    const charCount  = document.getElementById('charCount');
    if (pesanField && charCount) {
        const updateCount = () => {
            const len = pesanField.value.length;
            charCount.textContent = `${len} / 2000`;
            charCount.style.color = len > 1800 ? '#E11D48' : '';
        };
        pesanField.addEventListener('input', updateCount);
        updateCount();
    }

    /* ══════════════════════════════════════
       12. ADMIN SIDEBAR TOGGLE (mobile)
    ══════════════════════════════════════ */
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar  = document.getElementById('adminSidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', () => {
            adminSidebar.classList.toggle('open');
        });
        // Tutup saat klik di luar
        document.addEventListener('click', e => {
            if (!adminSidebar.contains(e.target) && e.target !== sidebarToggle) {
                adminSidebar.classList.remove('open');
            }
        });
    }

    /* ══════════════════════════════════════
       13. HERO FLOATING CARDS PARALLAX (ringan)
    ══════════════════════════════════════ */
    const floatCards = document.querySelectorAll('.float-card');
    if (floatCards.length && window.innerWidth > 991) {
        window.addEventListener('mousemove', e => {
            const x = (e.clientX / window.innerWidth  - 0.5) * 15;
            const y = (e.clientY / window.innerHeight - 0.5) * 10;
            floatCards.forEach((card, i) => {
                const dir = i % 2 === 0 ? 1 : -1;
                card.style.transform = `translate(${x * dir * 0.4}px, ${y * dir * 0.3}px)`;
            });
        }, { passive: true });
    }

    /* ══════════════════════════════════════
       14. AUTOMATIC SKELETON SHIMMER LOADER FOR IMAGES
    ══════════════════════════════════════ */
    const targetImages = document.querySelectorAll('.news-card img, .galeri-item img, .prestasi-card img, .dosen-card img, .org-card img');
    targetImages.forEach(img => {
        const wrapper = img.parentElement;
        if (!wrapper) return;
        
        wrapper.classList.add('skeleton-wrap');
        
        if (img.complete) {
            wrapper.classList.add('loaded');
        } else {
            img.addEventListener('load', () => {
                wrapper.classList.add('loaded');
            });
            img.addEventListener('error', () => {
                wrapper.classList.add('loaded');
            });
        }
    });

}); // end DOMContentLoaded
