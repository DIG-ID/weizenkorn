<?php
/**
 * Template Name: Services Template
 *
 * Services overview (Figma "services_desktop") — top level of the Services
 * section. Lists the 3 service category pages (Schreinerei, Kreativatelier,
 * Fiduciary services) as its children (post_parent), via a shared "overview
 * cards" module. Sections to be added as template-parts.
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
