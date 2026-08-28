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
 * contact-person module ("Bereit für Weizenkorn?"), and the shared faq
 * module. The Figma frame's last section ("Das könnte Sie auch
 * interessieren") is intentionally not built — the page ends at the FAQ,
 * per the brief.
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
		get_template_part( 'template-parts/pages/supported-apprenticeships/programs' );
		get_template_part( 'template-parts/modules/numbered-steps', null, array( 'prefix' => 'apprenticeships_process_' ) );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/contact-person', null, array( 'prefix' => 'apprenticeships_' ) );
		get_template_part( 'template-parts/modules/faq', null, array( 'prefix' => 'apprenticeships_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
