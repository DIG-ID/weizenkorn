import { initFilterPanel } from './filter-panel.js';

/**
 * Open Positions archive — filter panel + "Mehr Laden" pagination.
 *
 * Filtering and "loading more" are the same query (see inc/rest-job-filters.php's
 * own docblock): both GET weizenkorn/v1/jobs and replace/append
 * job-listing.php's own grid. The panel's checkboxes are draft state until
 * "Apply Filters" commits them — "Mehr Laden" always pages against the last
 * committed filters, not whatever's ticked but not yet applied.
 *
 * The panel's own open/close/backdrop/Escape/badge mechanics come from
 * filter-panel.js, shared with Das Weizenkorn Team's filter — this file only
 * wires up what "Apply"/"Clear"/"Mehr Laden" do against this page's grid.
 *
 * weizenkornJobs (localized in inc/enqueue.php) carries the REST URL and the
 * translated result-count strings, so this file has no hardcoded German.
 *
 * Markup: template-parts/archives/offene-stellen/job-listing.php,
 * template-parts/archives/offene-stellen/job-filters.php.
 */

export function initJobFilters() {
  const grid = document.querySelector('.js-job-listing-grid');

  if (!grid || !window.weizenkornJobs || !window.weizenkornJobs.restUrl) {
    return;
  }

  const filterPanel = initFilterPanel();

  if (!filterPanel) {
    return;
  }

  const { restUrl, resultSingular, resultPlural } = window.weizenkornJobs;
  const { inputs, applyBtn, clearBtn, close, updateBadge } = filterPanel;

  const countText = document.querySelector('.js-job-listing-count');
  const moreWrap = document.querySelector('.js-job-listing-more');
  const moreBtn = moreWrap ? moreWrap.querySelector('.btn') : null;

  let committed = { anstellungsart: [], standort: [] };

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

  if (applyBtn) {
    applyBtn.addEventListener('click', () => {
      committed = checkedValues();
      updateBadge(totalSelected(committed));
      load(committed, 1, false);
      close();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      inputs().forEach((input) => {
        input.checked = false;
      });
      committed = { anstellungsart: [], standort: [] };
      updateBadge(0);
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
