# Changelog

All notable changes to this theme are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- **MAJOR** (`1.0.0` → `2.0.0`) — structural or breaking changes (redesign, feature removal, changes requiring manual intervention).
- **MINOR** (`1.0.0` → `1.1.0`) — new features / implementations (new template, new CPT, new section).
- **PATCH** (`1.0.0` → `1.0.1`) — bug fixes and small adjustments that do not add functionality.

---

## [1.6.1] — 2026-08-20

### Changed
- Gastronomie und Hotellerie page: venues section now shows 5 venues instead of 4 (3 across the top row, 2 wider ones across the bottom), with a 20px gap between the two rows and each tile capped at 384px tall on desktop.
- Home page: its own gastronomy section gets the same 5-venue layout, so both pages behave identically.
- Gastronomy venue cards: a venue without a logo now shows its name instead, styled to match (bold, 20px, 0.5px tracking) — on both pages.

### Fixed
- Footer: a leftover commented-out `do_action( 'socials' )` call failed lint (not properly punctuated) — reworded as a plain placeholder note.

## [1.6.0] — 2026-08-19

### Added
- Holzmanufaktur and Xyloba range pages, each composing the shared modules in the order its design has them.
- `our-equipment` module: the machine-park slider — two photos per view at desktop, the run carrying on past the container's right edge, one per view below. Each photo is captioned with the technique it shows, and the controls follow the design: arrows at desktop, bullets on tablet and mobile.
- `trust` module: the reassurance block — title, wide workshop photo, then a lead and a list of commitments side by side at desktop and stacked below.
- `photo-mosaic` module.
- Buttons pick their arrow from the link: one pointing at a file (PDF, ZIP, Office documents) points down, so a download no longer needs a second field to get the right icon.
- Product range cards: the title now shares its line with the arrow and the arrow takes the brand red.

### Changed
- Every section reads its heading through one shared helper. A heading whose fields are stored flat now keeps its buttons — before, they were silently dropped while the title still rendered.
- Section eyebrows carry the shared overline type (14 → 15 → 20px) instead of the browser's default size.
- Services cards: at desktop the cream panel is an overlay that opens on hover; below desktop it stays under the image with everything visible.
- Quote slider: 56px of side padding in the quote box at desktop.
- Gastronomy: 20px between the rows of images.

### Fixed
- The overline text style was renamed, because Tailwind ships an `.overline` utility of its own that drew a literal line above the text wherever the class was used.
- Services cards no longer come out at different heights depending on whether a card has a link.

---

## [1.5.0] — 2026-08-17

### Added
- Product range pages: a template per range (Kerzen, Holzspielwaren, Living Collection), each composing shared modules in the order its design has them. The range is picked in the post's Template panel, so renaming or translating a range never changes which layout it gets.
- `single-products.php`: the fallback for a range with no template assigned, so it renders as a product page rather than as a blog article.
- `product-overview` module: the product-type card grid — an image with the name on a cream bar that opens on hover to reveal a short text and an optional link. Card widths follow the number of cards, so the last row always fills the page grid whether it holds one, two, three or four.
- `stories-references` module: a slider of story cards, three per view at desktop and one below, with the copy revealed on hover at desktop and always visible on touch.
- `craft-showcase` module: a heading and two images, the first with a paragraph beneath it.
- Second ordering form for the range pages, with the page name in the mail subject so one form serves every range.

### Changed
- The "Sie möchten bestellen?" section is now a shared module with two arrangements: the archive's stacked halves, and the range pages' two columns.
- Products archive: the range cards now come from the `products` posts themselves instead of a repeater, so the cards and the pages they link to can no longer drift apart.
- Home services section rebuilt as a slider — three cards at desktop, one per view below — with every card's text box the same height.
- Section heading: the title now runs to the container's last column and the red rule has equal air above and below it, matching the designs.
- Quote slider: red arrows, centred on the quote box rather than on the whole section, and bullets spaced as designed.

### Fixed
- Quote slider arrows could not be clicked at desktop: the slider was painted over the layer holding them.
- Slider bullets sat 8px further apart than designed, because Contact Form 7's — and Swiper's — own spacing was added to the theme's.
- Product overview: the copy revealed on hover showed a sliver of its first line while collapsed.

