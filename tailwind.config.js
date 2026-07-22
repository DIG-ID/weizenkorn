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
      primary: ['Inter', 'sans-serif'],   // replace per project
      secondary: ['Inter', 'sans-serif'], // replace per project
    },

    extend: {
      letterSpacing: {
        // wide: '.038em',
        // wider: '.06em',
      },
      colors: {
        // project colors go here
        // example: 'brand-blue': '#1a3c5e',
        // weizenkorn colors
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
