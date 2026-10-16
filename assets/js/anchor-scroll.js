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
 * Both halves run on every page. Left to the browser, a same-page link jumps with no
 * animation, and can lose to Lenis outright: if Lenis is still easing a wheel scroll when
 * the link is clicked, it carries on to its own target and the jump appears not to happen.
 * Handing the jump to Lenis fixes both.
 *
 * A "same-page" link is any link whose URL points at this page with a hash — a bare
 * "#ausbildungen" typed into a field, or the page's own full URL with one. A hash on another
 * page's URL is left alone: that is a normal navigation, picked up by the hash-on-load half
 * once the next page loads. So are modified clicks and new-tab links, which the reader
 * means for the browser.
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

  // "/page/" and "/page" are the same page.
  const samePath = (a, b) => a.replace(/\/$/, '') === b.replace(/\/$/, '');

  document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href*="#"]');

    if (!link || !link.hash || link.hash === '#') {
      return;
    }

    if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target === '_blank') {
      return;
    }

    if (link.origin !== window.location.origin || !samePath(link.pathname, window.location.pathname)) {
      return;
    }

    const target = findTarget(link.hash);

    if (!target) {
      return;
    }

    event.preventDefault();
    lenis.scrollTo(target, { offset: -getHeaderHeight() });
  });
}
