/**
 * Video facade — swaps a poster button for the real player iframe on click.
 *
 * The point is that nothing third-party is requested until a visitor asks for it: no
 * player script, no cookies, no extra weight on a page most readers scroll past. The URL
 * is built server-side (weizenkorn_get_vimeo_embed_url) and already carries dnt=1 and
 * autoplay=1, so the film starts on the click that made it.
 */

const SELECTOR = '[data-video-facade]';

/**
 * Replaces one facade with its iframe.
 *
 * @param {HTMLElement} facade The button holding the poster.
 */
function activate(facade) {
  const src = facade.dataset.videoFacade;

  if (!src) {
    return;
  }

  const iframe = document.createElement('iframe');

  iframe.src = src;
  iframe.title = facade.getAttribute('aria-label') || '';
  iframe.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media';
  iframe.referrerPolicy = 'strict-origin-when-cross-origin';
  iframe.setAttribute('frameborder', '0');
  iframe.setAttribute('allowfullscreen', '');

  // The button becomes a plain box: it has done its job, and leaving a <button> wrapped
  // around a player would keep it in the tab order with nothing to activate.
  const box = document.createElement('div');

  box.className = facade.className;
  box.appendChild(iframe);
  facade.replaceWith(box);

  // Focus follows the swap, so a keyboard user lands inside the player they just opened
  // rather than back at the top of the document.
  iframe.focus({ preventScroll: true });
}

/**
 * Binds every facade on the page.
 */
export function initVideoFacade() {
  const facades = document.querySelectorAll(SELECTOR);

  if (!facades.length) {
    return;
  }

  facades.forEach((facade) => {
    facade.addEventListener('click', () => activate(facade));
  });
}

export default initVideoFacade;
