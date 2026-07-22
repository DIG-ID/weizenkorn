<?php
/**
 * Home — Gastronomy section ("Gut essen. Gut schlafen." + "zu allen Betrieben").
 * Intro + feature cards for the gastronomy/hotel venues (Cantina e9, Rhyvage,
 * DASBREITEHOTEL, Bäckerei).
 *
 * ACF fields (flat, prefixed):
 *   home_gastronomy_overline (text)
 *   home_gastronomy_title    (text)
 *   home_gastronomy_lead     (textarea / wysiwyg)
 *   home_gastronomy_link     (link)
 *   home_gastronomy_venues   (repeater) → image (image, ID), title (text),
 *                                         text (textarea), page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$overline      = get_field( 'home_gastronomy_overline' );
$section_title = get_field( 'home_gastronomy_title' );
$lead          = get_field( 'home_gastronomy_lead' );
$cta_link      = get_field( 'home_gastronomy_link' );
?>
<section class="section-gastronomy">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => $overline,
				'title'       => $section_title ? $section_title : 'Gut essen. Gut schlafen.',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<?php if ( $lead ) : ?>
			<div class="body-text section-gastronomy__lead"><?php echo wp_kses_post( $lead ); ?></div>
		<?php endif; ?>

		<?php if ( have_rows( 'home_gastronomy_venues' ) ) : ?>
			<div class="theme-grid section-gastronomy__grid">
				<?php
				while ( have_rows( 'home_gastronomy_venues' ) ) :
					the_row();
					?>
					<div class="col-span-2 md:col-span-3 xl:col-span-6">
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id' => get_sub_field( 'image' ),
								'title'    => get_sub_field( 'title' ),
								'text'     => get_sub_field( 'text' ),
								'url'      => get_sub_field( 'page' ),
								'variant'  => 'venue',
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
