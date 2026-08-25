<?php
/**
 * Template Name: Contact Template
 *
 * The contact page: the standard page header, the shared enquiry form, and a map with the
 * address under it.
 *
 * SECTIONS, in order: hero-section, cta-form, location.
 *
 * Every one is a shared module, so this template is only the order they come in.
 *
 * ACF. On a page there is a post context, so every module reads the current post with no
 * prefix. Clone the GROUP per section, never a repeater inside one — and clone the shared
 * "Section Title" group itself, never another page's clone of it: a chain of clones stops
 * composing usable subfield names, and half the section renders while the other half does
 * not.
 *
 * The form is the site-wide one from the theme options unless `cta_shortcode` names another
 * here, and the map takes either a single `location_pin` or the `location_items` repeater.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.7.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/modules/cta-form' );
		get_template_part( 'template-parts/modules/location' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
