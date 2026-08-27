<?php
/**
 * Template Name: Services - Schreinerei Template
 *
 * The Schreinerei overview — a child of Services, and the parent of the seven service
 * pages, which use page-services-schreinerei-child.php (six of them) and
 * page-services-schreinerei-child-alt.php (the seventh).
 *
 * SECTIONS, in order:
 *
 *   1. hero-section    shared module
 *   2. offer-grid      shared module — the services, one card each. This is what lists
 *                      them, rather than the overview-cards module reading child pages:
 *                      the cards are authored here, and a card links wherever it likes.
 *   3. usp-band        shared module
 *   4. quote-slider    shared module
 *   5. teaser          with the `intro_` prefix — the heading, paragraph and photograph
 *                      block, the same section as the Fiduciary page's "Vertrauen, das
 *                      weiterbringt". Its overline and CTA are optional.
 *   6. location        shared module, the map
 *   7. service-info-downloads  module, new to this page — the documents, one outlined
 *                      button each. The button component picks the download arrow off the
 *                      URL, so a PDF and a page can share the row.
 *   8. cta-form        shared module
 *   9. faq             shared module
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
		get_template_part( 'template-parts/modules/offer-grid' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part(
			'template-parts/modules/teaser',
			null,
			array(
				'prefix'  => 'intro_',
				'variant' => 'stacked',
			)
		);
		get_template_part( 'template-parts/modules/location' );
		get_template_part( 'template-parts/modules/service-info-downloads' );
		get_template_part( 'template-parts/modules/cta-form' );
		get_template_part( 'template-parts/modules/faq' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
