import Swiper from 'swiper';
import { Navigation, Pagination, A11y, Autoplay } from 'swiper/modules';
import { debounce } from './utils/helpers.js';

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
 * Below xl, matches every .quote-slider__box in a slider to the tallest one's own
 * natural height, so the border reaches the bottom of whatever the tallest slide needs
 * rather than leaving blank space above the pagination on a shorter one. At tablet,
 * where the box sits beside the image rather than stacked above it, this alone also
 * squares the image away: it stretches to match by itself (md:h-auto, the grid row's
 * default align-self:stretch) once the row is as tall as the box's own new height.
 *
 * Plain CSS can't do this: the box would need its height as a percentage of an
 * ancestor (.theme-container, then .swiper-slide) whose OWN auto height depends on
 * that same box's content — a circular reference a browser resolves by treating the
 * percentage as auto again, so the stretch never actually lands. Measuring here
 * sidesteps that entirely. See _modules/_quote-slider.sass's own docblock for why
 * .quote-slider__box's own justify-between (already there from md up) is what then
 * pins &__author to the bottom of that taller box.
 *
 * Heights are reset to auto before every measurement, at xl or not: a previous run's
 * inline height would otherwise report itself back as this run's "natural" one, and
 * never shrink again once a resize (crossing into xl, or a rotated phone that now wraps
 * the same quote onto fewer lines) makes the tallest box shorter than a stale value.
 *
 * @param {Element|null} root .quote-slider section element.
 */
