<?php
/**
 * Gives Yoast a meta description where it cannot work out a good one itself.
 *
 * Two content types need help, for opposite reasons.
 *
 * A JOB POSTING has none at all. Yoast falls back to the excerpt, and the excerpt falls
 * back to post_content — but a posting's body is an ACF field, offene_stellen_body, and
 * nothing ever writes post_content because no template renders it. So %%excerpt%% resolves
 * to nothing and the posting goes out with an empty description, quietly: no warning in the
 * admin, and the only way to notice is to read the page source. A template in Yoast's
 * settings cannot fix that either — the text it needs is somewhere Yoast cannot reach.
 *
 * A NEWS POST has one, and it is cut badly. There post_content is filled, so %%excerpt%%
 * does produce text — but Yoast cuts it at a character count with no regard for words and
 * no mark to say it was cut, which leaves a sentence hanging: "…schlägt die Stiftung
 * Weizenkorn ein". Accurate, and it reads like a mistake.
 *
 * Both end up cut the same way below: on a word boundary, with an ellipsis, within the
 * length a search result will actually show.
 *
 * A description typed into the Yoast box always wins. That is checked against the post's
 * own meta rather than against the value passed in, because the value passed in may be a
 * template's output — which is exactly what needs replacing on a news post.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.18.5
 */

/**
 * Cuts a description to length on a word boundary.
 *
 * Measured in characters, not bytes: "Ausbildungsplätze" is shorter than strlen() thinks,
 * and a byte-wise cut through an umlaut leaves a broken character in the markup.
 *
 * @since 1.18.5
 *
 * @param string $text The full text, already stripped of markup.
 * @return string The text, cut and marked if it had to be.
 */
function weizenkorn_trim_meta_description( $text ) {

	$text = trim( preg_replace( '/\s+/u', ' ', $text ) );

	if ( '' === $text ) {
		return '';
	}

	// 155 is where Google starts truncating on a desktop result. One character is left for
	// the ellipsis a cut sentence needs.
	$limit = 154;

	if ( mb_strlen( $text ) <= $limit + 1 ) {
		return $text;
	}

	$cut   = mb_substr( $text, 0, $limit );
	$space = mb_strrpos( $cut, ' ' );

	// Text with no space inside the limit is one very long word; taking the hard cut is
	// better than returning nothing.
	if ( false !== $space && $space > 0 ) {
		$cut = mb_substr( $cut, 0, $space );
	}

	return rtrim( $cut, " \t\n\r\0\x0B.,;:–-" ) . '…';
}

/**
 * Supplies the description for the two types Yoast handles badly.
 *
 * @since 1.18.5
 *
 * @param string $description The description Yoast settled on.
 * @return string A description built from the post's own text, or Yoast's own value.
 */
function weizenkorn_meta_description( $description ) {

	if ( ! function_exists( 'get_field' ) || ! is_singular( array( 'offene-stellen', 'news' ) ) ) {
		return $description;
	}

	$post_id = get_queried_object_id();

	// Written by hand in the Yoast box: leave it alone, whatever it says.
	if ( '' !== trim( (string) get_post_meta( $post_id, '_yoast_wpseo_metadesc', true ) ) ) {
		return $description;
	}

	$source = is_singular( 'offene-stellen' )
		? (string) get_field( 'offene_stellen_body', $post_id )
		: (string) get_post_field( 'post_content', $post_id );

	$source  = wp_strip_all_tags( strip_shortcodes( $source ) );
	$trimmed = weizenkorn_trim_meta_description( $source );

	return '' !== $trimmed ? $trimmed : $description;
}
add_filter( 'wpseo_metadesc', 'weizenkorn_meta_description' );

/*
 * And the same text for a share. Yoast keeps the Open Graph description as its own value
 * rather than reading the one above, so a posting shared on LinkedIn — which is where a
 * vacancy actually travels — would otherwise carry the site's description instead of its
 * own.
 */
add_filter( 'wpseo_opengraph_desc', 'weizenkorn_meta_description' );
add_filter( 'wpseo_twitter_description', 'weizenkorn_meta_description' );
