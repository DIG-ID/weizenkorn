<?php
/**
 * Process steps — a heading and a row of photographs, each captioned with the step it
 * shows and carrying a sentence that appears on hover.
 *
 * A module because four pages repeat it with their own steps — Kreativatelier's "So
 * entsteht Mehrwert", Sponsoring, and the two supported-work pages.
 *
 * THE HOVER TEXT
 *
 * A hover state is not reachable by touch, so the sentence is not hidden behind one where
 * there is no pointer to hover with: @media (hover: hover) is what gates it, and anywhere
 * else the caption simply shows the sentence under the step. The text is always in the
 * markup either way, so it is read out and indexed regardless of how it is revealed.
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
 *                                → text  (textarea) the sentence revealed on hover
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
			?>
			<div class="process-steps__grid mt-8 xl:mt-24">
				<?php
				while ( have_rows( $ps_prefix . 'process_steps_items', $ps_ctx ) ) :
					the_row();

					if ( ! get_sub_field( 'image' ) ) {
						continue;
					}
					?>
					<figure class="process-steps__item">
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

						<?php if ( get_sub_field( 'title' ) || get_sub_field( 'text' ) ) : ?>
							<figcaption class="process-steps__caption">
								<?php if ( get_sub_field( 'title' ) ) : ?>
									<span class="process-steps__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
								<?php endif; ?>

								<?php if ( get_sub_field( 'text' ) ) : ?>
									<?php
									// Two elements because the reveal animates grid-template-rows from 0fr
									// to 1fr — the outer one is the track, the inner one the thing clipped.
									?>
									<span class="process-steps__text">
										<span class="process-steps__text-inner"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
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
