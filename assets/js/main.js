import './gsap.js';

// Optional libraries — uncomment as needed per project.
import { initGastronomySlider, initQuoteSlider, initStoriesSlider, initServicesSlider, initEquipmentSlider, initDiversitySlider, initDiversityCardsSlider, initRelatedJobsSlider } from './swiper.js';
// import './fancybox.js';

import { debounce, isTouchDevice } from './utils/helpers.js';

import { initStickyHeader } from './sticky-header.js';
import { initMenuOverlay } from './menu-overlay.js';
import { initLanguageSwitcher } from './language-switcher.js';
import { initStickyCta } from './sticky-cta.js';
import { initHeroFit } from './hero-fit.js';
import { initProcessSteps } from './process-steps.js';
import { initJobFilters } from './job-filters.js';
import { initTeamFilters } from './team-filters.js';

document.addEventListener('DOMContentLoaded', () => {

  // First, and unconditional on the rest: an uncaught error in any init below (a
  // slider, hero-fit, ...) stops the rest of this callback dead, which would silently
  // take the filter panels out with it purely because of list order, on pages that
  // have nothing to do with what actually threw.
  initJobFilters();
  initTeamFilters();

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
  initRelatedJobsSlider();
  initStickyCta();
  initHeroFit();
  initProcessSteps();

});
