# Changelog

All notable changes to this theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- **MAJOR** (`1.0.0` → `2.0.0`) — structural or breaking changes (redesign, feature removal, changes requiring manual intervention).
- **MINOR** (`1.0.0` → `1.1.0`) — new features / implementations (new template, new CPT, new section).
- **PATCH** (`1.0.0` → `1.0.1`) — bug fixes and small adjustments that do not add functionality.

---

## [1.2.0] — 2026-07-28

### Added
- Gastronomy venues slider: Swiper carousel on mobile that is destroyed above 768px, where the same markup becomes a bento grid.
- Typography styles for the hero tagline, USP band title and USP item label (`.title-tagline`, `.title-usp`, `.label-usp`).

### Changed
- Home sections (hero, products, services, gastronomy, work & training, about teaser) rebuilt against the real ACF field structure, with the field group export included as `acf-home-fields.json`.
- `section-heading` component now renders the reusable "Section Title" ACF group (heading tag, subtitle, title, left/right descriptions, two buttons, optional image) and receives it through `$args`.
- Typography scale updated to the Figma desktop/tablet/mobile values confirmed on 2026-07-23 (hero title, section title, card title, overline, body text).
- USP band markup restyled with Tailwind utilities directly in the template part.

### Removed
- Locations section from the home page template.
- `card.php` and `usp-item.php` components, superseded by the section markup and the new `_card.sass` / `_section-heading.sass` styles.

## [1.1.2] — 2026-07-22

### Added
- Home page template with ordered, ACF-driven sections (hero, products, USP band, services, gastronomy, locations, work & training, about teaser) and reusable `section-heading` / `card` / `usp-item` components.
- Responsive 12/6/2 column grid (`.theme-container` / `.theme-grid`) and a DM Sans typography scale (`.title-hero`, `.title-main`, `.overline`, `.body-text`…), built from the confirmed Figma grid and type styles.

### Changed
- SASS custom classes now use Tailwind utilities via `@apply` (typography aligned with the button component); convention documented in `CLAUDE.md`.

### Fixed
- Restored the asset build under webpack 5 — replaced `browser-sync-webpack-plugin@0.1.0` with `@2.4.0` and pinned `browser-sync@3` — so `npm run dev`/`prod` compile again.

## [1.1.1] — 2026-07-22

### Fixed
- Removed the `dist/ export-ignore` rule inherited from the starter theme's `.gitattributes`. It excluded compiled assets from any git-archive-based deploy (WP Pusher), so `dist/css`/`dist/js` never reached staging/production, leaving the site completely unstyled.

## [1.1.0] — 2026-07-22

### Added
- `button.php` component with the theme's 5 real button types (Primary, Secondary, Black, Arrow Down, Arrow Only), matching exact colors/states from the Figma Design System page.
- `weizenkorn_the_svg_icon()` template tag with the arrow icon set (arrow-right, arrow-down, arrow-download) from Figma.
- Design System preview page (`page-templates/page-design-system.php`), showcasing the button component live.

### Changed
- Documented the project's responsive breakpoint convention in `CLAUDE.md` (desktop = `xl`, tablet = `md`-`xl`, mobile = below `md`).

## [1.0.1] — 2026-07-22

### Changed
- Replaced placeholder colors and fonts with the real Weizenkorn design tokens from Figma: brand red, dark and cream colors, and DM Sans typography, applied across `_vars.sass`, `tailwind.config.js` and the Google Fonts setup.

## [1.0.0] — 2026-07-22

### Added
- Initial project setup from the dig.id starter theme (v1.13.0).
