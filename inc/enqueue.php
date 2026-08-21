<?php
/**
 * Enqueue scripts and styles.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.0.0
 */

if ( ! function_exists( 'weizenkorn_enqueue_fonts' ) ) :
	/**
	 * Enqueues web fonts for the provider configured in functions.php: 'google', 'adobe'
	 * (Typekit) or 'none'.
	 */
	function weizenkorn_enqueue_fonts() {
		$provider = defined( 'WEIZENKORN_FONT_PROVIDER' ) ? WEIZENKORN_FONT_PROVIDER : 'none';

		if ( 'google' === $provider && defined( 'WEIZENKORN_GOOGLE_FONTS_URL' ) && WEIZENKORN_GOOGLE_FONTS_URL ) {
			wp_enqueue_style(
				'weizenkorn-fonts',
				WEIZENKORN_GOOGLE_FONTS_URL,
				array(),
				null
			);
		}

		if ( 'adobe' === $provider && defined( 'WEIZENKORN_ADOBE_FONTS_ID' ) && WEIZENKORN_ADOBE_FONTS_ID ) {
			wp_enqueue_style(
				'weizenkorn-fonts',
				'https://use.typekit.net/' . WEIZENKORN_ADOBE_FONTS_ID . '.css',
				array(),
				null
			);
		}
	}
endif;

add_action( 'wp_enqueue_scripts', 'weizenkorn_enqueue_fonts' );

if ( ! function_exists( 'weizenkorn_font_resource_hints' ) ) :
	/**
	 * Adds preconnect resource hints for the configured font provider.
	 *
	 * @param array  $urls          Resource hint URLs.
	 * @param string $relation_type Hint type (preconnect, dns-prefetch, etc.).
	 * @return array
	 */
	function weizenkorn_font_resource_hints( $urls, $relation_type ) {
		if ( 'preconnect' !== $relation_type ) {
			return $urls;
		}

		$provider = defined( 'WEIZENKORN_FONT_PROVIDER' ) ? WEIZENKORN_FONT_PROVIDER : 'none';

		if ( 'google' === $provider ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array(
				'href'        => 'https://fonts.gstatic.com',
				'crossorigin' => 'crossorigin',
			);
		}

		if ( 'adobe' === $provider ) {
			$urls[] = array(
				'href'        => 'https://use.typekit.net',
				'crossorigin' => 'crossorigin',
			);
		}

		return $urls;
	}
endif;

add_filter( 'wp_resource_hints', 'weizenkorn_font_resource_hints', 10, 2 );

if ( ! function_exists( 'weizenkorn_enqueue_google_maps' ) ) :
	/**
	 * Enqueues the Google Maps API and init script where a map is actually rendered.
	 * Requires WEIZENKORN_GOOGLE_MAPS_API_KEY to be set in functions.php.
	 *
	 * The `location` module's pin normally decides it: every gastronomy venue has its own
	 * template, so a list of templates would grow by one per venue and be forgotten
	 * exactly once. A page that needs a map without that field goes in $templates.
	 */
	function weizenkorn_enqueue_google_maps() {
		if ( ! defined( 'WEIZENKORN_GOOGLE_MAPS_API_KEY' ) || ! WEIZENKORN_GOOGLE_MAPS_API_KEY ) {
			return;
		}

		$templates = array(
			// 'page-templates/page-contact.php',
		);

		$has_template = ! empty( $templates ) && is_page_template( $templates );

		// The same question the template asks: a map is drawn only for a pin with both
		// coordinates.
		$pin     = ( is_singular() && function_exists( 'get_field' ) ) ? get_field( 'location_pin', get_queried_object_id() ) : null;
		$has_pin = is_array( $pin ) && ! empty( $pin['lat'] ) && ! empty( $pin['lng'] );

		if ( ! $has_template && ! $has_pin ) {
			return;
		}

		$theme_version = wp_get_theme()->get( 'Version' );

		// jQuery is a real dependency, not a convention: google-maps.js is an IIFE over
		// jQuery and reads window.jQuery the moment it runs.
		wp_enqueue_script(
			'weizenkorn-google-maps-init',
			get_theme_file_uri( '/assets/js/google-maps.js' ),
			array( 'jquery' ),
			$theme_version,
			true
		);

		wp_enqueue_script(
			'weizenkorn-google-maps-api',
			add_query_arg(
				array(
					'key'      => WEIZENKORN_GOOGLE_MAPS_API_KEY,
					'callback' => 'initMap',
					'loading'  => 'async',
				),
				'https://maps.googleapis.com/maps/api/js'
			),
			array( 'weizenkorn-google-maps-init' ),
			null,
			true
		);
	}
endif;

add_action( 'wp_enqueue_scripts', 'weizenkorn_enqueue_google_maps' );

if ( ! function_exists( 'weizenkorn_asset_version' ) ) :
	/**
	 * Returns the cache-busting version for a compiled asset.
	 *
	 * Reads mix.version()'s content hash from dist/mix-manifest.json, so caches are
	 * invalidated exactly when the asset content changes. Falls back to the theme version
	 * when the manifest or the hash is unavailable, as in a dev build.
	 *
	 * @since 1.7.1
	 *
	 * @param string $asset Asset path as keyed in the manifest (e.g. '/css/main.css').
	 * @return string Version string to pass to wp_enqueue_style/script.
	 */
	function weizenkorn_asset_version( $asset ) {
		static $manifest = null;

		if ( null === $manifest ) {
			$manifest_path = get_theme_file_path( '/dist/mix-manifest.json' );
			$manifest      = array();

			if ( file_exists( $manifest_path ) ) {
				$contents = file_get_contents( $manifest_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file, not a remote request.
				$decoded  = $contents ? json_decode( $contents, true ) : null;

				if ( is_array( $decoded ) ) {
					$manifest = $decoded;
				}
			}
		}

		if ( ! empty( $manifest[ $asset ] ) ) {
			$query = wp_parse_url( $manifest[ $asset ], PHP_URL_QUERY );

			if ( $query ) {
				parse_str( $query, $params );

				if ( ! empty( $params['id'] ) ) {
					return $params['id'];
				}
			}
		}

		return wp_get_theme()->get( 'Version' );
	}
endif;

/**
 * Registers and enqueues the theme's main CSS and JS.
 */
function weizenkorn_enqueue_assets() {

	// Enqueue theme stylesheet.
	wp_enqueue_style(
		'theme-styles',
		get_theme_file_uri( '/dist/css/main.css' ),
		array(),
		weizenkorn_asset_version( '/css/main.css' )
	);

	wp_enqueue_script(
		'theme-scripts',
		get_theme_file_uri( '/dist/js/main.js' ),
		array( 'jquery' ),
		weizenkorn_asset_version( '/js/main.js' ),
		true
	);
}

add_action( 'wp_enqueue_scripts', 'weizenkorn_enqueue_assets' );
