// =============================================================================
// Base libraries
// =============================================================================
import './gsap.js';

// =============================================================================
// Optional libraries — uncomment as needed per project
// =============================================================================
import { initGastronomySlider, initQuoteSlider, initStoriesSlider, initServicesSlider, initEquipmentSlider } from './swiper.js';
// import './fancybox.js';

// =============================================================================
// Utilities
// =============================================================================
import { debounce, isTouchDevice } from './utils/helpers.js';

// =============================================================================
// Project modules
// =============================================================================
import { initStickyHeader } from './sticky-header.js';
import { initMenuOverlay } from './menu-overlay.js';
import { initLanguageSwitcher } from './language-switcher.js';
import { initStickyCta } from './sticky-cta.js';
import { initHeroFit } from './hero-fit.js';

// =============================================================================
// DOM ready
// =============================================================================
document.addEventListener('DOMContentLoaded', () => {

  initStickyHeader();
  initMenuOverlay();
  initLanguageSwitcher();
  initGastronomySlider();
  initQuoteSlider();
  initStoriesSlider();
  initServicesSlider();
  initEquipmentSlider();
  initStickyCta();
  initHeroFit();

});
