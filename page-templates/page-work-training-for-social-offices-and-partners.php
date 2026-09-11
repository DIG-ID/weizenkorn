<?php
/**
 * Template Name: For Social Offices & Partners Template
 *
 * For Social Offices & Partners (Zuweisende Stellen) overview page (Figma
 * "For social offices & partners_desktop") — English file/slug naming
 * chosen from the two the client offered, since it matches the Figma
 * frame's own name exactly. Shared hero-section module, the shared
 * intro-cta module ("Was uns wichtig ist", text-only — no button), this
 * page's own fork of offer-links ("Angebote im Überblick" — a narrower
 * desktop text column than the shared module's default; see that fork's
 * own docblock for why), this page's own "Downloads & Unterlagen" document
 * cards and "Wir sind für Sie da" team grid, and a closing "Dürfen wir
 * weiterhelfen?" contact + form section (the shared cta-form module,
 * prefixed 'social_offices_'). The Figma frame has no quote-slider or FAQ
 * section, unlike the other Supported pages. Its last section ("Das könnte
 * Sie auch interessieren") is intentionally not built — the shared
 * preview-cards module ("Entdecken Sie mehr"), unprefixed like every other
 * use of it, closes the page instead; see the module's own docblock for
 * the two ACF Clone fields it expects.
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
		get_template_part( 'template-parts/pages/work-training-for-social-offices-and-partners/offer-links' );
		get_template_part( 'template-parts/pages/work-training-for-social-offices-and-partners/downloads' );
		get_template_part( 'template-parts/pages/work-training-for-social-offices-and-partners/team' );
		get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'social_offices_' ) );
		get_template_part( 'template-parts/modules/preview-cards' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
