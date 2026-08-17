<?php
/**
 * Template Name: Product Range — Holzspielwaren
 * Template Post Type: products
 *
 * The Holzspielwaren product range page. One template per range — see
 * product-range-kerzen.php for why the assignment is explicit and not
 * single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS — the order the page has them in. Six are shared modules that already
 * exist; two are new to this page and are noted where they belong. Delete a line
 * from here as its section lands:
 *
 *   1. hero-section        done — shared module
 *   2. product-overview    done — shared module (the same card grid as Kerzen's)
 *   3. usp-band            done — shared module
 *   4. craft-showcase      done — module, this page's "Liebe zum Detail"
 *   5. "Jetzt aktuell"     done — modules/product-overview again, read through the
 *                          `latest_` prefix so the two instances on this page do not
 *                          share fields
 *   6. quote-slider        done — shared module
 *   7. stories-references  done — shared module
 *   8. order-form          done — shared module, 'split' variant
 *   9. cta-form            done — shared module
 *
 * ACF. On a single there is a post context, so every module reads the current post
 * with no prefix. The field group for this page needs a clone per section it uses —
 * see the Kerzen group for the pattern. The two shortcode fields (order_form and
 * cta) carry their Default Value, so a new range comes pre-filled.
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

		/*
		 * "Jetzt aktuell" is the product-overview module a second time — same heading,
		 * same cards, same hover. Two instances on one page cannot read the same field
		 * names, so this one goes through the prefix the module already takes for the
		 * archive: its fields live under `latest_product_overview_*`.
		 */
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
