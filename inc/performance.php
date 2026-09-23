<?php
/**
 * Performance optimizations: dequeue unused styles/scripts, clean head, disable unused features.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.0.0
 */

/**
 * Dequeues styles that are not needed on the frontend.
 * Runs at priority 100 to ensure it fires after all other enqueue hooks.
 */
function weizenkorn_dequeue_unused_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_enqueue_scripts', 'weizenkorn_dequeue_unused_styles', 100 );

/**
 * Dequeues the wp-embed script — embedding WP posts in external sites is rarely needed.
 */
function weizenkorn_dequeue_unused_scripts() {
	wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', 'weizenkorn_dequeue_unused_scripts' );

/**
 * Removes unnecessary links and meta tags from wp_head.
 */
function weizenkorn_clean_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}

add_action( 'init', 'weizenkorn_clean_head' );

/**
 * Disables the WordPress emoji detection script and styles.
 */
function weizenkorn_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'weizenkorn_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'weizenkorn_disable_emojis_dns_prefetch', 10, 2 );
}

add_action( 'init', 'weizenkorn_disable_emojis' );

/**
 * Removes the wpemoji TinyMCE plugin.
 *
 * @param array $plugins Active TinyMCE plugins.
 * @return array
 */
function weizenkorn_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	return array();
}

/**
 * Removes the emoji CDN URL from DNS prefetch hints.
 *
 * @param array  $urls          Resource hint URLs.
 * @param string $relation_type Hint type (dns-prefetch, preconnect, etc.).
 * @return array
 */
function weizenkorn_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls          = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

/**
 * Prevents WordPress from pinging its own URLs (self pingbacks).
 *
 * @param array $links List of URLs to ping.
 */
function weizenkorn_disable_self_pingbacks( &$links ) {
	foreach ( $links as $l => $link ) {
		if ( str_starts_with( $link, get_option( 'home' ) ) ) {
			unset( $links[ $l ] );
		}
	}
}

add_action( 'pre_ping', 'weizenkorn_disable_self_pingbacks' );

/**
 * Checks whether the current request renders a Contact Form 7 form — via the shared
 * cta-form or order-form modules, either called directly or pulled in by another module
 * that always includes one (contact-person calls cta-form for its own form band).
 *
 * Checking is_page_template() alone would miss the CPT archive/single templates below —
 * they are matched by file name (WordPress' own template hierarchy), not a selectable
 * Page template, so is_post_type_archive()/is_singular() is what actually catches them.
 *
 * @return bool
 */
function weizenkorn_has_contact_form() {
	$page_templates = array(
		'page-templates/page-about-us-organization.php',
		'page-templates/page-contact.php',
		'page-templates/page-gastronomie-events-seminare.php',
		'page-templates/page-gastronomie-our-bakery.php',
		'page-templates/page-services.php',
		'page-templates/page-services-fiduciary.php',
		'page-templates/page-services-kreativatelier.php',
		'page-templates/page-services-schreinerei.php',
		'page-templates/page-work-training.php',
		'page-templates/page-work-training-for-social-offices-and-partners.php',
		'page-templates/page-work-training-supported-apprenticeships.php',
		'page-templates/page-work-training-supported-jobs.php',
		'page-templates/product-range-holzmanufaktur.php',
		'page-templates/product-range-holzspielwaren.php',
		'page-templates/product-range-kerzen.php',
		'page-templates/product-range-living-collection.php',
		'page-templates/product-range-xyloba.php',
	);

	return is_page_template( $page_templates )
		|| is_post_type_archive( 'products' )
		|| is_singular( 'products' )
		|| is_post_type_archive( 'offene-stellen' );
}

/**
 * Hides Google's reCAPTCHA badge everywhere except pages that render a Contact Form 7
 * form — CF7 only loads reCAPTCHA where a form needs it, but the badge itself is fixed
 * in a page corner site-wide once the API script has loaded anywhere.
 */
function weizenkorn_recaptcha_badge_visibility() {
	$visibility = weizenkorn_has_contact_form() ? 'visible' : 'hidden';
	echo '<style>.grecaptcha-badge { visibility: ' . esc_html( $visibility ) . ' !important; }</style>';
}

add_action( 'wp_head', 'weizenkorn_recaptcha_badge_visibility' );

/**
 * Loads Contact Form 7's own JS/CSS only where a form actually renders, instead of on
 * every page site-wide.
 *
 * @param bool $load Whether CF7 would otherwise load its assets.
 * @return bool
 */
function weizenkorn_cf7_load_assets( $load ) {
	return $load && weizenkorn_has_contact_form();
}

add_filter( 'wpcf7_load_js', 'weizenkorn_cf7_load_assets' );
add_filter( 'wpcf7_load_css', 'weizenkorn_cf7_load_assets' );
