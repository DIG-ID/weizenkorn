<?php
/**
 * News archive.
 *
 * SECTIONS, in order:
 *
 *   1. hero-section    shared module, from the archive's own ACF options
 *   2. news-featured   the most recent article
 *   3. news-cards      everything else, six to a page
 *
 * The featured article is read once here and its id handed to both parts, so the grid
 * leaves out exactly the post shown above it. Querying twice for "the latest" would work
 * until two articles shared a date.
 *
 * The hero reads the options store rather than a post: an archive has no post of its own,
 * so its heading lives in the theme's options with the `news_archive_` prefix.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.10.0
 */

get_header();

do_action( 'before_main_content' );

get_template_part(
	'template-parts/modules/hero-section',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'news_archive_',
	)
);

$weizenkorn_latest = get_posts(
	array(
		'post_type'        => 'news',
		'post_status'      => 'publish',
		'numberposts'      => 1,
		'suppress_filters' => false,
	)
);

$weizenkorn_featured_id = ! empty( $weizenkorn_latest[0] ) ? (int) $weizenkorn_latest[0]->ID : 0;

if ( $weizenkorn_featured_id ) {
	get_template_part(
		'template-parts/modules/news-featured',
		null,
		array( 'post_id' => $weizenkorn_featured_id )
	);
}

get_template_part(
	'template-parts/modules/news-cards',
	null,
	array( 'exclude' => $weizenkorn_featured_id )
);

do_action( 'after_main_content' );

get_footer();
