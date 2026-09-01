<?php
/**
 * REST endpoint powering the Open Positions archive's filter panel and
 * "Mehr Laden" pagination (template-parts/archives/offene-stellen/job-filters.php,
 * assets/js/job-filters.js). Filtering and "loading more" are the same
 * query — the client always asks for page 1 on Apply/Clear and page+1 on
 * Mehr Laden, with whichever taxonomy terms are currently checked.
 *
 * Public GET, no nonce: read-only, gated by nothing a logged-out visitor
 * couldn't already see on the archive itself, and worth keeping cacheable.
 * Inputs are sanitized to taxonomy term slugs and a positive page number —
 * WP_Query's tax_query does the rest, no manual SQL.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.11.0
 */

/**
 * Registers the weizenkorn/v1/jobs route.
 */
function weizenkorn_register_job_filters_route() {
	register_rest_route(
		'weizenkorn/v1',
		'/jobs',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'weizenkorn_job_filters_query',
			'permission_callback' => '__return_true',
			'args'                => array(
				'anstellungsart' => array(
					'type'              => 'array',
					'items'             => array( 'type' => 'string' ),
					'default'           => array(),
					'sanitize_callback' => 'weizenkorn_sanitize_term_slugs',
				),
				'standort'       => array(
					'type'              => 'array',
					'items'             => array( 'type' => 'string' ),
					'default'           => array(),
					'sanitize_callback' => 'weizenkorn_sanitize_term_slugs',
				),
				'page'           => array(
					'type'              => 'integer',
					'default'           => 1,
					'sanitize_callback' => 'absint',
				),
			),
		)
	);
}

add_action( 'rest_api_init', 'weizenkorn_register_job_filters_route' );

/**
 * Sanitizes a REST param expected to be an array of taxonomy term slugs.
 *
 * @param mixed $value Raw param value.
 * @return string[]
 */
function weizenkorn_sanitize_term_slugs( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	return array_values( array_filter( array_map( 'sanitize_title', $value ) ) );
}

/**
 * Runs the filtered/paginated offene-stellen query and returns rendered cards.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function weizenkorn_job_filters_query( WP_REST_Request $request ) {
	// Matches the archive's own SSR first page — see job-listing.php's $jl_per_page.
	$per_page = 9;

	$tax_query = array();

	$anstellungsart = $request->get_param( 'anstellungsart' );
	if ( ! empty( $anstellungsart ) ) {
		$tax_query[] = array(
			'taxonomy' => 'offene_stellen_anstellungsart',
			'field'    => 'slug',
			'terms'    => $anstellungsart,
		);
	}

	$standort = $request->get_param( 'standort' );
	if ( ! empty( $standort ) ) {
		$tax_query[] = array(
			'taxonomy' => 'offene_stellen_standort',
			'field'    => 'slug',
			'terms'    => $standort,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}

	$page = max( 1, (int) $request->get_param( 'page' ) );

	$query = new WP_Query(
		array(
			'post_type'      => 'offene-stellen',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => $tax_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- only 2 possible clauses, on an indexed term-relationships lookup, not postmeta.
		)
	);

	return new WP_REST_Response(
		array(
			'html'      => weizenkorn_render_job_cards( $query ),
			'page'      => $page,
			'max_pages' => (int) $query->max_num_pages,
			'found'     => (int) $query->found_posts,
		)
	);
}
