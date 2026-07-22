<?php
/**
 * Home — Services section ("Massgeschneidert für Sie" / DIENSTLEISTUNGEN).
 * Overline + title + lead + service cards + "Zu allen Dienstleistungen".
 *
 * ACF fields (flat, prefixed):
 *   home_services_overline (text)
 *   home_services_title    (text)
 *   home_services_lead     (textarea / wysiwyg)
 *   home_services_link     (link)
 *   home_services_items    (repeater) → image (image, ID), title (text),
 *                                       text (textarea), page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$overline      = get_field( 'home_services_overline' );
$section_title = get_field( 'home_services_title' );
$lead          = get_field( 'home_services_lead' );
$cta_link      = get_field( 'home_services_link' );
?>
<section class="section-services">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => $overline ? $overline : 'DIENSTLEISTUNGEN',
				'title'       => $section_title ? $section_title : 'Massgeschneidert für Sie',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<?php if ( $lead ) : ?>
			<div class="body-text section-services__lead"><?php echo wp_kses_post( $lead ); ?></div>
		<?php endif; ?>

		<?php if ( have_rows( 'home_services_items' ) ) : ?>
			<div class="theme-grid section-services__grid">
				<?php
				while ( have_rows( 'home_services_items' ) ) :
					the_row();
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
								'variant'  => 'service',
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
