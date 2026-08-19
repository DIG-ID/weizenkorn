<?php
/**
 * Gastronomie und Hotellerie — venues section. Same structure/behaviour as
 * template-parts/pages/home/gastronomy.php (section heading + venues in two
 * forms: mobile Swiper slider pairing image+card per slide, tablet+ a bento
 * of images with name bars on top and a row of info cards below) — not a
 * module, since it's specific to this one page, but kept identical on
 * purpose so both are easy to maintain side by side.
 *
 * Unlike the Home version, the heading isn't a "Section Title" clone (no
 * access to that shared group's internal key from outside the admin) — same
 * approach as services-overview.php: 3 flat fields, $args built manually.
 *
 * ACF fields (flat, prefixed):
 *   gastronomy_section_title    (text)
 *   gastronomy_section_subtitle (text)
 *   gastronomy_section_text     (textarea / wpautop)
 *   gastronomy_items            (repeater) → image (image, ID), title (text),
 *                                            logo (image, ID), text (textarea),
 *                                            page (link)
 *   Repeater order drives the images bento: 1 Rhyvage · 2 Cantina · 3 Hotel · 4 Bäckerei.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.5.0
 */

$gastro_title = get_field( 'gastronomy_section_title' );

if ( ! $gastro_title ) {
	return;
}

// Images bento placement per repeater index (tablet 6-col · desktop 12-col).
$gastro_bento = array(
	1 => 'md:col-start-1 md:col-span-3 md:row-start-1 xl:col-start-1 xl:col-span-3 xl:row-start-1',
	2 => 'md:col-start-1 md:col-span-3 md:row-start-2 xl:col-start-4 xl:col-span-3 xl:row-start-1',
	3 => 'md:col-start-4 md:col-span-3 md:row-start-1 md:row-span-2 xl:col-start-7 xl:col-span-6 xl:row-start-1 xl:row-span-2',
	4 => 'md:col-start-1 md:col-span-6 md:row-start-3 xl:col-start-1 xl:col-span-6 xl:row-start-2',
);

// Info-cards row order (differs from the bento) via CSS order — repeater stays
// in image order: 1 Rhyvage → 2nd · 2 Cantina → 3rd · 3 Hotel → 1st · 4 Bäckerei → 4th.
$gastro_card_order = array(
	1 => 'order-2',
	2 => 'order-3',
	3 => 'order-1',
	4 => 'order-4',
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
				<div class="section-gastronomy__images theme-grid">
					<?php
					while ( have_rows( 'gastronomy_items' ) ) :
						the_row();
						$g_idx  = get_row_index();
						$g_cols = isset( $gastro_bento[ $g_idx ] ) ? $gastro_bento[ $g_idx ] : '';
						?>
						<div class="section-gastronomy__img relative overflow-hidden <?php echo esc_attr( $g_cols ); ?>">
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

				<div class="section-gastronomy__cards grid md:grid-cols-2 xl:grid-cols-4 gap-5 mt-5">
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
