<?php
/**
 * Open Positions single post — details section (Figma desktop node
 * 4450:6539, "Ihre Aufgaben" / "Voraussetzungen"). A repeater of boxes,
 * not the two fixed fields the frame shows: a posting may need a third
 * or fourth (e.g. "Ihre Vorteile"), and each box's own title is editable
 * rather than hard-coded. Same card style as
 * template-parts/archives/offene-stellen/category-grid.php's own
 * card-category (a red uppercase label above a bordered bullet-list box).
 *
 * Up to four boxes share the row evenly (full width alone, half each at
 * two, a third at three, a quarter at four) — confirmed as the intended
 * behaviour for "Ihre Aufgaben" / "Voraussetzungen" specifically, and
 * extended here since the field is now open-ended. Past four, later rows
 * fall back to a plain four-per-row grid with no even-split adjustment:
 * the frame only ever shows two, so a fifth box is an edge case the
 * design gives no guidance for.
 *
 * ACF fields (flat, unprefixed):
 *   offene_stellen_details_items (repeater, max 6) → title (text),
 *                                 list (wysiwyg — bullet list only)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

if ( ! have_rows( 'offene_stellen_details_items' ) ) {
	return;
}

$jd_count = 0;

while ( have_rows( 'offene_stellen_details_items' ) ) {
	the_row();

	if ( get_sub_field( 'title' ) ) {
		++$jd_count;
	}
}

if ( ! $jd_count ) {
	return;
}

/*
 * Both class strings for every case are written out in full below (never built by
 * interpolating a number) so Tailwind's content scanner — a text match, not a PHP
 * evaluation — sees each one as a literal class and generates it, whichever branch
 * actually runs.
 */
if ( $jd_count <= 4 ) {
	$jd_box_classes = array(
		1 => 'md:col-span-6 xl:col-span-12',
		2 => 'md:col-span-3 xl:col-span-6',
		3 => 'md:col-span-2 xl:col-span-4',
		4 => 'md:col-span-2 xl:col-span-3',
	);
	$jd_box_class   = $jd_box_classes[ $jd_count ];
} else {
	$jd_box_class = 'md:col-span-2 xl:col-span-3';
}
?>
<section class="job-details mt-14 md:mt-20 xl:mt-28">
	<div class="theme-container">
		<div class="theme-grid">
			<div class="col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-7">
				<div class="theme-grid gap-y-8">
					<?php
					while ( have_rows( 'offene_stellen_details_items' ) ) :
						the_row();

						if ( ! get_sub_field( 'title' ) ) {
							continue;
						}
						?>
						<div class="card-category col-span-2 <?php echo esc_attr( $jd_box_class ); ?> flex flex-col">
							<?php
							/*
							 * h2, not h3: nothing on this page wraps "Ihre Aufgaben"/"Voraussetzungen"
							 * in a section heading of its own (unlike category-grid.php's own use of
							 * this exact card shape, nested under a real h2 there) — h3 here would
							 * skip straight from the page's h1, no h2 in between.
							 */
							?>
							<h2 class="card-category__title text-brand-red font-primary font-bold uppercase tracking-[0.5px] text-[14px] xl:text-[15px] mb-4">
								<?php echo esc_html( get_sub_field( 'title' ) ); ?>
							</h2>

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
		</div>
	</div>
</section>
