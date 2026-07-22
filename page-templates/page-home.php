<?php
/**
 * Template Name: Home Template
 *
 * Front page composed of ordered sections (see figma-architecture-analysis.txt,
 * §7 "Home"). Each section is a template-part under template-parts/pages/home/;
 * the USP band is a shared module. Sections read their own ACF fields.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.0.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/pages/home/hero' );
		get_template_part( 'template-parts/pages/home/products' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/pages/home/services' );
		get_template_part( 'template-parts/pages/home/gastronomy' );
		get_template_part( 'template-parts/pages/home/locations' );
		get_template_part( 'template-parts/pages/home/work-training' );
		get_template_part( 'template-parts/pages/home/about-teaser' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
