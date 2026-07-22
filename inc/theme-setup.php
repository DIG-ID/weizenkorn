<?php
/**
 * Theme setup: theme supports, nav menus, sidebars, and plugin integrations.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.0.0
 */

/**
 * Registers nav menus and theme supports.
 */
function weizenkorn_theme_setup() {

	register_nav_menus(
		array(
			'main-menu'   => __( 'Main Menu', 'weizenkorn' ),
			'footer-menu' => __( 'Footer Menu', 'weizenkorn' ),
		)
	);

	add_theme_support( 'menus' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' )
	);
}

add_action( 'after_setup_theme', 'weizenkorn_theme_setup' );

/**
 * Registers widgetized areas.
 */
function weizenkorn_register_sidebars() {

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'weizenkorn' ),
			'id'            => 'footer',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Header Language Switcher', 'weizenkorn' ),
			'id'            => 'header_ls',
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
}

add_action( 'widgets_init', 'weizenkorn_register_sidebars' );

/**
 * Removes auto <p> wrapping from Contact Form 7 fields.
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

if ( ! function_exists( 'weizenkorn_acf_google_maps_key' ) ) :
	/**
	 * Sets the Google Maps API key for ACF map fields.
	 * Requires WEIZENKORN_GOOGLE_MAPS_API_KEY to be set in functions.php.
	 */
	function weizenkorn_acf_google_maps_key() {
		if ( defined( 'WEIZENKORN_GOOGLE_MAPS_API_KEY' ) && WEIZENKORN_GOOGLE_MAPS_API_KEY ) {
			acf_update_setting( 'google_api_key', WEIZENKORN_GOOGLE_MAPS_API_KEY );
		}
	}
endif;

add_action( 'acf/init', 'weizenkorn_acf_google_maps_key' );

/**
 * Lowers Yoast SEO metabox priority so ACF fields appear above it.
 *
 * @return string
 */
function weizenkorn_lower_yoast_metabox_priority() {
	return 'core';
}

add_filter( 'wpseo_metabox_prio', 'weizenkorn_lower_yoast_metabox_priority' );
