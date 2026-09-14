<?php
/**
 * Legal content — the body of the AGB and Datenschutz pages: a run of blocks, each a
 * section heading in the left column and its text in the right, under a rule.
 *
 * A module because the two share one layout and differ only in their text. Impressum
 * reads as a legal page too but is laid out differently, so it is not a caller here.
 *
 * Each block is a <section> with its own <h2> rather than a description list: these are
 * document sections with prose under them, not terms and their definitions, and the
 * headings belong in the page's outline under the title band's <h1>.
 *
 * The rule runs the container's full width while the two columns keep to the inset, which
 * is why it sits on the block and not on the columns.
 *
 * WHY A REPEATER AND NOT THE EDITOR
 *
 * The editor's content is linear — a run of h2, p and ul — and this design needs each
 * heading beside its own block of text, not above it. No CSS places a heading in one
 * column and the several paragraphs that belong to it in another, so the pairing has to be
 * in the data. One row per section is what buys the layout.
 *
 * ACF fields (flat, prefixed) — the `legal_content` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   legal_content_items  (repeater) one row per section:
 *                        → heading (text)    required — set in the overline type, uppercase
 *                        → text    (wysiwyg) the section's body. Sub-headings inside it go
 *                                    in as Heading 3, not as bold paragraphs — the space
 *                                    above them is styled off the h3 (see the SASS)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/legal-content' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.14.1
 */

$lg_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$lg_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $lg_prefix . 'legal_content_items', $lg_ctx ) ) {
	return;
}
?>
<div class="legal-content mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php
		while ( have_rows( $lg_prefix . 'legal_content_items', $lg_ctx ) ) :
			the_row();

			if ( ! get_sub_field( 'heading' ) ) {
				continue;
			}
			?>
			<section class="legal-content__block theme-grid">
				<h2 class="legal-content__heading col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-3">
					<?php echo esc_html( get_sub_field( 'heading' ) ); ?>
				</h2>

				<?php if ( get_sub_field( 'text' ) ) : ?>
					<?php // WYSIWYG: already wrapped in <p>, so the wrapper is a div. ?>
					<div class="legal-content__text col-span-2 md:col-span-6 xl:col-start-5 xl:col-span-7">
						<?php echo wp_kses_post( get_sub_field( 'text' ) ); ?>
					</div>
				<?php endif; ?>
			</section>
			<?php
		endwhile;
		?>
	</div>
</div>
