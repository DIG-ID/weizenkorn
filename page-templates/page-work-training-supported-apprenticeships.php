<?php
/**
 * Template Name: Supported Apprenticeships Template
 *
 * Supported Apprenticeships (Ausbildungsstellen) overview page (Figma
 * "Supported Apprenticeships_desktop"): shared hero-section module, the
 * shared intro-cta module ("Auf der Suche nach der passenden
 * Ausbildungsstelle?"), this page's own "Unsere Ausbildung" programme
 * bento, the shared numbered-steps module ("Der Weg zu einem Platz bei
 * Weizenkorn in 4 Schritten"), the shared quote-slider, the shared
 * contact-person module ("Bereit für Weizenkorn?"), the shared faq
 * module, and the shared preview-cards module ("Entdecken Sie mehr"),
 * unprefixed like every other use of it — see the module's own docblock
 * for the two ACF Clone fields it expects. The Figma frame's "Das könnte
 * Sie auch interessieren" section is intentionally not built.
 *
 * "Einblicke unserer Auszubildenden" (a card carousel, the same shape as
 * Supported Jobs' diversity-cards-slider) sits between the intro and
 * "Unsere Ausbildung" in the Figma frame but is annotated there to stay
 * hidden for now — intentionally not called here either, pending that
 * decision.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.9.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/modules/intro-cta', null, array( 'prefix' => 'apprenticeships_' ) );
		get_template_part( 'template-parts/pages/work-training-supported-apprenticeships/programs' );
		get_template_part( 'template-parts/modules/numbered-steps', null, array( 'prefix' => 'apprenticeships_process_' ) );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/contact-person', null, array( 'prefix' => 'apprenticeships_' ) );
		get_template_part( 'template-parts/modules/faq', null, array( 'prefix' => 'apprenticeships_' ) );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
