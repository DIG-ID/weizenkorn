<?php
/**
 * Gastronomie und Hotellerie — venues section.
 *
 * The same structure and behaviour as template-parts/pages/home/gastronomy.php: a section
 * heading and the venues in two forms — a slider pairing image and card per slide at
 * mobile, and from tablet up a bento of images with name bars over a row of info cards.
 * Not a module, being specific to this page, but kept identical on purpose so the two are
 * easy to maintain side by side.
 *
 * Unlike the Home version the heading is not a "Section Title" clone — that shared group's
 * internal key is not reachable from outside the admin — so it is three flat fields and
 * $args built by hand, the same as services-overview.php.
 *
 * ACF fields (flat, prefixed):
 *   gastronomy_section_title    (text)
 *   gastronomy_section_subtitle (text)
 *   gastronomy_section_text     (textarea / wpautop)
 *   gastronomy_items            (repeater, up to 5) → image (image, ID), title (text),
 *                                                     logo (image, ID), text (textarea),
 *                                                     page (link)
 *
 * The repeater order drives the images bento: 1 Rhyvage · 2 Cantina · 3 Seminare & Events
 * · 4 Bäckerei · 5 DASBREITEHOTEL. Three across the top row, two across the bottom, each
 * image sized by a consistent aspect ratio rather than hand-picked row heights.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.5.0
 */

$gastro_title = get_field( 'gastronomy_section_title' );

if ( ! $gastro_title ) {
	return;
}

// Bento placement per repeater index. Three equal columns on top, two wider ones below;
// no image spans multiple rows, so every cell is a plain col-span.
$gastro_bento = array(
	1 => 'md:col-start-1 md:col-span-2 md:row-start-1 xl:col-start-1 xl:col-span-4 xl:row-start-1',
	2 => 'md:col-start-3 md:col-span-2 md:row-start-1 xl:col-start-5 xl:col-span-4 xl:row-start-1',
	3 => 'md:col-start-5 md:col-span-2 md:row-start-1 xl:col-start-9 xl:col-span-4 xl:row-start-1',
	4 => 'md:col-start-1 md:col-span-3 md:row-start-2 xl:col-start-1 xl:col-span-6 xl:row-start-2',
	5 => 'md:col-start-4 md:col-span-3 md:row-start-2 xl:col-start-7 xl:col-span-6 xl:row-start-2',
);

