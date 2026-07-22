<?php
/**
 * Home — Hero section ("Breit aufgestellt. Tief verwurzelt.").
 * Split hero-card: red title + intro (lead + body) + "Mehr erfahren" + image.
 * ACF group suggestion: `home_hero`
 *   { title, tagline, body, button, image }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

$hero       = get_field( 'home_hero' );
$hero_title = ( $hero && ! empty( $hero['title'] ) ) ? $hero['title'] : '';
$tagline    = ( $hero && ! empty( $hero['tagline'] ) ) ? $hero['tagline'] : '';
$body       = ( $hero && ! empty( $hero['body'] ) ) ? $hero['body'] : '';
$button     = ( $hero && ! empty( $hero['button'] ) ) ? $hero['button'] : false;
$img_id     = ( $hero && ! empty( $hero['image'] ) ) ? $hero['image'] : 0;
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
