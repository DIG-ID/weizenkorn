# Changelog

All notable changes to this theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- **MAJOR** (`1.0.0` → `2.0.0`) — structural or breaking changes (redesign, feature removal, changes requiring manual intervention).
- **MINOR** (`1.0.0` → `1.1.0`) — new features / implementations (new template, new CPT, new section).
- **PATCH** (`1.0.0` → `1.0.1`) — bug fixes and small adjustments that do not add functionality.

---

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
