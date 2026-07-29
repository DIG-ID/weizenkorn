/**
 * Fixed bottom-right promo box: expanded on load, auto-collapses to
 * icon-only after a few seconds, hover/focus re-expands.
 * Markup: template-parts/components/sticky-cta.php.
 */

const COLLAPSE_DELAY_MS = 7000;

export function initStickyCta() {
  const el = document.getElementById('sticky-cta');

  if (!el) {
    return;
  }

  let collapseTimer = setTimeout(() => el.classList.add('is-collapsed'), COLLAPSE_DELAY_MS);

  const expand = () => {
    clearTimeout(collapseTimer);
    el.classList.remove('is-collapsed');
  };

  const collapse = () => {
    el.classList.add('is-collapsed');
  };

  el.addEventListener('mouseenter', expand);
  el.addEventListener('mouseleave', collapse);
  el.addEventListener('focusin', expand);
  el.addEventListener('focusout', collapse);
}
