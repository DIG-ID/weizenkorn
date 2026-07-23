/**
 * Sticky header bar — shown after scrolling past the normal header.
 * Hidden whenever the mega menu overlay is open (body.menu-open) — only the
 * normal header shows then. Markup: template-parts/header-main.php ([data-sticky-header]).
 */

export function initStickyHeader() {
  const header = document.querySelector('.header-main');
  const sticky = document.querySelector('[data-sticky-header]');

  if (!header || !sticky) {
    return;
  }

  const toggleSticky = () => {
    const isMenuOpen = document.body.classList.contains('menu-open');
    sticky.classList.toggle('is-visible', !isMenuOpen && window.scrollY > header.offsetHeight);
  };

  toggleSticky();
  window.addEventListener('scroll', toggleSticky, { passive: true });

  // Re-check immediately when the mega menu opens/closes (no scroll event fires then).
  new MutationObserver(toggleSticky).observe(document.body, { attributes: true, attributeFilter: ['class'] });
}
