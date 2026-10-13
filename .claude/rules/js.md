---
description: JavaScript house style — entry point, jQuery in an IIFE, Alpine, script enqueueing.
paths:
  - "assets/js/**/*.js"
---

# JavaScript — convenções de escrita

### JavaScript
```javascript
// Entry (main.js): libs opcionais comentadas até serem precisas
import './gsap.js';
// import './swiper.js';
import { debounce, isTouchDevice } from './utils/helpers.js';
document.addEventListener('DOMContentLoaded', () => { /* init */ });

// jQuery sempre em IIFE
/* globals jQuery */
(function ($) {
  document.addEventListener('DOMContentLoaded', function () { /* ... */ });
})(jQuery);
```
- Alpine.js (quando usado): `<style>[x-cloak]{display:none !important;}</style>` + `x-data`, `x-show`, `x-cloak`, `:aria-expanded`.
- Enqueue de scripts sempre no footer, com dependência `array( 'jquery' )` e versão do tema.

