<?php
/**
 * Work & Training — "Arbeitsvielfalt bei Weizenkorn" section (Figma "Frame
 * 1000006300" desktop / "Frame 1000006310" tablet / "Frame 1000006312"
 * mobile). One large photo per slide, a caption bar overlapping its bottom
 * edge (category + title), pagination bullets below — no arrows at any
 * breakpoint, the design never shows any. Page-specific: no other page uses
 * this slide shape (image height 256/360/767px) yet.
 *
 * The caption's "Inria Sans" font in Figma is a one-off outside the site's
 * single-typeface rule (CLAUDE.md) — approximated with italic DM Sans
 * instead of loading a second font family for one small label.
 *
 * ACF fields (flat, prefixed):
 *   work_training_diversity_title (text)
 *   work_training_diversity_items (repeater) → image (image, ID),
 *                                  category (text), title (text)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.7.0
 */

$wtd_title = get_field( 'work_training_diversity_title' );

if ( ! $wtd_title || ! have_rows( 'work_training_diversity_items' ) ) {
	return;
}
?>
<section class="section-diversity-slider mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $wtd_title ) ); ?>

		<div class="mt-8 md:mt-14 xl:mt-24">
			<div class="swiper js-diversity-slider">
				<div class="swiper-wrapper">
					<?php
					while ( have_rows( 'work_training_diversity_items' ) ) :
						the_row();

						if ( ! get_sub_field( 'image' ) ) {
							continue;
						}
						?>
						<div class="swiper-slide">
							<figure class="section-diversity-slider__slide relative overflow-hidden">
								<?php
								echo wp_get_attachment_image(
									get_sub_field( 'image' ),
									'large',
									false,
									array(
										'class'   => 'w-full h-[256px] md:h-[360px] xl:h-[767px] object-cover',
										'loading' => 'lazy',
									)
								);
								?>

								<?php if ( get_sub_field( 'category' ) || get_sub_field( 'title' ) ) : ?>
									<figcaption class="section-diversity-slider__caption absolute bottom-0 left-0 w-full">
										<?php if ( get_sub_field( 'category' ) ) : ?>
											<span class="uppercase text-brand-dark"><?php echo esc_html( get_sub_field( 'category' ) ); ?></span>
										<?php endif; ?>
										<?php if ( get_sub_field( 'title' ) ) : ?>
											<span><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
										<?php endif; ?>
									</figcaption>
								<?php endif; ?>
							</figure>
						</div>
						<?php
					endwhile;
					?>
				</div>
			</div>

			<div class="section-diversity-slider__pagination swiper-pagination js-diversity-pagination"></div>
		</div>
	</div>
</section>
