<?php
/**
 * Gives Yoast a meta description for a job posting, which it cannot work one out for.
 *
 * Yoast falls back to the excerpt, and the excerpt falls back to post_content — but a job
 * posting's body is an ACF field, offene_stellen_body, and nothing ever writes post_content
 * because no template renders it. So %%excerpt%% resolves to nothing there and the posting
 * goes out with no description at all, quietly: no warning in the admin, and the only way
 * to notice is to read the page source.
 *
 * A fixed template in Yoast's settings would not fix it either, for the same reason — the
 * text it needs is somewhere Yoast cannot reach. Hence a filter, the same shape as the one
 * in social-image.php: the theme hands over what only it knows where to find.
 *
 * Only when the field is empty. An editor who writes a description in the Yoast box keeps
 * it — this fills a blank, it does not overrule anyone.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.18.5
 */

/**
 * Builds a description from a job posting's body.
 *
 * Cut on a word boundary and measured in characters, not bytes: "Ausbildungsplätze" is
 * shorter than strlen() thinks, and a byte-wise cut through an umlaut leaves a broken
 * character in the markup.
 *
 * @since 1.18.5
 *
 * @param string $description The description Yoast settled on.
 * @return string The posting's opening lines, or Yoast's own value.
 */
function weizenkorn_job_meta_description( $description ) {

	if ( '' !== trim( (string) $description ) ) {
		return $description;
	}

	if ( ! function_exists( 'get_field' ) || ! is_singular( 'offene-stellen' ) ) {
		return $description;
	}

	$body = (string) get_field( 'offene_stellen_body', get_queried_object_id() );
	$body = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( $body ) ) ) );

	if ( '' === $body ) {
		return $description;
	}

	// 155 is where Google starts truncating on a desktop result. One character is left for
	// the ellipsis a cut sentence needs.
	$limit = 154;

	if ( mb_strlen( $body ) <= $limit + 1 ) {
		return $body;
	}

	$cut   = mb_substr( $body, 0, $limit );
	$space = mb_strrpos( $cut, ' ' );

	// A body with no space inside the limit is one very long word; taking the hard cut is
	// better than returning nothing.
	if ( false !== $space && $space > 0 ) {
		$cut = mb_substr( $cut, 0, $space );
	}

	return rtrim( $cut, " \t\n\r\0\x0B.,;:–-" ) . '…';
}
add_filter( 'wpseo_metadesc', 'weizenkorn_job_meta_description' );

/*
 * And the same text for a share. Yoast keeps the Open Graph description as its own value
 * rather than reading the one above, so a posting shared on LinkedIn — which is where a
 * vacancy actually travels — would otherwise carry the site's description instead of its
 * own.
 */
add_filter( 'wpseo_opengraph_desc', 'weizenkorn_job_meta_description' );
add_filter( 'wpseo_twitter_description', 'weizenkorn_job_meta_description' );
