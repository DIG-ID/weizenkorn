<?php
/**
 * Impressum details — the body of the Impressum page: two groups of labelled cells, three
 * to a row at desktop and stacked below it.
 *
 * A page part and not a module: this arrangement belongs to the Impressum alone. The AGB
 * and Datenschutz use modules/legal-content, a single column of heading-beside-text blocks,
 * and the only thing the three pages share is the title band above.
 *
 * THE TWO GROUPS
 *
 * `details` — Adresse, Kontakt, Konzeption: a heading, a red rule under it, then the text
 *             in a display size. What a visitor came to find.
 * `notes`   — Redaktion, Haftungsausschluss, Copyright: a heading, then a red-bordered
 *             panel holding the text at the body size. The small print.
 *
 * Two repeaters and not one with a style field, because the design separates the groups
 * rather than interleaving them: each is its own row at desktop, and the gap between them
 * is wider than the gap inside them (96px at mobile, 128 at tablet, 192 at desktop). That
 * gap is the first group's bottom padding, which is why the groups are real elements.
 *
 * ACF fields (flat, prefixed) — the `impressum` group. The group name produces the prefix,
 * so renaming it orphans whatever is stored:
 *   impressum_details  (repeater) the rule cells — one row each:
 *                      → heading (text)    required — shown uppercase by the stylesheet
 *                      → text    (wysiwyg) address lines, set one leading apart
 *   impressum_notes    (repeater) the boxed cells, same two fields
 *
 * Usage:
 *   get_template_part( 'template-parts/pages/impressum/details' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.14.1
 */

$im_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$im_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$im_has_details = have_rows( $im_prefix . 'impressum_details', $im_ctx );
$im_has_notes   = have_rows( $im_prefix . 'impressum_notes', $im_ctx );

if ( ! $im_has_details && ! $im_has_notes ) {
	return;
}

// The inset is the same for both groups, and each divides it into its own three columns —
// a third of ten columns is not a whole number of them.
$im_group_span = 'col-span-2 md:col-start-2 md:col-span-4 xl:col-start-2 xl:col-span-10';
?>
<div class="impressum-details mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<div class="theme-grid">

			<?php if ( $im_has_details ) : ?>
				<div class="impressum-details__group impressum-details__group--details <?php echo esc_attr( $im_group_span ); ?>">
					<?php
					while ( have_rows( $im_prefix . 'impressum_details', $im_ctx ) ) :
						the_row();

						if ( ! get_sub_field( 'heading' ) ) {
							continue;
						}
						?>
						<section class="impressum-details__cell impressum-details__cell--rule">
							<h2 class="impressum-details__heading"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>

							<?php if ( get_sub_field( 'text' ) ) : ?>
								<?php // WYSIWYG: already wrapped in <p>, so the wrapper is a div. ?>
								<div class="impressum-details__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
							<?php endif; ?>
						</section>
						<?php
					endwhile;
					?>
				</div>
			<?php endif; ?>

			<?php if ( $im_has_notes ) : ?>
				<div class="impressum-details__group impressum-details__group--notes <?php echo esc_attr( $im_group_span ); ?>">
					<?php
					while ( have_rows( $im_prefix . 'impressum_notes', $im_ctx ) ) :
						the_row();

						if ( ! get_sub_field( 'heading' ) ) {
							continue;
						}
						?>
						<section class="impressum-details__cell impressum-details__cell--box">
							<h2 class="impressum-details__heading"><?php echo esc_html( get_sub_field( 'heading' ) ); ?></h2>

							<?php if ( get_sub_field( 'text' ) ) : ?>
								<div class="impressum-details__box">
									<div class="impressum-details__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
								</div>
							<?php endif; ?>
						</section>
						<?php
					endwhile;
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
