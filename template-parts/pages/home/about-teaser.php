<?php
/**
 * Home — About teaser ("Lerne uns kennen" / Über uns).
 * Short intro about the foundation + image + link to the About Us page.
 * ACF group suggestion: `home_about`
 *   { overline, title, body, image, link }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$data     = get_field( 'home_about' );
$body     = ( $data && ! empty( $data['body'] ) ) ? $data['body'] : '';
$image    = ( $data && ! empty( $data['image'] ) ) ? $data['image'] : 0;
$cta_link = ( $data && ! empty( $data['link'] ) ) ? $data['link'] : false;
?>
<section class="section-about-teaser">
	<div class="theme-container">
		<div class="theme-grid items-stretch">

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-about-teaser__content">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'overline'    => ( $data && ! empty( $data['overline'] ) ) ? $data['overline'] : 'ÜBER UNS',
						'title'       => ( $data && ! empty( $data['title'] ) ) ? $data['title'] : 'Lerne uns kennen',
						'tag'         => 'h2',
						'title_class' => 'title-main',
					)
				);
				?>

				<?php if ( $body ) : ?>
					<div class="body-text section-about-teaser__body"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>

				<?php
				if ( $cta_link ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge( $cta_link, array( 'style' => 'primary' ) )
					);
				}
				?>
			</div>

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-about-teaser__media overflow-hidden">
				<?php
				if ( $image ) {
					echo wp_get_attachment_image(
						$image,
						'full',
						false,
						array(
							'class'   => 'w-full h-full object-cover',
							'loading' => 'lazy',
						)
					);
				}
				?>
			</div>

		</div>
	</div>
</section>
