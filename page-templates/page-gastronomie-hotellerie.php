<?php
/**
 * Template Name: Gastronomie und Hotellerie Template
 *
 * Gastronomie und Hotellerie overview page. Starts with the shared
 * hero-section module (same as page-services.php); further sections to be
 * added as template-parts.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.5.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
