<?php
/**
 * Template Name: Legal Template
 *
 * The AGB and Datenschutzerklärung pages. One template for the two, which share this
 * layout and differ only in their text. Impressum has a layout of its own and does not
 * use this template.
 *
 * No hero: these pages open on a title band and go straight into the text, which is what
 * separates them from every other page in the theme.
 *
 * SECTIONS, in order:
 *   1. title-band      module, the page's <h1> inside a red-bordered band
 *   2. legal-content   module, the blocks of heading and text
 *
 * The editor's content is deliberately not output. Everything shows through the
 * `legal_content` repeater, for the reason in that module's docblock — so anything typed
 * into the editor on these pages will not appear.
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
		get_template_part( 'template-parts/modules/legal-content' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
