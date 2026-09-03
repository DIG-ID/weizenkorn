import Swiper from 'swiper';
import { Navigation, Pagination, A11y } from 'swiper/modules';

/**
 * Gastronomy venues — Swiper on mobile only.
 *
 * The same markup is a slider on phones and a bento CSS grid from tablet up, so Swiper is
 * created and destroyed on the media query and the grid takes over cleanly.
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
 * Bullets only, and hidden from xl in CSS where the three cards fill one view. They live
 * outside .swiper, in the section's own grid row, so the element is passed explicitly.
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
 * The arrows and bullets are siblings of .swiper in the page grid, not children, so both
 * are looked up from the section root. The template renders them only when there are more
 * slides than fit a view, so this never wires up a dead control.
 */
export function initStoriesSlider() {
  document.querySelectorAll('.js-stories-slider').forEach((el) => {
    const root = el.closest('.stories-references');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      // The grid's own gutter.
      spaceBetween: 20,
      // One per view up to tablet, three from desktop — 2-up is not a step the design
      // has.
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
 * News — the three articles a single one carries under it.
 *
 * Three abreast at desktop and one at a time on a phone, with six loaded so both the
 * arrows and the bullets have somewhere to go. Same shape as the stories slider, and the
 * same reason the controls are looked up from the section root: they sit outside the
 * slider element — the arrows in the container's margins, the bullets under the cards.
 */
export function initNewsSlider() {
  document.querySelectorAll('.js-news-slider').forEach((el) => {
    const root = el.closest('.news-cards');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      // The grid's own gutter.
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 3 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-news-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-news-prev') : null,
        nextEl: root ? root.querySelector('.js-news-next') : null,
      },
    });
  });
}

/**
 * Quote slider — one testimonial per slide, at every breakpoint.
 *
 * The arrows sit in the outer grid columns rather than inside the slider element, so they
 * are looked up from the section root.
 */
export function initQuoteSlider() {
  document.querySelectorAll('.js-quote-slider').forEach((el) => {
    const root = el.closest('.quote-slider');

    // Both controls are always wired up and hidden with CSS, so crossing the breakpoint
    // needs no re-init.
    //
    // autoHeight up to tablet, where the card stacks under the image and its position
    // differs per slide, so sizing the viewport to the active slide keeps the bullets
    // under the card rather than under the tallest slide. Off from xl, where the panels
    // are side by side and a constant height stops the centred arrows moving.
    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      slidesPerView: 1,
      autoHeight: true,
      breakpoints: {
        1280: { autoHeight: false },
      },
      observer: true,
      observeParents: true,
      // Neither control sits inside .swiper, so both elements are passed explicitly.
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

/**
 * Our equipment — two five-column slides per view at desktop, one below.
 *
 * slidesPerView is a whole 2 with no slide width set anywhere: the viewport is the
 * ten-column inset and two five-column slides with the grid's gutter between them come to
 * exactly that. The slides after them stay visible past the container's right edge because
 * the CSS shows .swiper's overflow — see _modules/_our-equipment.sass.
 *
 * Both controls are wired here even though the CSS shows one per breakpoint: Swiper binds
 * them once at init, so a viewport that later crosses 1280 finds the control already live.
 * All of them sit outside .swiper, so they are looked up from the section root.
 */
export function initEquipmentSlider() {
  document.querySelectorAll('.js-equipment-slider').forEach((el) => {
    const root = el.closest('.our-equipment');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 2 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-equipment-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-equipment-prev') : null,
        nextEl: root ? root.querySelector('.js-equipment-next') : null,
      },
    });
  });
}

/**
 * Work & Training — diversity slider ("Arbeitsvielfalt bei Weizenkorn"): one
 * photo per slide, at every breakpoint. Bullets only, no arrows — the design
 * never shows any.
 */
export function initDiversitySlider() {
  document.querySelectorAll('.js-diversity-slider').forEach((el) => {
    const root = el.closest('.section-diversity-slider');

    new Swiper(el, {
      modules: [Pagination, A11y],
      slidesPerView: 1,
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-diversity-pagination') : null,
        clickable: true,
      },
    });
  });
}

/**
 * Supported Jobs — diversity cards slider ("Arbeitsvielfalt bei
 * Weizenkorn"): two 747px cards per view at desktop, one full-width card
 * below it — same configuration as initEquipmentSlider(), a different
 * section on a different page.
 */
export function initDiversityCardsSlider() {
  document.querySelectorAll('.js-diversity-cards-slider').forEach((el) => {
    const root = el.closest('.section-diversity-cards');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 2 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-diversity-cards-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-diversity-cards-prev') : null,
        nextEl: root ? root.querySelector('.js-diversity-cards-next') : null,
      },
    });
  });
}

/**
 * Open Positions single post — related jobs slider ("Weitere
 * Stellenausschreibungen"): three job cards per view at desktop, one below
 * it. Same configuration as initStoriesSlider() — the arrows and bullets
 * sit outside .swiper, in the section's own grid row, so both are looked
 * up from the section root.
 */
export function initRelatedJobsSlider() {
  document.querySelectorAll('.js-related-jobs-slider').forEach((el) => {
    const root = el.closest('.related-jobs');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 3 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-related-jobs-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-related-jobs-prev') : null,
        nextEl: root ? root.querySelector('.js-related-jobs-next') : null,
      },
    });
  });
}

/**
 * Donate page — "Unsere Spenden-Projekte" slider: three project cards per view at
 * desktop, one below it. Same configuration as initRelatedJobsSlider() — the arrows
 * and bullets sit outside .swiper, in the section's own grid row, so both are looked
 * up from the section root — but CSS (_pages/_donate.sass) shows only the arrows at
 * xl and only the dots below it, rather than both together, matching Figma's own
 * desktop-arrows / tablet+mobile-dots split.
 */
export function initDonationProjectsSlider() {
  document.querySelectorAll('.js-donation-projects-slider').forEach((el) => {
    const root = el.closest('.donation-projects');

    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        1280: { slidesPerView: 3 },
      },
      observer: true,
      observeParents: true,
      pagination: {
        el: root ? root.querySelector('.js-donation-projects-pagination') : null,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-donation-projects-prev') : null,
        nextEl: root ? root.querySelector('.js-donation-projects-next') : null,
      },
    });
  });
}
