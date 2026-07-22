<?php
/**
 * Home — Products section ("Schönes mit Sinn" / PRODUKTE).
 * Overline + title + lead + grid of product-range cards + "zu allen Produkten".
 * ACF group suggestion: `home_products`
 *   { overline, title, lead, ranges[] { image, title, text, page }, link }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$data     = get_field( 'home_products' );
$lead     = ( $data && ! empty( $data['lead'] ) ) ? $data['lead'] : '';
$ranges   = ( $data && ! empty( $data['ranges'] ) ) ? $data['ranges'] : array();
$cta_link = ( $data && ! empty( $data['link'] ) ) ? $data['link'] : false;
?>
<section class="section-products">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => ( $data && ! empty( $data['overline'] ) ) ? $data['overline'] : 'PRODUKTE',
				'title'       => ( $data && ! empty( $data['title'] ) ) ? $data['title'] : 'Schönes mit Sinn',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<?php if ( $lead ) : ?>
			<div class="body-text section-products__lead"><?php echo wp_kses_post( $lead ); ?></div>
		<?php endif; ?>

		<?php if ( ! empty( $ranges ) ) : ?>
			<div class="theme-grid section-products__grid">
				<?php
				foreach ( $ranges as $range ) {
					// Each range card spans 4 of 12 cols on desktop (3-up), 3 of 6 on
					// tablet (2-up), full width on mobile.
					?>
					<div class="col-span-2 md:col-span-3 xl:col-span-4">
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id' => ! empty( $range['image'] ) ? $range['image'] : 0,
								'title'    => ! empty( $range['title'] ) ? $range['title'] : '',
								'text'     => ! empty( $range['text'] ) ? $range['text'] : '',
								'url'      => ! empty( $range['page'] ) ? $range['page'] : '',
								'variant'  => 'range',
							)
						);
						?>
					</div>
					<?php
				}
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
