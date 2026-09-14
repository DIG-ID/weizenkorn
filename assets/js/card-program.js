/**
 * Programme cards (template-parts/pages/work-training-supported-apprenticeships/programs.php)
 * — the tap-to-expand toggle below desktop. At xl the reveal opens on :hover/:focus-within,
 * CSS only, no JS involved — this only ever runs the button that breakpoint hides. Same
 * mechanism as assets/js/preview-cards.js (template-parts/components/card-preview.php), kept
 * as its own small module rather than shared with it: the two components live in unrelated
 * parts of the theme and neither one is the other's shared dependency.
 *
 * Each card toggles independently — nothing in the design closes one when another opens.
 */
export function initCardProgram() {
  document.querySelectorAll('.js-card-program-toggle').forEach((button) => {
    button.addEventListener('click', () => {
      const card = button.closest('.js-card-program');

      if (!card) {
        return;
      }

      const isOpen = card.classList.toggle('is-open');
      button.setAttribute('aria-expanded', String(isOpen));
    });
  });
}
