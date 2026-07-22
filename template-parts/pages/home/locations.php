<?php
/**
 * Home — Locations band ("UNSERE STANDORTE").
 * Grid of venue cards (DASBREITEHOTEL, Rhyvage, Cantina e9, Bäckerei) with a
 * photo, name and short description.
 * ACF group suggestion: `home_locations`
 *   { overline, title, locations[] { image, title, text, page } }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$data      = get_field( 'home_locations' );
$locations = ( $data && ! empty( $data['locations'] ) ) ? $data['locations'] : array();

if ( empty( $locations ) ) {
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
				'overline'    => ( $data && ! empty( $data['overline'] ) ) ? $data['overline'] : 'UNSERE STANDORTE',
				'title'       => ( $data && ! empty( $data['title'] ) ) ? $data['title'] : '',
				'tag'         => 'h2',
				'title_class' => 'title-main',
			)
		);
		?>

		<div class="theme-grid section-locations__grid">
			<?php
			foreach ( $locations as $location ) {
				?>
				<div class="col-span-2 md:col-span-2 xl:col-span-3">
					<?php
					get_template_part(
						'template-parts/components/card',
						null,
						array(
							'image_id' => ! empty( $location['image'] ) ? $location['image'] : 0,
							'title'    => ! empty( $location['title'] ) ? $location['title'] : '',
							'text'     => ! empty( $location['text'] ) ? $location['text'] : '',
							'url'      => ! empty( $location['page'] ) ? $location['page'] : '',
							'variant'  => 'venue',
						)
					);
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
