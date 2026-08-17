<?php
/**
 * The template for a single product range, when no range template is assigned.
 *
 * The five ranges each get their own template (page-templates/product-range-*.php),
 * picked in the Template panel, because their compositions differ — see the docblock
 * in product-range-kerzen.php for why the assignment is explicit and not
 * single-products-{slug}.php.
 *
 * This file is the floor under that. Without it get_single_template() falls all the
 * way through to single.php, the blog article template, and a product range renders
 * with a publish date and a raw featured image. So this renders the three sections
 * every range has, and nothing else: a range with no template assigned still looks
 * like a product page, just an incomplete one.
 *
 * The sections a range does NOT share — the tabbed overview, the offer grid, the
 * feature blocks, the capabilities grid — belong to its own template. Do not add
 * them here and switch on the slug: that is the conditional soup the per-range
 * templates exist to avoid.
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
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/cta-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
