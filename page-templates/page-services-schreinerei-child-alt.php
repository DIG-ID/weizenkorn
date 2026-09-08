<?php
/**
 * Template Name: Services - Schreinerei Child (Alt) Template
 *
 * Schreinerei service detail page — the 1 of the 7 child pages under
 * Schreinerei with a different layout from the other 6 (see
 * page-services-schreinerei-child.php). Sections to be added as
 * template-parts; preview-cards ("Entdecken Sie mehr") is already wired
 * in, unprefixed like every other use of it — see the module's own
 * docblock for the two ACF Clone fields it expects.
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
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
