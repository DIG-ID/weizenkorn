<?php
/**
 * Home — About teaser.
 *
 * The section-heading component for the full-width title and red rule, then a two-column
 * row: intro (overline, body, CTA) on the left and the image on the right.
 *
 * ACF fields (flat, prefixed):
 *   about_subtitle (text)
 *   about_title    (text)
 *   about_body     (textarea)
 *   about_image    (image → return ID)
 *   about_link     (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

?>
<section class="section-about-teaser mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		// Title + full-width rule only (the component skips its own row here).
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'title_heading' => 'h2',
				'title'         => get_field( 'about_title' ) ? get_field( 'about_title' ) : 'Lerne uns kennen',
			)
		);
		?>

		<div class="section-about-teaser__row theme-grid">

			<div class="section-about-teaser__content col-span-2 md:col-span-3 xl:col-start-2 xl:col-span-4">
				<?php if ( get_field( 'about_subtitle' ) ) : ?>
					<p class="uppercase mb-6 xl:mb-8"><?php the_field( 'about_subtitle' ); ?></p>
				<?php endif; ?>

				<?php if ( get_field( 'about_body' ) ) : ?>
					<div class="body-text section-about-teaser__body mb-8 xl:mb-8"><?php echo wp_kses_post( get_field( 'about_body' ) ); ?></div>
				<?php endif; ?>

				<?php
				$about_link = get_field( 'about_link' );
				if ( $about_link ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge( $about_link, array( 'style' => 'primary' ) )
					);
				}
				?>
			</div>

			<div class="section-about-teaser__media col-span-2 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5 overflow-hidden">
				<?php if ( get_field( 'about_image' ) ) : ?>
					<?php
					echo wp_get_attachment_image(
						get_field( 'about_image' ),
						'full',
						false,
						array(
							'class'   => 'w-full h-auto object-cover md:min-h-[393px]',
							'loading' => 'lazy',
						)
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
