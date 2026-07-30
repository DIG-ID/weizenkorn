<?php
/**
 * Hero section — the standard page header used across the site: full-width
 * image, a bordered box with the page title (left) and an intro text (right),
 * and an optional centred wheat separator below.
 *
 * Not to be confused with template-parts/pages/home/hero.php (`.section-hero`),
 * which is the home page's own split hero and is not reused elsewhere.
 *
 * Layout — Figma, confirmed 2026-07-28. The box height is intentionally auto:
 * titles may wrap to any number of lines and the box grows with them.
 *
 *   desktop "page-hero-desktop" (2882:4705, 1820px / 12 col)
 *     image 519h → 26px gap → box padded 56px top/bottom, no side padding;
 *     title on columns 2–6, intro on columns 7–11 (text inset one column each
 *     side, aligned to the container grid) → 192px gap → 244px separator.
 *   tablet "page-hero-tablet" (2653:2069, 700px / 6 col)
 *     image 256h → 16px gap → box padded 48px top/bottom and 44px each side
 *     (plain padding here, NOT a grid inset); title on columns 1–3, intro on
 *     columns 4–6 — still side by side → 128px gap → 212px separator.
 *   mobile "Products_mobile" (2653:2078, 321px / 2 col)
 *     image 176h → 16px gap → box padded 32px all round; title and intro
 *     stacked full width, 48px apart → 96px gap → 96px separator.
 *
 * ACF fields (flat, prefixed):
 *   hero_section_image   (image → return ID)  omit to hide the image
 *   hero_section_title   (text)               falls back to the post title
 *   hero_section_body    (textarea / wysiwyg)
 *   hero_section_seperator_logo  (image → return ID)  omit to hide the separator
 *                                             (field name spelled as in ACF)
 *
 * Usage — on a page or single post the fields come from the current post:
 *   get_template_part( 'template-parts/modules/hero-section' );
 *
 * On a CPT archive there is no post context (get_field() would read the first
 * post of the loop), so pass the theme options store plus the archive's field
 * prefix — the prefix is what keeps two archives from sharing option keys:
 *   get_template_part(
 *       'template-parts/modules/hero-section',
 *       null,
 *       array(
 *           'post_id' => 'option',
 *           'prefix'  => 'products_archive_',
 *       )
 *   );
 * reads options_products_archive_hero_section_title, and so on.
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read
 *                               the fields from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name, for
 *                               archives that keep their fields in the theme
 *                               options. Default: '' (names used as-is).
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.4.0
 */

// ACF read context: the current post normally, the options store on archives.
$hero_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several archives.
$hero_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';
?>
<header class="hero-section mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php if ( get_field( $hero_prefix . 'hero_section_image', $hero_ctx ) ) : ?>
			<div class="hero-section__media h-[176px] md:h-[256px] xl:h-[519px] mb-4 xl:mb-[26px] overflow-hidden">
				<?php
				echo wp_get_attachment_image(
					get_field( $hero_prefix . 'hero_section_image', $hero_ctx ),
					'full',
					false,
					array(
						'class'         => 'w-full h-full object-cover',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<div class="hero-section__box border-2 border-brand-dark p-8 md:px-11 md:py-12 xl:px-0 xl:py-14 break-words">
			<div class="theme-grid">

				<?php // max-w matches the 698px title box in Figma (its column is ~747px), so the line breaks stay as designed. ?>
				<h1 class="hero-section__title title-hero text-brand-red col-span-2 md:col-span-3 xl:col-start-2 xl:col-span-5 xl:max-w-[698px] mb-12 md:mb-0">
					<?php
					// Falls back to the post title — or to the archive title, since
					// get_the_title() there would return the loop's first post — so
					// the page always has its <h1>.
					echo wp_kses(
						get_field( $hero_prefix . 'hero_section_title', $hero_ctx )
							? get_field( $hero_prefix . 'hero_section_title', $hero_ctx )
							: ( is_post_type_archive() ? post_type_archive_title( '', false ) : get_the_title() ),
						array( 'br' => array() )
					);
					?>
				</h1>

				<?php if ( get_field( $hero_prefix . 'hero_section_body', $hero_ctx ) ) : ?>
					<div class="hero-section__body body-text text-brand-dark col-span-2 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5">
						<?php echo wp_kses_post( get_field( $hero_prefix . 'hero_section_body', $hero_ctx ) ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>

		<?php if ( get_field( $hero_prefix . 'hero_section_seperator_logo', $hero_ctx ) ) : ?>
			<div class="hero-section__separator mt-24 md:mt-32 xl:mt-48 flex justify-center">
				<?php
				echo wp_get_attachment_image(
					get_field( $hero_prefix . 'hero_section_seperator_logo', $hero_ctx ),
					'full',
					false,
					array(
						'class'   => 'max-w-[96px] md:max-w-[212px] xl:max-w-[244px] h-auto',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		<?php endif; ?>

	</div>
</header>
