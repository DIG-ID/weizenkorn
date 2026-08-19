<?php
/**
 * Quote slider — shared module. One testimonial per slide: a red-bordered box
 * with the quotation and its attribution, and an optional image panel beside
 * it. Reused on the products archive and the product-range pages.
 *
 * Layout — Figma "quote-desktop" (3018:3034), "quote-tablet" (3018:3047) and the
 * mobile group (2857:458), all confirmed 2026-07-30. Heights are min-heights, so
 * a longer quote grows the box rather than overflowing it.
 *
 *   desktop (1820px / 12 col) — the block spans columns 2–11: quote box on
 *     columns 3–7 (745px, 2px red border, 62/38px padding), image panel on
 *     columns 8–10 (440px), both 553px tall. Arrows centred in columns 2 and 11,
 *     inside the container. Name and role on one line, separated by a comma.
 *   tablet (700px / 6 col) — box on 3.5 columns + image panel on 2.5 columns
 *     (410 + 20 + 270 at a 700px container), 376px tall, 40/32px padding. The
 *     split lands mid-column, so the tracks are calculated in
 *     _modules/_quote-slider.sass rather than spanned. Arrows sit OUTSIDE the
 *     container, in the page margins. Name and role on two stacked lines, no
 *     comma. Without an image the box keeps its 3.5 columns and centres.
 *   mobile (321px / 2 col) — full-width stack: 296px image panel, 14px gap,
 *     then a 396px box with 28/21px padding and a fixed 60px gap between the
 *     quote and the attribution (top-aligned, not pushed apart). Nothing is
 *     reserved for the image: a slide without one starts with its card at the
 *     top, and a slide with one pushes the card down below the image.
 *
 * Navigation is pagination bullets below the slider at every breakpoint, plus
 * arrows in columns 2 and 11 from desktop. Swiper runs with autoHeight up to
 * tablet, so the viewport ends where the active card ends and the bullets follow
 * it; from xl it is off, so the constant height keeps the arrows from moving.
 *
 * With a single quote there is nothing to navigate: Swiper is not initialised at
 * all (so the card cannot be swiped or dragged) and neither the bullets nor the
 * arrows are rendered. The markup still carries the .swiper classes, which lay
 * the one slide out full width on their own.
 *
 * The image is per slide and optional (Figma annotation: "this slider needs to
 * work with or without image. They should be able to chose."). A slide without
 * one keeps the box at its designed width and centres it on the page.
 *
 * ACF fields (flat, prefixed):
 *   quote_slider_items (repeater)
 *     → quote  (textarea)          the quotation, guillemets included
 *     → author (text)              name, rendered bold
 *     → role   (text)              job title, rendered lighter after it
 *     → image  (image → return ID) optional panel beside the quote
 *
 * Usage — on a page the fields come from the current post:
 *   get_template_part( 'template-parts/modules/quote-slider' );
 *
 * A CPT archive has no post context, so pass the options store plus the
 * archive's field prefix, the same way as modules/hero-section:
 *   get_template_part(
 *       'template-parts/modules/quote-slider',
 *       null,
 *       array(
 *           'post_id' => 'option',
 *           'prefix'  => 'products_archive_',
 *       )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read
 *                               the fields from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 *                               Default: '' (names used as-is).
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

// ACF read context: the current post normally, the options store on archives.
$quote_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several archives.
$quote_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $quote_prefix . 'quote_slider_items', $quote_ctx ) ) {
	return;
}

/*
 * How many slides there are has to be known before the render loop starts, and
 * have_rows() only answers per row — so count the set once with get_field().
 * With a single quote there is nothing to navigate: Swiper is never initialised
 * (no swiping, no transforms) and neither the bullets nor the arrows are
 * rendered at all.
 */
