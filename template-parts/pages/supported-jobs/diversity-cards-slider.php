<?php
/**
 * Supported Jobs — "Arbeitsvielfalt bei Weizenkorn" section (Figma desktop
 * node 180:3610). A card carousel: two 747×650 cards per view at desktop
 * with arrows, one full-width card per view at tablet/mobile with bullets
 * below — the same viewport/nav/pagination pattern as
 * template-parts/modules/our-equipment.php (both controls always rendered,
 * CSS shows the one that matches the breakpoint).
 *
 * Distinct from template-parts/pages/work-training/diversity-slider.php,
 * which shares this section's name in Figma but not its shape: that one is
 * a single full-bleed photo per slide with no arrows; this one is a
 * two-per-view card grid with a dark overlay on each photo for caption
 * contrast.
 *
 * The caption's "Inria Sans" font in Figma is a one-off outside the site's
 * single-typeface rule (CLAUDE.md) — approximated with italic DM Sans,
 * matching the diversity-slider's own substitution.
 *
 * ACF fields (flat, prefixed):
 *   supported_jobs_diversity_title (text)
 *   supported_jobs_diversity_items (repeater) → image (image, ID),
 *                                   category (text), title (text)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.8.0
 */

$sjd_title = get_field( 'supported_jobs_diversity_title' );

if ( ! $sjd_title || ! have_rows( 'supported_jobs_diversity_items' ) ) {
	return;
}
?>
<section class="section-diversity-cards mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48 overflow-hidden">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $sjd_title ) ); ?>

		<div class="section-diversity-cards__row theme-grid mt-8 md:mt-14 xl:mt-16">

			<div class="section-diversity-cards__viewport">
				<div class="swiper js-diversity-cards-slider">
					<div class="swiper-wrapper">
						<?php
						while ( have_rows( 'supported_jobs_diversity_items' ) ) :
							the_row();

							if ( ! get_sub_field( 'image' ) ) {
								continue;
							}
							?>
							<div class="swiper-slide">
								<figure class="card-diversity relative overflow-hidden w-full m-0">
									<?php
									echo wp_get_attachment_image(
										get_sub_field( 'image' ),
										'large',
										false,
										array(
											'class'   => 'card-diversity__media w-full object-cover',
											'loading' => 'lazy',
										)
									);
									?>

									<span class="card-diversity__overlay absolute inset-0 bg-brand-dark/20" aria-hidden="true"></span>

									<?php if ( get_sub_field( 'category' ) || get_sub_field( 'title' ) ) : ?>
										<figcaption class="card-diversity__caption absolute bottom-0 left-0 w-full">
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
			</div>

			<div class="section-diversity-cards__nav">
				<button type="button" class="section-diversity-cards__arrow js-diversity-cards-prev" aria-label="<?php echo esc_attr_x( 'Previous', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>

				<button type="button" class="section-diversity-cards__arrow js-diversity-cards-next" aria-label="<?php echo esc_attr_x( 'Next', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>
			</div>

			<div class="section-diversity-cards__pagination swiper-pagination js-diversity-cards-pagination"></div>

		</div>
	</div>
</section>
