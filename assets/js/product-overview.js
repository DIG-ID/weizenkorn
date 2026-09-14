/**
 * Product range cards (template-parts/modules/product-overview.php) — the first tap opens
 * the card's copy instead of following its link.
 *
 * The whole card is an <a>, which is right with a pointer: hovering opens the copy and a
 * click goes to the product. Without hover there is no in-between — the copy never shows
 * and the first tap leaves the page, so the text the design writes for these cards is
 * unreachable on a phone.
 *
 * So where the device cannot hover, a tap on the card opens it and only "zum Produkt"
 * navigates. Keyed on (hover: none) rather than a width: a tablet with a mouse keeps the
 * hover behaviour, and a large touch screen gets the tap. It is the capability that decides,
 * not the size of the glass.
 *
 * Not the toggle-button pattern of preview-cards.js and card-program.js: a <button> inside
 * an <a> is invalid, and those cards are not links themselves.
 */

const CARD = '.product-overview__card';
const LINK = '.product-overview__link';
const OPEN = 'is-open';

/**
 * Binds one card.
 *
 * @param {HTMLElement} card The card, which is also the link.
 */
function bindCard(card) {
  // Only a card that links anywhere has something to intercept; an <article> card already
  // does nothing on tap.
  if (!card.hasAttribute('href')) {
    return;
  }

  card.setAttribute('aria-expanded', 'false');

  card.addEventListener('click', (event) => {
    // "zum Produkt" is the way out, open or closed — it is the only part of the card that
    // says where the tap leads.
    if (event.target.closest(LINK)) {
      return;
    }

    if (card.classList.contains(OPEN)) {
      return;
    }

    event.preventDefault();
    card.classList.add(OPEN);
    card.setAttribute('aria-expanded', 'true');
  });
}

/**
 * Binds every card, on touch devices only.
 */
export function initProductOverview() {
  if (window.matchMedia('(hover: hover)').matches) {
    return;
  }

  document.querySelectorAll(CARD).forEach(bindCard);
}

export default initProductOverview;
