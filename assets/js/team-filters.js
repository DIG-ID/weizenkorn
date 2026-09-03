import { initFilterPanel } from './filter-panel.js';

/**
 * Organization page — "Das Weizenkorn Team" filter + "Mehr Laden", entirely
 * client-side (see team.php's own docblock for why a repeater-backed grid
 * filters/paginates in the browser instead of against a REST endpoint the
 * way the Open Positions archive's taxonomy-backed one does).
 *
 * Every team member is already in the DOM (team.php), each card carrying
 * its own data-bereich/data-standort. "Apply" recomputes which cards match
 * the checked filters and reveals the first 12 of them; "Mehr Laden"
 * reveals 12 more of that same matching set — never a fetch.
 *
 * render() also runs once on init, not just from Apply/Clear/Mehr Laden —
 * this file used to trust team.php's own initial hidden/data-team-extra
 * markup to already be correct and never re-derive it itself, which is one
 * less thing to keep in sync by hand between two files; calling render()
 * up front costs nothing (it reproduces exactly what the markup already
 * shows) and means the "Mehr Laden" button's own moreWrap.hidden state
 * comes from the same code path as every later click, not from a PHP
 * `count() > 12` check that has to keep agreeing with it separately.
 *
 * The panel's own open/close/backdrop/Escape/badge mechanics come from
 * filter-panel.js, shared with the Open Positions archive's filter — this
 * file only wires up what "Apply"/"Clear"/"Mehr Laden" do against this
 * page's grid.
 *
 * weizenkornTeam (localized in inc/enqueue.php) carries the translated
 * result-count strings, so this file has no hardcoded German.
 *
 * Markup: template-parts/pages/about-us-organization/team.php,
 * template-parts/pages/about-us-organization/team-filters.php.
 */

export function initTeamFilters() {
  const grid = document.querySelector('.js-team-grid');

  if (!grid || !window.weizenkornTeam) {
    return;
  }

  const filterPanel = initFilterPanel();

  if (!filterPanel) {
    return;
  }

  const { resultSingular, resultPlural } = window.weizenkornTeam;
  const { inputs, applyBtn, clearBtn, close, updateBadge } = filterPanel;

  const PER_PAGE = 12;
  const cards = Array.prototype.slice.call(grid.querySelectorAll('[data-team-card]'));
  const countText = document.querySelector('.js-team-count');
  const moreWrap = document.querySelector('.js-team-more');
  const moreBtn = moreWrap ? moreWrap.querySelector('.btn') : null;

  let matches = cards.slice();
  let revealed = PER_PAGE;

  const render = () => {
    cards.forEach((card) => {
      const index = matches.indexOf(card);
      card.hidden = index === -1 || index >= revealed;
    });

    if (countText) {
      countText.textContent = `${matches.length} ${matches.length === 1 ? resultSingular : resultPlural}`;
    }

    if (moreWrap) {
      moreWrap.hidden = revealed >= matches.length;
    }
  };

  const checkedValues = () => {
    const selected = { bereich: [], standort: [] };

    inputs().forEach((input) => {
      if (input.checked && selected[input.dataset.filter]) {
        selected[input.dataset.filter].push(input.value);
      }
    });

    return selected;
  };

  const totalSelected = (selected) =>
    Object.keys(selected).reduce((total, key) => total + selected[key].length, 0);

  const applyFilters = (selected) => {
    matches = cards.filter((card) => {
      const bereichOk = !selected.bereich.length || selected.bereich.includes(card.dataset.bereich);
      const standortOk = !selected.standort.length || selected.standort.includes(card.dataset.standort);
      return bereichOk && standortOk;
    });
    revealed = PER_PAGE;
    render();
  };

  if (applyBtn) {
    applyBtn.addEventListener('click', () => {
      const selected = checkedValues();
      updateBadge(totalSelected(selected));
      applyFilters(selected);
      close();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      inputs().forEach((input) => {
        input.checked = false;
      });
      updateBadge(0);
      applyFilters({ bereich: [], standort: [] });
    });
  }

  if (moreBtn) {
    moreBtn.addEventListener('click', (event) => {
      event.preventDefault();
      revealed += PER_PAGE;
      render();
    });
  }

  render();
}
