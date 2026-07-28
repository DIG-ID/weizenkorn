/**
 * Mobile (<768px) language switcher: WPML's own markup (a horizontal list,
 * see assets/sass/_components/_language-switcher.sass) is hidden and
 * replaced by a compact "DE" trigger that reveals the other languages as a
 * dropdown panel, with the same "roll down" clip-path reveal as the mega
 * menu (assets/js/menu-overlay.js). Tablet/desktop keep WPML's original
 * horizontal list, untouched.
 */

import { gsap } from './gsap.js';

const MOBILE_BREAKPOINT = 768;

export function initLanguageSwitcher() {
  const wrappers = document.querySelectorAll('.header-main__lang');

  wrappers.forEach((wrapper) => {
    const list = wrapper.querySelector('.wpml-ls-legacy-list-horizontal');
    const currentLabel = wrapper.querySelector('.wpml-ls-current-language .wpml-ls-native');

    if (!list || !currentLabel) {
      return;
    }

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'header-main__lang-toggle';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.textContent = currentLabel.textContent;
    wrapper.insertBefore(toggle, list);

    gsap.set(list, { display: 'none', clipPath: 'inset(0% 0% 100% 0%)', autoAlpha: 0 });

    const dropdownTl = gsap.timeline({ paused: true })
      .set(list, { display: 'block' })
      .to(list, { clipPath: 'inset(0% 0% 0% 0%)', autoAlpha: 1, duration: 0.3, ease: 'power2.out' });

    dropdownTl.eventCallback('onReverseComplete', () => {
      gsap.set(list, { display: 'none' });
    });

    const openDropdown = () => {
      wrapper.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
      dropdownTl.play();
    };

    const closeDropdown = () => {
      wrapper.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      dropdownTl.reverse();
    };

    toggle.addEventListener('click', () => {
      if (window.innerWidth >= MOBILE_BREAKPOINT) {
        return;
      }

      wrapper.classList.contains('is-open') ? closeDropdown() : openDropdown();
    });

    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target) && wrapper.classList.contains('is-open')) {
        closeDropdown();
      }
    });
  });
}
