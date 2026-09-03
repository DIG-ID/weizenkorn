<?php
/**
 * Template Name: About Us Template
 *
 * SECTIONS, in order:
 *
 *   1. hero-section   shared module
 *   2. intro-cta      with the `drive_` prefix — "Was uns antreibt". Title, rule and a
 *                     paragraph in the right-hand columns; the module's button field is
 *                     left empty, which is a shape it already serves on For Social
 *                     Offices & Partners.
 *   3. values-grid    module, new to this page — "Unsere Werte"
 *   4. intro-cta      with the `origin_` prefix — "Unser Ursprung". Same module as (2),
 *                     a second prefix rather than a second section.
 *   5. craft-showcase shared module in its 'pair' variant — "So arbeiten wir". No left
 *                     image, so the paragraph and the picture sit side by side.
 *   6. quote-slider   shared module
 *   7. video-panel    module, new to this page — "Weizenkorn entdecken". The video fills
 *                     the left six columns and the text sits in 8-12, the two ending on
 *                     the same line.
 *   8. button-text    with the `organisation_` prefix — "Organisation". Daniel's module
 *                     off the Organization page, which already pairs a button on the left
 *                     with a paragraph on the right; here it also carries the wide picture
 *                     its optional image field draws.
 *   9. offer-links    with the `donation_` prefix — "Spenden". One item, so the module
 *                     draws a single overline above a single button on the left with the
 *                     paragraph opposite — the same one-link shape the Open Positions
 *                     archive uses, in its 'stepped' spacing — the two sections that came
 *                     before it hold one value at every width and keep it.
 *
 * The About Us frames put 32 above the row and between the overline and the button where
 * the module carries the theme's 48 and 24. Left as the module has them: the two pages it
 * already serves were built to those, and nothing here is worth a third arrangement.
 *
 * ACF. On a page there is a post context, so every module reads the current post with no
 * prefix of its own — the two intro-ctas excepted, which take theirs above. Clone the
 * GROUP per section, never a repeater inside one: a cloned repeater stores a composite
 * field reference that have_rows() cannot resolve, and the admin still shows the values
 * while the page renders nothing.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.11.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );

		get_template_part(
			'template-parts/modules/intro-cta',
			null,
			array( 'prefix' => 'drive_' )
		);

		get_template_part( 'template-parts/modules/values-grid' );

		get_template_part(
			'template-parts/modules/intro-cta',
			null,
			array( 'prefix' => 'origin_' )
		);

		get_template_part(
			'template-parts/modules/craft-showcase',
			null,
			array( 'variant' => 'pair' )
		);
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/video-panel' );

		get_template_part(
			'template-parts/modules/button-text',
			null,
			array( 'prefix' => 'organisation_' )
		);

		get_template_part(
			'template-parts/modules/offer-links',
			null,
			array(
				'prefix'  => 'donation_',
				'variant' => 'stepped',
			)
		);

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
