/**
 * Filter panel — trigger + slide-in drawer open/close mechanics, shared by every
 * section that filters a grid: the Open Positions archive (assets/js/job-filters.js)
 * and Das Weizenkorn Team (assets/js/team-filters.js). Only one panel exists per page,
 * so this queries the document directly rather than taking a root/scope argument.
 *
 * Handles open/close, the backdrop, Escape, focus and the badge/checkbox-count
 * plumbing that's identical either way — everything specific to what "Apply"/"Clear"
 * actually does against a page's own grid stays in the caller, which gets the found
 * elements (and open/close) back to build that on top of.
 *
 * Markup: _components/_filter-panel.sass's own .filter-panel__* classes.
 *
 * @return {?object} null if no panel is on the page, otherwise the found elements
 *                    plus open()/close() and updateBadge(selectedCount).
 */
export function initFilterPanel() {
  const panel = document.querySelector('.js-filter-panel');
  const trigger = document.querySelector('.js-filter-panel-trigger');

  if (!panel || !trigger) {
    return null;
  }

  const closeBtn = document.querySelector('.js-filter-panel-close');
  const backdrop = document.querySelector('.js-filter-panel-backdrop');
  const badge = document.querySelector('.js-filter-panel-badge');
  const applyBtn = document.querySelector('.js-filter-panel-apply');
  const clearBtn = document.querySelector('.js-filter-panel-clear');
  const inputs = () => Array.prototype.slice.call(panel.querySelectorAll('.filter-panel__input'));

  const open = () => {
    panel.classList.add('is-open');

    if (backdrop) {
      backdrop.hidden = false;
      requestAnimationFrame(() => backdrop.classList.add('is-visible'));
    }

    panel.setAttribute('aria-hidden', 'false');
    trigger.setAttribute('aria-expanded', 'true');
    document.body.classList.add('filter-panel-open');

    if (closeBtn) {
      closeBtn.focus();
    }
  };

  const close = () => {
    panel.classList.remove('is-open');

    if (backdrop) {
      backdrop.classList.remove('is-visible');
    }

    panel.setAttribute('aria-hidden', 'true');
    trigger.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('filter-panel-open');
    trigger.focus();
  };

  const updateBadge = (count) => {
    if (!badge) {
      return;
    }

    badge.hidden = count === 0;
    badge.textContent = String(count);
  };

  trigger.addEventListener('click', open);

  if (closeBtn) {
    closeBtn.addEventListener('click', close);
  }

  if (backdrop) {
    backdrop.addEventListener('click', close);
  }

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && panel.classList.contains('is-open')) {
      close();
    }
  });

  return { panel, trigger, closeBtn, applyBtn, clearBtn, inputs, open, close, updateBadge };
}
