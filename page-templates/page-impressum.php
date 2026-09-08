<?php
/**
 * Template Name: Impressum Template
 *
 * The Impressum page. Its own template because the body is a grid of labelled cells, not
 * the single column of heading-beside-text blocks the AGB and Datenschutz share — see
 * page-legal.php for those two. The title band above is the same on all three.
 *
 * SECTIONS, in order:
 *   1. title-band      module, the page's <h1> inside a red-bordered band
 *   2. impressum/details  page part, the six labelled cells
 *
 * The editor's content is deliberately not output — everything shows through the
 * `impressum` repeater, and the field group takes the editor off the screen.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.14.1
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/title-band' );
		get_template_part( 'template-parts/pages/impressum/details' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
