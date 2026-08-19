<?php
/**
 * Template Name: Product Range — Xyloba
 * Template Post Type: products
 *
 * The Xyloba product range page. One template per range — see
 * product-range-kerzen.php for why the assignment is explicit and not
 * single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS — the order the page has them in. All of them are shared modules:
 *
 *   1. hero-section        shared module
 *   2. photo-mosaic        module, new to this page — "Kreativ. Lehrreich.
 *                          Begeisternd.". A bento grid of photographs with no card,
 *                          no hover and no per-image link, which is why it is its own
 *                          module and not product-overview. The gastronomy pages use
 *                          it with a different arrangement.
 *   3. usp-band            shared module
 *   4. craft-showcase      shared module — "Das Prinzip: Bauen, Rollen, Hören". This
 *                          page shows the module without its first image: the copy
 *                          column and the single tall image on the right, nothing
 *                          above the copy. That falls out of the content rather than
 *                          needing a variant here — leave craft_showcase_image empty
 *                          and the section composes itself for this page while the
 *                          other two keep the full arrangement.
 *
 *                          The copy here is a paragraph followed by a bullet list, so
 *                          craft_showcase_text has to be a WYSIWYG rather than a
 *                          textarea: a textarea's wpautop produces <p> and cannot
 *                          produce <ul>. The module already runs the value through
 *                          wp_kses_post(), so the list needs no code change.
 *   5. quote-slider        shared module
 *   6. stories-references  shared module
 *   7. order-form          shared module, 'split' variant
 *   8. cta-form            shared module
 *
 * ACF. On a single there is a post context, so every module reads the current post
 * with no prefix. The field group for this page needs a clone per section it uses —
 * and clone the GROUP, never a repeater inside it: a cloned repeater stores a
 * composite field reference that have_rows() cannot resolve, and the admin still
 * shows the values while the page renders nothing. Cloning the group keeps the
 * originals' keys and works.
 *
 * The two shortcode fields (order_form and cta) carry their Default Value, so a new
 * range comes pre-filled.
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
