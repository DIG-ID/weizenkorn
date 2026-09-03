<?php
/**
 * Template Name: Donate Template
 *
 * Donate page (Figma "Sponsoring / Donation_desktop" — called "Spenden" in
 * German, "Donate" here to match this template's own file name): shared
 * hero-section module, "Unsere Spenden-Projekte" (a slider of donation
 * projects, page-specific — template-parts/pages/about-us-donate/projects.php),
 * the shared usp-band module ("Wirkung im Fokus"), "Jetzt Spenden" (payment
 * methods, bank details, a tax-deductibility note — page-specific,
 * template-parts/pages/about-us-donate/payment-info.php), and the shared faq
 * module.
 *
 * "Das könnte Sie auch interessieren" is the one section from the Figma
 * frame deliberately not built here — never built on any page in this
 * theme, not in this task's scope either.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.13.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/pages/about-us-donate/projects' );
		get_template_part( 'template-parts/modules/usp-band', null, array( 'prefix' => 'donate_' ) );
		get_template_part( 'template-parts/pages/about-us-donate/payment-info' );
		get_template_part( 'template-parts/modules/faq', null, array( 'prefix' => 'donate_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
