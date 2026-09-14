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
 *   hero_section_image_mobile    (image → ID) optional — a different crop below 768px;
 *                                             without it the main image serves every width
 *   hero_section_title           (text)       falls back to the post title
 *   hero_section_subtitle        (text)       optional — the smaller title under
 *                                             the main one, same column
 *   hero_section_body            (textarea / wysiwyg)
 *   hero_section_seperator_logo  (image → ID) omit to hide the separator (field name
 *                                             spelled as in ACF)
 *
 * Heading tag, for SEO: the subtitle is the intended <h1> when it exists — the title
 * above it becomes a <p> that keeps its own display styling regardless. Without a
 * subtitle, the title alone is the <h1>. Either way there is exactly one <h1>.
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

$hero_ctx      = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$hero_prefix   = ! empty( $args['prefix'] ) ? $args['prefix'] : '';
$hero_subtitle = get_field( $hero_prefix . 'hero_section_subtitle', $hero_ctx );

// Whichever of the two titles is last needs the mobile-only gap before the body column
// stacks under it — the subtitle when there is one, the title itself otherwise.
$hero_title_margin = $hero_subtitle ? 'mb-4 xl:mb-6' : 'mb-12 md:mb-0';

// The subtitle is the <h1> when it exists (SEO: it's the more specific, page-relevant
// text) and the title becomes a <p> — visually unchanged either way, since both keep
// their own class regardless of tag. Without a subtitle, the title is the <h1>.
$hero_title_tag = $hero_subtitle ? 'p' : 'h1';

// An attachment id whose file was deleted stays in the field and still passes a truthy check,
// and wp_get_attachment_image() then returns '' — leaving an empty box at the media's fixed
// height. Asking for a real URL is what rules that out.
$hero_image        = get_field( $hero_prefix . 'hero_section_image', $hero_ctx );
$hero_image        = ( $hero_image && wp_get_attachment_image_url( $hero_image, 'full' ) ) ? $hero_image : 0;
$hero_image_mobile = get_field( $hero_prefix . 'hero_section_image_mobile', $hero_ctx );

/*
 * The <source> is only worth emitting for a genuinely different mobile crop — the same id in
 * both fields, or an empty mobile field, leaves the main image serving every width on its own.
 * srcset is false when the attachment has no size metadata (an SVG, or a file smaller than the
 * first registered size), where the plain URL still works, and false again for a deleted file,
 * where the empty result simply drops the <source> — so the orphan case needs no check of its own.
 */
$hero_mobile_srcset = '';
if ( $hero_image_mobile && (int) $hero_image_mobile !== (int) $hero_image ) {
	$hero_mobile_srcset = wp_get_attachment_image_srcset( $hero_image_mobile, 'full' );

	if ( ! $hero_mobile_srcset ) {
		$hero_mobile_srcset = wp_get_attachment_image_url( $hero_image_mobile, 'full' );
	}
}

// This image is the page's LCP element, at every width.
$hero_image_atts = array(
	'class'         => 'w-full h-full object-cover',
	'loading'       => 'eager',
	'fetchpriority' => 'high',
);
?>
<header class="hero-section mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php if ( $hero_image ) : ?>
			<div class="hero-section__media h-[176px] md:h-[256px] xl:h-[519px] mb-4 xl:mb-[26px] overflow-hidden">
				<?php
				/*
				 * <picture> is here whether or not a mobile crop is: with no <source> to match it
				 * behaves exactly as the bare <img> would, so the markup stays the same shape
				 * either way. It is inline by default and would give the img inside it no height
				 * to resolve h-full against, hence it carrying the box's dimensions itself.
				 */
				?>
				<picture class="block w-full h-full">
					<?php if ( $hero_mobile_srcset ) : ?>
						<?php
						// sizes subtracts .theme-container's 37px of side padding at each edge: the
						// image is never the full viewport width, and 100vw would pull a needlessly
						// large candidate on exactly the devices that can least afford it.
						?>
						<source
							media="(max-width: 767px)"
							srcset="<?php echo esc_attr( $hero_mobile_srcset ); ?>"
							sizes="calc(100vw - 74px)">
					<?php endif; ?>
					<?php echo wp_get_attachment_image( $hero_image, 'full', false, $hero_image_atts ); ?>
				</picture>
			</div>
		<?php endif; ?>

		<?php
		/*
		 * The box is the one place the theme uses lg:. Its padding has to serve the whole
		 * 768-1279 tablet range, and that range is wide: at its narrow end the 44px the
		 * frames draw leaves the title column too tight for "Perspektiven" to fit a line,
		 * and the word breaks mid-syllable. So the frames' padding starts at lg and the
		 * narrow half of tablet gets a tighter one. Everything else in the theme stays on
		 * base / md / xl.
		 */
		?>
		<div class="hero-section__box border-2 border-brand-dark p-8 md:px-[0.8rem] md:py-6 lg:px-11 lg:py-12 xl:px-0 xl:py-14 break-words">
			<?php
			/*
			 * Explicit row-start on title/subtitle/body at md and xl: without it, the
			 * subtitle (same columns as the title, placed second in the grid) advances
			 * the browser's row-auto-placement cursor to row 2 before the body is laid
			 * out, and that cursor never moves back up — so the body would land beside
			 * the subtitle in row 2 instead of the title in row 1. Mobile needs none of
			 * this: every item is col-span-2 there, so each already takes its own row in
			 * DOM order.
			 */
			?>
			<div class="theme-grid">

				<?php // max-w matches the 698px title box in Figma (its column is ~747px), so the line breaks stay as designed. ?>
				<<?php echo esc_html( $hero_title_tag ); ?> class="hero-section__title title-hero text-brand-red col-span-2 md:col-span-3 md:row-start-1 xl:col-start-2 xl:col-span-5 xl:row-start-1 <?php echo esc_attr( $hero_title_margin ); ?>">
					<?php
					// Falls back to the post title — or to the archive title, since
					// get_the_title() there would return the loop's first post — so
					// the page always has a title here even with nothing in the field.
					echo wp_kses(
						get_field( $hero_prefix . 'hero_section_title', $hero_ctx )
							? get_field( $hero_prefix . 'hero_section_title', $hero_ctx )
							: ( is_post_type_archive() ? post_type_archive_title( '', false ) : get_the_title() ),
						// class on <br> so a title can break at some widths and not others:
						// <br class="xl:hidden"> breaks at tablet and mobile and closes up at
						// desktop. A bare <br> still breaks at every width, which is what the
						// Rhyvage and Cantina e9 heroes use. Both utilities are safelisted in
						// tailwind.config.js — Tailwind never scans the database.
						array( 'br' => array( 'class' => array() ) )
					);
					?>
				</<?php echo esc_html( $hero_title_tag ); ?>>

				<?php if ( $hero_subtitle ) : ?>
					<h1 class="hero-section__subtitle title-hero-subtitle text-brand-red col-span-2 md:col-span-3 md:row-start-2 xl:col-start-2 xl:col-span-5 xl:row-start-2 mb-12 md:mb-0">
						<?php echo esc_html( $hero_subtitle ); ?>
					</h1>
				<?php endif; ?>

				<?php if ( get_field( $hero_prefix . 'hero_section_body', $hero_ctx ) ) : ?>
					<div class="hero-section__body body-text text-brand-dark col-span-2 md:col-start-4 md:col-span-3 md:row-start-1 xl:col-start-7 xl:col-span-5 xl:row-start-1">
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
						'class'   => 'w-auto h-[32px] md:h-[71px] xl:h-[101px] max-w-full',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		<?php endif; ?>

	</div>
</header>
