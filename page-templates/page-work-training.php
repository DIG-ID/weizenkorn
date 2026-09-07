<?php
/**
 * Template Name: Work & Training Template
 *
 * Work & Training (Arbeiten und Ausbildung) overview page (Figma
 * "Work & Training_desktop"): shared hero-section module, then this page's
 * own "Ihre Perspektiven bei uns" (3-card offer grid) and "Zuweisende
 * Stellen" sections, a venue-diversity slider, the shared quote-slider, and
 * a closing "Dürfen wir weiterhelfen?" contact + form section — the shared
 * cta-form module, prefixed 'work_training_' (its own cta_phone/cta_email
 * fields cover the contact row, no page-specific markup needed) — and the
 * shared preview-cards module ("Entdecken Sie mehr"), unprefixed like every
 * other use of it; see the module's own docblock for the two ACF Clone
 * fields it expects.
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
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'work_training_' ) );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
