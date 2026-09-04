<?php
/**
 * Template Name: Organization Template
 *
 * Organization page (Figma "Organization_desktop"): shared hero-section
 * module, a page-specific fork of intro-cta ("Warum Weizenkorn?" — an
 * optional iframe embed in the left column beside the text; see
 * template-parts/pages/about-us-organization/intro-cta.php's own docblock
 * for why this one isn't the shared module), the shared button-text module
 * ("Organigramm" — a "PDF herunterladen" button beside a paragraph), "Das
 * Weizenkorn Team" (a filterable grid built from a plain ACF repeater, not
 * a post type — see team.php's own docblock), and the shared cta-form
 * module ("Kommen wir ins Gespräch?").
 *
 * "Transparency" (also button-text, prefix 'organization_transparency_' —
 * a "Mehr erfahren" button, same shape as Organigramm) is commented out
 * below at the client's own request, temporarily — its ACF fields and the
 * shared module still support it unchanged, so re-enabling it later is
 * just uncommenting the one call.
 *
 * "Das könnte Sie auch interessieren" is the one section from the Figma
 * frame deliberately not built here — never built on any page in this
 * theme, not in this task's scope either.
 *
 * Organigramm and Transparency both used to be the shared intro-cta module
 * too, until it turned out Transparency's button sits in its own left
 * column beside the text rather than stacked under it in the same column —
 * Organigramm's exact shape, not intro-cta's; button-text.php now covers
 * both.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.12.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/pages/about-us-organization/intro-cta' );
		get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organigramm_' ) );
		get_template_part( 'template-parts/pages/about-us-organization/team' );
		// Transparency is temporarily hidden — client's own request, no date to restore it yet.
		// get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organization_transparency_' ) );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'organization_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
