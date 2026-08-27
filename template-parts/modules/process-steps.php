<?php
/**
 * Process steps — a heading and a row of photographs, each captioned with the step it
 * shows and carrying a sentence the reader opens.
 *
 * A module because four pages repeat it with their own steps — Kreativatelier's "So
 * entsteht Mehrwert", Sponsoring, and the two supported-work pages.
 *
 * OPENING THE SENTENCE
 *
 * Where there is a pointer the sentence follows the hover, as the desktop frame draws it.
 * A hover is unreachable by touch, so the caption's title is also a button: a tap opens the
 * sentence and a second tap closes it, which is what the tablet and mobile frames call for
 * — they draw the tile closed, with the bar showing the step's name alone. The same button
 * is what a keyboard uses, and assets/js/process-steps.js keeps one step open at a time,
 * the way only one tile can be hovered.
 *
 * The text is always in the markup, so it is read out and indexed however it is revealed.
 *
 * The caption grows upward from the photograph's bottom edge rather than covering it, so
 * the tile's height never changes and the row stays level.
 *
 * Not the spaces module, which this resembles: that one is a bento of differently sized
 * tiles with no hover at all, and its title is set as an overline. Here the tiles are equal,
 * the row is the container's full width rather than the usual inset, and the heading is the
 * display title.
 *
 * ACF fields (flat, prefixed) — the `process_steps` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   process_steps_section_title  (clone of "Section Title") the title and its red rule.
 *                                Clone the GROUP, never a repeater inside one.
 *   process_steps_items          (repeater) one row per step:
 *                                → image (image → return ID) required
 *                                → title (text)
 *                                → text  (textarea) the sentence the button opens
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/process-steps' );
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

$ps_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$ps_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$ps_heading = weizenkorn_get_section_heading( $ps_prefix . 'process_steps_', $ps_ctx );

if ( ! $ps_heading && ! have_rows( $ps_prefix . 'process_steps_items', $ps_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="process-steps mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $ps_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $ps_heading );
		}
		?>

		<?php if ( have_rows( $ps_prefix . 'process_steps_items', $ps_ctx ) ) : ?>
			<?php
			// Its own grid and not .theme-grid: five equal tiles have no expression in twelve
			// columns, and the row runs the container's full width rather than the usual inset.
			//
			// 56px under the rule at tablet, where the sections that keep to the inset use 32 —
			// this one is measured from its own frame. The heading's bottom margin collapses
			// with this one rather than adding to it, so this is the whole gap.
			?>
			<div class="process-steps__grid mt-8 md:mt-14 xl:mt-24">
				<?php
				while ( have_rows( $ps_prefix . 'process_steps_items', $ps_ctx ) ) :
					the_row();

					if ( ! get_sub_field( 'image' ) ) {
						continue;
					}

					$ps_title = get_sub_field( 'title' );
					$ps_text  = get_sub_field( 'text' );
					$ps_id    = 'process-step-' . $ps_ctx . '-' . get_row_index();
					?>
					<figure class="process-steps__item<?php echo ( $ps_title && $ps_text ) ? ' process-steps__item--openable' : ''; ?>">
						<?php
						echo wp_get_attachment_image(
							get_sub_field( 'image' ),
							'large',
							false,
							array(
								'class'   => 'w-full h-full object-cover',
								'loading' => 'lazy',
							)
						);
						?>

						<?php if ( $ps_title || $ps_text ) : ?>
							<figcaption class="process-steps__caption">
								<?php if ( $ps_title && $ps_text ) : ?>
									<?php
									// The sentence is behind a pointer's hover, and behind a click or a
									// tap everywhere else. The whole tile is the target — the photograph
									// as much as the bar — but the control is this button, so that a
									// keyboard reaches it and a screen reader is told what it does; the
									// tile's own handler is what widens the target around it.
									// The title is the button's label, so a step with a sentence and no
									// title has nothing to press and simply shows it (below).
									?>
									<button type="button" class="process-steps__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $ps_id ); ?>">
										<span class="process-steps__title"><?php echo esc_html( $ps_title ); ?></span>
									</button>
								<?php elseif ( $ps_title ) : ?>
									<span class="process-steps__title"><?php echo esc_html( $ps_title ); ?></span>
								<?php endif; ?>

								<?php if ( $ps_text ) : ?>
									<?php
									// Two elements because the reveal animates grid-template-rows from 0fr
									// to 1fr — the outer one is the track, the inner one the thing clipped.
									?>
									<span class="process-steps__text<?php echo $ps_title ? '' : ' is-open'; ?>" id="<?php echo esc_attr( $ps_id ); ?>">
										<span class="process-steps__text-inner"><?php echo esc_html( $ps_text ); ?></span>
									</span>
								<?php endif; ?>
							</figcaption>
						<?php endif; ?>
					</figure>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
