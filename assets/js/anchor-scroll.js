import { lenis } from './gsap.js';

/**
 * Smooth-scrolls same-page anchor links (`href="#id"`) via Lenis instead of the browser's
 * instant native jump. Lenis needs this: it sets scroll-behavior:auto on <html> precisely
 * to stop the CSS scroll-behavior:smooth fallback fighting its own JS-driven scroll (see
 * _lenis.sass), so without this a click on one currently jumps with no animation at all.
 *
 * Home only for now (the sticky-cta widget's own first anchor link) — gated on the
 * ".home" body class WordPress adds to the front page, same scope as the is_front_page()
 * check that includes sticky-cta.php in header.php.
 *
 * Offset by --header-height (assets/js/menu-overlay.js keeps it synced to the real header):
 * at rest the header sits in normal flow, but past the point where sticky-header.js shows
 * the fixed header-main__sticky bar — the exact scroll distance an anchor jump covers —
 * that bar would otherwise cover the target section's own heading.
 *
 * @returns {void}
 */
export function initAnchorScroll() {
  if (!document.body.classList.contains('home')) {
    return;
  }

  const getHeaderHeight = () => {
    const raw = getComputedStyle(document.documentElement).getPropertyValue('--header-height');
    return parseFloat(raw) || 0;
  };

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href^="#"]');

    if (!link || link.getAttribute('href') === '#') {
      return;
    }

    let target;
    try {
      target = document.querySelector(link.getAttribute('href'));
    } catch (error) {
      return; // Not a valid CSS selector (e.g. a bare "#" fragment some plugin adds).
    }

    if (!target) {
      return;
    }

    event.preventDefault();
    lenis.scrollTo(target, { offset: -getHeaderHeight() });
  });
}
