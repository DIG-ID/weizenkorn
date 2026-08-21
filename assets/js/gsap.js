import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

const easeExpoOut = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

/**
 * Initialise Lenis. Tweak duration and wheelMultiplier per project.
 *
 * @returns {Lenis}
 */
function initLenis() {
  const lenis = new Lenis({
    duration: 1.35,          // adjust per project — higher = longer glide
    easing: easeExpoOut,
    smooth: true,
    smoothWheel: true,
    wheelMultiplier: 0.85,   // lower = softer on high-res/trackpad devices
  });

  lenis.on('scroll', ScrollTrigger.update);

  // A single RAF source for both.
  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });

  gsap.ticker.lagSmoothing(0);

  return lenis;
}

const lenis = initLenis();

export { gsap, ScrollTrigger, lenis };
