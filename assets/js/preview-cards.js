/**
 * Preview cards (template-parts/components/card-preview.php) — the tap-to-expand toggle
 * below desktop. At xl the reveal opens on :hover/:focus-within, CSS only, no JS involved
 * — this only ever runs the button that breakpoint hides (see that component's own
 * docblock for why hover isn't reliable enough below it to skip a real toggle target).
 *
 * Each card toggles independently — nothing in the design closes one when another opens.
 */
export function initPreviewCards() {
  document.querySelectorAll('.js-card-preview-toggle').forEach((button) => {
    button.addEventListener('click', () => {
      const card = button.closest('.js-card-preview');

      if (!card) {
        return;
      }

      const isOpen = card.classList.toggle('is-open');
      button.setAttribute('aria-expanded', String(isOpen));
    });
  });
}
