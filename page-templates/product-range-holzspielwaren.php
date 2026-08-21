<?php
/**
 * Template Name: Product Range — Holzspielwaren
 * Template Post Type: products
 *
 * One template per range — see product-range-kerzen.php for why the assignment is
 * explicit and not single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS, in order: hero-section, product-overview, usp-band, craft-showcase,
 * "Jetzt aktuell" (product-overview again, through the `latest_` prefix), quote-slider,
 * stories-references, order-form ('split' variant), cta-form.
 *
 * ACF. On a single there is a post context, so every module reads the current post with
 * no prefix. Clone the GROUP per section, never a repeater inside one: a cloned repeater
 * stores a composite field reference that have_rows() cannot resolve, and the admin still
 * shows the values while the page renders nothing.
 *
 * The two shortcode fields (order_form and cta) carry their Default Value, so a new range
 * comes pre-filled.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.5.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/modules/product-overview' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/craft-showcase' );

		// Two instances on one page cannot read the same field names, so this one goes through
		// the prefix the module already takes for the archive: `latest_product_overview_*`.
		get_template_part(
			'template-parts/modules/product-overview',
			null,
			array( 'prefix' => 'latest_' )
		);

		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/stories-references' );
		get_template_part(
			'template-parts/modules/order-form',
			null,
			array( 'variant' => 'split' )
		);
		get_template_part( 'template-parts/modules/cta-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
