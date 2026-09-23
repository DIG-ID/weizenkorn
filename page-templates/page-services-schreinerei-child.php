<?php
/**
 * Template Name: Services - Schreinerei Child Template
 *
 * Schreinerei service detail page — shared by the 7 child pages under
 * Schreinerei.
 *
 * SECTIONS, in order:
 *
 *   1. hero-section-detail  shared module
 *   2. offer-showcase       module, new to this page — "Eine Auswahl von unserem
 *                           Angebot", three cards to a row. Not offer-grid, which
 *                           is the Schreinerei parent's own two-up arrangement with
 *                           a different card; see offer-showcase's own docblock.
 *   3. text-columns         module, new to this page — the untitled block of prose
 *                           two columns wide, under a rule. Not legal-content,
 *                           whose heading sits beside its text rather than above
 *                           it; see text-columns' own docblock.
 *   4. service-info-downloads  shared module, the row of outlined buttons. Passed
 *                           'arrow-right': this row mixes pages in with the
 *                           documents and the frame points all of them sideways,
 *                           where the Schreinerei overview's row is documents only.
 *   5. cta-form             shared module, "Möchten Sie mehr wissen?" — the heading
 *                           and the red band with the Contact Form 7 form in it.
 *   6. preview-cards        shared module, "Entdecken Sie mehr", unprefixed like
 *                           every other use of it — see the module's own docblock
 *                           for the two ACF Clone fields it expects.
 *
 * The intro section is still to be added.
 *
 * ACF. On a page there is a post context, so every module reads the current post
 * with no prefix. Clone the GROUP per section, never a repeater inside one: a
 * cloned repeater stores a composite field reference that have_rows() cannot
 * resolve, and the admin still shows the values while the page renders nothing.
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

		get_template_part( 'template-parts/modules/hero-section-detail' );
		get_template_part( 'template-parts/modules/offer-showcase' );
		get_template_part( 'template-parts/modules/text-columns' );
		get_template_part(
			'template-parts/modules/service-info-downloads',
			null,
			array( 'icon' => 'arrow-right' )
		);
		get_template_part( 'template-parts/modules/cta-form' );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
