# Weizenkorn

Custom WordPress theme for **Weizenkorn** (Stiftung Weizenkorn, Basel — [weizenkorn.ch](https://weizenkorn.ch)). Built from the [dig.id](https://dig.id) starter theme. WooCommerce-ready, WPML-compatible (EN/DE/FR), built with Tailwind CSS and Laravel Mix.

## Stack

- **WordPress** custom theme (PHP 8.0+, text domain `weizenkorn`)
- **Tailwind CSS v3** + modular **SASS**, compiled with **Laravel Mix** (webpack)
- **JS**: GSAP, Lenis (smooth scroll), Swiper, Isotope, Fancybox
- **ACF Pro** for content fields, **Yoast** for SEO/schema, **WPML** for translations

## Getting started

```bash
npm install        # also runs composer install
npm run dev        # development build + BrowserSync watch
npm run prod       # production build (minified, versioned)
```

Local dev URL: `https://weizenkorn.digid/` (BrowserSync proxy configured in `webpack.mix.js`).

## Code quality

```bash
npm run php:lint   # PHPCS (WordPress-Core, -Docs, -Extra)
npm run php:fix    # PHPCBF auto-fix
```

All PHP follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/). Every file carries a PHPDoc header (`@package weizenkorn`, `@subpackage`, `@since`) — see [CLAUDE.md](CLAUDE.md) for the full conventions (also used by AI tooling).

## Structure

```
├── assets/
│   ├── js/               # source JS (main.js + utils)
│   └── sass/             # modular SASS (_vars, _components, _modules, _pages…)
├── dist/                 # compiled assets — never edit directly
├── inc/                  # theme functionality (setup, enqueue, security, performance…)
├── page-templates/       # WordPress page templates
├── template-parts/       # reusable sections and components
├── functions.php         # config constants + loads inc/ files
└── webpack.mix.js        # build config
```

## Documentation

- **[CLAUDE.md](CLAUDE.md)** — coding conventions, CPTs, ACF field groups, SEO/schema, plugin stack.
- **[figma-architecture-analysis.txt](figma-architecture-analysis.txt)** — mapping from the Figma design to sections, components, JS modules, WP templates/CPTs and ACF field suggestions. Used as the build roadmap.

## Versioning

[Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`), tracked in **[CHANGELOG.md](CHANGELOG.md)** and kept in sync with the `Version:` header in `style.css`.
