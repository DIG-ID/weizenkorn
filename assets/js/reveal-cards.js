/**
 * Reveal cards — a picture with a bar across its bottom that opens to show a line of copy
 * and a named link. Below xl the card opens and closes its own copy, and that named link
 * is the only thing that leaves the page.
 *
 * Two modules draw this card, in different grids and with different class names but with
 * the same behaviour to the line — see CARDS below. They shared one file's worth of logic
 * as two copies until 1.19.0; this is that file.
 *
 * The whole card is an <a>, which is right with a pointer: hovering opens the copy and a
 * click goes where the card goes. Without hover there is no in-between — the copy never
 * shows and a tap leaves the page, so the text written for these cards is unreachable on a
 * phone. Below xl the stylesheet therefore drops the hover reveal and .is-open, set here,
 * becomes the only thing that opens a card.
 *
 * Which gesture does what, below xl:
 *
 *   tap the card, anywhere    opens the copy; tap again and it closes
 *   tap the named link        follows it, open or closed
 *   Escape                    closes it
 *
 * So the card's own href never fires from a tap down here — only the named link does. Which
 * is why both templates give every linked card that label whether or not the editor typed
 * one ("zum Produkt" on the product range, "mehr erfahren" on the offer showcase): without
 * it a card would open on a tap and have no way out.
 *
 * The element stays an <a> regardless, which is what keeps long-press, "open in new tab"
 * and copy-link working, and keeps the destination visible to a crawler.
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

const OPEN = 'is-open';

/**
 * One entry per module that draws this card. Only the names differ; a module is added by
 * listing its selectors here and nothing else.
 *
 * `card` is the card itself, which is also the link. `toggle` is the +/- the stylesheet
 * hides at xl — the element this file reads the breakpoint off, so it must be the one
 * carrying xl:hidden. `toggleButton` is the real <button> an unlinked card gets instead,
 * and is absent on a linked one. `reveal` is each collapsing row, and `link` the named
 * link inside one.
 *
 * @type {Array<{card: string, toggle: string, toggleButton: string, reveal: string, link: string}>}
 */
const CARDS = [
  {
    // template-parts/modules/product-overview.php
    card: '.product-overview__card',
    toggle: '.product-overview__toggle',
    toggleButton: '.js-product-overview-toggle',
    reveal: '.product-overview__reveal',
    link: '.product-overview__link',
  },
  {
    // template-parts/modules/offer-showcase.php — the card is .card-story, shared with the
    // stories slider, so it is scoped to the section rather than named on its own.
    card: '.offer-showcase .card-story',
    toggle: '.offer-showcase__toggle',
    toggleButton: '.js-offer-showcase-toggle',
    reveal: '.card__reveal',
    link: '.card__more',
  },
];

/**
 * Whether the card is in its tap-to-open mode.
 *
 * Asked of the +/- rather than of a media query: the icon is xl:hidden, so the rule that
 * ends the desktop hover behaviour is the same rule that answers this. An element the
 * stylesheet has hidden generates no boxes, and so has no client rects.
 *
 * @param {HTMLElement} card      The card.
 * @param {Object}      selectors The card's own entry from CARDS.
 * @return {boolean} True below xl, where the icon is drawn.
 */
function isTapMode(card, selectors) {
  const toggle = card.querySelector(selectors.toggle);

  return !!toggle && toggle.getClientRects().length > 0;
}

/**
 * Binds one card.
 *
 * @param {HTMLElement} card      The card, which is also the link.
 * @param {Object}      selectors The card's own entry from CARDS.
 */
function bindCard(card, selectors) {
  // Nothing to open — a card with neither copy nor link row is left alone, so a linked one
  // stays a plain link instead of being held on a reveal that does not exist.
  if (!card.querySelector(selectors.reveal)) {
    return;
  }

  // Where the state is announced: the real <button> when the card has one, the card itself
  // otherwise. Only one of the two exists on any given card.
  const state = card.querySelector(selectors.toggleButton) || card;

  state.setAttribute('aria-expanded', 'false');

  card.addEventListener('click', (event) => {
    // Desktop: the pointer opens the copy and the click goes where the card goes, untouched.
    if (!isTapMode(card, selectors)) {
      return;
    }

    // The named link is the only way out — it is the one part of the card that says where
    // the tap leads, so it is the one part that is allowed to leave.
    if (event.target.closest(selectors.link)) {
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
 * Binds every reveal card on the page, of every arrangement listed in CARDS.
 */
export function initRevealCards() {
  CARDS.forEach((selectors) => {
    document
      .querySelectorAll(selectors.card)
      .forEach((card) => bindCard(card, selectors));
  });
}

export default initRevealCards;
