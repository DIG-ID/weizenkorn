<?php
/**
 * Template Name: Product Range — Kerzen
 * Template Post Type: products
 *
 * The Kerzen product range page. One template per range, because the five ranges do
 * not share a composition — see figma-architecture-analysis.txt §7: Kerzen has a
 * tabbed product overview, Living Collection an offer grid, Xyloba a feature block,
 * Holzmanufaktur a capabilities grid. A single single-products.php would have to turn
 * those on and off with conditionals, which is flexible content done badly.
 *
 * WHY THIS IS A POST TEMPLATE AND NOT single-products-kerzen.php
 *
 * The template hierarchy does support single-{post_type}-{post_name}.php, but it
 * matches on the slug, and this site is multilingual: the French translation of
 * "Kerzen" gets its own slug, at which point the file stops matching and WordPress
 * falls back to single.php with no warning. Editing the slug in the admin does the
 * same. get_single_template() checks get_page_template_slug() BEFORE the slug-based
 * name, so an explicitly assigned template wins — and that assignment lives in the
 * _wp_page_template postmeta, which has nothing to do with the slug.
 *
 * Assign it per post: edit the range, Template panel, "Product Range — Kerzen".
 * For the translations, make sure WPML copies _wp_page_template (wpml-config.xml)
 * so a translator does not have to pick it again.
 *
 * SECTIONS — the order from the Figma analysis. The four wired below are shared
 * modules that already exist; the rest are still to be built and are listed so the
 * sequence is not lost. Delete a line from here as its section lands:
 *
 *   1. hero-section        done — shared module
 *   2. intro-panel         TO BUILD — module (also Holzspielwaren, Living Collection)
 *   3. product-overview    done — shared module (also Holzspielwaren). No tabs after
 *                          all: the frame is a card grid with hover reveals.
 *   4. usp-band            done — shared module
 *   5. quote-slider        done — shared module
 *   6. stories-references  done — shared module (4 of the 5 ranges use it)
 *   7. sales-points        done — modules/order-form in its 'split' variant. Same
 *                          content and fields as the archive's; only the columns
 *                          differ, so it is one module with two arrangements.
 *   8. cta-form            done — shared module
 *
 * ACF. On a single there is a post context, so every module reads the current post
 * with no prefix — the field groups have to be attached to the `products` post type.
 * The names the modules read are documented in each module; the groups needed here:
 *   hero_section   image, title (falls back to the post title), body, seperator_logo
 *   usp_band       title, items (repeater → icon, label)
 *   quote_slider   items (repeater → quote, author, role, image)
 *   cta            title or section_title, and shortcode (optional — the theme
 *                  options default is used when it is empty)
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
