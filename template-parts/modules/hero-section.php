<?php
/**
 * Hero section — the standard page header: full-width image, a bordered box with the page
 * title on the left and an intro text on the right, and an optional centred wheat
 * separator below.
 *
 * Not to be confused with template-parts/pages/home/hero.php (`.section-hero`), which is
 * the home page's own split hero and is not reused.
 *
 * The box height is deliberately auto: titles may wrap to any number of lines.
 *
 * ACF fields (flat, prefixed):
 *   hero_section_image           (image → ID) omit to hide the image
 *   hero_section_title           (text)       falls back to the post title
 *   hero_section_body            (textarea / wysiwyg)
 *   hero_section_seperator_logo  (image → ID) omit to hide the separator (field name
 *                                             spelled as in ACF)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/hero-section' );
 *
 * On a CPT archive there is no post context — get_field() would read the first post of the
 * loop — so pass the options store plus the archive's prefix, which is what keeps two
 * archives from sharing option keys:
 *   get_template_part(
 *       'template-parts/modules/hero-section',
 *       null,
 *       array( 'post_id' => 'option', 'prefix' => 'products_archive_' )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read the fields
 *                               from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.4.0
 */

$hero_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
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
