<?php
/**
 * Template Name: For Social Offices & Partners Template
 *
 * For Social Offices & Partners (Zuweisende Stellen) overview page (Figma
 * "For social offices & partners_desktop") — English file/slug naming
 * chosen from the two the client offered, since it matches the Figma
 * frame's own name exactly. Shared hero-section module, the shared
 * intro-cta module ("Was uns wichtig ist", text-only — no button), this
 * page's own "Angebote im Überblick" offer links, "Downloads &
 * Unterlagen" document cards, "Wir sind für Sie da" team grid, and a
 * closing "Dürfen wir weiterhelfen?" contact + form section (the shared
 * cta-form module, prefixed 'social_offices_'). The Figma frame has no
 * quote-slider or FAQ section, unlike the other Supported pages. Its last
 * section ("Das könnte Sie auch interessieren") is intentionally not
 * built — the page ends at the form, per the brief.
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
		get_template_part( 'template-parts/modules/intro-cta', null, array( 'prefix' => 'social_offices_' ) );
		get_template_part( 'template-parts/pages/for-social-offices-and-partners/offers' );
		get_template_part( 'template-parts/pages/for-social-offices-and-partners/downloads' );
		get_template_part( 'template-parts/pages/for-social-offices-and-partners/team' );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'social_offices_' ) );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
