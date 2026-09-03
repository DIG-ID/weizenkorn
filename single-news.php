<?php
/**
 * Single news article — the photograph and the links to the articles either side of it,
 * the article itself, then the rest of the news under it.
 *
 * The grid leaves out the article being read, which is the same module the archive uses
 * with a different id kept out of it.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.10.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/posts/news', 'hero' );
		get_template_part( 'template-parts/posts/news', 'header' );
		get_template_part( 'template-parts/posts/news', 'content' );

		get_template_part(
			'template-parts/modules/news-cards',
			null,
			array(
				'exclude' => get_the_ID(),
				'back'    => true,
				'variant' => 'slider',
			)
		);

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
