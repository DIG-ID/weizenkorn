<?php
/**
 * Template Name: Services - Schreinerei Template
 *
 * Schreinerei category overview (Figma "Schreinerei_desktop") — child of the
 * Services page. Lists its own 7 child pages as preview cards via a shared
 * "overview cards" module. Sections to be added as template-parts.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.4.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		// Sections go here (get_template_part calls).

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
