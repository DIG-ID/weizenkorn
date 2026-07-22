<?php
/**
 * Home — Hero section ("Breit aufgestellt. Tief verwurzelt.").
 * Split hero-card: red title + intro (tagline + body) + "Mehr erfahren" + image.
 *
 * ACF fields (flat, prefixed):
 *   home_hero_title   (text)
 *   home_hero_tagline (text)
 *   home_hero_body    (textarea / wysiwyg)
 *   home_hero_button  (link)
 *   home_hero_image   (image → return ID)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

$hero_title = get_field( 'home_hero_title' );
$tagline    = get_field( 'home_hero_tagline' );
$body       = get_field( 'home_hero_body' );
$button     = get_field( 'home_hero_button' );
$img_id     = get_field( 'home_hero_image' );
?>
<section class="section-hero">
	<div class="theme-container">
		<div class="theme-grid items-stretch">

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-hero__content">
				<?php if ( $hero_title ) : ?>
					<h1 class="title-hero section-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php endif; ?>

				<?php if ( $tagline ) : ?>
					<p class="title-card section-hero__tagline"><?php echo esc_html( $tagline ); ?></p>
				<?php endif; ?>

				<?php if ( $body ) : ?>
					<div class="body-text section-hero__body"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>

				<?php
				if ( $button ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge( $button, array( 'style' => 'arrow-down' ) )
					);
				}
				?>
			</div>

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-hero__media overflow-hidden">
				<?php
				if ( $img_id ) {
					echo wp_get_attachment_image(
						$img_id,
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
	</div>
</section>
