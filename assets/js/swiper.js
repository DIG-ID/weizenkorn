import Swiper from 'swiper';
import { Navigation, Pagination, A11y } from 'swiper/modules';

/**
 * Gastronomy venues — Swiper on mobile only.
 *
 * The same markup is a slider on phones (< 768px) and a bento CSS grid from
 * tablet up. We init/destroy Swiper on the media query so the grid takes over
 * cleanly once the slider is no longer needed.
 */
export function initGastronomySlider() {
  document.querySelectorAll('.js-gastronomy-slider').forEach((el) => {
    const mq = window.matchMedia('(max-width: 767px)');
    let swiper = null;

    const sync = () => {
      if (mq.matches && !swiper) {
        swiper = new Swiper(el, {
          modules: [Pagination],
          slidesPerView: 1,
          spaceBetween: 30,
          pagination: {
            el: el.querySelector('.swiper-pagination'),
            clickable: true,
          },
        });
      } else if (!mq.matches && swiper) {
        swiper.destroy(true, true);
        swiper = null;
      }
    };

    sync();
    mq.addEventListener('change', sync);
  });
}

/**
 * Home services — three cards per view at desktop, one below it.
 *
 * Bullets only: no arrows at any breakpoint, and the bullets themselves are hidden from
 * xl in CSS, where the three cards fill one view. They live outside .swiper, in the
 * section's own grid row, so the element is passed explicitly.
 */
export function initServicesSlider() {
  document.querySelectorAll('.js-services-slider').forEach((el) => {
    const root = el.closest('.section-services');

    new Swiper(el, {
      modules: [Pagination, A11y],
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 3 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-services-pagination') : null,
        clickable: true,
      },
    });
  });
}

/**
 * Stories & references — three story cards per view at desktop, fewer below.
 *
 * The arrows and the bullets are siblings of .swiper in the page grid, not children
 * of it, so both are looked up from the section root. The template only renders them
 * when there are more slides than fit a view, so this never wires up a dead control.
 */
export function initStoriesSlider() {
  document.querySelectorAll('.js-stories-slider').forEach((el) => {
    const root = el.closest('.stories-references');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      // spaceBetween is the grid's own gutter, which puts the desktop cards at
      // 388.67px against the frame's 390 — see the template's layout note.
      spaceBetween: 20,
      // One per view up to tablet, three from desktop. The tablet frame gives the
      // slider four of six columns for a single card, so 2-up is not a step the
      // design has.
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 3 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-stories-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-stories-prev') : null,
        nextEl: root ? root.querySelector('.js-stories-next') : null,
      },
    });
  });
}

/**
 * Quote slider — one testimonial per slide, at every breakpoint.
 *
 * The arrows are not inside the slider element (they sit in the outer grid
 * columns on desktop), so they are looked up from the section root instead of
 * from the Swiper container. autoHeight keeps the viewport at the height of the
 * current slide, since quotes differ in length.
 */
export function initQuoteSlider() {
  document.querySelectorAll('.js-quote-slider').forEach((el) => {
    const root = el.closest('.quote-slider');

    // Bullets below xl, arrows from xl. Both are always wired up and hidden with
    // CSS, so crossing the breakpoint needs no re-init.
    //
    // autoHeight up to tablet, off from desktop. Up to tablet the card stacks
    // under the image, so its vertical position differs from slide to slide;
    // sizing the viewport to the active slide is what keeps the bullets sitting
    // right under the card instead of under the tallest slide. From xl the two
    // panels are side by side and the arrows are vertically centred, so a
    // constant height is what stops them moving between slides.
    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      slidesPerView: 1,
      autoHeight: true,
      breakpoints: {
        1280: { autoHeight: false },
      },
      observer: true,
      observeParents: true,
      // Neither control sits inside .swiper — the bullets are in their own
      // .theme-container below it and the arrows in an overlay — so both
      // elements have to be passed explicitly.
      pagination: {
        el: root ? root.querySelector('.js-quote-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-quote-prev') : null,
        nextEl: root ? root.querySelector('.js-quote-next') : null,
      },
    });
  });
}
