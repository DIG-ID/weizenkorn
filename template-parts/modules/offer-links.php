<?php
/**
 * Offer links — a title, then a left column of labelled links (each a small
 * label above its own button — stacked at mobile/tablet, side by side at
 * desktop) and a description on the right, on the same columns
 * section-heading's own right-description uses.
 *
 * Not a fit for that component: its buttons_prmary slot is one button with
 * no label of its own, not a repeater of labelled links. Shared by For
 * Social Offices & Partners' "Angebote im Überblick" (two links) and the
 * Open Positions archive's "Weizenkorn mitgestalten" (one link) — both the
 * same shape, confirmed against Figma.
 *
 * .theme-grid sets gap-x only, so the two columns touch where they stack — the paragraph
 * carries the 16 the frame puts between it and the button, dropped again from tablet up
 * where the two share a row.
 * *
 * TWO SPACING SCALES
 * The module held one value at every breakpoint for the gap under the rule and the gap
 * between a label and its button. About Us's "Spenden" frames give both as a step —
 * 16/32/32 and 16/24/32, the latter the same scale the video panel's overline already uses
 * — which a single value cannot match at more than one width. That is the 'stepped'
 * variant, and it is opt-in: the two sections that came first were built against frames
 * this file has never seen, so they keep what they were built with until someone checks
 * them.
 *
 * The gap under the rule is written in full rather than as a difference from the heading's
 * own bottom margin: the two are adjacent siblings, so they collapse to the larger of the
 * pair, and anything smaller than the heading's simply vanishes.
 * ACF fields (flat, prefixed):
 *   {prefix}offers_title (text)
 *   {prefix}offers_items (repeater) → title (text), link (link)
 *   {prefix}offers_text  (textarea / wpautop)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/offer-links', null, array( 'prefix' => 'social_offices_' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 *     @type string     $variant Optional. 'stepped' for the spacing scale above.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.10.0
 */

$ol_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$ol_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$ol_stepped = ( ! empty( $args['variant'] ) && 'stepped' === $args['variant'] );

// Written out rather than composed: Tailwind scans this file for whole class names.
$ol_row_margin = $ol_stepped ? 'mt-4 md:mt-8 xl:mt-8' : 'mt-8 xl:mt-12';
$ol_item_gap   = $ol_stepped ? 'gap-4 md:gap-6 xl:gap-8' : 'gap-6';

$ol_title = get_field( $ol_prefix . 'offers_title', $ol_ctx );

if ( ! $ol_title ) {
	return;
}

$ol_text = get_field( $ol_prefix . 'offers_text', $ol_ctx );
?>
<section class="offer-links mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $ol_title ) ); ?>

		<?php if ( have_rows( $ol_prefix . 'offers_items', $ol_ctx ) || $ol_text ) : ?>
			<div class="theme-grid <?php echo esc_attr( $ol_row_margin ); ?>">
				<?php if ( have_rows( $ol_prefix . 'offers_items', $ol_ctx ) ) : ?>
					<div class="offer-links__list col-span-2 md:col-start-1 md:col-span-3 xl:col-start-2 xl:col-span-5 flex flex-col items-start xl:flex-row gap-8 xl:gap-16">
						<?php
						while ( have_rows( $ol_prefix . 'offers_items', $ol_ctx ) ) :
							the_row();

							$ol_link = get_sub_field( 'link' );

							if ( ! get_sub_field( 'title' ) && ! $ol_link ) {
								continue;
							}
							?>
							<div class="offer-links__item flex flex-col items-start <?php echo esc_attr( $ol_item_gap ); ?>">
								<?php if ( get_sub_field( 'title' ) ) : ?>
									<p class="label-overline"><?php echo esc_html( get_sub_field( 'title' ) ); ?></p>
								<?php endif; ?>

								<?php if ( $ol_link ) : ?>
									<?php get_template_part( 'template-parts/components/button', null, array_merge( $ol_link, array( 'style' => 'primary' ) ) ); ?>
								<?php endif; ?>
							</div>
							<?php
						endwhile;
						?>
					</div>
				<?php endif; ?>

				<?php if ( $ol_text ) : ?>
					<div class="col-span-2 mt-4 md:mt-0 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5">
						<div class="body-text"><?php echo wp_kses_post( $ol_text ); ?></div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
