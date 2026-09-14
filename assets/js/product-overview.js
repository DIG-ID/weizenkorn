/**
 * Product range cards (template-parts/modules/product-overview.php) — a tap opens the
 * card's copy before the card's link will follow.
 *
 * The whole card is an <a>, which is right with a pointer: hovering opens the copy and a
 * click goes to the product. Without hover there is no in-between — the copy never shows
 * and the first tap leaves the page, so the text the design writes for these cards is
 * unreachable on a phone.
 *
 * No media query decides this. An earlier version gated the whole thing on (hover: hover)
 * at load, which had two faults: it resolved once, so a window that changed after load
 * kept the wrong behaviour, and it does not reproduce in a browser's device toolbar — a
 * desktop browser reports hover: hover no matter how narrow the viewport is drawn, so the
 * behaviour could not be reviewed anywhere but on a real phone.
 *
 * What it asks instead is the question that actually matters: is the copy already showing?
 * The reveal collapses with grid-template-rows: 0fr and clips its own overflow, so a
 * closed one measures zero high. Closed, a tap opens it. Open — because the pointer is
 * hovering, because focus is on the card, or because an earlier tap opened it — the click
 * goes to the product. That reads correctly on a phone, on a tablet with a mouse, and at
 * any width, without asking the browser to describe the device.
 *
 * Not the toggle-button pattern of preview-cards.js and card-program.js: a <button> inside
 * an <a> is invalid, and those cards are not links themselves.
 */

const CARD = '.product-overview__card';
const REVEAL = '.product-overview__reveal';
const LINK = '.product-overview__link';
const OPEN = 'is-open';

/**
 * Whether the card's hidden copy is currently showing.
 *
 * A card can hold two reveals — the copy and the "zum Produkt" row — but they open
 * together, so the first one speaks for both. A card with neither is never intercepted:
 * it has nothing to open, and its tap should go straight to the product.
 *
 * @param {HTMLElement} card The card.
 * @return {boolean} True when the card has a reveal and it has height.
 */
function isRevealed(card) {
  const reveal = card.querySelector(REVEAL);

  return !!reveal && reveal.getBoundingClientRect().height > 0;
}

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

  // Declared only where the device cannot hover. On a pointer the card opens on :hover
  // with no JS involved, and a card that says aria-expanded="false" while its copy is
  // plainly showing tells a screen reader the opposite of what is on screen. Here the
  // query only labels the card — it never decides what a tap does.
  if (window.matchMedia('(hover: none)').matches) {
    card.setAttribute('aria-expanded', 'false');
  }

  card.addEventListener('click', (event) => {
    // "zum Produkt" is the way out, open or closed — it is the only part of the card that
    // says where the tap leads.
    if (event.target.closest(LINK)) {
      return;
    }

    // Already open, by hover, focus or an earlier tap: this click means "go".
    if (isRevealed(card)) {
      return;
    }

    event.preventDefault();
    card.classList.add(OPEN);
    card.setAttribute('aria-expanded', 'true');
  });
}

/**
 * Binds every product range card.
 */
export function initProductOverview() {
  document.querySelectorAll(CARD).forEach(bindCard);
}

export default initProductOverview;
