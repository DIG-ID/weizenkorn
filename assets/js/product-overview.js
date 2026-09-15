/**
 * Product range cards (template-parts/modules/product-overview.php) — below xl the card's
 * copy opens on a tap, closes on the +/-, and only then does the card behave as the link
 * it is.
 *
 * The whole card is an <a>, which is right with a pointer: hovering opens the copy and a
 * click goes to the product. Without hover there is no in-between — the copy never shows
 * and the first tap leaves the page, so the text the design writes for these cards is
 * unreachable on a phone. Below xl the stylesheet therefore drops the hover reveal and
 * .is-open, set here, becomes the only thing that opens a card.
 *
 * Which gesture does what, below xl:
 *
 *   tap the +        opens the copy        tap the -   closes it again
 *   tap the card     opens the copy, then, once open, follows the card's link
 *   tap "zum Produkt"  follows the link, open or closed
 *   Enter on the card  the same two steps; Escape closes it again
 *
 * Escape is there because the +/- cannot be: it is a <span>, so it is not in the tab
 * order and a screen reader never announces it. Without it a keyboard user could open a
 * card and not close it — and that is not a rare corner, since WCAG's 400% zoom puts a
 * desktop keyboard user in this same below-xl layout.
 *
 * No media query decides where this applies. An earlier version gated the whole thing on
 * (hover: hover) at load, which resolved once and could not be reviewed in a browser's
 * device toolbar — a desktop browser reports hover: hover however narrow the viewport is
 * drawn. What the file asks instead is whether the stylesheet is currently drawing the
 * +/-, which is xl:hidden: one breakpoint, declared once, in the CSS. The two cannot drift
 * apart, and resizing the window switches the behaviour with no reload.
 *
 * All of the above is the LINKED card. A card with no link is an <article>, where a
 * <button> is allowed, so it gets the real toggle of preview-cards.js and card-program.js
 * — keyboard and all — and nothing else: it has nowhere to go, so the button is the whole
 * interaction. Only inside an <a> is a <button> invalid, which is what the <span> above is
 * working around.
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
  // Only a card that links anywhere has something to intercept; an <article> card already
  // does nothing on tap.
  if (!card.hasAttribute('href')) {
    return;
  }

  // Nothing to open — a card with neither copy nor link row stays a plain link. Without
  // this the handler would intercept every tap and hold the card on a reveal that does
  // not exist, so the link could never be followed at all.
  if (!card.querySelector(REVEAL)) {
    return;
  }

  card.setAttribute('aria-expanded', 'false');

  card.addEventListener('click', (event) => {
    // Desktop: the pointer opens the copy and the click goes to the product, untouched.
    if (!isTapMode(card)) {
      return;
    }

    // The +/- is a state control and never a way out: it opens, it closes, it does not
    // navigate. It sits inside the card's own <a>, so the default has to be stopped in
    // both directions.
    if (event.target.closest(TOGGLE)) {
      event.preventDefault();

      const isOpen = card.classList.toggle(OPEN);

      card.setAttribute('aria-expanded', String(isOpen));

      return;
    }

    // "zum Produkt" is the way out, open or closed — it is the only part of the card that
    // says where the tap leads.
    if (event.target.closest(LINK)) {
      return;
    }

    // Already open: this tap means "go".
    if (card.classList.contains(OPEN)) {
      return;
    }

    event.preventDefault();
    card.classList.add(OPEN);
    card.setAttribute('aria-expanded', 'true');
  });

  // Escape closes the card that has focus — the keyboard's counterpart to tapping the -.
  // Only when it is open, so a stray Escape on a closed card stays the browser's to handle.
  card.addEventListener('keydown', (event) => {
    if ('Escape' !== event.key || !card.classList.contains(OPEN)) {
      return;
    }

    event.preventDefault();
    card.classList.remove(OPEN);
    card.setAttribute('aria-expanded', 'false');
  });
}

/**
 * Binds the +/- of a card that is not a link.
 *
 * Such a card is an <article>, so the toggle is a real <button> and this is the whole of
 * its behaviour — there is no link to hold back and nothing else on the card to tap. The
 * same shape as preview-cards.js, which is what those cards are.
 *
 * @param {HTMLElement} button The toggle button.
 */
function bindToggleButton(button) {
  button.addEventListener('click', () => {
    const card = button.closest(CARD);

    if (!card) {
      return;
    }

    const isOpen = card.classList.toggle(OPEN);

    button.setAttribute('aria-expanded', String(isOpen));
  });
}

/**
 * Binds every product range card, and the toggles of those that are not links.
 */
export function initProductOverview() {
  document.querySelectorAll(CARD).forEach(bindCard);
  document.querySelectorAll(TOGGLE_BUTTON).forEach(bindToggleButton);
}

export default initProductOverview;
