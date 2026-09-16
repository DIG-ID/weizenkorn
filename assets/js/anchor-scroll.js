import { lenis } from './gsap.js';

/**
 * Smooth-scrolls same-page anchor links (`href="#id"`) via Lenis instead of the browser's
 * instant native jump. Lenis needs this: it sets scroll-behavior:auto on <html> precisely
 * to stop the CSS scroll-behavior:smooth fallback fighting its own JS-driven scroll (see
 * _lenis.sass), so without this a click on one currently jumps with no animation at all.
 * Also re-plays that same jump when a page is *loaded* with a hash already in the URL
 * (e.g. a link on another page pointing at /kontakt#location) — the browser's own native
 * jump has normally already happened by the time this runs, so this resets to the top
 * first and lets Lenis animate back down, rather than leave the page sitting there with no
 * visible scroll at all.
 *
 * The click-triggered half stays home-only for now (the sticky-cta widget's own first
 * anchor link) — gated on the ".home" body class WordPress adds to the front page, same
 * scope as the is_front_page() check that includes sticky-cta.php in header.php. The
 * hash-on-load half runs on every page: nothing about it is home-specific, and a page like
 * Kontakt is exactly where an incoming #location link is expected to land.
 *
 * Offset by --header-height (assets/js/menu-overlay.js keeps it synced to the real header):
 * at rest the header sits in normal flow, but past the point where sticky-header.js shows
 * the fixed header-main__sticky bar — the exact scroll distance an anchor jump covers —
 * that bar would otherwise cover the target section's own heading.
 *
 * @returns {void}
 */
export function initAnchorScroll() {
  const getHeaderHeight = () => {
    const raw = getComputedStyle(document.documentElement).getPropertyValue('--header-height');
    return parseFloat(raw) || 0;
  };

  const findTarget = (hash) => {
    try {
      return document.querySelector(hash);
    } catch (error) {
      return null; // Not a valid CSS selector (e.g. a bare "#" fragment some plugin adds).
    }
  };

  if (window.location.hash) {
    const target = findTarget(window.location.hash);

    if (target) {
      window.scrollTo(0, 0);
      lenis.scrollTo(target, { offset: -getHeaderHeight() });
    }
  }

  if (!document.body.classList.contains('home')) {
    return;
  }

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href^="#"]');

    if (!link || link.getAttribute('href') === '#') {
      return;
    }

    const target = findTarget(link.getAttribute('href'));

    if (!target) {
      return;
    }

    event.preventDefault();
    lenis.scrollTo(target, { offset: -getHeaderHeight() });
  });
}
