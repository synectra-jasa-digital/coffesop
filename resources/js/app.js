import './echo';

/**
 * Global front-end interactivity for Good Coffee.
 * - Scroll reveal (IntersectionObserver) for elegant entrance motion
 * - Navbar scroll state (shadow/hairline toggle)
 * - Mobile menu helpers where Alpine isn't present
 */

document.addEventListener('DOMContentLoaded', () => {
    initScrollReveal();
    initNavbarScroll();
    initActiveNavHighlight();
});

/**
 * Reveal elements as they enter the viewport.
 * Elements opt-in with `data-reveal` and optional `data-reveal-delay`.
 */
function initScrollReveal() {
    const revealEls = document.querySelectorAll('[data-reveal]');
    if (!revealEls.length) return;

    // Respect reduced-motion: reveal instantly.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        revealEls.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const delay = entry.target.getAttribute('data-reveal-delay');
                    if (delay) {
                        entry.target.style.transitionDelay = `${delay}ms`;
                    }
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    revealEls.forEach((el) => observer.observe(el));
}

/**
 * Toggle a hairline border / background on the top navbar after scroll.
 */
function initNavbarScroll() {
    const navbar = document.querySelector('[data-navbar-scroll]');
    if (!navbar) return;

    const apply = () => {
        if (window.scrollY > 8) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    };

    window.addEventListener('scroll', apply, { passive: true });
    apply();
}

/**
 * Ensure the active sidebar item stays visible / highlighted.
 * (Alpine normally owns highlighting; this is a safeguard for none-Alpine pages.)
 */
function initActiveNavHighlight() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('[data-nav-link]').forEach((link) => {
        const href = link.getAttribute('href') || '';
        if (href !== '/' && currentPath.startsWith(href)) {
            link.classList.add('bg-primary-light', 'text-primary');
        }
    });
}
