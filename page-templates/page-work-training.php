<?php
/**
 * Template Name: Work & Training Template
 *
 * Work & Training (Arbeiten und Ausbildung) overview page (Figma
 * "Work & Training_desktop"): shared hero-section module, then this page's
 * own "Ihre Perspektiven bei uns" (3-card offer grid) and "Zuweisende
 * Stellen" sections, a venue-diversity slider, the shared quote-slider, and
 * a closing "Dürfen wir weiterhelfen?" contact + form section. The Figma
 * frame's last section ("Entdecken Sie mehr") is intentionally not built —
 * the page ends at the form, per the brief.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.7.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/pages/work-training/perspectives' );
		get_template_part( 'template-parts/pages/work-training/referrals' );
		get_template_part( 'template-parts/pages/work-training/diversity-slider' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/pages/work-training/contact-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
