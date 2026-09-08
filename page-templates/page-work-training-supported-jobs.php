<?php
/**
 * Template Name: Supported Jobs Template
 *
 * Supported Jobs overview page (Figma "Supported jobs_desktop"): shared
 * hero-section module, the shared intro-cta module ("Arbeiten mit
 * IV-Rente"), a "Arbeitsvielfalt bei Weizenkorn" card carousel, the shared
 * numbered-steps module ("Der Weg zu uns"), the shared quote-slider, a
 * closing "Bereit für Weizenkorn?" contact + form section (the shared
 * cta-form module, prefixed 'supported_jobs_' — also used by Work &
 * Training), the shared faq module (plain list, not an accordion —
 * confirmed against Figma, which shows every answer open), and the shared
 * preview-cards module ("Entdecken Sie mehr"), unprefixed like every other
 * use of it — see the module's own docblock for the two ACF Clone fields
 * it expects. The Figma frame's "Das könnte Sie auch interessieren"
 * section is intentionally not built.
 *
 * intro-cta and numbered-steps are shared with Supported Apprenticeships,
 * which uses the exact same two shapes for its own intro and 4-step
 * sections.
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
		get_template_part( 'template-parts/modules/intro-cta', null, array( 'prefix' => 'supported_jobs_' ) );
		get_template_part( 'template-parts/pages/work-training-supported-jobs/diversity-cards-slider' );
		get_template_part( 'template-parts/modules/numbered-steps', null, array( 'prefix' => 'supported_jobs_process_' ) );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'supported_jobs_' ) );
		get_template_part( 'template-parts/modules/faq', null, array( 'prefix' => 'supported_jobs_' ) );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
