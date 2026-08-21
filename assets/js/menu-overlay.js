/**
 * Mega menu overlay: open/close "roll down" reveal, hover/focus image swap,
 * and the mobile (<768px) tap-to-expand accordion.
 * Markup: template-parts/menu-overlay.php + template-parts/components/header-nav.php.
 */

import { gsap } from './gsap.js';

const IMAGE_FADE_MS = 200;
const MOBILE_BREAKPOINT = 768;
const ARROW_ICON = '<svg width="24" height="19" viewBox="0 0 23.7301 18.632" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 9.31602H22M10 17.816L22 9.31602L10 0.816024" stroke="currentColor" stroke-width="2" /></svg>';

/**
 * Mobile accordion: tapping a group heading expands its links, while a group with a single
 * link is always expanded. Reads the real sub-menu child count rather than hardcoding which
 * groups qualify, editors being free to add and remove links at any time.
 */
function initMobileMenuAccordion(overlay) {
  const groups = overlay.querySelectorAll('.menu-overlay__col > ul > li');
  const overviewLabel = overlay.dataset.overviewLabel || 'Übersicht';

  groups.forEach((group, index) => {
    const link = group.querySelector(':scope > a');
    const subMenu = group.querySelector(':scope > .sub-menu');

    if (!link || !subMenu) {
      return;
    }

    if (subMenu.children.length <= 1) {
      group.classList.add('menu-overlay__group--flat');
      return;
    }

    group.classList.add('menu-overlay__group--accordion');

    // The heading's own link becomes the accordion toggle below, so its destination would
    // otherwise be unreachable on mobile — added back as the sub-menu's first entry.
    const overviewItem = document.createElement('li');
    overviewItem.className = 'menu-overlay__group-overview';
    const overviewLink = document.createElement('a');
    overviewLink.href = link.getAttribute('href');
    overviewLink.textContent = overviewLabel;
    overviewItem.appendChild(overviewLink);
    subMenu.insertBefore(overviewItem, subMenu.firstElementChild);

    const subMenuId = subMenu.id || `menu-overlay-submenu-${index}`;
    subMenu.id = subMenuId;
    link.setAttribute('aria-expanded', 'false');
    link.setAttribute('aria-controls', subMenuId);

    const arrow = document.createElement('span');
    arrow.className = 'menu-overlay__group-arrow';
    arrow.setAttribute('aria-hidden', 'true');
    arrow.innerHTML = ARROW_ICON;
    link.appendChild(arrow);

    link.addEventListener('click', (e) => {
      if (window.innerWidth >= MOBILE_BREAKPOINT) {
        return;
      }

      e.preventDefault();

      const isOpen = link.getAttribute('aria-expanded') === 'true';
      link.setAttribute('aria-expanded', String(!isOpen));
      subMenu.style.maxHeight = isOpen ? '0px' : `${subMenu.scrollHeight}px`;
    });
  });

  // A stale inline max-height would clip the sub-menu above mobile, where it is meant to
  // be fully visible.
  window.addEventListener('resize', () => {
    if (window.innerWidth < MOBILE_BREAKPOINT) {
      return;
    }

    overlay.querySelectorAll('.menu-overlay__group--accordion > .sub-menu').forEach((subMenu) => {
      subMenu.style.maxHeight = '';
    });
  }, { passive: true });
}

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

  // --header-height must match the header's REAL rendered height, not a hand-calculated
  // guess: the logo's aspect ratio and the WPML markup can make it taller than assumed,
  // pushing the content behind the fixed header.
  const syncHeaderHeight = () => {
    if (!header) {
      return;
    }
    document.documentElement.style.setProperty('--header-height', `${header.offsetHeight}px`);
  };

  syncHeaderHeight();
  window.addEventListener('resize', syncHeaderHeight, { passive: true });
  window.addEventListener('load', syncHeaderHeight);

  // Caps the image to the grid's rendered height. CSS cannot do this reliably here: the
  // rows are auto-sized to the text columns' content, so a grid item's percentage height
  // resolves against that content-sized area rather than the grid's visible box.
  const syncImageHeight = () => {
    if (!grid || !imageContainer) {
      return;
    }
    imageContainer.style.maxHeight = `${grid.clientHeight}px`;
  };

  syncImageHeight();
  window.addEventListener('resize', syncImageHeight, { passive: true });

  initMobileMenuAccordion(overlay);

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

  // A clip-path tween reveals the overlay from the top down, then the groups and the image
  // stagger in. The targets are the top-level <li> and .menu-overlay__image — NOT
  // .menu-overlay__col or its <ul>, which become display:contents at xl, and
  // opacity/transform have no effect on a contents box.
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

  // body.menu-open freezes the page with position:fixed rather than overflow:hidden, so the
  // scrollbar never disappears and reappears. Fixed positioning drops the body to the top,
  // so `top` holds its visual place at minus the saved scroll position.
  let savedScrollY = 0;

  const openMenu = () => {
    overlay.style.visibility = 'visible';
    overlay.removeAttribute('aria-hidden');
    setExpanded(true);
    savedScrollY = window.scrollY;
    document.body.style.top = `-${savedScrollY}px`;
    document.body.classList.add('menu-open');
    syncHeaderHeight();
    syncImageHeight();
    revealTl.play();
  };

  const closeMenu = () => {
    setExpanded(false);
    document.body.classList.remove('menu-open');
    document.body.style.top = '';
    window.scrollTo(0, savedScrollY);
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
