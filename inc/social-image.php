<?php
/**
 * Hands Yoast the image a page already shows, for Open Graph and Twitter cards.
 *
 * Yoast looks for the featured image, and this theme sets none: every hero picture lives in
 * an ACF field instead, which Yoast cannot see. Left alone, every page on the site shares
 * without a picture. The alternative — setting a featured image on thirty pages that the
 * theme never renders — asks editors to keep a field in step with one they can see, and
 * that goes stale the first time someone changes a hero.
 *
 * So the chain below reads the hero the page actually draws, in the order the templates
 * themselves use:
 *
 *   hero_section_image       most pages, product singles, and the two archives (options)
 *   hero_image               the home page — its hero is a video with a poster image
 *   page_hero_detail_image   the Schreinerei child page, which uses hero-section-detail
 *   news_hero_image          a news post's own override, before its featured image
 *
 * Four contexts draw no hero at all and are meant to fall through to the default social
 * image set in Yoast: the three legal pages, which are a title band and text, and a job
 * posting, which opens with its own header. Those are not gaps — they are pages with no
 * picture of their own, and the site-wide default is the right answer for them.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.18.5
 */

/**
 * Returns the attachment id of the hero image for the context being rendered.
 *
 * Archives have no post to read from, so they take their fields from the options store
 * under the prefix their template passes to the hero module — kept in step with
 * archive-products.php and archive-offene-stellen.php.
 *
 * @since 1.18.5
 *
 * @return int Attachment id, or 0 when this context draws no hero.
 */
function weizenkorn_social_image_id() {

	if ( ! function_exists( 'get_field' ) ) {
		return 0;
	}

	if ( is_post_type_archive( 'products' ) ) {
		return (int) get_field( 'products_archive_hero_section_image', 'option' );
	}

	if ( is_post_type_archive( 'offene-stellen' ) ) {
		return (int) get_field( 'offene_stellen_archive_hero_section_image', 'option' );
	}

	if ( ! is_singular() ) {
		return 0;
	}

	$post_id = get_queried_object_id();

	// In the order the templates read them; the first one set wins. get_post_thumbnail_id()
	// closes the list for news, whose hero already falls back to it — see news-hero.php.
	$candidates = array(
		(int) get_field( 'hero_section_image', $post_id ),
		(int) get_field( 'hero_image', $post_id ),
		(int) get_field( 'page_hero_detail_image', $post_id ),
		(int) get_field( 'news_hero_image', $post_id ),
		(int) get_post_thumbnail_id( $post_id ),
	);

	foreach ( $candidates as $candidate ) {
		if ( $candidate > 0 ) {
			return $candidate;
		}
	}

	return 0;
}

/**
 * Points Yoast's Open Graph image at the page's hero.
 *
 * Only when the page has one: returning the untouched id lets Yoast carry on to whatever it
 * would have used, which for this theme means the default image set in its settings.
 *
 * @since 1.18.5
 *
 * @param int $image_id The attachment id Yoast settled on.
 * @return int The hero's id, or Yoast's own when there is no hero.
 */
function weizenkorn_opengraph_image_id( $image_id ) {

	$hero_id = weizenkorn_social_image_id();

	return $hero_id > 0 ? $hero_id : $image_id;
}
add_filter( 'wpseo_opengraph_image_id', 'weizenkorn_opengraph_image_id' );
add_filter( 'wpseo_twitter_image_id', 'weizenkorn_opengraph_image_id' );

/**
 * The same thing by URL.
 *
 * This is the one that works. The id filters above were invented — Yoast 28.5 has no
 * wpseo_opengraph_image_id, so they never fired, and the product pages that looked correct
 * were correct for another reason: they are the only post type here with a featured image,
 * which Yoast finds by itself. Left in place because they cost nothing, and removed the day
 * someone confirms no Yoast version ever had them.
 *
 * The real set, read from src/presenters/open-graph/image-presenter.php, is url / width /
 * height / type, each filtered separately. Which is why swapping only the url left every
 * hero page announcing the dimensions of whatever image Yoast had picked before — the three
 * below put that right.
 *
 * @since 1.18.5
 *
 * @param string $image The image URL Yoast settled on.
 * @return string The hero's URL, or Yoast's own when there is no hero.
 */
function weizenkorn_opengraph_image_url( $image ) {

	$hero_id = weizenkorn_social_image_id();

	if ( $hero_id <= 0 ) {
		return $image;
	}

	// full, not a crop: a hero is already wide, and the sizes WordPress generates are cut to
	// this theme's own proportions rather than the 1.91:1 the networks ask for.
	$url = wp_get_attachment_image_url( $hero_id, 'full' );

	return $url ? $url : $image;
}
add_filter( 'wpseo_opengraph_image', 'weizenkorn_opengraph_image_url' );
add_filter( 'wpseo_twitter_image', 'weizenkorn_opengraph_image_url' );

/**
 * Reports the hero's real dimensions and type alongside its URL.
 *
 * Yoast filters these three separately from the URL, so changing the URL alone leaves the
 * numbers describing an image that is no longer there — and a network reads them before it
 * fetches anything, so a wrong pair is a wrongly cropped card.
 *
 * Returns the value Yoast had whenever this page has no hero, which is what keeps the
 * default image's own numbers intact on the pages that fall back to it.
 *
 * @since 1.18.5
 *
 * @param mixed  $value The width, height or mime type Yoast settled on.
 * @param string $key Which of the three: 'width', 'height' or 'type'.
 * @return mixed The hero's own value, or Yoast's.
 */
function weizenkorn_opengraph_image_meta( $value, $key ) {

	$hero_id = weizenkorn_social_image_id();

	if ( $hero_id <= 0 ) {
		return $value;
	}

	if ( 'type' === $key ) {
		$type = get_post_mime_type( $hero_id );

		return $type ? $type : $value;
	}

	$meta = wp_get_attachment_metadata( $hero_id );

	return ( is_array( $meta ) && ! empty( $meta[ $key ] ) ) ? (int) $meta[ $key ] : $value;
}

add_filter(
	'wpseo_opengraph_image_width',
	static function ( $value ) {
		return weizenkorn_opengraph_image_meta( $value, 'width' );
	}
);

add_filter(
	'wpseo_opengraph_image_height',
	static function ( $value ) {
		return weizenkorn_opengraph_image_meta( $value, 'height' );
	}
);

add_filter(
	'wpseo_opengraph_image_type',
	static function ( $value ) {
		return weizenkorn_opengraph_image_meta( $value, 'type' );
	}
);
