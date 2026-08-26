<?php
/**
 * Numbered steps — a heading and a row of numbered boxes (no photos), each with a title
 * and a short paragraph: one row of four at desktop, a 2×2 grid at tablet, stacked at
 * mobile. Two pages share this exact shape: Supported Jobs' "Der Weg zu uns" and
 * Supported Apprenticeships' "Der Weg zu einem Platz bei Weizenkorn in 4 Schritten".
 *
 * Distinct from template-parts/modules/process-steps.php, which requires a photo per
 * item (Kreativatelier's hover-caption tiles) — this module's Figma frames have no
 * images at all.
 *
 * The step number is generated from the loop index (N.), not read from the field: the
 * Figma source types it as a literal string on one item ("3. Gemeinsam planen") and a
 * real ordered-list counter on the rest, so trusting the field would risk an editor
 * breaking the sequence by editing one box's text. It's prepended to the title on the
 * same line, same title-card style — not a separate, larger digit.
 *
 * A fixed box height (286 mobile / 445 tablet+desktop — the Figma frames' own values,
 * confirmed identical on both pages) with justify-between pins the title to the top and
 * the text to the bottom, whatever either one's length: the gap between them is
 * whatever height leaves over, not a value chosen by hand.
 *
 * ACF fields (flat, prefixed):
 *   {prefix}title (text)
 *   {prefix}items (repeater, max 4) → title (text), text (textarea / wpautop)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/numbered-steps', null, array( 'prefix' => 'supported_jobs_process_' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.9.0
 */

$ns_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$ns_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$ns_title = get_field( $ns_prefix . 'title', $ns_ctx );

if ( ! $ns_title || ! have_rows( $ns_prefix . 'items', $ns_ctx ) ) {
	return;
}
?>
<section class="numbered-steps mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $ns_title ) ); ?>

		<div class="theme-grid mt-8 md:mt-14 xl:mt-16 gap-y-5">
			<?php
			$ns_step = 0;

			while ( have_rows( $ns_prefix . 'items', $ns_ctx ) ) :
				the_row();
				++$ns_step;

				if ( ! get_sub_field( 'title' ) ) {
					continue;
				}
				?>
				<div class="card-numbered-step bg-brand-cream col-span-2 md:col-span-3 xl:col-span-3">
					<h3 class="card-numbered-step__title title-card">
						<?php echo esc_html( $ns_step ); ?>. <?php echo esc_html( get_sub_field( 'title' ) ); ?>
					</h3>

					<?php if ( get_sub_field( 'text' ) ) : ?>
						<div class="card-numbered-step__text body-text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
