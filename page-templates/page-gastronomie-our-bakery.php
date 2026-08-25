<?php
/**
 * Template Name: Gastronomie - Our Bakery Template
 *
 * The Our Bakery page of the gastronomy venues. Each venue gets its own template, so a
 * venue whose design diverges is changed on its own file and nowhere else.
 *
 * SECTIONS, in order. Two are still to build and are named here so the order is not lost;
 * delete a line as its section lands:
 *
 *   1. hero-section      shared module
 *   2. our-locations     module, new to this page
 *   3. location          shared module, the map
 *   4. usp-band          shared module
 *   5. product-overview  shared module — this page's "Entdecken Sie mehr", with the
 *                        title typeset as an overline, as the gastronomy photo mosaic is
 *   6. catering          shared module
 *   7. social            modules/teaser with the `social_` prefix — the same section as
 *                        the home page's "Lerne uns kennen"
 *   8. cta-form          shared module
 *
 * ACF. On a page there is a post context, so every module reads the current post with no
 * prefix. Clone the GROUP per section, never a repeater inside one: a cloned repeater
 * stores a composite field reference that have_rows() cannot resolve, and the admin still
 * shows the values while the page renders nothing.
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
		get_template_part( 'template-parts/modules/our-locations' );
		get_template_part( 'template-parts/modules/location' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part(
			'template-parts/modules/product-overview',
			null,
			array(
				'variant'     => 'downloads',
				'title_style' => 'overline',
			)
		);
		get_template_part( 'template-parts/modules/catering' );
		get_template_part( 'template-parts/modules/teaser', null, array( 'prefix' => 'social_' ) );
		get_template_part( 'template-parts/modules/cta-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
