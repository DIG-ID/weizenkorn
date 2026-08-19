<?php
/**
 * Our equipment — the machine park: a heading and a slider of photographs, each captioned
 * with the technique it shows.
 *
 * Holzmanufaktur's "Vom Feinsten – unsere Ausrüstung" (Formatieren, UV Digitaldruck, CNC
 * fräsen…). The Figma analysis (§3) gives the same block to the Schreinerei, which is why
 * it is a module rather than a part of one page.
 *
 * The analysis calls it capabilities-grid. It is neither: a grid does not page through its
 * items, and what the frame lists is equipment.
 *
 * Layout — Figma 3974:6714, desktop confirmed 2026-08-18 (canvas 1920 / container 1820):
 *
 *   heading   title on column 2, red rule full width, overline under the rule — all from
 *             the shared section-heading. 96px down to the slider.
 *   viewport  columns 2–11. Two slides of five columns with the grid's 20px between them
 *             come to 1513px, which is that inset exactly — so slidesPerView is 2 and the
 *             slide width needs no value of its own.
 *   bleed     the slides carry on past the container's right edge. The .swiper shows its
 *             overflow and the section clips it, so the run ends at the browser's edge
 *             with no negative margins and no width arithmetic.
 *   slide     a photo at 744/570 with a bordered 80px caption box under it.
 *   arrows    both below the slider and right-aligned to the viewport's right edge, 56px
 *             under it — not flanking the slides the way stories-references does.
 *
 * Tablet — Figma 3974:6668, confirmed 2026-08-18 (canvas 834 / container 703):
 *
 *   viewport  the whole container, one slide per view, and no bleed: the frame's slider
 *             is exactly the 703 the container is.
 *   slide     the photo at 703/543 with a 66px caption box under it (20/18 of padding).
 *   controls  bullets, not arrows — three 15px red outlines 30px apart, centred, 56px
 *             under the slider. The arrows are hidden here and the bullets at desktop.
 *   caption   20/30, the same as desktop: the frame uses the Desktop/Subtitle style for
 *             it, while the section's own overline above drops to the tablet 15/30.
 *
 * Mobile — Figma 3974:6667, confirmed 2026-08-18 (canvas 393 / container 321):
 *
 *   viewport  the container again, one slide, no bleed.
 *   slide     the photo at 321/229 with a 49px caption box under it (16 all round), the
 *             caption itself down to 14/15.
 *   controls  the same bullets, 30px apart, but 32px under the slider rather than 56.
 *   heading   24px down to the slider, which is exactly the margin the section-heading
 *             carries under its overline — so the row adds nothing of its own here.
 *
 * ACF fields (flat, prefixed) — the `our_equipment` group. The group name produces
 * the prefix, so renaming it renames all of these and orphans whatever is stored:
 *   our_equipment_section_title  (clone of "Section Title") title + overline. Clone
 *                                      the GROUP, never a repeater inside one.
 *   our_equipment_items          (repeater) one row per technique:
 *     → image  (image → ID)
 *     → title  (text)  the caption, e.g. "UV Digitaldruck"
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/our-equipment' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.6.0
 */

// ACF read context: the current post normally, the options store on archives.
$eq_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several contexts.
$eq_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

/*
 * The heading comes from the shared helper, which reads a cloned "Section Title" group
 * in either of its Displays and rebuilds the links a Seamless clone hides behind a
 * composite field reference. See weizenkorn_get_section_heading() in
 * inc/theme-template-tags.php for why that read is not a plain get_field().
 */
$eq_heading = weizenkorn_get_section_heading( $eq_prefix . 'our_equipment_', $eq_ctx );

if ( ! $eq_heading && ! have_rows( $eq_prefix . 'our_equipment_items', $eq_ctx ) ) {
	return;
}
?>
<section class="our-equipment mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $eq_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $eq_heading );
		}
		?>

		<?php if ( have_rows( $eq_prefix . 'our_equipment_items', $eq_ctx ) ) : ?>
			<?php
			/*
			 * The frames' distance from the heading, less the margin the section-heading
			 * already carries under its overline: 96 − 32 at desktop, 56 − 24 at tablet and
			 * 24 − 24 at mobile, where that margin is the whole gap and there is nothing
			 * left to add.
			 */
			?>
			<div class="our-equipment__row theme-grid md:mt-8 xl:mt-16">

				<div class="our-equipment__viewport">
					<div class="swiper js-equipment-slider">
						<div class="swiper-wrapper">
							<?php
							while ( have_rows( $eq_prefix . 'our_equipment_items', $eq_ctx ) ) :
								the_row();

								if ( ! get_sub_field( 'image' ) && ! get_sub_field( 'title' ) ) {
									continue;
								}
								?>
								<div class="swiper-slide">
									<figure class="card-equipment">
										<?php if ( get_sub_field( 'image' ) ) : ?>
											<div class="card-equipment__media">
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
											</div>
										<?php endif; ?>

										<?php if ( get_sub_field( 'title' ) ) : ?>
											<figcaption class="card-equipment__label text-brand-dark">
												<?php echo esc_html( get_sub_field( 'title' ) ); ?>
											</figcaption>
										<?php endif; ?>
									</figure>
								</div>
								<?php
							endwhile;
							?>
						</div>
					</div>
				</div>

				<?php
				/*
				 * Both arrows in one block, right-aligned under the slider — desktop only, the
				 * CSS hides them below xl. They are a grid item like the viewport rather than
				 * an overlay: Swiper gives .swiper
				 * `position: relative; z-index: 1`, so an absolute nav layer underneath it
				 * ends up visible but unclickable — the trap the quote-slider hit.
				 */
				?>
				<div class="our-equipment__nav">
					<button type="button" class="our-equipment__arrow js-equipment-prev" aria-label="<?php echo esc_attr_x( 'Previous', 'slider control', 'weizenkorn' ); ?>">
						<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
					</button>

					<button type="button" class="our-equipment__arrow js-equipment-next" aria-label="<?php echo esc_attr_x( 'Next', 'slider control', 'weizenkorn' ); ?>">
						<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
					</button>
				</div>

				<?php
				/*
				 * The tablet control, in place of the arrows: the bullets the frame draws,
				 * centred under the slider. Both blocks are always rendered and the CSS shows
				 * one or the other, because Swiper binds navigation and pagination once at
				 * init and the viewport can cross the breakpoint after that.
				 */
				?>
				<div class="our-equipment__pagination swiper-pagination js-equipment-pagination"></div>

			</div>
		<?php endif; ?>

	</div>
</section>