function equalizeQuoteBoxHeights(root) {
  const boxes = root ? Array.from(root.querySelectorAll('.quote-slider__box')) : [];

  if (!boxes.length) {
    return;
  }

  boxes.forEach((box) => {
    box.style.height = '';
  });

  if (!window.matchMedia('(max-width: 1279px)').matches) {
    return;
  }

  const tallest = Math.max(...boxes.map((box) => box.offsetHeight));

  boxes.forEach((box) => {
    box.style.height = `${tallest}px`;
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
    // No autoHeight, at any breakpoint: every slide matches the tallest one instead of
    // the viewport resizing to whichever is active — see equalizeQuoteBoxHeights() above
    // for what makes the card itself, not just the slide around it, match that height
    // below xl.
    new Swiper(el, {
      modules: [Navigation, Pagination, A11y],
      slidesPerView: 1,
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
      on: {
        init: () => equalizeQuoteBoxHeights(root),
      },
    });

    window.addEventListener('resize', debounce(() => equalizeQuoteBoxHeights(root)), {
      passive: true,
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
    const paginationEl = root ? root.querySelector('.js-equipment-pagination') : null;

    // Below md the row is nowrap + overflow-x-auto instead of wrapping (see
    // _modules/_our-equipment.sass's own __pagination) — this keeps the active bullet
    // scrolled into the centre of that row as the slide changes. Same as
    // initDiversityCardsSlider()'s own centerActiveBullet(); kept as its own small copy
    // rather than a shared helper since each closure captures a different pagination
    // element and root.
    const centerActiveBullet = () => {
      if (!paginationEl || !window.matchMedia('(max-width: 767px)').matches) {
        return;
      }

      const activeBullet = paginationEl.querySelector('.swiper-pagination-bullet-active');

      if (activeBullet) {
        // scrollBy on the row itself, not scrollIntoView on the bullet: scrollIntoView walks
        // every scrollable ancestor, the document included, so block:'nearest' drags the page
        // down to the slider whenever the bullet is off-screen — which autoplay makes happen
        // every few seconds while the reader is somewhere else entirely. Only the horizontal
        // centring was ever wanted. Rects rather than offsetLeft: that needs a positioned
        // ancestor, which the pagination row has no guarantee of.
        const bulletRect = activeBullet.getBoundingClientRect();
        const rowRect = paginationEl.getBoundingClientRect();

        paginationEl.scrollBy({
          left: bulletRect.left + bulletRect.width / 2 - (rowRect.left + rowRect.width / 2),
          behavior: 'smooth',
        });
      }
    };

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
        el: paginationEl,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-equipment-prev') : null,
        nextEl: root ? root.querySelector('.js-equipment-next') : null,
      },
      on: {
        slideChange: centerActiveBullet,
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
    const paginationEl = root ? root.querySelector('.js-diversity-pagination') : null;

    // Below md the row is nowrap + overflow-x-auto instead of shrinking the bullets to
    // fit (see _pages/_work-training.sass's own __pagination) — this keeps the active
    // bullet scrolled into the centre of that row as the slide changes, autoplay
    // included (slideChange fires either way). Same as initDiversityCardsSlider()'s own
    // centerActiveBullet(); kept as its own small copy since each closure captures a
    // different pagination element and root.
    const centerActiveBullet = () => {
      if (!paginationEl || !window.matchMedia('(max-width: 767px)').matches) {
        return;
      }

      const activeBullet = paginationEl.querySelector('.swiper-pagination-bullet-active');

      if (activeBullet) {
        // scrollBy on the row itself, not scrollIntoView on the bullet: scrollIntoView walks
        // every scrollable ancestor, the document included, so block:'nearest' drags the page
        // down to the slider whenever the bullet is off-screen — which autoplay makes happen
        // every few seconds while the reader is somewhere else entirely. Only the horizontal
        // centring was ever wanted. Rects rather than offsetLeft: that needs a positioned
        // ancestor, which the pagination row has no guarantee of.
        const bulletRect = activeBullet.getBoundingClientRect();
        const rowRect = paginationEl.getBoundingClientRect();

        paginationEl.scrollBy({
          left: bulletRect.left + bulletRect.width / 2 - (rowRect.left + rowRect.width / 2),
          behavior: 'smooth',
        });
      }
    };

    new Swiper(el, {
      autoplay: {
        delay: 5000,
      },
      modules: [Pagination, A11y, Autoplay],
      slidesPerView: 1,
      observer: true,
      observeParents: true,
      pagination: {
        el: paginationEl,
        clickable: true,
      },
      on: {
        slideChange: centerActiveBullet,
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
    const paginationEl = root ? root.querySelector('.js-diversity-cards-pagination') : null;

    // Below md the row is nowrap + overflow-x-auto instead of wrapping (see
    // _pages/_supported-jobs.sass's own __pagination) — this keeps the active bullet
    // scrolled into the centre of that row as the slide changes. matchMedia guards it:
    // from md up the row wraps with nothing to scroll, so centring would be a no-op at
    // best and an unwanted scroll-into-view of an already-visible bullet at worst.
    const centerActiveBullet = () => {
      if (!paginationEl || !window.matchMedia('(max-width: 767px)').matches) {
        return;
      }

      const activeBullet = paginationEl.querySelector('.swiper-pagination-bullet-active');

      if (activeBullet) {
        // scrollBy on the row itself, not scrollIntoView on the bullet: scrollIntoView walks
        // every scrollable ancestor, the document included, so block:'nearest' drags the page
        // down to the slider whenever the bullet is off-screen — which autoplay makes happen
        // every few seconds while the reader is somewhere else entirely. Only the horizontal
        // centring was ever wanted. Rects rather than offsetLeft: that needs a positioned
        // ancestor, which the pagination row has no guarantee of.
        const bulletRect = activeBullet.getBoundingClientRect();
        const rowRect = paginationEl.getBoundingClientRect();

        paginationEl.scrollBy({
          left: bulletRect.left + bulletRect.width / 2 - (rowRect.left + rowRect.width / 2),
          behavior: 'smooth',
        });
      }
    };

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
        el: paginationEl,
        clickable: true,
      },
      navigation: {
        prevEl: root ? root.querySelector('.js-diversity-cards-prev') : null,
        nextEl: root ? root.querySelector('.js-diversity-cards-next') : null,
      },
      on: {
        slideChange: centerActiveBullet,
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
