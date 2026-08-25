<?php
/**
 * Our locations — a heading and one row per location: a photograph on one side, and on the
 * other the location's name, a paragraph, and a bordered box with its opening hours and
 * address.
 *
 * The rows alternate: the photograph is on the left of an odd row and on the right of an
 * even one. That comes from the row's position, not from a field, so adding a location
 * keeps the rhythm — see _modules/_our-locations.sass.
 *
 * The bordered box is pinned to the bottom of its column with mt-auto and carries a
 * minimum height, so every row's box lines up however long the paragraph above it runs.
 * A minimum and not a fixed height: the hours are editorial and a fourth line would be
 * clipped by a fixed one.
 *
 * ACF fields (flat, prefixed) — the `our_locations` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   our_locations_section_title  (clone of "Section Title") the title and its rule. Clone
 *                                the GROUP, never a repeater inside one.
 *   our_locations_items          (repeater) one row per location:
 *     → image           (image → ID)
 *     → is_new          (true/false)  draws the "Neu!" badge over the photograph
 *     → title           (text)        the location's name, in the overline's type
 *     → text            (textarea)    the paragraph under it
 *     → schedule_title  (text)        heading of the box's left column. Optional — falls
 *                                     back to "Öffnungszeiten"
 *     → schedule_text   (textarea)    the opening hours, one line per row
 *     → address_title   (text)        heading of its right column. Falls back to "Adresse"
 *     → address_text    (textarea)    the postal address and phone number
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/our-locations' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.8.0
 */

$ol_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$ol_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$ol_heading = weizenkorn_get_section_heading( $ol_prefix . 'our_locations_', $ol_ctx );

if ( ! $ol_heading && ! have_rows( $ol_prefix . 'our_locations_items', $ol_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="our-locations mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $ol_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $ol_heading );
		}
		?>

		<?php if ( have_rows( $ol_prefix . 'our_locations_items', $ol_ctx ) ) : ?>
			<?php
				// The full 40 / 56 / 96 the frames put between the rule and the first row, not
				// the difference: this margin and the section-heading's own collapse against
				// each other, so the browser keeps the larger of the two rather than the sum.
			?>
				<div class="our-locations__list mt-10 md:mt-14 xl:mt-24">
				<?php
				while ( have_rows( $ol_prefix . 'our_locations_items', $ol_ctx ) ) :
					the_row();
					?>
					<?php
					/*
					 * One .theme-grid per row, not one for the section: each row places its two
					 * halves on real columns, and a row is a sibling of the next rather than a
					 * cell inside a bigger grid. Which half goes where is the row's parity, set
					 * in the stylesheet.
					 */
					?>
					<article class="our-locations__item theme-grid items-stretch">

						<?php if ( get_sub_field( 'image' ) ) : ?>
							<figure class="our-locations__media">
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

								<?php if ( get_sub_field( 'is_new' ) ) : ?>
									<span class="our-locations__badge"><?php echo esc_html_x( 'Neu!', 'new location badge', 'weizenkorn' ); ?></span>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="our-locations__text text-brand-dark">

							<?php if ( get_sub_field( 'title' ) ) : ?>
								<h3 class="our-locations__name"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
							<?php endif; ?>

							<?php if ( get_sub_field( 'text' ) ) : ?>
								<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
								<div class="our-locations__intro"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
							<?php endif; ?>

							<?php if ( get_sub_field( 'schedule_text' ) || get_sub_field( 'address_text' ) ) : ?>
								<?php
								// The two headings fall back to the design's own wording, so a row can
								// leave them empty rather than retype the same label per location.
								?>
								<div class="our-locations__box">

									<?php if ( get_sub_field( 'schedule_text' ) ) : ?>
										<div class="our-locations__col">
											<p class="our-locations__col-title">
												<?php echo esc_html( get_sub_field( 'schedule_title' ) ? get_sub_field( 'schedule_title' ) : _x( 'Öffnungszeiten', 'opening hours heading', 'weizenkorn' ) ); ?>
											</p>
											<div class="our-locations__col-body"><?php echo wp_kses_post( get_sub_field( 'schedule_text' ) ); ?></div>
										</div>
									<?php endif; ?>

									<?php if ( get_sub_field( 'address_text' ) ) : ?>
										<div class="our-locations__col">
											<p class="our-locations__col-title">
												<?php echo esc_html( get_sub_field( 'address_title' ) ? get_sub_field( 'address_title' ) : _x( 'Adresse', 'address heading', 'weizenkorn' ) ); ?>
											</p>
											<div class="our-locations__col-body"><?php echo wp_kses_post( get_sub_field( 'address_text' ) ); ?></div>
										</div>
									<?php endif; ?>

								</div>
							<?php endif; ?>

						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
