<?php
/**
 * Template Name: Supported Jobs Template
 *
 * Supported Jobs overview page (Figma "Supported jobs_desktop"): shared
 * hero-section module, then this page's own "Arbeiten mit IV-Rente" intro,
 * a "Arbeitsvielfalt bei Weizenkorn" card carousel, the "Der Weg zu uns"
 * 4-step process, the shared quote-slider, a closing "Bereit für
 * Weizenkorn?" contact + form section (the shared cta-form module,
 * prefixed 'supported_jobs_' — also used by Work & Training), and the
 * shared faq module (plain list, not an accordion — confirmed against
 * Figma, which shows every answer open). The Figma frame's last section
 * ("Das könnte Sie auch interessieren") is intentionally not built — the
 * page ends at the FAQ, per the brief.
 *
 * "Der Weg zu uns" stays a page-specific section rather than the shared
 * template-parts/modules/process-steps module: that module requires a
 * photo per item (Kreativatelier's hover-caption tiles), while this
 * section's Figma frame (node 3815:5698) has no images at all — four
 * plain title+text boxes.
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
		get_template_part( 'template-parts/pages/supported-jobs/intro' );
		get_template_part( 'template-parts/pages/supported-jobs/diversity-cards-slider' );
		get_template_part( 'template-parts/pages/supported-jobs/process-steps' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'supported_jobs_' ) );
		get_template_part( 'template-parts/modules/faq', null, array( 'prefix' => 'supported_jobs_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