### Added (in progress)
- Services page: overview section (heading + preview cards) listing the Schreinerei/Kreativatelier/Fiduciary services pages, USP band and quote slider.
- `overview-cards` module + `card-overview` component: a "hub" page's own children (image/title/text) become its preview cards automatically, no manual re-entry.
- `hero-section-detail` module: the 2nd-level interior-page hero (bordered panel + image side by side at desktop) for the Schreinerei service detail pages.
- New page template: Gastronomie und Hotellerie, starting with the shared hero section.
- Gastronomie und Hotellerie page: venues section (same layout as the Home page's own gastronomy section — mobile slider, tablet+ image bento with info cards), plus the shared USP band and quote slider.

## [1.4.0] — 2026-08-12

### Added
- Products archive template (`archive-products.php`) for the `products` post type, composed of the shared hero, USP band, quote slider and form sections.
- `hero-section` module: the standard page header (image, bordered title/intro box, optional separator), shared by every page and archive except the home page.
- `cta-form` module: a red band with a heading and a Contact Form 7 form, for the pages that end on a contact prompt. The form is chosen once for the whole site in the theme options, and any single page or archive can point at a different one.
- Products archive: "Sie möchten bestellen?" ordering section — reseller cards with a sign-up form, plus one card per product range listing where to buy it in store and online.
- Products archive: product-range section, showing the same cards as the home page with its own copy.
- `range-grid` component: the product-range card flow, now shared by the home page and the products archive instead of being written twice.
- Shared styling for Contact Form 7 controls, so every form on the site uses the same fields, selects and buttons.

### Changed
- `hero-section` and `usp-band` take an ACF read context and a field-name prefix through `$args`, so archives can keep their content in the theme options.
- Layout grid gutter is now horizontal only — stacked columns no longer inherit an unintended 20px vertical gap.
- Footer and USP band read their ACF fields inline at the point of use.
- `section-heading`: the optional description now renders in the left column when the field is set to the left, instead of always going to the right.
- Quote slider: pagination bullets now show at every breakpoint, alongside the desktop arrows.

### Fixed
- Hero separator logo reads `hero_section_seperator_logo`, matching the ACF field group.

## [1.3.6] — 2026-07-29

### Added
- Sticky CTA: fixed bottom-right promo box on the homepage (Figma "hero section_first moment on website"), driven by the new "Sticky CTA" ACF field group (Title/Text/Link). Auto-collapses to a circular icon-only state after 5 seconds; hover/focus re-expands.
- Home hero: optional background video (`hero_enable_video` + `hero_video_mp4`/`hero_video_webm`), replacing the image when enabled. WebM is tried first, MP4 is the fallback; falls back to the existing image when no video is set.

### Fixed
- Home hero: content can no longer be visually cut off at short/narrow viewports — the text column always sizes to its own content, while the media column caps to the available height instead of stretching the whole row taller. The existing spacing-shrink system on shorter viewports is preserved.
- Mega menu: opening/closing it no longer hides the browser scrollbar and shifts the page width — the scrollbar stays permanently reserved and the background is frozen in place (without losing the scroll position) instead of using `overflow: hidden`.
- Header/footer logo: fixed not rendering when uploaded as SVG (percentage-based intrinsic size collapsed to 0×0) — logo image now sized with an explicit height instead of `max-height`.
- Home hero: video now sets `preload="auto"` so it starts buffering immediately instead of waiting on the browser's default metadata-only preload.
- Mega menu: the mobile-only "Übersicht" accordion link no longer shows up on tablet/desktop, where the group heading itself is already the destination.

### Changed
- Typography: bumped several base/mobile font sizes (buttons, titles, tagline, overline, body/card/USP labels) and added a dedicated `2xl` step for the hero title/tagline/body and section titles, between the existing `xl` value and the largest breakpoint.
- Footer (mobile): flattened text size raised from 12px to 14px to match the wider typography bump above.

### Added (in progress)
- Services section scaffolding: empty page templates for the new hierarchy (Services overview → Schreinerei/Kreativatelier/Fiduciary services → Schreinerei's 7 child pages), ready to receive their sections.

## [1.3.5] — 2026-07-28

### Changed
- Home hero (desktop): pinned to the viewport height left below the header (minus a 48px gap), so it's always fully visible above the fold instead of assuming a specific tall viewport. Title/tagline/body spacing shrinks smoothly on shorter windows down to ~800px tall.

## [1.3.4] — 2026-07-28

### Changed
- Footer (desktop): logo/address/sitemap/newsletter now split evenly (3+3+3+3) between the `xl` and `2xl` breakpoints, reverting to the original 4+2+4+2 proportions from `2xl` up.
- Footer sitemap: 2-column link layout between `xl` and `2xl` (was 3, too cramped at that narrower card width), with a smaller row gap in that range; 3 columns again from `2xl` up.

## [1.3.3] — 2026-07-28

### Changed
- Mega menu (desktop): the 3 menu columns are now equal thirds of the same width the image column already used, via a purpose-built 4-track grid instead of the site's 12-column one (8 didn't divide evenly by 3).
- Mega menu (desktop): row gap, sub-menu margin, divider margin and grid padding now shrink smoothly on shorter windows (down to ~800px tall) instead of a fixed size that could overflow.
- Footer legal bar: 2x2 grid for the Datenschutz/AGB/Impressum/Jobs links on mobile (right column flush right, copyright centered below), matching Figma.
- Footer "Kontakt"/"Newsletter" headings: no longer uppercase on mobile/tablet (desktop unchanged).
- Footer "Kontakt" card: heading and address now sit side by side (1/3 + 2/3) on mobile/tablet instead of stacked.

### Fixed
- Mega menu mobile: "Kontakt" line-height was equal to its font-size (no vertical breathing room); now 24px.
- Header burger icon: the open-state "X" used a 45° rotation sized for a square icon, which overflowed a non-square (40x20 / 30x16) box. Now uses the exact angle and arm length for each box's own aspect ratio.

## [1.3.2] — 2026-07-28

### Added
- Mega menu: 3-column layout on tablet (previously stacked in a single column, like mobile).
- Footer: tablet-specific layout (logo/socials on one row bottom-aligned, address+newsletter side by side with the sitemap full-width below, legal menu before the copyright line) and a 14px type scale; mobile also gets its own 12px scale, including the newsletter button.
- Header burger icon: turns brand-red on hover, and is 30px wide on mobile (was 40px at every size).
- Footer social icons: 18x18px on mobile/tablet, 30x30px on desktop (previously 32x32px everywhere), and bottom-aligned with the logo on tablet.

### Fixed
- Mobile language switcher dropdown was hiding WPML's language list at every screen size, not just mobile — tablet and desktop had lost the language switcher entirely.

## [1.3.1] — 2026-07-28

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

## [1.3.0] — 2026-07-24

### Added
- Mega menu mobile accordion: on screens below 768px, top-level menu groups expand/collapse to reveal their links, with an "Übersicht" link added to each group so its original destination stays reachable (previously only usable as the accordion trigger). Groups with a single link (e.g. "News" > "Kontakt") stay always-expanded with no toggle.
- Mobile language switcher: the language list, previously hidden below 768px, now shows as a compact dropdown (current language + tap-to-reveal the others), instead of the tablet/desktop horizontal list.

### Fixed
- Mega menu mobile columns no longer stretch to match the tallest sibling's height, which was leaving large phantom gaps between groups.

## [1.2.2] — 2026-07-24

### Changed
- Footer newsletter form: shortcode is now read from the "Theme Options" ACF field (`general_newsletter_shortcode`) instead of being hardcoded, field labels replaced with placeholders to match the design, and the submit button's border is reasserted against MC4WP's default stylesheet.
- Footer social icons updated to the final Figma artwork and spacing.
- Refreshed `admin-bar.css`, `admin-dashboard.css` and `admin-login.css` — these hadn't been rebuilt into a commit since v1.0.1, so wp-admin/wp-login were serving an outdated build.

## [1.2.1] — 2026-07-24

### Added
- Site footer: logo + social links, contact info (address/phone/email from Theme Options), sitemap menu, MC4WP newsletter form, and a legal/copyright bar (new "Copyright Bar Menu" nav location).

### Changed
- Mega menu now opens with a "roll down" reveal (GSAP) instead of a plain fade, with the menu groups fading in with a slight stagger.
- Header burger icon now morphs into an X when the mega menu opens, instead of swapping between two icons.

## [1.2.0] — 2026-07-23

### Added
- Site header: centered logo (from the new "Theme Options" ACF options page), hamburger menu toggle, and WPML language switcher.
- Sticky header bar shown after scrolling past the main header, hidden automatically while the mega menu is open.
- Fullscreen mega menu overlay with 3 nav-menu-driven columns and a photo column that swaps on hover/focus of individual menu items; responsive fallback (stacked columns) below desktop until the mobile/tablet design is finalised.
- "Theme Options" ACF options page (Logo, Address) and a custom "Image" field on nav menu items (Appearance → Menus) to drive the mega menu's photos.

### Fixed
- Mega menu image height and the header/divider spacing now sync from the header's real rendered height via JS instead of hand-calculated pixel offsets, preventing overflow/scrollbars and the divider rendering behind the header at some viewport sizes.

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
