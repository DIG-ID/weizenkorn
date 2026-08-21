<?php
/**
 * Template Name: Product Range — Holzmanufaktur
 * Template Post Type: products
 *
 * One template per range — see product-range-kerzen.php for why the assignment is
 * explicit and not single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS, in order: hero-section, product-overview, usp-band, quote-slider,
 * our-equipment, trust, cta-form.
 *
 * The only range with no order-form and no stories-references — the design has neither.
 *
 * ACF. On a single there is a post context, so every module reads the current post with
 * no prefix. Clone the GROUP per section, never a repeater inside one: a cloned repeater
 * stores a composite field reference that have_rows() cannot resolve, and the admin still
 * shows the values while the page renders nothing.
 *
 * The cta shortcode field carries its Default Value, so a new range comes pre-filled.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.6.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/modules/product-overview' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/our-equipment' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/trust' );
		get_template_part( 'template-parts/modules/cta-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
