/**
 * Fixed bottom-right promo box: expanded on load, auto-collapses to
 * icon-only after a few seconds, hover/focus re-expands. On mobile, where
 * the expanded box covers more of the screen, it starts collapsed instead
 * of waiting for the timer.
 * Markup: template-parts/components/sticky-cta.php.
 */

const COLLAPSE_DELAY_MS = 7000;
const MOBILE_QUERY = '(max-width: 767px)';

export function initStickyCta() {
  const el = document.getElementById('sticky-cta');

  if (!el) {
    return;
  }

  const startsCollapsed = window.matchMedia(MOBILE_QUERY).matches;

  if (startsCollapsed) {
    el.classList.add('is-collapsed');
  }

  let collapseTimer = startsCollapsed
    ? null
    : setTimeout(() => el.classList.add('is-collapsed'), COLLAPSE_DELAY_MS);

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
