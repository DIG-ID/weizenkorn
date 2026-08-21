<?php
/**
 * Template Name: Product Range — Xyloba
 * Template Post Type: products
 *
 * One template per range — see product-range-kerzen.php for why the assignment is
 * explicit and not single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS, in order: hero-section, photo-mosaic, usp-band, craft-showcase,
 * quote-slider, stories-references, order-form ('split' variant), cta-form.
 *
 * Two configuration notes for this page:
 *
 *   - craft-showcase runs without its first image here: leave craft_showcase_image empty
 *     and the section composes itself for this page, while the other two pages keep the
 *     full arrangement. No variant needed.
 *   - craft_showcase_text has to be a WYSIWYG rather than a textarea, the copy being a
 *     paragraph followed by a bullet list: a textarea's wpautop produces <p> and cannot
 *     produce <ul>. The module already runs the value through wp_kses_post().
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
 * @since 1.6.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		do_action( 'before_main_content' );

		get_template_part( 'template-parts/modules/hero-section' );
		get_template_part( 'template-parts/modules/photo-mosaic' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/craft-showcase' );
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
