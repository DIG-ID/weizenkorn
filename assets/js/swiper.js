import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';

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
