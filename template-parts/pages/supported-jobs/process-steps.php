<?php
/**
 * Supported Jobs — "Der Weg zu uns" section (Figma desktop node 180:3610).
 * Title + four numbered cream boxes: one row of four at desktop, a 2×2
 * grid at tablet, stacked at mobile.
 *
 * The step number is generated from the loop index (N.), not read from the
 * field: the Figma source types it as a literal string on one item ("3.
 * Gemeinsam planen") and a real ordered-list counter on the rest, so
 * trusting the field would risk an editor breaking the sequence by editing
 * one box's text. It's prepended to the title on the same line, same
 * title-card style — not a separate, larger red digit.
 *
 * ACF fields (flat, prefixed):
 *   supported_jobs_process_title (text)
 *   supported_jobs_process_items (repeater) → title (text),
 *                                 text (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.8.0
 */

$sjp_title = get_field( 'supported_jobs_process_title' );

if ( ! $sjp_title || ! have_rows( 'supported_jobs_process_items' ) ) {
	return;
}
?>
<section class="section-process-steps mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $sjp_title ) ); ?>

		<div class="theme-grid mt-8 md:mt-14 xl:mt-16 gap-y-5">
			<?php
			$sjp_step = 0;

			while ( have_rows( 'supported_jobs_process_items' ) ) :
				the_row();
				++$sjp_step;

				if ( ! get_sub_field( 'title' ) ) {
					continue;
				}
				?>
				<div class="card-process bg-brand-cream col-span-2 md:col-span-3 xl:col-span-3">
					<h3 class="card-process__title title-card">
						<?php echo esc_html( $sjp_step ); ?>. <?php echo esc_html( get_sub_field( 'title' ) ); ?>
					</h3>

					<?php if ( get_sub_field( 'text' ) ) : ?>
						<div class="card-process__text body-text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
