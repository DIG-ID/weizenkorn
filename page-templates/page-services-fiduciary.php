<?php
/**
 * Template Name: Services - Fiduciary Services Template
 *
 * The Treuhand page — a child of Services, and the page the Services overview's first card
 * links to.
 *
 * SECTIONS, in order:
 *
 *   1. hero-section    shared module
 *   2. usp-band        shared module
 *   3. intro           modules/teaser with the `intro_` prefix — "Vertrauen, das
 *                      weiterbringt": the paragraph on columns 2-5 and the photograph on
 *                      7-11. The module's overline and CTA stay empty here, which is what
 *                      leaves the heading, the paragraph and the photograph alone.
 *   4. offer-grid      shared module
 *   5. quote-slider    shared module
 *   6. location        shared module, the map
 *   7. cta-form        shared module
 *   8. faq             shared module
 *
 * The teaser is prefixed rather than plain because its field names (title, body, image)
 * are generic enough to collide; `intro_` names the section's job on this page. Not
 * `trust_`, which is the `trust` module's own group prefix.
 *
 * ACF. On a page there is a post context, so every module reads the current post with no
 * prefix — the teaser excepted, which takes the one above. Clone the GROUP per section,
 * never a repeater inside one: a cloned repeater stores a composite field reference that
 * have_rows() cannot resolve, and the admin still shows the values while the page renders
 * nothing.
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
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part(
			'template-parts/modules/teaser',
			null,
			array(
				'prefix'  => 'intro_',
				'variant' => 'stacked',
			)
		);
		get_template_part( 'template-parts/modules/offer-grid' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/location' );
		get_template_part( 'template-parts/modules/cta-form' );
		get_template_part( 'template-parts/modules/faq' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
