<?php
/**
 * Quote slider — one testimonial per slide: a red-bordered box with the quotation and its
 * attribution, and an optional image panel beside it.
 *
 * Heights are min-heights, so a longer quote grows the box rather than overflowing it.
 * The image is per slide and optional; a slide without one keeps the box at its designed
 * width and centres it.
 *
 * With a single quote there is nothing to navigate: Swiper is not initialised at all, so
 * the card cannot be swiped, and neither the bullets nor the arrows are rendered. The
 * markup still carries the .swiper classes, which lay one slide out full width on their
 * own.
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
 * A CPT archive has no post context, so pass the options store plus the archive's prefix:
 *   get_template_part(
 *       'template-parts/modules/quote-slider',
 *       null,
 *       array( 'post_id' => 'option', 'prefix' => 'products_archive_' )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read the fields
 *                               from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

$quote_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$quote_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $quote_prefix . 'quote_slider_items', $quote_ctx ) ) {
	return;
}

// Counted with get_field() because the answer is needed before the render loop starts
// and have_rows() only answers per row.
$quote_is_slider = count( (array) get_field( $quote_prefix . 'quote_slider_items', $quote_ctx ) ) > 1;
?>
<section class="quote-slider relative mt-20 mb-28 md:mb-32 md:mt-32 xl:mb-48 xl:mt-48">

	<?php
	/*
	 * The slider sits OUTSIDE .theme-container and each slide holds its own container +
	 * grid, so the panels are laid out by the page grid itself — one grid, never a grid
	 * inside a grid, a slide being exactly the viewport width. It also keeps .swiper a
	 * plain full-width block, with no grid-item sizing for Swiper to mismeasure.
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
							 * Every panel repeats col-start at every breakpoint where its span
							 * changes, and that is not redundant: col-span-* compiles to the
							 * `grid-column` shorthand, which resets grid-column-start. A
							 * `md:col-span-4` would wipe an unprefixed `col-start-1`, the panel
							 * would fall back to auto-placement, and the grid would invent
							 * implicit columns for it beside its placed sibling.
							 *
							 * The tablet split lands mid-column, so it is trimmed in
							 * _quote-slider.sass rather than spanned.
							 */
							?>
							<figure class="quote-slider__box border-2 border-brand-red flex flex-col gap-[60px] px-7 py-[21px] min-h-[396px] md:row-start-1 md:gap-0 md:justify-between md:px-10 md:py-8 md:min-h-[376px] xl:px-14 xl:py-[38px] xl:min-h-[553px] col-start-1 col-span-2 <?php echo $quote_has_image ? 'row-start-2 md:col-start-1 md:col-span-4 xl:col-start-3 xl:col-span-5' : 'row-start-1 quote-slider__box--centered md:col-start-1 md:col-span-6 xl:col-start-1 xl:col-span-12'; ?>">

								<?php if ( get_sub_field( 'quote' ) ) : ?>
									<blockquote class="quote-slider__text text-brand-red">
										<?php echo wp_kses_post( get_sub_field( 'quote' ) ); ?>
									</blockquote>
								<?php endif; ?>

								<?php if ( get_sub_field( 'author' ) || get_sub_field( 'role' ) ) : ?>
									<?php // From md the box is justify-between, so the margin is only a minimum. ?>
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
								<?php // From md it stretches to the box's height. ?>
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
			 * Inside .swiper and a sibling of .swiper-wrapper, not a layer over the whole
			 * section: .swiper is already position: relative, so inset-0 is the slider's own
			 * box. Over the section it was the slider plus the bullets below it, and
			 * items-center put the arrows well under the card.
			 *
			 * Clicks pass through everywhere except on the buttons themselves.
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
		 * Outside .swiper and handed to Swiper explicitly, so its absolute positioning
		 * rules for a nested pagination never apply.
		 *
		 * The arrows are the other control on the same Swiper, hidden with CSS below xl
		 * rather than left out, so Swiper keeps them in sync and nothing has to be rebuilt
		 * at a breakpoint.
		 */
		?>
	<div class="theme-container">
		<div class="quote-slider__pagination swiper-pagination js-quote-pagination"></div>
	</div>


	<?php endif; ?>

</section>
