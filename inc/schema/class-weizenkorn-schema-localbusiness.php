<?php
/**
 * LocalBusiness-family schema piece — see inc/schema.php for how this is registered.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.18.5
 */

/**
 * LocalBusiness/Restaurant/Bakery/Hotel/CafeOrCoffeeShop schema for the Gastronomie
 * venues — see schema-gastronomie-acf-fields.md (theme root) for exactly which ACF fields
 * this reads and on which pages. One graph node per row of gastro_schema_locations, since
 * a single page (Our Bakery) can list several physical locations that each need their
 * own address rather than one shared one.
 *
 * Deliberately keyed off the field data being present, not a page-template whitelist: any
 * page carrying gastro_schema_locations rows gets a piece, whichever page that turns out
 * to be — the ACF field group's own Location Rules already decide which pages have it.
 */
class Weizenkorn_Schema_LocalBusiness extends \Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece {

	/**
	 * Locations found for the current post, set by is_needed().
	 *
	 * @var array
	 */
	private $locations = array();

	/**
	 * Only needed where gastro_schema_locations has actual rows with a name.
	 *
	 * @return bool
	 */
	public function is_needed() {
		if ( ! ( $this->context->post instanceof WP_Post ) ) {
			return false;
		}

		$post_id = $this->context->post->ID;

		if ( ! have_rows( 'gastro_schema_locations', $post_id ) ) {
			return false;
		}

		$type = get_field( 'gastro_schema_business_type', $post_id );

		while ( have_rows( 'gastro_schema_locations', $post_id ) ) {
			the_row();

			$name = get_sub_field( 'name' );

			if ( ! $name ) {
				continue;
			}

			$this->locations[] = array(
				'type'           => $type ? $type : 'LocalBusiness',
				'name'           => $name,
				'street_address' => get_sub_field( 'street_address' ),
				'postal_code'    => get_sub_field( 'postal_code' ),
				'locality'       => get_sub_field( 'locality' ),
				'phone'          => get_sub_field( 'phone' ),
				'opening_hours'  => $this->parse_opening_hours( get_sub_field( 'opening_hours' ) ),
			);
		}

		return (bool) $this->locations;
	}

	/**
	 * One openingHours string per non-empty line, in the exact Schema.org shorthand the
	 * field itself asks for (schema-gastronomie-acf-fields.md) — "Mo-Fr 06:30-18:30" and
	 * the like. A day left out of the textarea just means the property has no entry for
	 * it, not that it is asserted closed.
	 *
	 * @param string $raw Raw textarea value.
	 *
	 * @return string[]
	 */
	private function parse_opening_hours( $raw ) {
		if ( ! $raw ) {
			return array();
		}

		$lines = preg_split( '/[\r\n]+/', (string) $raw );

		return array_values( array_filter( array_map( 'trim', $lines ) ) );
	}

	/**
	 * Renders one graph node per location.
	 *
	 * @return array
	 */
	public function generate() {
		$graph = array();

		foreach ( $this->locations as $index => $location ) {
			$data = array(
				'@type' => $location['type'],
				'@id'   => $this->context->canonical . '#location-' . ( $index + 1 ),
				'url'   => $this->context->canonical,
				'name'  => $this->helpers->schema->html->smart_strip_tags( $location['name'] ),
			);

			// Only asserted once all three parts are there — a partial address is worse
			// than none, same reasoning as JobPosting's own employmentType.
			if ( $location['street_address'] && $location['postal_code'] && $location['locality'] ) {
				$data['address'] = array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => $location['street_address'],
					'postalCode'      => $location['postal_code'],
					'addressLocality' => $location['locality'],
					'addressCountry'  => 'CH',
				);
			}

			if ( $location['phone'] ) {
				$data['telephone'] = $location['phone'];
			}

			if ( $location['opening_hours'] ) {
				$data['openingHours'] = $location['opening_hours'];
			}

			$graph[] = $this->helpers->schema->language->add_piece_language( $data );
		}

		return $graph;
	}
}
