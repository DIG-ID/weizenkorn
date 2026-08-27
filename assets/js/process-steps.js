/**
 * Process steps — opens the sentence under a step's title.
 *
 * Where there is a pointer, CSS does this on hover. A hover is unreachable by touch, so
 * the step also opens on a click, and the whole tile is the target: the photograph as much
 * as the bar under it. The control itself is the button around the title — that is what a
 * keyboard reaches and what a screen reader is told about — and this listener sits on the
 * tile so the button's own click bubbles up to it rather than firing twice.
 *
 * One step stays open at a time, the way only one tile can be hovered.
 *
 * Markup: template-parts/modules/process-steps.php.
 */

export function initProcessSteps() {
  const items = document.querySelectorAll('.process-steps__item');

  if (!items.length) {
    return;
  }

  const steps = [];

  items.forEach((item) => {
    const button = item.querySelector('.process-steps__toggle');
    const text = button ? document.getElementById(button.getAttribute('aria-controls')) : null;

    if (!button || !text) {
      return;
    }

    steps.push({ item, button, text });
  });

  const setOpen = (step, open) => {
    step.button.setAttribute('aria-expanded', open ? 'true' : 'false');
    step.text.classList.toggle('is-open', open);
  };

  steps.forEach((step) => {
    step.item.addEventListener('click', () => {
      const open = !step.text.classList.contains('is-open');

      // Close the others first, so a click moves the sentence rather than stacking
      // captions over every photograph in the row.
      steps.forEach((other) => {
        if (other !== step) {
          setOpen(other, false);
        }
      });

      setOpen(step, open);
    });
  });
}
