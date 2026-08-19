<?php
/**
 * Services — "Dienstleistungen mit Mehrwert" overview section (Figma
 * "Frame 1000006007" on Services_desktop). Section heading (reusable
 * "Section Title" component, fed from this page's own fields) + a grid of
 * preview cards for its child pages (Schreinerei/Kreativatelier/Fiduciary
 * services), via the shared overview-cards module.
 *
 * ACF fields (flat, prefixed):
 *   services_overview_title    (text)
 *   services_overview_subtitle (text)
 *   services_overview_text     (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.4.0
 */

$services_overview_title = get_field( 'services_overview_title' );

if ( ! $services_overview_title ) {
	return;
}
?>
<section class="section-services-overview my-24 md:my-32 xl:my-48">
	<div class="theme-container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'title'             => $services_overview_title,
				'subtitle'          => get_field( 'services_overview_subtitle' ),
				'description'       => 'right',
				'description_right' => get_field( 'services_overview_text' ),
			)
		);
		?>

		<div class="mt-8 md:mt-14 xl:mt-24">
			<?php get_template_part( 'template-parts/modules/overview-cards' ); ?>
		</div>
	</div>
</section>
