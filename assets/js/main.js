import './gsap.js';

// Optional libraries — uncomment as needed per project.
import { initGastronomySlider, initQuoteSlider, initStoriesSlider, initServicesSlider, initEquipmentSlider, initDiversitySlider, initDiversityCardsSlider } from './swiper.js';
// import './fancybox.js';

import { debounce, isTouchDevice } from './utils/helpers.js';

import { initStickyHeader } from './sticky-header.js';
import { initMenuOverlay } from './menu-overlay.js';
import { initLanguageSwitcher } from './language-switcher.js';
import { initStickyCta } from './sticky-cta.js';
import { initHeroFit } from './hero-fit.js';

document.addEventListener('DOMContentLoaded', () => {

  initStickyHeader();
  initMenuOverlay();
  initLanguageSwitcher();
  initGastronomySlider();
  initQuoteSlider();
  initStoriesSlider();
  initServicesSlider();
  initEquipmentSlider();
  initDiversitySlider();
  initDiversityCardsSlider();
  initStickyCta();
  initHeroFit();

});
