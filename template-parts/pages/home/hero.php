<?php
/**
 * Home — Hero section ("Breit aufgestellt. Tief verwurzelt.").
 * Split hero-card: red title + intro (tagline + body) + "Mehr erfahren" + image.
 *
 * ACF fields (flat, prefixed):
 *   hero_title   (text)
 *   hero_tagline (text)
 *   hero_body    (textarea / wysiwyg)
 *   hero_button  (link)
 *   hero_image   (image → return ID)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

?>
<section class="section-hero pb-[60px] xl:pb-[120px]">
	<div class="theme-container">
		<div class="theme-grid items-stretch">

			<div class="col-span-2 md:col-span-3 xl:col-span-5 section-hero__content border-2 border-brand-dark order-2 md:order-none pt-4 xl:pt-16 px-4 xl:pl-16 ">
				<?php if ( get_field( 'hero_title' ) ) : ?>
					<div class="section-hero__title mb-12 md:mb-14 xl:mb-32">
						<h1 class="title-hero xl:max-w-2xl"><?php the_field( 'hero_title' ); ?></h1>
					</div>
				<?php endif; ?>

				<?php if ( get_field( 'hero_tagline' ) ) : ?>
					<div class="section-hero__tagline mb-5 xl:mb-4">
						<p class="title-tagline"><?php the_field( 'hero_tagline' ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( get_field( 'hero_body' ) ) : ?>
					<div class="section-hero__body mb-12 md:mb-24 xl:mb-16">
						<div class="body-text"><?php the_field( 'hero_body' ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( get_field( 'hero_button' ) ) : ?>
					<div class="section-hero__cta ">
								<?php
									$cta_button = get_field( 'hero_button' );

								if ( $cta_button ) {
									get_template_part(
										'template-parts/components/button',
										null,
										array_merge( $cta_button, array( 'style' => 'arrow-down' ) )
									);
								}
								?>
					</div>
				<?php endif; ?>
			</div>

			<div class="col-span-2 md:col-span-3 xl:col-span-7 section-hero__media overflow-hidden order-1 md:order-none">
				<?php
				if ( get_field( 'hero_image' ) ) {
					echo wp_get_attachment_image(
						get_field( 'hero_image' ),
						'full',
						false,
						array(
							'class'         => 'w-full h-full object-cover',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
						)
					);
				}
				?>
			</div>

		</div>
		<?php if ( get_field( 'hero_seperator_logo' ) ) : ?>
			<div class="section-hero__separator mt-24 md:mt-40 xl:mt-48 flex justify-center">
				<?php
				echo wp_get_attachment_image(
					get_field( 'hero_seperator_logo' ),
					'full',
					false,
					array(
						'class'   => 'max-w-[96px] md:max-w-[212px] xl:max-w-[244px] h-auto',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
