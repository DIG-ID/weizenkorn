/**
 * Mobile language switcher: WPML's own horizontal list is hidden and replaced by a compact
 * trigger that reveals the other languages as a dropdown panel, with the same clip-path
 * reveal as the mega menu. Tablet and desktop keep WPML's list untouched.
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

    // The hidden-by-default state is GSAP inline styles, which only make sense at mobile:
    // above it nothing ever calls dropdownTl.play(), so they would permanently hide WPML's
    // own list. clearProps hands control back to the stylesheet.
    let isMobileMode = null;

    const applyResponsiveState = () => {
      const shouldBeMobile = window.innerWidth < MOBILE_BREAKPOINT;

      if (shouldBeMobile === isMobileMode) {
        return;
      }

      isMobileMode = shouldBeMobile;

      if (shouldBeMobile) {
        gsap.set(list, { display: 'none', clipPath: 'inset(0% 0% 100% 0%)', autoAlpha: 0 });
      } else {
        closeDropdown();
        gsap.set(list, { clearProps: 'display,clipPath,opacity,visibility' });
      }
    };

    applyResponsiveState();
    window.addEventListener('resize', applyResponsiveState, { passive: true });

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
