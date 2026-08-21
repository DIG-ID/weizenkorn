<?php
/**
 * The template for the products archive.
 *
 * The hero, USP band, quote slider and the two forms are shared modules, the product-range
 * pages repeating the same stack. No intro-panel: the hero already carries the page title
 * and the intro paragraph.
 *
 * An archive has no post context, so its page-level fields live in the theme options under
 * the `products_archive_` prefix, and every section is told to read from there via $args.
 *
 * Nothing here touches the main query. The page is a straight sequence of sections, so
 * there is no loop for a section to accidentally end up inside.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.4.0
 */

get_header();

do_action( 'before_main_content' );

get_template_part(
	'template-parts/modules/hero-section',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'products_archive_',
	)
);
get_template_part(
	'template-parts/archives/product/ranges',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'products_archive_',
	)
);
get_template_part(
	'template-parts/modules/usp-band',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'products_archive_',
	)
);
get_template_part(
	'template-parts/modules/quote-slider',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'products_archive_',
	)
);
get_template_part(
	'template-parts/modules/order-form',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'products_archive_',
	)
);
get_template_part(
	'template-parts/modules/cta-form',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'products_archive_',
	)
);

do_action( 'after_main_content' );

get_footer();
