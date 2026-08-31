<?php
/**
 * The template for a single Open Position (offene-stellen).
 *
 * Figma has no responsive frames for this template (desktop only, node
 * 4450:6539) — tablet and mobile are common-sense adaptations of the
 * desktop layout, not a match against a design.
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

		get_template_part( 'template-parts/single/offene-stellen/job-header' );
		get_template_part( 'template-parts/single/offene-stellen/job-details' );
		get_template_part( 'template-parts/single/offene-stellen/job-contact' );
		get_template_part( 'template-parts/single/offene-stellen/related-jobs' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
