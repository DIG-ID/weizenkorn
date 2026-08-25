<?php
/**
 * Template Name: Gastronomie - Cantina e9 Template
 *
 * The Cantina e9 page of the gastronomy venues. Each venue gets its own template, so a
 * venue whose design diverges is changed on its own file and nowhere else.
 *
 * SECTIONS, in order: hero-section, usp-band, photo-mosaic ('gastronomy' variant, which
 * every venue page passes), catering, booking, location.
 *
 * Every one is a shared module, so a sibling venue repeats the page by calling the same
 * parts in its own template.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.8.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/photo-mosaic', null, array( 'variant' => 'gastronomy' ) );
		get_template_part( 'template-parts/modules/catering' );
		get_template_part( 'template-parts/modules/booking' );
		get_template_part( 'template-parts/modules/location' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
