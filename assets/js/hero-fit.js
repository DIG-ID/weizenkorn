/**
 * Home hero (desktop): .real-hero-section has a CSS xl:min-height of
 * "100vh minus header minus 48px" (see hero.php) — content should fit
 * within that whenever possible, but the section is allowed to grow
 * taller if it genuinely doesn't (never clipped, never forced into an
 * internal scrollbar). The CSS clamp()s there approximate the right
 * spacing for a viewport height, assuming the title wraps to its usual
 * number of lines — but real line-wrap count depends on the actual
 * (translated) text and the exact viewport width too, so the CSS alone
 * can't guarantee a fit in every case. This re-checks the real rendered
 * height against that same target after render and, only if it's still
 * taller, shaves a bit more off the same spacing the CSS already
 * controls, largest/least-visually-important first, down to a floor —
 * a best-effort top-up, not a hard requirement (see the CSS min-height
 * for why it's safe for this to fall short: the section just grows).
 */

const XL_BREAKPOINT = 1280;
const BOTTOM_GAP_PX = 48;
const RESIZE_DEBOUNCE_MS = 150;

export function initHeroFit() {
  const content = document.querySelector('.section-hero__content');

  if (!content) {
    return;
  }

  const targets = [
    { el: content.querySelector('.section-hero__title'), prop: 'marginBottom', floor: 24 },
    { el: content, prop: 'paddingTop', floor: 16 },
    { el: content.querySelector('.section-hero__body'), prop: 'marginBottom', floor: 16 },
    { el: content.querySelector('.section-hero__tagline'), prop: 'marginBottom', floor: 4 },
  ].filter((target) => target.el);

  const reset = () => {
    targets.forEach(({ el, prop }) => {
      el.style[prop] = '';
    });
  };

  const getHeaderHeight = () => {
    const raw = getComputedStyle(document.documentElement).getPropertyValue('--header-height');
    return parseFloat(raw) || 0;
  };

  const fit = () => {
    if (window.innerWidth < XL_BREAKPOINT) {
      reset();
      return;
    }

    // Start from the CSS clamp()'s own value for the current viewport —
    // this only ever tops up that system, never fights it.
    reset();

    const targetHeight = window.innerHeight - getHeaderHeight() - BOTTOM_GAP_PX;
    let overflow = content.scrollHeight - targetHeight;

    if (overflow <= 0) {
      return;
    }

    targets.forEach(({ el, prop, floor }) => {
      if (overflow <= 0) {
        return;
      }

      const current = parseFloat(getComputedStyle(el)[prop]) || 0;
      const reduceBy = Math.min(Math.max(0, current - floor), overflow);

      if (reduceBy > 0) {
        el.style[prop] = `${current - reduceBy}px`;
        overflow -= reduceBy;
      }
    });

    // Whatever's left (every spacing target already at its floor) is left
    // alone on purpose — the section's own min-height just grows to fit.
  };

  let resizeTimer;
  const onResize = () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(fit, RESIZE_DEBOUNCE_MS);
  };

  fit();
  window.addEventListener('resize', onResize, { passive: true });
}
