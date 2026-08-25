<?php
/**
 * Offer grid — a heading and a grid of offer cards, two to a row, each linking through to
 * the page that describes it.
 *
 * A module because the same grid carries the Kreativatelier's offerings and the six
 * Schreinerei sub-services' "Eine Auswahl aus unserem Angebot".
 *
 * The card is the same template-parts/components/card-overview.php the Services overview
 * uses, so the two sections stay one component apart. What differs is the grid: three to a
 * row there, two here, and an odd last card taking the full width instead of half of it —
 * see _modules/_offer-grid.sass. A row without an image has nothing to draw and is skipped;
 * one without a link is drawn as a plain block, arrow and all dropped.
 *
 * Unlike overview-cards, the items are a repeater and not the page's children: these
 * offerings have no pages of their own, and point at whatever the editor links them to.
 *
 * ACF fields (flat, prefixed) — the `offer_grid` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   offer_grid_section_title  (clone of "Section Title") the title and its red rule. Clone
 *                             the GROUP, never a repeater inside one.
 *   offer_grid_items          (repeater) one row per offer:
 *                             → image (image → return ID) required
 *                             → title (text)
 *                             → text  (textarea / wpautop)
 *                             → link  (link) optional — no link, no arrow
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/offer-grid' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.8.1
 */

$og_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$og_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$og_heading = weizenkorn_get_section_heading( $og_prefix . 'offer_grid_', $og_ctx );

/*
 * Collected before rendering rather than drawn inside the loop, because the layout depends
 * on how many cards survive: the last one goes full width only when the count is odd, and
 * a row skipped for a missing image would otherwise throw that count off.
 */
$og_cards = array();

if ( have_rows( $og_prefix . 'offer_grid_items', $og_ctx ) ) {
	while ( have_rows( $og_prefix . 'offer_grid_items', $og_ctx ) ) {
		the_row();

		if ( ! get_sub_field( 'image' ) ) {
			continue;
		}

		$og_link = get_sub_field( 'link' );

		$og_cards[] = array(
			'image'        => get_sub_field( 'image' ),
			'title'        => get_sub_field( 'title' ),
			'text'         => get_sub_field( 'text' ),
			'url'          => ! empty( $og_link['url'] ) ? $og_link['url'] : '',
			// The wide card is taller at tablet only; that override is in the SASS, keyed off
			// the same selector that widens it, so nothing here has to know which card it is.
			'media_height' => 'h-[192px] xl:h-[400px]',
		);
	}
}

if ( ! $og_heading && ! $og_cards ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="offer-grid mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $og_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $og_heading );
		}
		?>

		<?php if ( $og_cards ) : ?>
			<?php
			// 32px under the rule at tablet and 96px at desktop. The heading's own bottom margin
			// collapses with this one rather than adding to it, so these are the whole gap.
			?>
			<div class="theme-grid mt-8 xl:mt-24">
				<?php
				// A nested .theme-grid and not flex: .theme-grid already sets display:grid, so
				// flex utilities on the same element silently lose. The nested one re-divides
				// the inset into twelve, which is what puts two cards of six on a row.
				?>
				<ul class="offer-grid__list theme-grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 gap-y-8 md:gap-y-6 xl:gap-y-[54px] list-none m-0 p-0">
					<?php foreach ( $og_cards as $og_card ) : ?>
						<li class="offer-grid__item col-span-2 md:col-span-3 xl:col-span-6">
							<?php get_template_part( 'template-parts/components/card-overview', null, $og_card ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

	</div>
</section>
