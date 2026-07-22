<?php
/**
 * Home — Gastronomy section ("Gut essen. Gut schlafen." + "zu allen Betrieben").
 * Intro + feature cards for the gastronomy/hotel venues (Cantina e9, Rhyvage,
 * DASBREITEHOTEL, Bäckerei). Cards link to the venue pages.
 * ACF group suggestion: `home_gastronomy`
 *   { overline, title, lead, venues[] { image, title, text, page }, link }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$data     = get_field( 'home_gastronomy' );
$lead     = ( $data && ! empty( $data['lead'] ) ) ? $data['lead'] : '';
$venues   = ( $data && ! empty( $data['venues'] ) ) ? $data['venues'] : array();
$cta_link = ( $data && ! empty( $data['link'] ) ) ? $data['link'] : false;
?>
<section class="section-gastronomy">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => ( $data && ! empty( $data['overline'] ) ) ? $data['overline'] : '',
				'title'       => ( $data && ! empty( $data['title'] ) ) ? $data['title'] : 'Gut essen. Gut schlafen.',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<?php if ( $lead ) : ?>
			<div class="body-text section-gastronomy__lead"><?php echo wp_kses_post( $lead ); ?></div>
		<?php endif; ?>

		<?php if ( ! empty( $venues ) ) : ?>
			<div class="theme-grid section-gastronomy__grid">
				<?php
				foreach ( $venues as $venue ) {
					?>
					<div class="col-span-2 md:col-span-3 xl:col-span-6">
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id' => ! empty( $venue['image'] ) ? $venue['image'] : 0,
								'title'    => ! empty( $venue['title'] ) ? $venue['title'] : '',
								'text'     => ! empty( $venue['text'] ) ? $venue['text'] : '',
								'url'      => ! empty( $venue['page'] ) ? $venue['page'] : '',
								'variant'  => 'venue',
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
