// =============================================================================
// Base libraries
// =============================================================================
import './gsap.js';

// =============================================================================
// Optional libraries — uncomment as needed per project
// =============================================================================
import { initGastronomySlider } from './swiper.js';
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

// =============================================================================
// DOM ready
// =============================================================================
document.addEventListener('DOMContentLoaded', () => {

  initStickyHeader();
  initMenuOverlay();
  initLanguageSwitcher();
  initGastronomySlider();
  initStickyCta();

});
