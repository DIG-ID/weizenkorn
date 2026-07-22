/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './404.php',
    './archive.php',
    './footer.php',
    './functions.php',
    './header.php',
    './index.php',
    './page.php',
    './single.php',
    './inc/**/*.php',
    './page-templates/**/*.php',
    './template-parts/**/*.php',
  ],
  theme: {
    fontFamily: {
      primary: ['DM Sans', 'sans-serif'],
      secondary: ['DM Sans', 'sans-serif'],
    },

    extend: {
      letterSpacing: {
        // wide: '.038em',
        // wider: '.06em',
      },
      colors: {
        // Weizenkorn brand colors (Figma "Design System" page — confirmed 2026-07-22)
        'brand-red': '#E30613',
        'brand-dark': '#252525',
        'brand-cream': '#F8F3E9',
        // dig.id agency colors (wp-admin welcome widget, login screen, admin bar)
        'weizenkorn-turquoise': '#00CCCC',
        'weizenkorn-light-grey': '#F8F8F8',
        'weizenkorn-pink': '#EA526C',
        'weizenkorn-black': '#12232B',
        'weizenkorn-grey': '#5B7376',
      },
      transitionTimingFunction: {
        'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
      },
      gridTemplateRows: {
        masonry: 'masonry',
      },
    },
  },
  plugins: [],
};
