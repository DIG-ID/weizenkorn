<?php
/**
 * Template Name: Gastronomie und Hotellerie Template
 *
 * Gastronomie und Hotellerie overview page: shared hero-section module,
 * then the page-specific venues section (same structure as the Home page's
 * gastronomy section, not a module), then the shared USP band and quote
 * slider modules.
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
		get_template_part( 'template-parts/pages/gastronomie-hotellerie/gastronomy' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/quote-slider' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
