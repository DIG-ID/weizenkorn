<?php
/**
 * FAQ — a heading and a list of questions with their answers, each pair separated by a
 * rule that runs the container's full width while the text sits on columns 4 to 11.
 *
 * A module because six pages carry it: Fiduciary, Kreativatelier, Schreinerei, Sponsoring
 * and the two supported-work pages.
 *
 * A description list, which is what a FAQ is — <dt> the question, <dd> the answer. The
 * <div> around each pair is what HTML allows a <dl> to group them with, and it doubles as
 * the grid that places the two columns and carries the rule beneath them.
 *
 * Not an accordion, despite the name the architecture notes give it: the frame shows every
 * answer open with no toggle of any kind. If a page turns up with a collapsed state, a
 * <details>/<summary> pair replaces the <dt>/<dd> here and the rest of the layout stands.
 *
 * ACF fields (flat, prefixed) — the `faq` group. The group name produces the prefix, so
 * renaming it orphans whatever is stored:
 *   faq_section_title  (clone of "Section Title") the title and its red rule. Clone the
 *                      GROUP, never a repeater inside one.
 *   faq_items          (repeater) one row per question:
 *                      → question (text)     required — an empty one is a blank line
 *                      → answer   (textarea / wpautop)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/faq' );
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

$faq_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$faq_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$faq_heading = weizenkorn_get_section_heading( $faq_prefix . 'faq_', $faq_ctx );

if ( ! $faq_heading && ! have_rows( $faq_prefix . 'faq_items', $faq_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="faq mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $faq_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $faq_heading );
		}
		?>

		<?php if ( have_rows( $faq_prefix . 'faq_items', $faq_ctx ) ) : ?>
			<?php
			// No top margin: the first pair's own padding is dropped in the SASS, so the gap
			// under the rule is the heading's bottom margin alone.
			?>
			<dl class="faq__list">
				<?php
				while ( have_rows( $faq_prefix . 'faq_items', $faq_ctx ) ) :
					the_row();

					if ( ! get_sub_field( 'question' ) ) {
						continue;
					}
					?>
					<div class="faq__item theme-grid">
						<dt class="faq__question col-span-2 md:col-start-1 md:col-span-5 xl:col-start-4 xl:col-span-3">
							<?php echo esc_html( get_sub_field( 'question' ) ); ?>
						</dt>

						<?php if ( get_sub_field( 'answer' ) ) : ?>
							<?php // Textarea with wpautop: already wrapped in <p>. ?>
							<dd class="faq__answer col-span-2 md:col-start-1 md:col-span-5 xl:col-start-7 xl:col-span-5">
								<?php echo wp_kses_post( get_sub_field( 'answer' ) ); ?>
							</dd>
						<?php endif; ?>
					</div>
					<?php
				endwhile;
				?>
			</dl>
		<?php endif; ?>

	</div>
</section>
