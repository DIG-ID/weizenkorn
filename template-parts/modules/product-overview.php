<?php
/**
 * Product overview — the product-type grid on a product range page.
 *
 * A heading and a grid of cards, each an image with the product-type name on a cream bar
 * across its bottom. Hovering grows that bar upward to reveal a line of copy and, where
 * the row has a link, a "zum Produkt" affordance.
 *
 * The card widths are decided in CSS from how many cards there are, not counted here:
 * every card is three columns, and only a trailing row of exactly three widens to four so
 * it still fills the twelve. Add an eighth card and the second row becomes four of three
 * like the first, with no change to this file. See _modules/_product-overview.sass.
 *
 * ACF fields (flat, prefixed) — the `product_overview` group on the `products` post type.
 * The group name produces the prefix, so renaming it orphans whatever is stored:
 *   product_overview_section_title  (clone of "Section Title", trimmed to title_heading +
 *                                   title + subtitle) the heading, passed straight to the
 *                                   component. `subtitle` is the overline.
 *   product_overview_items          (repeater) one per product type:
 *     → image  (image → ID)      the card image
 *     → title  (text)            "Unikerzen", "Kerzenzubehör"
 *     → text   (textarea)        the copy the hover reveals
 *     → link   (link, optional)  makes the whole card clickable and shows "zum Produkt".
 *                                A Link holds any URL, so the product PDFs go here too.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/product-overview' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

$po_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$po_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$po_heading = weizenkorn_get_section_heading( $po_prefix . 'product_overview_', $po_ctx );

if ( ! $po_heading && ! have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) {
	return;
}
?>
<section class="product-overview mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $po_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $po_heading );
		}
		?>

		<?php if ( have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) : ?>
			<div class="product-overview__grid theme-grid gap-y-6 md:gap-y-4 mt-8 xl:mt-16">
				<?php
				while ( have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) :
					the_row();

					$po_link   = get_sub_field( 'link' );
					$po_url    = ( is_array( $po_link ) && ! empty( $po_link['url'] ) ) ? $po_link['url'] : '';
					$po_target = ( is_array( $po_link ) && ! empty( $po_link['target'] ) ) ? $po_link['target'] : '';

					// A card with a link IS the link, so the "zum Produkt" inside it is a span —
					// an <a> inside an <a> is invalid and browsers unnest it.
					$po_tag = $po_url ? 'a' : 'article';

					?>
					<<?php echo esc_html( $po_tag ); ?> class="product-overview__card"<?php echo $po_url ? ' href="' . esc_url( $po_url ) . '"' : ''; ?><?php echo ( '_blank' === $po_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>

						<?php if ( get_sub_field( 'image' ) ) : ?>
							<figure class="product-overview__media">
								<?php
								/*
								 * cover below xl, contain from xl. The card is nearly square at tablet
								 * and much wider than tall at desktop, so one fit cannot serve both:
								 * contain left empty bands on the small breakpoints, cover cropped the
								 * products the frames show whole.
								 *
								 * The cost is the same photo cropped on a phone and whole on a
								 * computer. It goes away once the images arrive at the card's own
								 * proportions with the product centred.
								 */
								echo wp_get_attachment_image(
									get_sub_field( 'image' ),
									'large',
									false,
									array(
										'class'   => 'w-full h-full object-cover xl:object-contain',
										'loading' => 'lazy',
									)
								);
								?>
							</figure>
						<?php endif; ?>

						<div class="product-overview__panel">
							<?php
							// Title and copy are one block, the copy running straight on from the
							// title. Only the link is pushed to the panel's bottom edge, which is
							// why it is the second flex child and not part of this one.
							?>
							<div class="product-overview__head">
								<?php if ( get_sub_field( 'title' ) ) : ?>
									<h3 class="product-overview__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
								<?php endif; ?>

								<?php if ( get_sub_field( 'text' ) ) : ?>
									<div class="product-overview__reveal">
										<div class="product-overview__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( $po_url ) : ?>
								<div class="product-overview__reveal">
									<span class="product-overview__link">
										<span><?php echo esc_html_x( 'zum Produkt', 'product overview card link', 'weizenkorn' ); ?></span>
										<span class="product-overview__link-icon" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-download' ); ?></span>
									</span>
								</div>
							<?php endif; ?>
						</div>
					</<?php echo esc_html( $po_tag ); ?>>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
