<?php
/**
 * Home — Products section ("Schönes mit Sinn" / PRODUKTE).
 * Section heading (reusable "Section Title" clone) + grid of product-range cards.
 *
 * ACF structure (group "products"):
 *   section_title (clone → "Section Title" group; fed to the section-heading
 *                  component, which renders subtitle, title, descriptions and
 *                  the CTA buttons)
 *   ranges        (repeater) → image (image, ID), title (text),
 *                                   text (textarea), page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

?>
<section class="section-products mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php if ( have_rows( 'products' ) ) : ?>
			<?php
			while ( have_rows( 'products' ) ) :
				the_row();
				?>
				<?php if ( get_sub_field( 'section_title' ) ) : ?>
					<?php get_template_part( 'template-parts/components/section-heading', null, get_sub_field( 'section_title' ) ); ?>
				<?php endif; ?>

				<?php if ( have_rows( 'ranges' ) ) : ?>
					<?php
					// Bespoke card flow (see _home.sass): mobile 1-up, tablet 2-up,
					// desktop row 1 = three 1/3 cards + row 2 = 40% / 60% (5-card design).
					// The page grid stays 12-col; only this flow is custom.
					?>
					<div class="theme-grid">
						<div class="section-products__grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 mt-8 md:mt-14 xl:mt-24">
							<?php
							while ( have_rows( 'ranges' ) ) :
								the_row();
								$range_link   = get_sub_field( 'page' );
								$range_url    = ( is_array( $range_link ) && ! empty( $range_link['url'] ) ) ? $range_link['url'] : '';
								$range_target = ( is_array( $range_link ) && ! empty( $range_link['target'] ) ) ? $range_link['target'] : '';
								$range_tag    = $range_url ? 'a' : 'div';
								?>
								<<?php echo esc_html( $range_tag ); ?> class="card-range"<?php echo $range_url ? ' href="' . esc_url( $range_url ) . '"' : ''; ?><?php echo ( '_blank' === $range_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
									<?php if ( get_sub_field( 'image' ) ) : ?>
										<figure class="card__media">
											<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'large', false, array( 'loading' => 'lazy' ) ); ?>
										</figure>
									<?php endif; ?>

									<div class="card__body">
										<div class="card__head">
											<h3 class="title-card card__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
											<?php if ( $range_url ) : ?>
												<span class="card__arrow" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
											<?php endif; ?>
										</div>

										<?php if ( get_sub_field( 'text' ) ) : ?>
											<div class="body-text card__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
										<?php endif; ?>
									</div>
								</<?php echo esc_html( $range_tag ); ?>>
								<?php
							endwhile;
							?>
						</div>
					</div>
				<?php endif; ?>
				<?php
			endwhile;
			?>
		<?php endif; ?>
	</div>
</section>
