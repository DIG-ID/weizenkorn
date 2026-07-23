/**
 * Mega menu overlay: open/close toggle + hover/focus image swap.
 * Markup: template-parts/menu-overlay.php + template-parts/components/header-nav.php.
 */

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

  const openMenu = () => {
    overlay.classList.add('is-visible');
    overlay.removeAttribute('aria-hidden');
    setExpanded(true);
    document.body.classList.add('menu-open');
    syncHeaderHeight();
    syncImageHeight();
  };

  const closeMenu = () => {
    overlay.classList.remove('is-visible');
    overlay.setAttribute('aria-hidden', 'true');
    setExpanded(false);
    document.body.classList.remove('menu-open');
  };

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      isOpen ? closeMenu() : openMenu();
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('is-visible')) {
      closeMenu();
    }
  });
}
