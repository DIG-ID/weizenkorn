<?php
/**
 * Donate page — "Unsere Spenden-Projekte" section (Figma desktop node
 * 4129:5406, filter panel n/a). A title + intro paragraph, then a slider of
 * donation-project cards — three per view at desktop with arrow
 * navigation, one per view at tablet/mobile with dot pagination instead
 * (confirmed against all three Figma frames: arrows only exist on the
 * desktop frame, dots only on tablet/mobile's).
 *
 * Not a post type: donate_projects_items is a plain ACF repeater on this
 * page, the same call this theme has already made for a few small,
 * client-curated lists that aren't worth a CPT of their own (Das
 * Weizenkorn Team, most recently — see that section's own docblock).
 *
 * The card is template-parts/components/card-donation-project.php ("news-
 * card" in Figma) — its own link is optional, since there's no "donation
 * project" post type yet for it to point to.
 *
 * ACF fields (flat, prefixed):
 *   donate_projects_title (text)
 *   donate_projects_text  (textarea / wpautop)
 *   donate_projects_items (repeater) → image, year (text), title (text),
 *                          text (textarea), link (link, optional)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.13.0
 */

$dp_title = get_field( 'donate_projects_title' );

if ( ! $dp_title || ! have_rows( 'donate_projects_items' ) ) {
	return;
}

$dp_text  = get_field( 'donate_projects_text' );
$dp_items = array();

while ( have_rows( 'donate_projects_items' ) ) {
	the_row();

	if ( ! get_sub_field( 'title' ) ) {
		continue;
	}

	$dp_link = get_sub_field( 'link' );

	$dp_items[] = array(
		'image' => get_sub_field( 'image' ),
		'year'  => get_sub_field( 'year' ),
		'title' => get_sub_field( 'title' ),
		'text'  => get_sub_field( 'text' ),
		'url'   => $dp_link ? $dp_link['url'] : '',
	);
}

if ( ! $dp_items ) {
	return;
}

$dp_count        = count( $dp_items );
$dp_has_controls = ( $dp_count > 1 );
$dp_fits_xl      = ( $dp_count <= 3 );
?>
<section class="donation-projects<?php echo $dp_fits_xl ? ' donation-projects--fits-xl' : ''; ?> mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'title'             => $dp_title,
				'description'       => 'right',
				'description_right' => $dp_text,
			)
		);
		?>

		<div class="donation-projects__row theme-grid mt-8 xl:mt-12">

			<?php if ( $dp_has_controls ) : ?>
				<button type="button" class="donation-projects__nav donation-projects__nav--prev js-donation-projects-prev" aria-label="<?php echo esc_attr_x( 'Previous', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>
			<?php endif; ?>

			<div class="donation-projects__viewport">
				<div class="swiper js-donation-projects-slider">
					<div class="swiper-wrapper">
						<?php foreach ( $dp_items as $dp_item ) : ?>
							<div class="swiper-slide">
								<?php get_template_part( 'template-parts/components/card-donation-project', null, $dp_item ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<?php if ( $dp_has_controls ) : ?>
				<button type="button" class="donation-projects__nav donation-projects__nav--next js-donation-projects-next" aria-label="<?php echo esc_attr_x( 'Next', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>

				<div class="donation-projects__pagination js-donation-projects-pagination"></div>
			<?php endif; ?>

		</div>
	</div>
</section>
