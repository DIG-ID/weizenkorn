<?php
/**
 * Home — Products section ("Schönes mit Sinn" / PRODUKTE).
 * Overline + title + lead + grid of product-range cards + "zu allen Produkten".
 *
 * ACF fields (flat, prefixed):
 *   home_products_overline (text)
 *   home_products_title    (text)
 *   home_products_lead     (textarea / wysiwyg)
 *   home_products_link     (link)
 *   home_products_ranges   (repeater) → image (image, ID), title (text),
 *                                       text (textarea), page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$overline      = get_field( 'home_products_overline' );
$section_title = get_field( 'home_products_title' );
$lead          = get_field( 'home_products_lead' );
$cta_link      = get_field( 'home_products_link' );
?>
<section class="section-products">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => $overline ? $overline : 'PRODUKTE',
				'title'       => $section_title ? $section_title : 'Schönes mit Sinn',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<?php if ( $lead ) : ?>
			<div class="body-text section-products__lead"><?php echo wp_kses_post( $lead ); ?></div>
		<?php endif; ?>

		<?php if ( have_rows( 'home_products_ranges' ) ) : ?>
			<div class="theme-grid section-products__grid">
				<?php
				while ( have_rows( 'home_products_ranges' ) ) :
					the_row();
					// Each range card spans 4 of 12 cols on desktop (3-up), 3 of 6
					// on tablet (2-up), full width on mobile.
					?>
					<div class="col-span-2 md:col-span-3 xl:col-span-4">
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id' => get_sub_field( 'image' ),
								'title'    => get_sub_field( 'title' ),
								'text'     => get_sub_field( 'text' ),
								'url'      => get_sub_field( 'page' ),
								'variant'  => 'range',
							)
						);
						?>
					</div>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

		<?php
		if ( $cta_link ) {
			get_template_part(
				'template-parts/components/button',
				null,
				array_merge( $cta_link, array( 'style' => 'primary' ) )
			);
		}
		?>
	</div>
</section>
