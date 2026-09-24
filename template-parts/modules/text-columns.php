<?php
/**
 * Text columns — a rule across the container, then blocks of prose two to a row, each a
 * short heading with its own paragraphs under it. The Schreinerei service pages' untitled
 * text section, the one the Figma analysis calls simply "text".
 *
 * Its own module and not modules/legal-content.php, whose blocks put the heading BESIDE
 * its text on three columns and seven; here the heading sits above its text and the two
 * blocks are five columns each. Nor the section-heading component with `description: both`,
 * which lands on exactly these columns but hangs its rule off a title this section does not
 * have, and has nowhere to put a heading inside either column.
 *
 * The rule runs the container's full width while the columns keep to the inset, which is
 * why it sits on the row and not on either column — the same call legal-content makes.
 *
 * A repeater and not two fixed fields: six service pages share this template and nothing
 * says they all have exactly two blocks. Two to a row whatever the count, and a lone block
 * on the last row keeps its own column rather than stretching across both.
 *
 * ACF fields (flat, prefixed) — the `text_columns` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   text_columns_items  (repeater) one row per block:
 *                       → title (text)               the heading above the prose
 *                       → text  (textarea / wpautop) the prose itself
 *
 * A block needs one or the other; a row with neither is skipped. A block with no title is
 * drawn as a plain div rather than a <section>, a section being something with a heading.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/text-columns' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.19.0
 */

$tc_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$tc_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $tc_prefix . 'text_columns_items', $tc_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
//
// Above the rule: the same 32/56/96 the row sets below it (see the pt-* in
// _text-columns.sass), because every frame draws the line with equal air either side, not
// only the desktop one. The module before this sets the matching bottom margin, since a
// collapse takes the larger of the two and either one alone would win.
?>
<div class="text-columns mt-8 md:mt-14 xl:mt-24 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<div class="text-columns__row theme-grid">
			<?php
			while ( have_rows( $tc_prefix . 'text_columns_items', $tc_ctx ) ) :
				the_row();

				$tc_title = get_sub_field( 'title' );
				$tc_text  = get_sub_field( 'text' );

				if ( ! $tc_title && ! $tc_text ) {
					continue;
				}

				// A <section> is something with a heading; without one this is just a block of
				// prose, and a sectioning element with nothing to name it only adds noise to
				// the outline.
				$tc_tag = $tc_title ? 'section' : 'div';
				?>
				<<?php echo esc_html( $tc_tag ); ?> class="text-columns__item">
					<?php if ( $tc_title ) : ?>
						<?php
						// An h2 and not an h3: this block sits beside the page's other sections
						// rather than under one of them. Figma names the type style "H3", which
						// is a size and not a level in the outline.
						?>
						<h2 class="text-columns__title title-tagline"><?php echo esc_html( $tc_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $tc_text ) : ?>
						<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
						<div class="text-columns__text body-text"><?php echo wp_kses_post( $tc_text ); ?></div>
					<?php endif; ?>
				</<?php echo esc_html( $tc_tag ); ?>>
				<?php
			endwhile;
			?>
		</div>
	</div>
</div>
