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
 * The same thing by URL rather than by attachment id.
 *
 * Both, because the id filters above went out on their own first and did nothing: every
 * page kept the site's default image, and the product pages that looked right turned out to
 * be right for another reason — they are the only post type here with a featured image, so
 * Yoast was finding that by itself and the filter never ran at all. These two are the
 * oldest and most widely used names in Yoast's image API, so they are the better bet; the
 * pair above costs nothing if it is never called.
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
