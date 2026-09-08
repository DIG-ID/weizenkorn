<?php
/**
 * Template Name: Services - Schreinerei Child Template
 *
 * Schreinerei service detail page — one of the 7 child pages under
 * Schreinerei. Shared by the 6 children with the standard layout (the 7th,
 * differently laid out, uses page-services-schreinerei-child-alt.php).
 * Sections to be added as template-parts; preview-cards ("Entdecken Sie
 * mehr") is already wired in, unprefixed like every other use of it — see
 * the module's own docblock for the two ACF Clone fields it expects.
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

		get_template_part( 'template-parts/modules/hero-section-detail' );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