$quote_is_slider = count( (array) get_field( $quote_prefix . 'quote_slider_items', $quote_ctx ) ) > 1;
?>
<section class="quote-slider relative mt-20 mb-28 md:mb-32 md:mt-32 xl:mb-48 xl:mt-48">

	<?php
	/*
	 * The slider sits OUTSIDE .theme-container and each slide holds its own
	 * container + grid. That way the panels are laid out by the page grid itself
	 * — one grid, never a grid inside a grid — because a slide is exactly the
	 * viewport width, so its container and columns are the page's own. It also
	 * keeps .swiper a plain full-width block, with no grid-item sizing for Swiper
	 * to mismeasure.
	 */
	?>
	<div class="quote-slider__viewport swiper<?php echo $quote_is_slider ? ' js-quote-slider' : ''; ?>">
		<div class="swiper-wrapper">
			<?php
			while ( have_rows( $quote_prefix . 'quote_slider_items', $quote_ctx ) ) :
				the_row();
				$quote_has_image = (bool) get_sub_field( 'image' );
				?>
				<div class="swiper-slide">
					<div class="theme-container">
						<div class="quote-slider__grid theme-grid content-start md:content-stretch">

							<?php
							/*
							 * Column placement is the page grid's:
							 *   mobile  - both panels full width, image row above the card.
							 *   tablet  - box on columns 1-4 and image on 4-6, each trimmed by
							 *             half a column in _quote-slider.sass so the split lands
							 *             mid-column (3.5 / 2.5, per the design).
							 *   desktop - box on 3-7, image on 8-10. Exact, no trim.
							 *
							 * Every panel repeats col-start at every breakpoint where its span
							 * changes, and that is not redundant: col-span-* compiles to the
							 * `grid-column` shorthand, which resets grid-column-start. A
							 * `md:col-span-4` therefore wipes an unprefixed `col-start-1`, the
							 * panel falls back to auto-placement, and the grid invents implicit
							 * columns for it next to its definitely-placed sibling.
							 * Colour on the container, so the wpautop <p> inside inherits it.
							 */
							?>
							<figure class="quote-slider__box border-2 border-brand-red flex flex-col gap-[60px] px-7 py-[21px] min-h-[396px] md:row-start-1 md:gap-0 md:justify-between md:px-10 md:py-8 md:min-h-[376px] xl:px-14 xl:py-[38px] xl:min-h-[553px] col-start-1 col-span-2 <?php echo $quote_has_image ? 'row-start-2 md:col-start-1 md:col-span-4 xl:col-start-3 xl:col-span-5' : 'row-start-1 quote-slider__box--centered md:col-start-1 md:col-span-6 xl:col-start-1 xl:col-span-12'; ?>">

								<?php if ( get_sub_field( 'quote' ) ) : ?>
									<blockquote class="quote-slider__text text-brand-red">
										<?php echo wp_kses_post( get_sub_field( 'quote' ) ); ?>
									</blockquote>
								<?php endif; ?>

								<?php if ( get_sub_field( 'author' ) || get_sub_field( 'role' ) ) : ?>
									<?php // Mobile spaces this with the box's own 60px gap; from md the box is justify-between and the margin is only a minimum. ?>
									<figcaption class="quote-slider__author text-brand-dark mt-0 md:mt-10 xl:mt-14">
										<?php // rtrim so a name typed with its own trailing comma does not get a second one. ?>
										<?php if ( get_sub_field( 'author' ) ) : ?>
											<strong><?php echo esc_html( rtrim( get_sub_field( 'author' ), ' ,' ) ); ?></strong>
										<?php endif; ?>
										<?php if ( get_sub_field( 'role' ) ) : ?>
											<span><?php echo esc_html( get_sub_field( 'role' ) ); ?></span>
										<?php endif; ?>
									</figcaption>
								<?php endif; ?>
							</figure>

							<?php if ( $quote_has_image ) : ?>
								<?php // Mobile: 296px tall, above the box. From md it stretches to the box's height. ?>
								<div class="quote-slider__media bg-brand-dark overflow-hidden row-start-1 h-[296px] col-start-1 col-span-2 md:h-auto md:col-start-4 md:col-span-3 xl:col-start-8 xl:col-span-3">
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

						</div>
					</div>
				</div>
				<?php
			endwhile;
			?>
		</div>

		<?php if ( $quote_is_slider ) : ?>
			<?php
			/*
			 * Inside .swiper and a sibling of .swiper-wrapper, not a layer over the
			 * whole section. Swiper only manages the wrapper and its slides, so this is
			 * left where it is — and .swiper is already position: relative, which makes
			 * inset-0 the slider's own box. Over the section it was the slider plus the
			 * bullets below it, so items-center put the arrows well under the card.
			 *
			 * The container + grid inside it is what places the arrows on columns 2 and
			 * 11; no grid is ever nested inside a slide. Clicks pass through everywhere
			 * except on the buttons themselves.
			 */
			?>
			<div class="quote-slider__nav-layer absolute inset-0 z-10 hidden pointer-events-none xl:block">
				<div class="theme-container h-full">
					<div class="theme-grid h-full items-center">
						<button type="button" class="quote-slider__nav quote-slider__nav--prev js-quote-prev pointer-events-auto col-start-2 row-start-1 justify-self-center" aria-label="<?php esc_attr_e( 'Previous quote', 'weizenkorn' ); ?>">
							<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
						</button>
						<button type="button" class="quote-slider__nav quote-slider__nav--next js-quote-next pointer-events-auto col-start-11 row-start-1 justify-self-center" aria-label="<?php esc_attr_e( 'Next quote', 'weizenkorn' ); ?>">
							<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
						</button>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $quote_is_slider ) : ?>

		<?php
		/*
		* Bullets: same look as the gastronomy slider on the home page. The element
		* lives outside .swiper and is handed to Swiper explicitly, so Swiper's
		* absolute positioning rules for a nested pagination never apply. The top
		* margin is an interim value — no Figma frame for the bullets yet.
		*
		* The arrows are the other control on the same Swiper, rendered above inside
		* .swiper; they are hidden with CSS below xl rather than left out, so Swiper
		* keeps them in sync and nothing needs rebuilding at a breakpoint.
		*/
		?>
	<div class="theme-container">
		<div class="quote-slider__pagination swiper-pagination js-quote-pagination"></div>
	</div>


	<?php endif; ?>

</section>
