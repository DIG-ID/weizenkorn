<?php
/**
 * Template Name: Product Range — Living Collection
 * Template Post Type: products
 *
 * The Living Collection product range page. One template per range — see
 * product-range-kerzen.php for why the assignment is explicit and not
 * single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS — every one of them is a module that already exists, so this template is
 * only the order they come in:
 *
 *   1. hero-section        shared module
 *   2. product-overview    shared module. The analysis calls this page's version an
 *                          "offer-grid (LINEA/RONDO/PORTO)", but it is the same card
 *                          grid with the same hover, so it is the same module.
 *   3. usp-band            shared module
 *   4. craft-showcase      shared module — this page's "Zeitloses Design, nachhaltig
 *                          gefertigt", the second use the module is named for
 *   5. quote-slider        shared module
 *   6. stories-references  shared module
 *   7. order-form          shared module, 'split' variant
 *   8. cta-form            shared module
 *
 * ACF. On a single there is a post context, so every module reads the current post with
 * no prefix. The field group for this page needs a clone per section — and clone the
 * GROUP, never the repeater inside it: a cloned repeater stores a composite field
 * reference that have_rows() cannot resolve, and the admin still shows the values while
 * the page renders nothing. Cloning the group keeps the originals' keys and works.
 *
 * The two shortcode fields (order_form and cta) carry their Default Value, so a new
 * range comes pre-filled.
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
