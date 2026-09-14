<?php
/**
 * Template Name: Organization Template
 *
 * Organization page (Figma "Organization_desktop"): shared hero-section module, the
 * shared intro-cta module ("Warum Weizenkorn?", prefix 'organization_why_' — text only
 * since 1.16.2, the iframe it used to carry having moved to the new "Jahresbericht"
 * section below it), that new page-specific "Jahresbericht - Rückblick mit Ausblick"
 * section (the iframe embed plus a repeater of report-download buttons — see
 * jahresbericht.php's own docblock), the shared button-text module ("Organigramm" — a
 * "PDF herunterladen" button beside a paragraph), "Das Weizenkorn Team" (a filterable
 * grid built from a plain ACF repeater, not a post type — see team.php's own docblock),
 * and the shared cta-form module ("Kommen wir ins Gespräch?").
 *
 * "Transparency" (also button-text, prefix 'organization_transparency_' —
 * a "Mehr erfahren" button, same shape as Organigramm) is commented out
 * below at the client's own request, temporarily — its ACF fields and the
 * shared module still support it unchanged, so re-enabling it later is
 * just uncommenting the one call.
 *
 * "Das könnte Sie auch interessieren" is the one section from the Figma
 * frame deliberately not built here — never built on any page in this
 * theme, not in this task's scope either. preview-cards, unprefixed like
 * every other use of it, closes the page instead — see the module's own
 * docblock for the two ACF Clone fields it expects.
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
		get_template_part( 'template-parts/modules/intro-cta', null, array( 'prefix' => 'organization_why_' ) );
		get_template_part( 'template-parts/pages/about-us-organization/jahresbericht' );
		get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organigramm_' ) );
		get_template_part( 'template-parts/pages/about-us-organization/team' );
		// Transparency is temporarily hidden — client's own request, no date to restore it yet.
		/* get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organization_transparency_' ) ); */
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'organization_' ) );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
