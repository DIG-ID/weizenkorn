/**
 * Fixed bottom-right promo box: expanded on load, auto-collapses to
 * icon-only after a few seconds, hover/focus re-expands.
 *
 * Below xl there is no hover, so the box starts collapsed and a tap opens it —
 * the same thing hover does on desktop. The box is a link, so that first tap
 * must not follow it; a second tap on the open box does. Tapping anywhere
 * outside closes it again, as does Escape.
 *
 * Markup: template-parts/components/sticky-cta.php.
 */

const COLLAPSE_DELAY_MS = 7000;

// The project's "tablet and mobile" — the same 1280 the theme uses everywhere, and
// the range where there is no hover to open the box with.
const TOUCH_QUERY = '(max-width: 1279px)';

export function initStickyCta() {
  const el = document.getElementById('sticky-cta');

  if (!el) {
    return;
  }

  const touch = window.matchMedia(TOUCH_QUERY);

  let collapseTimer = null;

  // Whether the box was closed when the press started. Read at pointerdown and not in
  // the click handler, because focus moves to the box in between: by click time it has
  // already been expanded by focusin, and testing the state there would let the very
  // first tap follow the link.
  let wasCollapsedOnPress = false;

  const isCollapsed = () => el.classList.contains('is-collapsed');

  const expand = () => {
    clearTimeout(collapseTimer);
    el.classList.remove('is-collapsed');
  };

  const collapse = () => {
    el.classList.add('is-collapsed');
  };

  const onEnter = expand;
  const onLeave = collapse;

  const onPointerDown = () => {
    wasCollapsedOnPress = isCollapsed();
  };

  const onClick = (event) => {
    if (!touch.matches) {
      return;
    }

    // A click with no pointer behind it is keyboard activation (detail is 0 there).
    // Focus has already opened the box by then, so Enter should just follow the link.
    if (event.detail === 0) {
      return;
    }

    if (!wasCollapsedOnPress) {
      return;
    }

    // First tap on a closed box: open it instead of leaving the page.
    event.preventDefault();
    expand();
  };

  const onDocumentPointerDown = (event) => {
    if (!touch.matches || isCollapsed() || el.contains(event.target)) {
      return;
    }

    collapse();
  };

  const onKeydown = (event) => {
    if ('Escape' === event.key && !isCollapsed()) {
      collapse();
    }
  };

  // Re-applied when the viewport crosses the breakpoint, so a rotated tablet or a
  // resized window does not keep the other mode's listeners.
  const apply = () => {
    clearTimeout(collapseTimer);

    if (touch.matches) {
      collapse();
      el.removeEventListener('mouseenter', onEnter);
      el.removeEventListener('mouseleave', onLeave);
      return;
    }

    expand();
    collapseTimer = setTimeout(collapse, COLLAPSE_DELAY_MS);
    el.addEventListener('mouseenter', onEnter);
    el.addEventListener('mouseleave', onLeave);
  };

  apply();
  touch.addEventListener('change', apply);

  el.addEventListener('pointerdown', onPointerDown);
  el.addEventListener('click', onClick);
  el.addEventListener('focusin', expand);
  el.addEventListener('focusout', collapse);

  document.addEventListener('pointerdown', onDocumentPointerDown);
  document.addEventListener('keydown', onKeydown);
}
