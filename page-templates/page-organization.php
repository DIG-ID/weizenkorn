<?php
/**
 * Template Name: Organization Template
 *
 * Organization page (Figma "Organization_desktop"): shared hero-section
 * module, the shared intro-cta module ("Warum Weizenkorn?", no button —
 * text only), the shared button-text module ("Organigramm" — a "PDF
 * herunterladen" button beside a paragraph), "Das Weizenkorn Team" (a
 * filterable grid built from a plain ACF repeater, not a post type — see
 * team.php's own docblock), button-text again ("Transparency" — a "Mehr
 * erfahren" button, same button-beside-text shape as Organigramm, just a
 * different link/text), and the shared cta-form module ("Kommen wir ins
 * Gespräch?").
 *
 * "Das könnte Sie auch interessieren" is the one section from the Figma
 * frame deliberately not built here — never built on any page in this
 * theme, not in this task's scope either.
 *
 * intro-cta and button-text are each reused twice on this one page with
 * different prefixes — nothing unusual, both modules already support that
 * (see Supported Jobs/Apprenticeships for intro-cta's own precedent).
 * Organigramm and Transparency both used to be intro-cta too, until it
 * turned out Transparency's button sits in its own left column beside the
 * text rather than stacked under it in the same column — Organigramm's
 * exact shape, not intro-cta's; button-text.php now covers both.
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
		get_template_part( 'template-parts/modules/intro-cta', null, array( 'prefix' => 'organization_why_' ) );
		get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organigramm_' ) );
		get_template_part( 'template-parts/pages/organization/team' );
		get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organization_transparency_' ) );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'organization_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
