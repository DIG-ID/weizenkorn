<?php
/**
 * Open Positions archive — category grid. A title with a description on
 * the right (the shared section-heading component's own layout), then a
 * grid of category cards: a red uppercase label above a bordered box with
 * a bullet list — no photos.
 *
 * Two sections on this one archive share this exact shape ("Schaffen
 * Perspektiven. Unsere Ausbildungen" and "Sinnstiftend vielfältig Unsere
 * Arbeitsstellen"), so this file is a reusable partial rather than being
 * duplicated — called twice with a different prefix. It stays local to
 * this archive (template-parts/archives/offene-stellen/) rather than
 * template-parts/modules/ since nothing else needs it yet.
 *
 * Desktop columns are per caller, via $args['columns'] — three across for
 * the training categories (a fixed 3 items, one full row), four across for
 * the job categories, which also gets a CSS rule for a trailing row of
 * exactly two: those two cards each take half the row instead of a
 * quarter, the same :nth-last-child trick offer-grid.php uses for its own
 * odd-card-out (see _modules/_offer-grid.sass) — confirmed against Figma,
 * whose job-categories grid does this for its 14 items (12 + a wide pair).
 * Stacked at both tablet and mobile regardless of column count — confirmed
 * against the tablet frame, which has no 2-up step for this grid (unlike
 * most card grids in this theme).
 *
 * ACF fields (flat, prefixed):
 *   {prefix}title (text)
 *   {prefix}text  (textarea / wpautop) — the description, right column
 *   {prefix}items (repeater) → title (text), list (wysiwyg — a bullet
 *                 list, toolbar restricted to just that button)
 *
 * Usage:
 *   get_template_part(
 *       'template-parts/archives/offene-stellen/category-grid',
 *       null,
 *       array( 'post_id' => 'option', 'prefix' => 'offene_stellen_archive_training_', 'columns' => 3 )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 *     @type int        $columns Optional. Cards per row at desktop — 3 or 4. Default: 4.
 * }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

$cg_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$cg_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$cg_title = get_field( $cg_prefix . 'title', $cg_ctx );

if ( ! $cg_title ) {
	return;
}

// Both class strings are written out in full below (never built by interpolating the
// number) so Tailwind's content scanner — a text match, not a PHP evaluation — sees
// xl:col-span-3 and xl:col-span-4 as literal classes either way and generates both.
$cg_three_cols  = ! empty( $args['columns'] ) && 3 === (int) $args['columns'];
$cg_modifier    = $cg_three_cols ? 'category-grid--cols-3' : 'category-grid--cols-4';
$cg_item_column = $cg_three_cols ? 'xl:col-span-4' : 'xl:col-span-3';
?>
<section class="category-grid <?php echo esc_attr( $cg_modifier ); ?> mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'title'             => $cg_title,
				'description'       => 'right',
				'description_right' => get_field( $cg_prefix . 'text', $cg_ctx ),
			)
		);
		?>

		<?php
		/*
		 * flex-col + flex-1 on the bordered box: CSS Grid already stretches each
		 * .card-category to match the tallest one in its own row (align-items: stretch
		 * is the grid default), but that stretch only reaches the immediate grid item —
		 * the border lives one level deeper, on __list, so it needs to grow into that
		 * space itself rather than just sizing to its own bullet list.
		 */
		?>
		<?php if ( have_rows( $cg_prefix . 'items', $cg_ctx ) ) : ?>
			<?php
			/*
			 * The cards' own row is a SECOND, nested theme-grid rather than this outer
			 * one's direct children — this one exists only to place that row in the
			 * standard xl:col-start-2/col-span-10 inset (full width below xl, same as
			 * everywhere else). Nested rather than moving the inset onto each card
			 * individually, so the 3/4-across split (and the trailing-pair CSS in
			 * _offene-stellen.sass) stays relative to the inset's own 12-column grid,
			 * not the page's full width.
			 */
			?>
			<div class="theme-grid mt-8 md:mt-14 xl:mt-16">
				<div class="category-grid__grid theme-grid gap-y-8 col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php
					while ( have_rows( $cg_prefix . 'items', $cg_ctx ) ) :
						the_row();

						if ( ! get_sub_field( 'title' ) ) {
							continue;
						}
						?>
						<div class="card-category col-span-2 md:col-span-6 <?php echo esc_attr( $cg_item_column ); ?> flex flex-col">
							<h3 class="card-category__title text-brand-red font-primary font-bold uppercase tracking-[0.5px] text-[14px] xl:text-[15px] mb-4">
								<?php echo esc_html( get_sub_field( 'title' ) ); ?>
							</h3>

							<?php if ( get_sub_field( 'list' ) ) : ?>
								<div class="card-category__list border border-brand-red body-text px-6 py-6 flex-1">
									<?php echo wp_kses_post( get_sub_field( 'list' ) ); ?>
								</div>
							<?php endif; ?>
						</div>
						<?php
					endwhile;
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
