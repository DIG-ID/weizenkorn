<?php
/**
 * Template Name: Product Range — Holzmanufaktur
 * Template Post Type: products
 *
 * The Holzmanufaktur product range page, the fifth and last of the ranges. One template
 * per range — see product-range-kerzen.php for why the assignment is explicit and not
 * single-products-{slug}.php, and for how to attach it to a post.
 *
 * SECTIONS — the order the page has them in. Every one is a shared module that already
 * exists, so this template is only that order:
 *
 *   1. hero-section      shared module
 *   2. product-overview  shared module, the same card grid as the other ranges
 *   3. usp-band          shared module
 *   4. quote-slider      shared module
 *   5. our-equipment     module — "Vom Feinsten – unsere Ausrüstung", the machine park
 *                        as a slider that bleeds past the container's right edge. Shared
 *                        with the Schreinerei per the analysis, which calls it
 *                        capabilities-grid.
 *   6. trust             module — this page's "Ihr Projekt in guten Händen". The whole
 *                        section is components/section-heading used at full stretch:
 *                        title, wide image, overline and lead on the left, bullet list
 *                        opposite. Named for what it does, not `intro-panel`, which the
 *                        analysis reserves for the plain title-and-paragraph intro.
 *   7. cta-form          shared module
 *
 * This is the only range with no order-form and no stories-references — the Figma
 * analysis (§ page map) lists neither for it, and confirmed against the design it has
 * neither.
 *
 * ACF. On a single there is a post context, so every module reads the current post with
 * no prefix. The field group for this page needs a clone per section it uses — and clone
 * the GROUP, never a repeater inside it: a cloned repeater stores a composite field
 * reference that have_rows() cannot resolve, and the admin still shows the values while
 * the page renders nothing. Cloning the group keeps the originals' keys and works.
 *
 * The cta shortcode field carries its Default Value, so a new range comes pre-filled.
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
		get_template_part( 'template-parts/modules/product-overview' );
		get_template_part( 'template-parts/modules/usp-band' );
		get_template_part( 'template-parts/modules/our-equipment' );
		get_template_part( 'template-parts/modules/quote-slider' );
		get_template_part( 'template-parts/modules/trust' );
		get_template_part( 'template-parts/modules/cta-form' );

		do_action( 'after_main_content' );

	endwhile;
endif;

get_footer();
