/**
 * Product range cards (template-parts/modules/product-overview.php) — below xl the card
 * opens and closes its own copy, and "zum Produkt" is the only thing that leaves the page.
 *
 * The whole card is an <a>, which is right with a pointer: hovering opens the copy and a
 * click goes to the product. Without hover there is no in-between — the copy never shows
 * and a tap leaves the page, so the text the design writes for these cards is unreachable
 * on a phone. Below xl the stylesheet therefore drops the hover reveal and .is-open, set
 * here, becomes the only thing that opens a card.
 *
 * Which gesture does what, below xl:
 *
 *   tap the card, anywhere    opens the copy; tap again and it closes
 *   tap "zum Produkt"         follows the link, open or closed
 *   Escape                    closes it
 *
 * So the card's own href never fires from a tap down here — only the named link does. The
 * element stays an <a> regardless, which is what keeps long-press, "open in new tab" and
 * copy-link working, and keeps the destination visible to a crawler.
 *
 * A card with no link is an <article>, where a <button> is allowed, so its +/- is a real
 * one: in the tab order, announced, and carrying the aria-expanded. Everything else is the
 * same — one handler on the card serves both, because a click on that button reaches the
 * card by bubbling anyway, and two handlers toggling the same class would cancel out.
 *
 * Escape earns its place on the linked card, whose +/- is only a <span>: not in the tab
 * order, never announced, and so unreachable without it.
 *
 * No media query decides where this applies. An earlier version gated the whole thing on
 * (hover: hover) at load, which resolved once and could not be reviewed in a browser's
 * device toolbar — a desktop browser reports hover: hover however narrow the viewport is
 * drawn. What the file asks instead is whether the stylesheet is currently drawing the
 * +/-, which is xl:hidden: one breakpoint, declared once, in the CSS. The two cannot drift
 * apart, and resizing the window switches the behaviour with no reload.
 */

const CARD = '.product-overview__card';
const TOGGLE_BUTTON = '.js-product-overview-toggle';
const REVEAL = '.product-overview__reveal';
const TOGGLE = '.product-overview__toggle';
const LINK = '.product-overview__link';
const OPEN = 'is-open';

/**
 * Whether the card is in its tap-to-open mode.
 *
 * Asked of the +/- rather than of a media query: the icon is xl:hidden, so the rule that
 * ends the desktop hover behaviour is the same rule that answers this. An element the
 * stylesheet has hidden generates no boxes, and so has no client rects.
 *
 * @param {HTMLElement} card The card.
 * @return {boolean} True below xl, where the icon is drawn.
 */
function isTapMode(card) {
  const toggle = card.querySelector(TOGGLE);

  return !!toggle && toggle.getClientRects().length > 0;
}

/**
 * Binds one card.
 *
 * @param {HTMLElement} card The card, which is also the link.
 */
function bindCard(card) {
  // Nothing to open — a card with neither copy nor link row is left alone, so a linked one
  // stays a plain link instead of being held on a reveal that does not exist.
  if (!card.querySelector(REVEAL)) {
    return;
  }

  // Where the state is announced: the real <button> when the card has one, the card itself
  // otherwise. Only one of the two exists on any given card.
  const state = card.querySelector(TOGGLE_BUTTON) || card;

  state.setAttribute('aria-expanded', 'false');

  card.addEventListener('click', (event) => {
    // Desktop: the pointer opens the copy and the click goes to the product, untouched.
    if (!isTapMode(card)) {
      return;
    }

    // "zum Produkt" is the only way out — it is the one part of the card that says where
    // the tap leads, so it is the one part that is allowed to leave.
    if (event.target.closest(LINK)) {
      return;
    }

    // Everything else is the same switch: the picture, the title bar, the copy, and the
    // +/- itself, which needs no branch of its own — it sits inside the card, so its click
    // arrives here too.
    event.preventDefault();

    const isOpen = card.classList.toggle(OPEN);

    state.setAttribute('aria-expanded', String(isOpen));
  });

  // Escape closes the card that has focus — the keyboard's counterpart to tapping the -.
  // Only when it is open, so a stray Escape on a closed card stays the browser's to handle.
  card.addEventListener('keydown', (event) => {
    if ('Escape' !== event.key || !card.classList.contains(OPEN)) {
      return;
    }

    event.preventDefault();
    card.classList.remove(OPEN);
    state.setAttribute('aria-expanded', 'false');
  });
}

/**
 * Binds every product range card.
 */
export function initProductOverview() {
  document.querySelectorAll(CARD).forEach(bindCard);
}

export default initProductOverview;
