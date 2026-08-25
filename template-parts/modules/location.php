<?php
/**
 * Location — "Wo Sie uns finden": a title, a map of the venue and its address.
 *
 * A module and not a page part because every gastronomy venue repeats it with its own pin.
 *
 * THE MAP
 *
 * The markup is what assets/js/google-maps.js expects: a .acf-map element holding a .marker
 * per pin, each with data-lat / data-lng. The script reads data-zoom and drops two levels
 * below 1280px unless data-zoom-mobile says otherwise — but only for a single pin: with two
 * or more it calls fitBounds() instead, so the framing and the zoom come from the pins
 * themselves. A marker with inner HTML would become an info window on click; these are
 * empty, the address being written under the map when there is one.
 *
 * Two things have to be true for the map to appear, both outside this file:
 *   1. WEIZENKORN_GOOGLE_MAPS_API_KEY is set in functions.php.
 *   2. inc/enqueue.php loads the API on this page — see weizenkorn_enqueue_google_maps().
 * Without them the section still renders its title and address and the map is an empty
 * box, so the page never breaks on a missing key.
 *
 * The component's frame has a second column beside the address holding a phone number and
 * an email, hidden on this venue. If another one needs it, that is a field here and not a
 * change to the layout.
 *
 * ACF fields (flat, prefixed) — the `location` group. The group name produces the prefix,
 * so renaming it orphans whatever is stored:
 *   location_section_title  (clone of "Section Title") the title and its red rule. Clone
 *                           the GROUP, never a repeater inside one.
 *   location_pin            (Google Map) the venue's coordinates, for a section with one
 *   location_items          (repeater) for a section with several — one row per pin:
 *                           → pin  (Google Map)
 *                           Read first; the single field above is the fallback, so the
 *                           venues that already have it keep working untouched.
 *   location_address        (textarea) optional. The name and address under the map — Our
 *                           Bakery leaves it empty, its addresses being listed by the
 *                           our-locations section above.
 *   location_address_2      (textarea) optional. A second address beside the first, on the
 *                           four columns next to it. Events & Seminare names two venues;
 *                           leave it empty and the first keeps the row to itself.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/location' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.7.0
 */

$lc_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$lc_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$lc_heading = weizenkorn_get_section_heading( $lc_prefix . 'location_', $lc_ctx );

/*
 * Anything without both coordinates is not a pin and draws no map — which is what an
 * address typed into the field's search box without choosing a result comes back as.
 *
 * The repeater wins where it has rows, and the single field is what the venues built
 * before it still use, so nothing had to be re-entered when the second shape arrived.
 */
$lc_pins = array();

if ( have_rows( $lc_prefix . 'location_items', $lc_ctx ) ) {
	while ( have_rows( $lc_prefix . 'location_items', $lc_ctx ) ) {
		the_row();
		$lc_row = get_sub_field( 'pin' );

		if ( is_array( $lc_row ) && ! empty( $lc_row['lat'] ) && ! empty( $lc_row['lng'] ) ) {
			$lc_pins[] = $lc_row;
		}
	}
}

if ( ! $lc_pins ) {
	$lc_single = get_field( $lc_prefix . 'location_pin', $lc_ctx );

	if ( is_array( $lc_single ) && ! empty( $lc_single['lat'] ) && ! empty( $lc_single['lng'] ) ) {
		$lc_pins[] = $lc_single;
	}
}

if ( ! $lc_heading
	&& ! $lc_pins
	&& ! get_field( $lc_prefix . 'location_address', $lc_ctx )
	&& ! get_field( $lc_prefix . 'location_address_2', $lc_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="location mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $lc_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $lc_heading );
		}
		?>

		<?php if ( $lc_pins || get_field( $lc_prefix . 'location_address', $lc_ctx ) || get_field( $lc_prefix . 'location_address_2', $lc_ctx ) ) : ?>
			<?php
			// The section-heading already carries part of the gap below the rule, so the row
			// adds only what is left. The map-to-address gap is the same distance again at
			// each breakpoint, which is what the row gap sets.
			?>
			<div class="location__row theme-grid gap-y-4 md:mt-2 md:gap-y-8 xl:mt-6 xl:gap-y-14">

				<?php if ( $lc_pins ) : ?>
					<div class="location__map acf-map col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10" data-zoom="16" data-zoom-adjust="-0.5" data-zoom-adjust-mobile="-0.5">
						<?php foreach ( $lc_pins as $lc_pin ) : ?>
							<div class="marker" data-lat="<?php echo esc_attr( $lc_pin['lat'] ); ?>" data-lng="<?php echo esc_attr( $lc_pin['lng'] ); ?>"></div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
				<?php if ( get_field( $lc_prefix . 'location_address', $lc_ctx ) ) : ?>
					<div class="location__address text-brand-dark col-span-2 md:col-span-3 xl:col-start-2 xl:col-span-4">
						<?php echo wp_kses_post( get_field( $lc_prefix . 'location_address', $lc_ctx ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( get_field( $lc_prefix . 'location_address_2', $lc_ctx ) ) : ?>
					<?php
					/*
					 * Beside the first at both breakpoints above mobile — half the container at
					 * tablet, the next four columns at desktop. Only mobile stacks them.
					 */
					?>
					<div class="location__address text-brand-dark col-span-2 md:col-start-4 md:col-span-3 xl:col-start-6 xl:col-span-4">
						<?php echo wp_kses_post( get_field( $lc_prefix . 'location_address_2', $lc_ctx ) ); ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

	</div>
</section>
