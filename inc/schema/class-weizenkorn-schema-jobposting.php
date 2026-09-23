<?php
/**
 * JobPosting schema piece — see inc/schema.php for how this is registered.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.13.0
 */

/**
 * JobPosting schema for a single Open Positions post.
 *
 * Required properties Google looks for (title, description, datePosted,
 * hiringOrganization, jobLocation) are all covered. employmentType maps to Schema.org's
 * enum with confidence where it can (Ausbildungsplätze → INTERN) and falls back to OTHER
 * everywhere else — offene_stellen_employment is free text (e.g. "30 - 100 % Anstellung")
 * with no reliable full/part-time signal, so guessing FULL_TIME/PART_TIME from it would
 * risk asserting something untrue. OTHER is Schema.org's own catch-all for exactly this:
 * "the property is required/recommended but the actual value doesn't fit the other
 * enum options" — Google's docs list it as an accepted value, not a validation failure.
 *
 * baseSalary and validThrough are intentionally left out, not just unmapped: there is no
 * ACF field to source either from (no per-post salary, and offene_stellen_start_date is
 * the job's OWN start date, not an application deadline), and Google explicitly warns
 * against a made-up validThrough — a wrong one is worse than none, since a posting that
 * "expired" gets suppressed from Google for Jobs. Add real per-post ACF fields for these
 * first if the client wants them in the schema; do not synthesize placeholder values.
 *
 * jobLocation uses the Stiftung's own registered address (Oetlingerstrasse 81, 4057
 * Basel — the same one in the footer) rather than the offene_stellen_standort taxonomy
 * term's name: those terms (Schreinerei, DasBreiteHotel, …) are workshop names, not
 * street addresses, and Schema.org's PostalAddress needs a real address to be valid. If
 * per-Standort addresses are ever added as real fields, this should read from those
 * instead.
 */
class Weizenkorn_Schema_JobPosting extends \Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece {

	/**
	 * Only needed on a single offene-stellen post.
	 *
	 * @return bool
	 */
	public function is_needed() {
		return ( $this->context->post instanceof WP_Post ) && 'offene-stellen' === $this->context->post->post_type;
	}

	/**
	 * Builds the JobPosting graph piece.
	 *
	 * @return array
	 */
	public function generate() {
		$post_id = $this->context->post->ID;

		$data = array(
			'@type'              => 'JobPosting',
			'@id'                => $this->context->canonical . '#jobposting',
			'url'                => $this->context->canonical,
			'title'              => $this->helpers->schema->html->smart_strip_tags( get_the_title( $post_id ) ),
			'description'        => wp_kses_post( (string) get_field( 'offene_stellen_body', $post_id ) ),
			'datePosted'         => $this->helpers->date->format( $this->context->post->post_date_gmt ),
			'hiringOrganization' => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url( '/' ),
			),
			'jobLocation'        => array(
				'@type'   => 'Place',
				'address' => array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => 'Oetlingerstrasse 81',
					'postalCode'      => '4057',
					'addressLocality' => 'Basel',
					'addressRegion'   => 'Basel-Stadt',
					'addressCountry'  => 'CH',
				),
			),
			'identifier'         => array(
				'@type' => 'PropertyValue',
				'name'  => get_bloginfo( 'name' ),
				'value' => (string) $post_id,
			),
		);

		$data['employmentType'] = $this->get_employment_type( $post_id );

		return $this->helpers->schema->language->add_piece_language( $data );
	}

	/**
	 * Maps the offene_stellen_anstellungsart taxonomy to Schema.org's employmentType enum.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string Always a valid enum value — OTHER when no more specific one fits.
	 */
	private function get_employment_type( $post_id ) {
		$terms = get_the_terms( $post_id, 'offene_stellen_anstellungsart' );

		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( false !== stripos( $term->name, 'ausbildung' ) ) {
					return 'INTERN';
				}
			}
		}

		return 'OTHER';
	}
}
