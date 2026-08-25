<?php
/**
 * Template Name: Gastronomie - Events & Seminare Template
 *
 * The Events & Seminare page of the gastronomy venues. Each venue gets its own template, so
 * a venue whose design diverges is changed on its own file and nowhere else.
 *
 * SECTIONS, in order: hero-section, usp-band, spaces, quote-slider, catering, location,
 * cta-form.
 *
 * Every one is a shared module, so a sibling venue repeats the page by calling the same
 * parts in its own template.
 *
 * ACF. On a page there is a post context, so every module reads the current post with no
 * prefix. Clone the GROUP per section, never a repeater inside one — and clone the shared
 * "Section Title" group itself, never another page's clone of it: a chain of clones stops
 * composing usable subfield names, and half the section renders while the other half does
 * not.
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
		get_template_part( 'template-parts/modules/spaces' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/catering' );
		get_template_part( 'template-parts/modules/location' );
		get_template_part( 'template-parts/modules/cta-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
