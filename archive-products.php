<?php
/**
 * The template for the products archive.
 *
 * Products landing page, composed of ordered sections (see
 * figma-architecture-analysis.txt, §7 "Products"). The hero, USP band, quote
 * slider and the two forms are shared modules because the product-range pages
 * (Kerzen, Holzspielwaren, Living Collection, Xyloba) repeat the same stack.
 *
 * No intro-panel: the hero section already carries the page title and the intro
 * paragraph, so the separate intro block listed in the older analysis is gone.
 *
 * An archive has no post context, so its page-level fields live in the theme
 * options under the `products_archive_` prefix, and the shared hero module is
 * told to read from there. The main query — the products themselves — is
 * consumed inside archives/product/ranges: every other section must stay OUTSIDE
 * the loop, or each one would render once per product.
 *
 * STATUS — still to be built (the calls below output nothing until the template
 * parts exist, so this file is safe to ship meanwhile):
 *   pending: archives/product/ranges, archives/product/order-form,
 *            modules/quote-slider, modules/cta-form
 *   pending: the `product` post type. Until it is registered with `has_archive`
 *            (or WooCommerce is activated) WordPress never loads this template.
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
get_template_part( 'template-parts/archives/product/ranges' );
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
	'template-parts/archives/product/order-form',
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
