<?php
/**
 * Preview cards — a title + a row of cards (image, title, optional description + "mehr"
 * link) pointing visitors to other pages of the site. Figma "Design System" page (node
 * 3429:4759) for the card itself; first used on Services' own "Entdecken Sie mehr" (Figma
 * node 180:5356 desktop, 2645:65 tablet, 2645:309 mobile) — a shared module and not a
 * page-specific section, since nearly every page ends with a shape like this.
 *
 * Same nested-grid reasoning as template-parts/modules/overview-cards.php: the cards' own
 * row is a SECOND theme-grid rather than this one's direct children, placing it in the
 * standard xl:col-start-2/col-span-10 inset — confirmed against Figma, whose three
 * 488px cards (plus two 25px gaps) come to exactly that inset's own width at desktop.
 * Three across at desktop (xl:col-span-4 of the inner grid's 12 columns), one per row
 * below it — confirmed against all three Figma frames, which never show two across at
 * tablet the way most card grids in this theme do. At tablet each card is centred at
 * 4 of the inner grid's 6 columns (md:col-start-2 md:col-span-4), not full width — same
 * centring pattern as template-parts/modules/values-grid.php's own tablet item.
 *
 * Two ACF clone groups are involved, on purpose kept separate rather than one repeater:
 * - acf-preview-cards-fields.json ("Preview Cards") — the SELECTOR. Cloned (Seamless) only
 *   onto pages that call this module. Its own "Pages" field is a relationship picking up to
 *   3 OTHER pages to feature; this module never asks an editor to type out card content by
 *   hand.
 * - acf-preview-card-content-fields.json ("Preview Card Content") — the CONTENT. Cloned
 *   (Seamless, no prefix — always read straight off whatever page id is at hand) onto EVERY
 *   page in the site, since any page can turn up in another page's relationship field above.
 *   It is what a page itself says it should look like when featured as a card elsewhere,
 *   deliberately independent of that page's own hero/title/body content.
 *
 * ACF fields — prefixed only on the selector side, when the caller needs one (see below);
 * always unprefixed on the page being featured, since a page only ever needs one "Preview
 * Card Content" clone:
 *   {prefix}preview_cards_title (text)
 *   {prefix}preview_cards_pages (relationship → page IDs)
 *   → per selected page id: preview_card_title (text), preview_card_image (image, ID,
 *     falls back to the page's featured image), preview_card_text (textarea, plain — no
 *     wpautop; see card-preview.php's own docblock for why it's read with esc_html(), not
 *     wp_kses_post()). The card always links to that page's own permalink.
 *
 * Two usages:
 * - On a page/single (post context, one instance per post): no $args at all — the
 *   "Preview Cards" clone on that post's own field group is unprefixed, same as every
 *   other page in the theme.
 *     get_template_part( 'template-parts/modules/preview-cards' );
 * - On an archive with no post context (archive-offene-stellen.php, archive-products.php):
 *   same 'option' + prefix pattern as this theme's other shared modules on those archives,
 *   since Theme Options is one shared bucket and a bare 'preview_cards_title' clone from a
 *   second archive would collide with the first.
 *     get_template_part( 'template-parts/modules/preview-cards', null, array(
 *         'post_id' => 'option',
 *         'prefix'  => 'products_archive_',
 *     ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id (or 'option') to read the selector
 *                                fields from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to the selector fields only — never to
 *                                the featured pages' own preview_card_* fields.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.13.0
 */

$pc_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$pc_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$pc_title = get_field( $pc_prefix . 'preview_cards_title', $pc_ctx );
$pc_pages = get_field( $pc_prefix . 'preview_cards_pages', $pc_ctx );

if ( ! $pc_title || empty( $pc_pages ) ) {
	return;
}
?>
<section class="preview-cards mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $pc_title ) ); ?>

		<div class="theme-grid mt-8 md:mt-14 xl:mt-24">
			<?php
			/*
			 * A nested .theme-grid and not flex: .theme-grid already sets display:grid, so
			 * flex utilities on the same element silently lose — same reasoning as
			 * overview-cards.php's own version of this row.
			 */
			?>
			<ul class="preview-cards__list theme-grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 xl:gap-x-[25px] gap-y-8 list-none m-0 p-0">
				<?php
				foreach ( $pc_pages as $pc_page_id ) :
					$pc_card_title = get_field( 'preview_card_title', $pc_page_id );
					$pc_card_image = get_field( 'preview_card_image', $pc_page_id );
					if ( ! $pc_card_image ) {
						$pc_card_image = get_post_thumbnail_id( $pc_page_id );
					}

					if ( ! $pc_card_title || ! $pc_card_image ) {
						continue;
					}
					?>
					<li class="col-span-2 md:col-start-2 md:col-span-4 xl:col-start-auto xl:col-span-4">
						<?php
						get_template_part(
							'template-parts/components/card-preview',
							null,
							array(
								'image' => $pc_card_image,
								'title' => $pc_card_title,
								'text'  => get_field( 'preview_card_text', $pc_page_id ),
								'url'   => get_permalink( $pc_page_id ),
							)
						);
						?>
					</li>
					<?php
				endforeach;
				?>
			</ul>
		</div>
	</div>
</section>