// The info-cards row order differs from the bento and is set with CSS order, the
// repeater staying in image order.
$gastro_card_order = array(
	1 => 'order-2',
	2 => 'order-3',
	3 => 'order-5',
	4 => 'order-4',
	5 => 'order-1',
);
?>
<section class="section-gastronomy mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'title'             => $gastro_title,
				'subtitle'          => get_field( 'gastronomy_section_subtitle' ),
				'description'       => 'right',
				'description_right' => get_field( 'gastronomy_section_text' ),
			)
		);
		?>

		<?php // ---------- Mobile: slider (image + card paired per slide) ---------- ?>
		<?php if ( have_rows( 'gastronomy_items' ) ) : ?>
			<div class="section-gastronomy__slider js-gastronomy-slider swiper mt-8 md:hidden">
				<div class="swiper-wrapper">
					<?php
					while ( have_rows( 'gastronomy_items' ) ) :
						the_row();
						$g_link   = get_sub_field( 'page' );
						$g_url    = ( is_array( $g_link ) && ! empty( $g_link['url'] ) ) ? $g_link['url'] : '';
						$g_target = ( is_array( $g_link ) && ! empty( $g_link['target'] ) ) ? $g_link['target'] : '';
						?>
						<div class="swiper-slide">
							<?php if ( get_sub_field( 'image' ) ) : ?>
								<figure class="section-gastronomy__img overflow-hidden">
									<?php
									echo wp_get_attachment_image(
										get_sub_field( 'image' ),
										'large',
										false,
										array(
											'class'   => 'w-full h-[182px] object-cover',
											'loading' => 'lazy',
										)
									);
									?>
								</figure>
							<?php endif; ?>
							<<?php echo $g_url ? 'a' : 'div'; ?> class="card-venue"<?php echo $g_url ? ' href="' . esc_url( $g_url ) . '"' : ''; ?><?php echo ( '_blank' === $g_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
								<div class="card__head">
									<?php if ( get_sub_field( 'logo' ) ) : ?>
										<span class="card__logo"><?php echo wp_get_attachment_image( get_sub_field( 'logo' ), 'medium', false, array( 'loading' => 'lazy' ) ); ?></span>
									<?php elseif ( get_sub_field( 'title' ) ) : ?>
										<?php // No logo set — same text fallback as the tablet/desktop cards row below, styled the same fixed way rather than the responsive .title-card scale. ?>
										<span class="card__logo card__logo--text font-primary font-bold text-[20px] leading-[30px] tracking-[0.5px]"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
									<?php endif; ?>
									<?php if ( $g_url ) : ?>
										<span class="card__arrow" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
									<?php endif; ?>
								</div>
								<?php if ( get_sub_field( 'text' ) ) : ?>
									<div class="body-text card__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
								<?php endif; ?>
							</<?php echo $g_url ? 'a' : 'div'; ?>>
						</div>
						<?php
					endwhile;
					?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		<?php endif; ?>

		<?php // ---------- Tablet+: images bento (top) + info cards row (bottom) ---------- ?>
		<div class="hidden md:block mt-14 xl:mt-24">
			<?php if ( have_rows( 'gastronomy_items' ) ) : ?>
				<?php
				// gap-y-5 because .theme-grid carries no row gap on purpose — vertical spacing is each
				// section's own.
				?>
				<div class="section-gastronomy__images theme-grid gap-y-5">
					<?php
					while ( have_rows( 'gastronomy_items' ) ) :
						the_row();
						$g_idx  = get_row_index();
						$g_cols = isset( $gastro_bento[ $g_idx ] ) ? $gastro_bento[ $g_idx ] : '';
						?>
						<?php
						// xl:max-h caps the bottom row, whose wider span would otherwise let the aspect ratio
						// grow the height past the cap.
						//
						// No min-width here on purpose: an explicit min-width on a 1fr track item feeds into
						// the track-sizing algorithm, and the top row's narrower items need more per track than
						// the bottom row's, so every track ended up sized to the stronger constraint —
						// flattening the intended split into five equal tiles.
						//
						// w-full because once max-height caps the height, the browser is allowed to resolve
						// width FROM that height instead of from the grid column, which produced a box narrower
						// than its real column and undid the split visually. An explicit width keeps width tied
						// to the column and leaves only height for the aspect ratio to resolve.
						?>
						<div class="section-gastronomy__img relative overflow-hidden aspect-[3/2] w-full xl:max-h-[384px] <?php echo esc_attr( $g_cols ); ?>">
							<?php if ( get_sub_field( 'image' ) ) : ?>
								<?php
								echo wp_get_attachment_image(
									get_sub_field( 'image' ),
									'large',
									false,
									array(
										'class'   => 'absolute inset-0 w-full h-full object-cover',
										'loading' => 'lazy',
									)
								);
								?>
							<?php endif; ?>
							<span class="section-gastronomy__name absolute inset-x-0 bottom-0 bg-brand-cream px-4 py-2 title-card"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
						</div>
						<?php
					endwhile;
					?>
				</div>

				<div class="section-gastronomy__cards grid md:grid-cols-2 xl:grid-cols-5 gap-5 mt-5">
					<?php
					while ( have_rows( 'gastronomy_items' ) ) :
						the_row();
						$c_link   = get_sub_field( 'page' );
						$c_url    = ( is_array( $c_link ) && ! empty( $c_link['url'] ) ) ? $c_link['url'] : '';
						$c_target = ( is_array( $c_link ) && ! empty( $c_link['target'] ) ) ? $c_link['target'] : '';
						?>
						<<?php echo $c_url ? 'a' : 'div'; ?> class="card-venue <?php echo esc_attr( isset( $gastro_card_order[ get_row_index() ] ) ? $gastro_card_order[ get_row_index() ] : '' ); ?>"<?php echo $c_url ? ' href="' . esc_url( $c_url ) . '"' : ''; ?><?php echo ( '_blank' === $c_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<div class="card__head">
								<?php if ( get_sub_field( 'logo' ) ) : ?>
									<span class="card__logo"><?php echo wp_get_attachment_image( get_sub_field( 'logo' ), 'medium', false, array( 'loading' => 'lazy' ) ); ?></span>
								<?php elseif ( get_sub_field( 'title' ) ) : ?>
									<?php // No logo set — fall back to the venue's own name/title, styled to spec (font-bold/20px/30px/0.5px) rather than the responsive .title-card scale, since it's meant to read the same at every breakpoint here. ?>
									<span class="card__logo card__logo--text font-primary font-bold text-[20px] leading-[30px] tracking-[0.5px]"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
								<?php endif; ?>
								<?php if ( $c_url ) : ?>
									<span class="card__arrow" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
								<?php endif; ?>
							</div>
							<?php if ( get_sub_field( 'text' ) ) : ?>
								<div class="body-text card__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
							<?php endif; ?>
						</<?php echo $c_url ? 'a' : 'div'; ?>>
						<?php
					endwhile;
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
