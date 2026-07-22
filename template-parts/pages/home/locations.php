<?php
/**
 * Home — Locations band ("UNSERE STANDORTE").
 * Grid of venue cards (DASBREITEHOTEL, Rhyvage, Cantina e9, Bäckerei).
 *
 * ACF fields (flat, prefixed):
 *   home_locations_overline (text)
 *   home_locations_title    (text)
 *   home_locations_items    (repeater) → image (image, ID), title (text),
 *                                        text (textarea), page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$overline      = get_field( 'home_locations_overline' );
$section_title = get_field( 'home_locations_title' );

if ( ! have_rows( 'home_locations_items' ) ) {
	return;
}
?>
<section class="section-locations">
	<div class="theme-container">

		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'overline'    => $overline ? $overline : 'UNSERE STANDORTE',
				'title'       => $section_title,
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<div class="theme-grid section-locations__grid">
			<?php
			while ( have_rows( 'home_locations_items' ) ) :
				the_row();
				?>
				<div class="col-span-2 md:col-span-2 xl:col-span-3">
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
	</div>
</section>
