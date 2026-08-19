<?php
/**
 * Product overview — the product-type grid on a product range page.
 *
 * Kerzen's "Zeitlose Ästhetik: Symbiose aus Form und Farbtiefe": a heading and a
 * grid of cards, each an image with the product-type name on a cream bar across its
 * bottom. Hovering a card grows that bar upward to reveal a short line of copy and,
 * when the row has a link, a "zum Produkt" affordance.
 *
 * In modules/ because the Figma analysis gives Holzspielwaren the same section
 * (§7). If that turns out to be a different component, this moves to
 * template-parts/posts/products/ — the section reads its fields through $args, so
 * nothing else has to change.
 *
 * Layout — Figma 2882:593, desktop confirmed 2026-08-12 (canvas 1920 / container
 * 1820). The card widths land exactly on the project grid:
 *
 *   row 1   four cards of 440px = 3 columns each (3 x 133.33 + 2 x 20 = 440),
 *           media 369px tall.
 *   row 2+  three cards of 593px = 4 columns each, media 400px tall. Figma draws
 *           them 598px with a 13px gap; on the grid's own 20px gutter they come to
 *           593px, which is the 5px-per-card difference between a hand-placed frame
 *           and the grid it was drawn over.
 *   rows    16px apart.
 *
 * The widths are decided in CSS from how many cards there are, not counted here:
 * every card is three columns, and only a trailing row of exactly three widens to
 * four so it still fills the twelve. Seven cards therefore give the frames' 4 + 3;
 * add an eighth and the second row becomes four of three like the first, with no
 * change to this file or the stylesheet. See _modules/_product-overview.sass.
 *
 *   tablet / mobile — no Figma frame yet. Two up and then one, media shorter.
 *   Interim values.
 *
 * The heading is the shared components/section-heading: it draws the red rule as a
 * full-container border under a title inset to column 2 and spanning to column 12,
 * with the overline below it — this frame's arrangement exactly, since the component
 * was corrected to 2–12 and to the frames' 32px either side of the rule. The title
 * takes <br>, so where it breaks is editorial rather than a function of the columns.
 *
 * ACF fields (flat, prefixed) — the `product_overview` group on the `products` post
 * type. The group name is what produces this prefix, so renaming it renames all of
 * these and orphans whatever is stored:
 *   product_overview_section_title  (clone of "Section Title", trimmed to
 *                                   title_heading + title + subtitle) — the heading,
 *                                   passed straight to the component. `subtitle` is
 *                                   the overline, "KERZEN–PRODUKTÜBERSICHT".
 *   product_overview_items          (repeater) one per product type:
 *     → image  (image → ID)      the card image
 *     → title  (text)            "Unikerzen", "Kerzenzubehör"
 *     → text   (textarea)        the copy the hover reveals
 *     → link   (link, optional)  makes the whole card clickable and shows
 *                                "zum Produkt". A Link holds any URL, so this is
 *                                also where the product PDFs go.
 *
 * Usage — on a range page the fields come from the current post:
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

// ACF read context: the current post normally, the options store on archives.
$po_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several contexts.
$po_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

/*
 * The heading comes from the shared helper, which reads a cloned "Section Title" group
 * in either of its Displays and rebuilds the links a Seamless clone hides behind a
 * composite field reference. See weizenkorn_get_section_heading() in
 * inc/theme-template-tags.php for why that read is not a plain get_field().
 */
$po_heading = weizenkorn_get_section_heading( $po_prefix . 'product_overview_', $po_ctx );

if ( ! $po_heading && ! have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) {
	return;
}
?>
<section class="product-overview mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		// Heading, red rule and overline, all from the shared component.
		if ( $po_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $po_heading );
		}
		?>

		<?php if ( have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) : ?>
			<?php
			/*
			 * These are the frames' gaps to the heading minus the overline's own bottom
			 * margin, which the section-heading component already carries: 24px at
			 * tablet of the frame's 56, and 32px at desktop of its 96.
			 */
			?>
			<div class="product-overview__grid theme-grid gap-y-6 md:gap-y-4 mt-8 xl:mt-16">
				<?php
				while ( have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) :
					the_row();

					$po_link   = get_sub_field( 'link' );
					$po_url    = ( is_array( $po_link ) && ! empty( $po_link['url'] ) ) ? $po_link['url'] : '';
					$po_target = ( is_array( $po_link ) && ! empty( $po_link['target'] ) ) ? $po_link['target'] : '';

					// A card with a link is the link, so the "zum Produkt" inside it is
					// a span — an <a> inside an <a> is invalid and browsers unnest it.
					$po_tag = $po_url ? 'a' : 'article';

					?>
					<<?php echo esc_html( $po_tag ); ?> class="product-overview__card"<?php echo $po_url ? ' href="' . esc_url( $po_url ) . '"' : ''; ?><?php echo ( '_blank' === $po_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>

						<?php if ( get_sub_field( 'image' ) ) : ?>
							<figure class="product-overview__media">
								<?php
								/*
								 * cover below xl, contain from xl. The card is nearly square at
								 * tablet and much wider than tall in the desktop rows, so a single
								 * fit cannot serve both: contain everywhere left wide bands of empty
								 * space on the small breakpoints, and cover at desktop cropped the
								 * products the frames show whole.
								 *
								 * The cost is that the same photo is cropped on a phone and whole on
								 * a computer. It goes away if the images are supplied already at the
								 * card's proportions with the product centred, which is how catalogs
								 * usually solve this.
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

						<?php
						/*
						 * The panel is the cream bar. Collapsed it is exactly the height
						 * of one title line and clips everything below it; on hover it
						 * grows and the copy and the link come into view.
						 */
						?>
						<div class="product-overview__panel">
							<?php
							/*
							 * Title and copy are one block: the copy runs straight on from
							 * the title, no gap. Only the link is pushed to the panel's
							 * bottom edge, which is why it is the second flex child and
							 * not part of this one.
							 */
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
