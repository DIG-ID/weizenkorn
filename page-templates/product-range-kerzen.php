<?php
/**
 * Template Name: Product Range — Kerzen
 * Template Post Type: products
 *
 * One template per range: the five do not share a composition, and a single
 * single-products.php would have to turn sections on and off with conditionals.
 *
 * WHY THIS IS A POST TEMPLATE AND NOT single-products-kerzen.php
 *
 * The hierarchy does support single-{post_type}-{post_name}.php, but it matches on the
 * slug — and this site is multilingual, so the French translation gets its own slug, the
 * file stops matching, and WordPress falls back to single.php with no warning. Editing
 * the slug in the admin does the same. get_single_template() checks
 * get_page_template_slug() BEFORE the slug-based name, so an explicitly assigned template
 * wins, and that assignment lives in _wp_page_template postmeta.
 *
 * Assign it per post: edit the range, Template panel, "Product Range — Kerzen". Make sure
 * WPML copies _wp_page_template (wpml-config.xml) so a translator does not pick it again.
 *
 * SECTIONS — the order from the Figma analysis. Delete a line as its section lands:
 *
 *   1. hero-section        done
 *   2. intro-panel         TO BUILD — also Holzspielwaren and Living Collection
 *   3. product-overview    done
 *   4. usp-band            done
 *   5. quote-slider        done
 *   6. stories-references  done
 *   7. sales-points        done — modules/order-form in its 'split' variant
 *   8. cta-form            done
 *
 * ACF. On a single there is a post context, so every module reads the current post with
 * no prefix. Clone the GROUP per section, never a repeater inside one: a cloned repeater
 * stores a composite field reference that have_rows() cannot resolve, and the admin still
 * shows the values while the page renders nothing.
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
