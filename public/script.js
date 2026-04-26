/* ===========================================
   AI GROW BOT — Landing Page Scripts
   =========================================== */

(function () {
    'use strict';

    /* -------- Navbar scroll effect -------- */
    const navbar = document.getElementById('navbar');
    const onScroll = () => {
        if (window.scrollY > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* -------- Mobile menu toggle -------- */
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.className = navLinks.classList.contains('open')
                    ? 'fa-solid fa-xmark'
                    : 'fa-solid fa-bars';
            }
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                const icon = menuToggle.querySelector('i');
                if (icon) icon.className = 'fa-solid fa-bars';
            });
        });
    }

    /* -------- Scroll reveal animation -------- */
    const revealItems = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealItems.forEach((el, i) => {
            el.style.transitionDelay = (i % 3) * 80 + 'ms';
            observer.observe(el);
        });
    } else {
        revealItems.forEach(el => el.classList.add('visible'));
    }

    /* -------- Smooth scroll for anchor links -------- */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = this.getAttribute('href');
            if (target === '#' || target.length < 2) return;
            const el = document.querySelector(target);
            if (el) {
                e.preventDefault();
                window.scrollTo({
                    top: el.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    /* -------- Dynamic year in footer -------- */
    const yearEl = document.getElementById('year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    /* -------- Pricing: billing period + currency toggle -------- */
    const billingToggle  = document.getElementById('billingToggle');
    const labelMonthly   = document.getElementById('labelMonthly');
    const labelYearly    = document.getElementById('labelYearly');
    const currencyToggle = document.getElementById('currencyToggle');
    const labelINR       = document.getElementById('labelINR');
    const labelUSD       = document.getElementById('labelUSD');
    const fxNote         = document.getElementById('fxNote');
    const amountEls      = document.querySelectorAll('.amount');
    const periodLabels   = document.querySelectorAll('[data-period-label]');
    const saveBadges     = document.querySelectorAll('[data-yearly-only]');
    const currencyEls    = document.querySelectorAll('.price-value .currency');
    const freeEls        = document.querySelectorAll('[data-free]');

    /* Exchange-rate handling (open.er-api.com — no key, CORS-enabled) */
    const FX_CACHE_KEY = 'aigb_fx_inr_usd';
    const FX_TTL_MS    = 12 * 60 * 60 * 1000; // 12 hours
    const FX_FALLBACK  = 0.012;                // ~1 INR → 0.012 USD (safety net)

    const SYMBOLS = { INR: '\u20B9', USD: '$' };
    const LOCALES = { INR: 'en-IN',  USD: 'en-US' };

    let state = {
        yearly: false,
        currency: 'INR',
        rate: FX_FALLBACK, // rate is INR→USD (1 INR = ? USD). For INR display it's unused.
        fxFetchedAt: null,
    };

    const formatMoney = (inrAmount, code, rate) => {
        const val = code === 'INR' ? inrAmount : inrAmount * rate;
        return new Intl.NumberFormat(LOCALES[code], {
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(Math.round(val));
    };

    const renderFxNote = () => {
        if (!fxNote) return;
        if (state.currency === 'INR' || !state.fxFetchedAt) {
            fxNote.hidden = true;
            fxNote.textContent = '';
            return;
        }
        const usdPerInr = state.rate;
        const inrPerUsd = usdPerInr > 0 ? 1 / usdPerInr : 0;
        fxNote.hidden = false;
        fxNote.textContent = 'Converted at live rate — 1 USD \u2248 \u20B9'
            + inrPerUsd.toFixed(2)
            + ' (updated ' + new Date(state.fxFetchedAt).toLocaleDateString() + ')';
    };

    const updateAmounts = (animate) => {
        amountEls.forEach(el => {
            if (!el.dataset.monthly || !el.dataset.yearly) return;
            const inr = Number(state.yearly ? el.dataset.yearly : el.dataset.monthly);
            const next = formatMoney(inr, state.currency, state.rate);
            if (animate) {
                el.classList.add('flip');
                setTimeout(() => { el.textContent = next; }, 220);
                setTimeout(() => el.classList.remove('flip'), 550);
            } else {
                el.textContent = next;
            }
        });

        currencyEls.forEach(el => { el.textContent = SYMBOLS[state.currency]; });
        freeEls.forEach(el => { el.textContent = SYMBOLS[state.currency] + '0'; });
        periodLabels.forEach(el => { el.textContent = state.yearly ? 'Year' : 'Month'; });
        saveBadges.forEach(el => el.classList.toggle('show', state.yearly));
        renderFxNote();
    };

    const setBilling = (yearly) => {
        state.yearly = !!yearly;
        if (billingToggle) billingToggle.classList.toggle('active', state.yearly);
        if (labelMonthly)  labelMonthly.classList.toggle('active', !state.yearly);
        if (labelYearly)   labelYearly.classList.toggle('active', state.yearly);
        updateAmounts(true);
    };

    const setCurrency = (code, persist = true) => {
        if (code !== 'INR' && code !== 'USD') code = 'INR';
        state.currency = code;
        const isUsd = code === 'USD';
        if (currencyToggle) currencyToggle.classList.toggle('active', isUsd);
        if (labelINR)       labelINR.classList.toggle('active', !isUsd);
        if (labelUSD)       labelUSD.classList.toggle('active', isUsd);
        if (persist) {
            try { localStorage.setItem('aigb_currency', code); } catch (e) {}
        }
        updateAmounts(true);
    };

    /* Fetch FX rate with localStorage cache */
    const loadCachedFx = () => {
        try {
            const raw = localStorage.getItem(FX_CACHE_KEY);
            if (!raw) return null;
            const parsed = JSON.parse(raw);
            if (!parsed || !parsed.rate || !parsed.ts) return null;
            if (Date.now() - parsed.ts > FX_TTL_MS) return null;
            return parsed;
        } catch (e) { return null; }
    };

    const saveCachedFx = (rate) => {
        try {
            localStorage.setItem(FX_CACHE_KEY, JSON.stringify({ rate, ts: Date.now() }));
        } catch (e) {}
    };

    const fetchFxRate = async () => {
        // Primary: open.er-api.com — free, no key, CORS-friendly
        try {
            const res = await fetch('https://open.er-api.com/v6/latest/INR', { cache: 'no-store' });
            if (res.ok) {
                const j = await res.json();
                if (j && j.rates && j.rates.USD) return { rate: j.rates.USD, ts: Date.now() };
            }
        } catch (e) {}
        // Fallback: exchangerate.host
        try {
            const res = await fetch('https://api.exchangerate.host/latest?base=INR&symbols=USD', { cache: 'no-store' });
            if (res.ok) {
                const j = await res.json();
                if (j && j.rates && j.rates.USD) return { rate: j.rates.USD, ts: Date.now() };
            }
        } catch (e) {}
        return null;
    };

    const ensureFxRate = async () => {
        const cached = loadCachedFx();
        if (cached) {
            state.rate = cached.rate;
            state.fxFetchedAt = cached.ts;
            return;
        }
        const fresh = await fetchFxRate();
        if (fresh) {
            state.rate = fresh.rate;
            state.fxFetchedAt = fresh.ts;
            saveCachedFx(fresh.rate);
        }
    };

    /* Detect the visitor's preferred currency.
       Priority: saved preference → IP geolocation → timezone/locale → USD default. */
    const detectCurrency = async () => {
        try {
            const saved = localStorage.getItem('aigb_currency');
            if (saved === 'INR' || saved === 'USD') return saved;
        } catch (e) {}

        // IP geolocation (free, CORS-enabled, no key)
        try {
            const res = await fetch('https://ipapi.co/json/', { cache: 'no-store' });
            if (res.ok) {
                const j = await res.json();
                const cc = (j && j.country_code ? j.country_code : '').toUpperCase();
                if (cc === 'IN') return 'INR';
                if (cc) return 'USD';
            }
        } catch (e) {}

        // Timezone / language fallback
        try {
            const tz = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
            if (/Asia\/(Kolkata|Calcutta)/i.test(tz)) return 'INR';
        } catch (e) {}
        try {
            const lang = (navigator.language || '').toLowerCase();
            if (lang === 'hi' || lang.startsWith('hi-') || lang.endsWith('-in')) return 'INR';
        } catch (e) {}

        return 'USD';
    };

    if (billingToggle && labelMonthly && labelYearly) {
        labelMonthly.classList.add('active');
        billingToggle.addEventListener('click', () => {
            setBilling(!billingToggle.classList.contains('active'));
        });
        labelMonthly.addEventListener('click', () => setBilling(false));
        labelYearly.addEventListener('click',  () => setBilling(true));
    }

    if (currencyToggle && labelINR && labelUSD) {
        labelINR.classList.add('active');
        currencyToggle.addEventListener('click', () => {
            setCurrency(currencyToggle.classList.contains('active') ? 'INR' : 'USD');
        });
        labelINR.addEventListener('click', () => setCurrency('INR'));
        labelUSD.addEventListener('click', () => setCurrency('USD'));

        // Kick off: render with fallback rate, then refine with live rate + auto-detect
        (async () => {
            await ensureFxRate();                  // may update state.rate silently
            const detected = await detectCurrency();
            setCurrency(detected, false);          // don't overwrite user's saved pref
            updateAmounts(false);                  // ensure note reflects fresh rate
        })();
    }

    /* -------- Enquiry form → WhatsApp redirect -------- */
    const enquiryForm = document.getElementById('enquiryForm');
    if (enquiryForm) {
        const WA_NUMBER = '919870217934'; // +91 98702 17934
        const submitBtn = enquiryForm.querySelector('.form-submit');

        // Trigger staggered field animations when form scrolls into view
        if ('IntersectionObserver' in window) {
            const formObs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        enquiryForm.classList.add('visible');
                        formObs.unobserve(enquiryForm);
                    }
                });
            }, { threshold: 0.15 });
            formObs.observe(enquiryForm);
        } else {
            enquiryForm.classList.add('visible');
        }

        // Shake the whole form on invalid submit
        const shakeForm = () => {
            enquiryForm.style.animation = 'none';
            void enquiryForm.offsetWidth;
            enquiryForm.style.animation = 'shake .45s';
            setTimeout(() => { enquiryForm.style.animation = ''; }, 500);
        };

        enquiryForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const name = enquiryForm.fName.value.trim();
            const email = enquiryForm.fEmail.value.trim();
            const phone = enquiryForm.fPhone.value.trim();
            const subject = enquiryForm.fSubject.value.trim();
            const message = enquiryForm.fMessage.value.trim();

            // Required validation with shake animation
            let hasError = false;
            let firstInvalid = null;
            [['fName', name], ['fEmail', email], ['fSubject', subject], ['fMessage', message]].forEach(([id, val]) => {
                const field = document.getElementById(id);
                field.classList.remove('invalid');
                if (!val) {
                    void field.offsetWidth;
                    field.classList.add('invalid');
                    hasError = true;
                    if (!firstInvalid) firstInvalid = field;
                }
            });
            if (hasError) {
                shakeForm();
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            // Ripple animation on submit button
            submitBtn.classList.remove('rippling');
            void submitBtn.offsetWidth;
            submitBtn.classList.add('rippling');
            setTimeout(() => submitBtn.classList.remove('rippling'), 600);

            // Build WhatsApp message
            const lines = [
                '*New Enquiry — AI Grow Bot*',
                '',
                `*Name:* ${name}`,
                `*Email:* ${email}`,
            ];
            if (phone) lines.push(`*Phone:* ${phone}`);
            lines.push(`*Subject:* ${subject}`);
            lines.push('');
            lines.push(`*Message:*`);
            lines.push(message);

            const text = encodeURIComponent(lines.join('\n'));
            const waUrl = `https://wa.me/${WA_NUMBER}?text=${text}`;

            // Show animated success state above submit button
            const existing = enquiryForm.querySelector('.form-success');
            if (existing) existing.remove();
            const msg = document.createElement('div');
            msg.className = 'form-success';
            msg.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Opening WhatsApp…';
            enquiryForm.insertBefore(msg, submitBtn);

            // Redirect to WhatsApp
            setTimeout(() => window.open(waUrl, '_blank'), 350);

            // Reset form after short delay
            setTimeout(() => {
                enquiryForm.reset();
                msg.remove();
            }, 2500);
        });

        // Clear invalid state on input
        enquiryForm.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', () => el.classList.remove('invalid'));
            el.addEventListener('change', () => el.classList.remove('invalid'));
        });
    }

    /* -------- Video player (inline on site) -------- */
    const videoPlayer = document.getElementById('videoPlayer');
    if (videoPlayer) {
        const videoId = videoPlayer.dataset.videoId;
        const trigger = videoPlayer.querySelector('.video-play');

        const playVideo = (e) => {
            if (e) e.preventDefault();
            if (videoPlayer.classList.contains('playing')) return;

            // Build the embed iframe — play inline on the site
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&playsinline=1`;
            iframe.title = 'AI Grow Bot Demo Video';
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
            iframe.setAttribute('allowfullscreen', '');
            iframe.style.position = 'absolute';
            iframe.style.inset = '0';
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.style.border = '0';

            videoPlayer.appendChild(iframe);
            videoPlayer.classList.add('playing');
        };

        videoPlayer.addEventListener('click', playVideo);
        if (trigger) {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                playVideo(e);
            });
        }
    }

    /* -------- FAQ accordion -------- */
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const q = item.querySelector('.faq-q');
        if (!q) return;
        q.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            // Close all others for a clean accordion feel
            faqItems.forEach(i => {
                i.classList.remove('open');
                const btn = i.querySelector('.faq-q');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                item.classList.add('open');
                q.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* -------- Subtle parallax on hero floating shapes -------- */
    const shapes = document.querySelectorAll('.floating-shapes .shape');
    if (shapes.length && window.matchMedia('(min-width: 980px)').matches) {
        window.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            shapes.forEach((s, i) => {
                const depth = (i + 1) * 0.4;
                s.style.transform = `translate(${x * depth}px, ${y * depth}px)`;
            });
        });
    }
})();
