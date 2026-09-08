<?php
/**
 * Template Name: Services Template
 *
 * Services overview (Figma "services_desktop") — top level of the Services
 * section. Lists the 3 service category pages (Schreinerei, Kreativatelier,
 * Fiduciary services) as its children (post_parent), via a shared "overview
 * cards" module, plus the shared usp-band, quote-slider, cta-form and
 * preview-cards modules (prefix 'services_').
 *
 * cta-form was added after the fact — this page went live before the
 * Figma frame had a closing contact section — so acf-services-overview-
 * fields.json's own "Contact" tab is a later addition too, not part of
 * the page's original 1.4.0 build. preview-cards ("Entdecken Sie mehr") is
 * this page's own pilot use of that shared module — see
 * template-parts/modules/preview-cards.php's own docblock for the two ACF
 * Clone fields it expects: a "Preview Cards" clone on this page's own field
 * group picking which pages to feature, plus the "Preview Card Content"
 * clone that every featured page needs on its own field group so it has
 * something to show. Neither clone takes a prefix — a page only ever needs
 * one of each.
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
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
