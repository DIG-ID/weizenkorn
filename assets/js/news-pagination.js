/**
 * News grid paging.
 *
 * The arrows are real links that page the grid on their own with a page load. This
 * catches the click, asks the REST route for the next six cards, swaps them in and writes
 * the page into the URL — so the article above the grid and the reader's place on the page
 * both stay put.
 *
 * Everything it needs is on the grid: which post to leave out, how many to a page, where
 * it is now and how many pages there are. If the request fails it does nothing and lets
 * the link do what it always did.
 *
 * The grid slides out while the next page is fetched and the new one slides in from the
 * other side, the way a carousel moves — the direction follows the arrow that was pressed.
 * A reader who has asked the system for reduced motion gets the swap with no movement.
 *
 * Markup: template-parts/modules/news-cards.php.
 */

export function initNewsPagination() {
  const grid = document.querySelector('[data-news-grid]');

  if (!grid) {
    return;
  }

  const prev = document.querySelector('[data-news-prev]');
  const next = document.querySelector('[data-news-next]');

  if (!prev && !next) {
    return;
  }

  const endpoint = grid.dataset.endpoint || '/wp-json/weizenkorn/v1/news';
  const still = window.matchMedia('(prefers-reduced-motion: reduce)');
  let busy = false;

  // Matched to the transition in _news-cards.sass, and skipped entirely for a reader who
  // has asked the system for less movement — for them the cards simply change.
  const SLIDE = 300;
  const slideMs = () => (still.matches ? 0 : SLIDE);

  const setArrows = (page, max) => {
    [[prev, page > 1, page - 1], [next, page < max, page + 1]].forEach(([arrow, enabled, target]) => {
      if (!arrow) {
        return;
      }

      arrow.toggleAttribute('aria-disabled', !enabled);
      arrow.tabIndex = enabled ? 0 : -1;

      const url = new URL(arrow.href, window.location.origin);
      url.searchParams.set('news_page', String(Math.min(Math.max(target, 1), max)));
      arrow.href = url.toString();
    });
  };

  // Out one way and in from the other, with the step between the two done with the
  // transition switched off — otherwise the grid would be seen travelling back across to
  // where it enters from.
  const enter = (dir) => {
    if (still.matches) {
      return;
    }

    grid.classList.add('is-instant');
    grid.classList.remove(dir > 0 ? 'is-out-next' : 'is-out-prev');
    grid.classList.add(dir > 0 ? 'is-in-next' : 'is-in-prev');

    // Reading a layout property is what makes the browser apply the two lines above before
    // the ones below, rather than collapsing all of it into one paint with no movement.
    void grid.offsetHeight;

    grid.classList.remove('is-instant');
    grid.classList.remove('is-in-next', 'is-in-prev');
  };

  const go = (page, dir) => {
    const max = parseInt(grid.dataset.max, 10) || 1;

    if (busy || page < 1 || page > max) {
      return;
    }

    busy = true;
    grid.setAttribute('aria-busy', 'true');
    grid.classList.add(dir > 0 ? 'is-out-next' : 'is-out-prev');

    const url = new URL(endpoint, window.location.origin);
    url.searchParams.set('page', String(page));
    url.searchParams.set('exclude', grid.dataset.exclude || '0');
    url.searchParams.set('per_page', grid.dataset.perPage || '6');

    // The movement and the request run together rather than one after the other, so the
    // wait is whichever takes longer instead of the sum of the two.
    const faded = new Promise((resolve) => { window.setTimeout(resolve, slideMs()); });
    const asked = fetch(url.toString(), { headers: { Accept: 'application/json' } })
      .then((response) => (response.ok ? response.json() : Promise.reject(response.status)));

    Promise.all([asked, faded])
      .then(([data]) => {
        if (!data || !data.html) {
          return;
        }

        grid.innerHTML = data.html;
        grid.dataset.page = String(data.page);
        setArrows(data.page, data.max);

        const pageUrl = new URL(window.location.href);
        pageUrl.searchParams.set('news_page', String(data.page));
        window.history.pushState({ newsPage: data.page }, '', pageUrl.toString());
      })
      .catch(() => {
        // Leave the grid as it is: the arrows are still links, and one more click loads
        // the page the old way.
      })
      .finally(() => {
        // Two frames, not one: the first is where the browser takes in the cards that were
        // just written, the second is where the entry can animate from them.
        window.requestAnimationFrame(() => {
          window.requestAnimationFrame(() => {
            enter(dir);
            grid.classList.remove('is-out-next', 'is-out-prev');
          });
        });

        busy = false;
        grid.removeAttribute('aria-busy');
      });
  };

  const bind = (arrow, step) => {
    if (!arrow) {
      return;
    }

    arrow.addEventListener('click', (event) => {
      if (arrow.hasAttribute('aria-disabled')) {
        event.preventDefault();
        return;
      }

      event.preventDefault();
      go((parseInt(grid.dataset.page, 10) || 1) + step, step);
    });
  };

  bind(prev, -1);
  bind(next, 1);

  // The back button walks the pages it wrote, rather than leaving the reader on a grid
  // that no longer matches the URL.
  window.addEventListener('popstate', () => {
    const page = parseInt(new URL(window.location.href).searchParams.get('news_page'), 10) || 1;

    const current = parseInt(grid.dataset.page, 10) || 1;

    if (page !== current) {
      go(page, page > current ? 1 : -1);
    }
  });
}
