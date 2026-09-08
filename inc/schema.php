<?php
/**
 * Custom Yoast Schema API pieces — added via the `wpseo_schema_graph_pieces`
 * filter, never as manual JSON-LD in wp_head() (see CLAUDE.md's own SEO/
 * Schema rule). Yoast's own schema graph generator sets $piece->context and
 * $piece->helpers on every piece before calling is_needed()/generate(), so
 * neither class needs a constructor for that.
 *
 * Two pieces, one class per file (inc/schema/), same convention as WordPress
 * core's own class-*.php naming:
 *   Weizenkorn_Schema_JobPosting  Open Positions single post → schema.org/JobPosting,
 *                                 for Google for Jobs eligibility. Was flagged as "to
 *                                 define" in CLAUDE.md's own SEO table since the CPT was
 *                                 first built; the single post template exists now.
 *   Weizenkorn_Schema_FAQ         schema.org/Question + Answer for every page that calls
 *                                 template-parts/modules/faq.php — that module is a plain
 *                                 ACF repeater, not the Yoast FAQ block, so Yoast's own
 *                                 built-in FAQ piece (which only reads yoast/faq-block)
 *                                 never fires for it on its own.
 *
 * Both classes extend Yoast's own Abstract_Schema_Piece, so this file does nothing (no
 * fatal error, the two class files are never required) if Yoast SEO is ever deactivated.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.13.0
 */

if ( ! class_exists( '\Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece' ) ) {
	return;
}

require get_template_directory() . '/inc/schema/class-weizenkorn-schema-jobposting.php';
require get_template_directory() . '/inc/schema/class-weizenkorn-schema-faq.php';

/**
 * Registers both custom pieces with Yoast's schema graph.
 *
 * @param array $pieces Existing graph piece generators.
 *
 * @return array
 */
function weizenkorn_add_schema_pieces( $pieces ) {
	$pieces[] = new Weizenkorn_Schema_JobPosting();
	$pieces[] = new Weizenkorn_Schema_FAQ();

	return $pieces;
}
add_filter( 'wpseo_schema_graph_pieces', 'weizenkorn_add_schema_pieces' );
