<?php
/**
 * Home — Services section ("Massgeschneidert für Sie" / DIENSTLEISTUNGEN).
 * Overline + title + lead + service cards (Schreinerei / Kreativatelier /
 * Treuhand) + "Zu allen Dienstleistungen".
 * ACF group suggestion: `home_services`
 *   { overline, title, lead, services[] { image, title, text, page }, link }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$data     = get_field( 'home_services' );
$lead     = ( $data && ! empty( $data['lead'] ) ) ? $data['lead'] : '';
$services = ( $data && ! empty( $data['services'] ) ) ? $data['services'] : array();
$cta_link = ( $data && ! empty( $data['link'] ) ) ? $data['link'] : false;
?>
<section class="section-services">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => ( $data && ! empty( $data['overline'] ) ) ? $data['overline'] : 'DIENSTLEISTUNGEN',
				'title'       => ( $data && ! empty( $data['title'] ) ) ? $data['title'] : 'Massgeschneidert für Sie',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<?php if ( $lead ) : ?>
			<div class="body-text section-services__lead"><?php echo wp_kses_post( $lead ); ?></div>
		<?php endif; ?>

		<?php if ( ! empty( $services ) ) : ?>
			<div class="theme-grid section-services__grid">
				<?php
				foreach ( $services as $service ) {
					?>
					<div class="col-span-2 md:col-span-3 xl:col-span-4">
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id' => ! empty( $service['image'] ) ? $service['image'] : 0,
								'title'    => ! empty( $service['title'] ) ? $service['title'] : '',
								'text'     => ! empty( $service['text'] ) ? $service['text'] : '',
								'url'      => ! empty( $service['page'] ) ? $service['page'] : '',
								'variant'  => 'service',
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
