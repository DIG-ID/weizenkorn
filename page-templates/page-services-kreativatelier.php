<?php
/**
 * Template Name: Services - Kreativatelier Template
 *
 * The Kreativatelier page — a child of Services, and the page the Services overview's
 * middle card links to.
 *
 * SECTIONS, in order:
 *
 *   1. hero-section    shared module
 *   2. offer-grid      module, new to this page — "Gestalten mit Kopf, Herz und Hand"
 *   3. usp-band        shared module
 *   4. process-steps   module, new to this page — "So entsteht Mehrwert"
 *   5. quote-slider    shared module
 *   6. location        shared module, the map
 *   7. cta-form        shared module
 *   8. faq             module, new to this page
 *
 * ACF. On a page there is a post context, so every module reads the current post with no
 * prefix. Clone the GROUP per section, never a repeater inside one: a cloned repeater
 * stores a composite field reference that have_rows() cannot resolve, and the admin still
 * shows the values while the page renders nothing.
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
		get_template_part( 'template-parts/modules/process-steps' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/location' );
		get_template_part( 'template-parts/modules/cta-form' );
		get_template_part( 'template-parts/modules/faq' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
