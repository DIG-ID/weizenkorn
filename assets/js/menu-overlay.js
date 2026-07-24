/**
 * Mega menu overlay: open/close "roll down" reveal + hover/focus image swap.
 * Markup: template-parts/menu-overlay.php + template-parts/components/header-nav.php.
 */

import { gsap } from './gsap.js';

const IMAGE_FADE_MS = 200;

export function initMenuOverlay() {
  const toggles = document.querySelectorAll('.header-main__menu-toggle');
  const overlay = document.getElementById('menu-overlay');

  if (!toggles.length || !overlay) {
    return;
  }

  const image = overlay.querySelector('.menu-overlay__image-el');
  const imageContainer = overlay.querySelector('.menu-overlay__image');
  const grid = overlay.querySelector('.menu-overlay__grid');
  const header = document.querySelector('.header-main');
  const defaultSrc = image ? image.dataset.defaultSrc : '';

  // --header-height (used by .menu-overlay's padding-top, see
  // _menu-overlay.sass) must match the header's REAL rendered height, not a
  // hand-calculated guess — logo aspect ratio, WPML markup, etc. can make it
  // taller than assumed, pushing the divider/content behind the fixed header.
  const syncHeaderHeight = () => {
    if (!header) {
      return;
    }
    document.documentElement.style.setProperty('--header-height', `${header.offsetHeight}px`);
  };

  syncHeaderHeight();
  window.addEventListener('resize', syncHeaderHeight, { passive: true });
  window.addEventListener('load', syncHeaderHeight);

  // Cap the image to the grid's own rendered height. CSS alone can't do this
  // reliably here: the grid's rows are auto-sized to fit the text columns'
  // content (needed so "row 2" aligns across columns), and a grid item's
  // percentage height resolves against that content-sized area — not
  // against the grid's own (possibly smaller, scrollable) visible box.
  const syncImageHeight = () => {
    if (!grid || !imageContainer) {
      return;
    }
    imageContainer.style.maxHeight = `${grid.clientHeight}px`;
  };

  syncImageHeight();
  window.addEventListener('resize', syncImageHeight, { passive: true });

  const setImage = (src) => {
    if (!image || !src || image.getAttribute('src') === src) {
      return;
    }

    image.classList.add('is-fading');

    setTimeout(() => {
      image.setAttribute('src', src);
      image.classList.remove('is-fading');
    }, IMAGE_FADE_MS);
  };

  overlay.addEventListener('mouseover', (e) => {
    const link = e.target.closest('[data-menu-image]');
    if (link) {
      setImage(link.dataset.menuImage);
    }
  });

  overlay.addEventListener('focusin', (e) => {
    const link = e.target.closest('[data-menu-image]');
    if (link) {
      setImage(link.dataset.menuImage);
    }
  });

  overlay.addEventListener('mouseleave', () => setImage(defaultSrc));

  overlay.addEventListener('focusout', () => {
    if (!overlay.contains(document.activeElement)) {
      setImage(defaultSrc);
    }
  });

  const setExpanded = (expanded) => {
    toggles.forEach((toggle) => toggle.setAttribute('aria-expanded', String(expanded)));
  };

  // "Roll down" open: a clip-path tween reveals the overlay from the top
  // edge downward, then the menu groups (and the image) fade/slide in with
  // a stagger. Targets are the top-level <li> (each a heading + its
  // sub-menu, one unit) and .menu-overlay__image — NOT .menu-overlay__col
  // or its <ul>, since those become `display:contents` at xl (see
  // _menu-overlay.sass) and opacity/transform have no effect on a
  // `contents` box.
  const revealTargets = overlay.querySelectorAll('.menu-overlay__col > ul > li, .menu-overlay__image');

  gsap.set(revealTargets, { autoAlpha: 0, y: -16 });

  const revealTl = gsap.timeline({ paused: true })
    .to(overlay, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.6, ease: 'power3.out' })
    .to(revealTargets, { autoAlpha: 1, y: 0, duration: 0.45, ease: 'power2.out', stagger: 0.06 }, '-=0.3');

  // Only visible/focusable once the "closed" reverse animation has fully finished.
  revealTl.eventCallback('onReverseComplete', () => {
    overlay.style.visibility = 'hidden';
    overlay.setAttribute('aria-hidden', 'true');
  });

  const openMenu = () => {
    overlay.style.visibility = 'visible';
    overlay.removeAttribute('aria-hidden');
    setExpanded(true);
    document.body.classList.add('menu-open');
    syncHeaderHeight();
    syncImageHeight();
    revealTl.play();
  };

  const closeMenu = () => {
    setExpanded(false);
    document.body.classList.remove('menu-open');
    revealTl.reverse();
  };

  const isMenuOpen = () => toggles.length > 0 && toggles[0].getAttribute('aria-expanded') === 'true';

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      isOpen ? closeMenu() : openMenu();
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isMenuOpen()) {
      closeMenu();
    }
  });
}
