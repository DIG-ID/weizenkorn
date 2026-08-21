<?php
/**
 * The template for a single product range, when no range template is assigned.
 *
 * The five ranges each get their own template (page-templates/product-range-*.php), picked
 * in the Template panel — see product-range-kerzen.php for why that assignment is explicit.
 *
 * This file is the floor under that: without it get_single_template() falls through to
 * single.php, the blog article template, and a range renders with a publish date and a raw
 * featured image. So it renders the three sections every range has and nothing else.
 *
 * The sections a range does NOT share belong to its own template. Do not add them here and
 * switch on the slug — that is the conditional soup the per-range templates exist to avoid.
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
