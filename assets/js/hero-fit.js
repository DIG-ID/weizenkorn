/**
 * Home hero (desktop). The section has a CSS min-height of "100vh minus header minus the
 * bottom gap" (see hero.php) — content should fit within that where possible, but the
 * section is allowed to grow taller if it genuinely does not; it is never clipped and
 * never given an internal scrollbar.
 *
 * The CSS clamp()s approximate the right spacing for a viewport height, assuming the title
 * wraps to its usual number of lines. Real wrap count depends on the translated text and
 * the exact width, so this re-checks the rendered height against the same target and, only
 * if it is still taller, shaves more off the same spacing the CSS controls — largest and
 * least visually important first, down to a floor.
 *
 * A best-effort top-up, not a hard requirement: falling short is safe, the section simply
 * grows.
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

    // Start from the CSS clamp()'s own value for this viewport: this tops that system
    // up, never fights it.
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

    // Whatever is left once every target is at its floor is left alone on purpose — the
    // section's own min-height grows to fit.
  };

  let resizeTimer;
  const onResize = () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(fit, RESIZE_DEBOUNCE_MS);
  };

  fit();
  window.addEventListener('resize', onResize, { passive: true });
}
