<?php
/**
 * Template Name: Gastronomie und Hotellerie Template
 *
 * Gastronomie und Hotellerie overview page: shared hero-section module,
 * then the page-specific venues section (same structure as the Home page's
 * gastronomy section, not a module), then the shared USP band, quote
 * slider and preview-cards modules.
 *
 * preview-cards ("Entdecken Sie mehr") is this page's second use of that shared module
 * after Services — see template-parts/modules/preview-cards.php's own docblock for the two
 * ACF Clone fields it expects: a "Preview Cards" clone on this page's own field group
 * picking which pages to feature, plus the "Preview Card Content" clone that every featured
 * page needs on its own field group so it has something to show. Neither clone takes a
 * prefix — a page only ever needs one of each.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.5.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/pages/gastronomie-hotellerie/gastronomy' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
