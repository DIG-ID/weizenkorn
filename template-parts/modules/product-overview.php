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
 *     → link   (link, optional)  shows "zum Produkt" and, at desktop, makes the whole
 *                               card clickable. Below xl the card is a switch for its
 *                               own copy and only that link leaves the page — see
 *                               assets/js/reveal-cards.js.
 *                                A Link holds any URL, so the product PDFs go here too.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/product-overview' );
 *   get_template_part( 'template-parts/modules/product-overview', null, array( 'title_style' => 'overline' ) );
 *
 * @param array $args {
 *     @type int|string $post_id     Optional. ACF post id / options store to read from.
 *                                   Default: the current post.
 *     @type string     $prefix      Optional. Prepended to every field name.
 *     @type string     $variant     Optional. 'downloads' lays the cards two to a row on the
 *                                   inset columns instead of four, and turns the bar red on
 *                                   hover — Our Bakery's menu PDFs.
 *     @type string     $title_style Optional. 'overline' typesets the title as the eyebrow
 *                                   instead of the display heading — Our Bakery's
 *                                   "Entdecken Sie mehr" reads that way, the same as the
 *                                   gastronomy photo mosaic. Passed straight to the
 *                                   section-heading component.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

$po_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$po_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Whitelisted, so the modifier class below cannot be whatever a caller hands over.
$po_variant = ( ! empty( $args['variant'] ) && 'downloads' === $args['variant'] ) ? 'downloads' : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$po_heading = weizenkorn_get_section_heading( $po_prefix . 'product_overview_', $po_ctx );

if ( ! $po_heading && ! have_rows( $po_prefix . 'product_overview_items', $po_ctx ) ) {
	return;
}
?>
<section class="product-overview<?php echo $po_variant ? ' product-overview--' . esc_attr( $po_variant ) : ''; ?> mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $po_heading ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				! empty( $args['title_style'] )
					? array_merge( $po_heading, array( 'title_style' => $args['title_style'] ) )
					: $po_heading
			);
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

					/*
					 * Whether this card draws a __reveal at all — the same two conditions the
					 * markup below uses, read once here because three things depend on the
					 * answer: the +/- only means something when there is something to open,
					 * the JS only intercepts a tap when there is, and a linked card with
					 * nothing to reveal must stay a plain link.
					 */
					$po_has_reveal = $po_url || get_sub_field( 'text' );

					/*
					 * Which arrow "zum Produkt" carries: a file lands on the reader's machine,
					 * a page does not, and the two arrows are what tell them apart. Read from
					 * the URL rather than from a field, so an editor swapping a product page
					 * for a PDF gets the right arrow without knowing there was a choice —
					 * the same list and the same test card-preview.php already uses.
					 *
					 * The downloads variant says so outright and does not wait to be asked:
					 * every tile in it is a PDF by definition, and the arrow has to read right
					 * while the links are still placeholders and the files are being prepared.
					 */
					$po_download_types = array( 'pdf', 'zip', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'rar', '7z' );
					$po_url_extension  = strtolower( pathinfo( (string) wp_parse_url( $po_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
					$po_is_download    = ( '' !== $po_variant ) || in_array( $po_url_extension, $po_download_types, true );
					$po_reveal_id      = wp_unique_id( 'product-overview-reveal-' );

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
								<?php
								// The title, and the +/- beside it below xl. Both variants draw the
								// same bar now; the link lives in the reveal under it either way.
								?>
								<div class="product-overview__head-row">
									<?php if ( get_sub_field( 'title' ) ) : ?>
										<h3 class="product-overview__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
									<?php endif; ?>

									<?php if ( $po_has_reveal ) : ?>
										<?php
										/*
										 * The +/- of the preview cards, saying the same thing: there is
										 * more under this bar, and tapping it puts it away again. Below
										 * xl only — desktop opens on hover and the frames draw no icon
										 * there. Both variants carry it: since the hover reveal became
										 * desktop-only, a tap is the only way into the copy below xl, and
										 * the downloads variant's own arrow speaks for the file, not for
										 * the text. It sits before that arrow, so the two read in the
										 * order they act.
										 *
										 * A <span> and not the preview card's <button>: this card IS the
										 * <a>, and interactive content cannot nest inside a link. The JS
										 * gives it its behaviour; aria-hidden keeps it from being
										 * announced as a control a keyboard cannot reach, and the card's
										 * own aria-expanded is what assistive tech reads instead.
										 */
										?>
										<?php if ( $po_url ) : ?>
											<span class="product-overview__toggle xl:hidden" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'toggle' ); ?></span>
										<?php else : ?>
											<?php
											/*
											 * No link, so the card is an <article> and a real <button> is
											 * allowed — the card-preview pattern, keyboard and all. This
											 * is the only way into the copy here: the card has nowhere to
											 * go, so nothing about it is tappable but this.
											 */
											?>
											<button
												type="button"
												class="product-overview__toggle js-product-overview-toggle xl:hidden"
												aria-expanded="false"
												aria-controls="<?php echo esc_attr( $po_reveal_id ); ?>"
											>
												<span class="sr-only"><?php esc_html_e( 'Toggle description', 'weizenkorn' ); ?></span>
												<?php weizenkorn_the_svg_icon( 'toggle' ); ?>
											</button>
										<?php endif; ?>
									<?php endif; ?>
								</div>

								<?php if ( get_sub_field( 'text' ) ) : ?>
									<?php
									/*
									 * The bare <div> is load-bearing — see the note on __reveal in
									 * _modules/_product-overview.sass. It is the element the collapse
									 * clips, so it must carry no padding of its own; the spacing lives
									 * on the content inside it.
									 */
									?>
									<div class="product-overview__reveal" id="<?php echo esc_attr( $po_reveal_id ); ?>">
										<div>
											<div class="product-overview__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( $po_url ) : ?>
								<div class="product-overview__reveal">
									<?php // The same bare <div> as above, and for the same reason. ?>
									<div>
										<span class="product-overview__link">
											<span><?php echo esc_html_x( 'zum Produkt', 'product overview card link', 'weizenkorn' ); ?></span>
											<?php
											// Sideways for a page, downward for a file — see
											// $po_is_download above. The modifier is only for the
											// box: the download arrow is taller than wide, where
											// the sideways one is the reverse.
											?>
											<span class="product-overview__link-icon<?php echo $po_is_download ? ' product-overview__link-icon--download' : ''; ?>" aria-hidden="true"><?php weizenkorn_the_svg_icon( $po_is_download ? 'arrow-download' : 'arrow-right' ); ?></span>
										</span>
									</div>
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
