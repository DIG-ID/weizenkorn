<?php
/**
 * Template Name: Services Template
 *
 * Services overview (Figma "services_desktop") — top level of the Services
 * section. Lists the 3 service category pages (Schreinerei, Kreativatelier,
 * Fiduciary services) as its children (post_parent), via a shared "overview
 * cards" module, plus the shared usp-band, quote-slider and cta-form
 * modules (prefix 'services_').
 *
 * cta-form was added after the fact — this page went live before the
 * Figma frame had a closing contact section — so acf-services-overview-
 * fields.json's own "Contact" tab is a later addition too, not part of
 * the page's original 1.4.0 build.
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

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/pages/services/services-overview' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'services_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
