<?php
/**
 * Theme functions and definitions.
 *
 * @package weizenkorn
 */

// Safe defaults only. Each can be overridden per environment in wp-config.php — real API
// keys belong there, outside the repo, never committed to the theme. See SETUP.md.
if ( ! defined( 'WEIZENKORN_FONT_PROVIDER' ) ) {
	define( 'WEIZENKORN_FONT_PROVIDER', 'google' );
}
if ( ! defined( 'WEIZENKORN_GOOGLE_FONTS_URL' ) ) {
	define( 'WEIZENKORN_GOOGLE_FONTS_URL', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;0,900;1,300;1,400&display=swap' );
}
if ( ! defined( 'WEIZENKORN_ADOBE_FONTS_ID' ) ) {
	define( 'WEIZENKORN_ADOBE_FONTS_ID', '' ); // Typekit kit ID, e.g. 'abc1234'.
}

if ( ! defined( 'WEIZENKORN_GOOGLE_MAPS_API_KEY' ) ) {
	define( 'WEIZENKORN_GOOGLE_MAPS_API_KEY', '' );
}

require get_template_directory() . '/inc/theme-setup.php';

require get_template_directory() . '/inc/enqueue.php';

require get_template_directory() . '/inc/theme-admin-settings.php';

require get_template_directory() . '/inc/theme-template-tags.php';

require get_template_directory() . '/inc/rest-job-filters.php';

require get_template_directory() . '/inc/helpers.php';

require get_template_directory() . '/inc/news.php';

require get_template_directory() . '/inc/performance.php';

require get_template_directory() . '/inc/security.php';

require get_template_directory() . '/inc/schema.php';
