/**
 * Open Positions archive — filter panel + "Mehr Laden" pagination.
 *
 * Filtering and "loading more" are the same query (see inc/rest-job-filters.php's
 * own docblock): both GET weizenkorn/v1/jobs and replace/append
 * job-listing.php's own grid. The panel's checkboxes are draft state until
 * "Apply Filters" commits them — "Mehr Laden" always pages against the last
 * committed filters, not whatever's ticked but not yet applied.
 *
 * weizenkornJobs (localized in inc/enqueue.php) carries the REST URL and the
 * translated result-count strings, so this file has no hardcoded German.
 *
 * Markup: template-parts/archives/offene-stellen/job-listing.php,
 * template-parts/archives/offene-stellen/job-filters.php.
 */

export function initJobFilters() {
  const grid = document.querySelector('.js-job-listing-grid');
  const panel = document.querySelector('.js-job-filters-panel');

  if (!grid || !panel || !window.weizenkornJobs || !window.weizenkornJobs.restUrl) {
    return;
  }

  const { restUrl, resultSingular, resultPlural } = window.weizenkornJobs;

  const trigger = document.querySelector('.js-job-filters-trigger');
  const closeBtn = document.querySelector('.js-job-filters-close');
  const backdrop = document.querySelector('.js-job-filters-backdrop');
  const applyBtn = document.querySelector('.js-job-filters-apply');
  const clearBtn = document.querySelector('.js-job-filters-clear');
  const badge = document.querySelector('.js-job-filters-badge');
  const countText = document.querySelector('.js-job-listing-count');
  const moreWrap = document.querySelector('.js-job-listing-more');
  const moreBtn = moreWrap ? moreWrap.querySelector('.btn') : null;

  let committed = { anstellungsart: [], standort: [] };

  const inputs = () => Array.prototype.slice.call(panel.querySelectorAll('.job-filters__input'));

  const checkedValues = () => {
    const selected = { anstellungsart: [], standort: [] };

    inputs().forEach((input) => {
      if (input.checked && selected[input.dataset.filter]) {
        selected[input.dataset.filter].push(input.value);
      }
    });

    return selected;
  };

  const totalSelected = (selected) =>
    Object.keys(selected).reduce((total, key) => total + selected[key].length, 0);

  const updateBadge = (selected) => {
    if (!badge) {
      return;
    }

    const total = totalSelected(selected);
    badge.hidden = total === 0;
    badge.textContent = String(total);
  };

  const openPanel = () => {
    panel.classList.add('is-open');

    if (backdrop) {
      backdrop.hidden = false;
      requestAnimationFrame(() => backdrop.classList.add('is-visible'));
    }

    panel.setAttribute('aria-hidden', 'false');

    if (trigger) {
      trigger.setAttribute('aria-expanded', 'true');
    }

    document.body.classList.add('job-filters-open');

    if (closeBtn) {
      closeBtn.focus();
    }
  };

  const closePanel = () => {
    panel.classList.remove('is-open');

    if (backdrop) {
      backdrop.classList.remove('is-visible');
    }

    panel.setAttribute('aria-hidden', 'true');

    if (trigger) {
      trigger.setAttribute('aria-expanded', 'false');
    }

    document.body.classList.remove('job-filters-open');

    if (trigger) {
      trigger.focus();
    }
  };

  const buildUrl = (filters, page) => {
    const params = new URLSearchParams();

    filters.anstellungsart.forEach((value) => params.append('anstellungsart[]', value));
    filters.standort.forEach((value) => params.append('standort[]', value));
    params.set('page', String(page));

    return `${restUrl}?${params.toString()}`;
  };

  const load = (filters, page, append) => {
    grid.classList.add('is-loading');

    fetch(buildUrl(filters, page))
      .then((response) => response.json())
      .then((data) => {
        if (append) {
          grid.insertAdjacentHTML('beforeend', data.html);
        } else {
          grid.innerHTML = data.html;
        }

        grid.dataset.page = String(data.page);
        grid.dataset.maxPages = String(data.max_pages);

        if (moreWrap) {
          moreWrap.hidden = data.page >= data.max_pages;
        }

        if (countText) {
          countText.textContent = `${data.found} ${data.found === 1 ? resultSingular : resultPlural}`;
        }
      })
      .finally(() => {
        grid.classList.remove('is-loading');
      });
  };

  if (trigger) {
    trigger.addEventListener('click', openPanel);
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closePanel);
  }

  if (backdrop) {
    backdrop.addEventListener('click', closePanel);
  }

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && panel.classList.contains('is-open')) {
      closePanel();
    }
  });

  if (applyBtn) {
    applyBtn.addEventListener('click', () => {
      committed = checkedValues();
      updateBadge(committed);
      load(committed, 1, false);
      closePanel();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      inputs().forEach((input) => {
        input.checked = false;
      });
      committed = { anstellungsart: [], standort: [] };
      updateBadge(committed);
      load(committed, 1, false);
    });
  }

  if (moreBtn) {
    moreBtn.addEventListener('click', (event) => {
      event.preventDefault();
      load(committed, Number(grid.dataset.page || '1') + 1, true);
    });
  }
}
