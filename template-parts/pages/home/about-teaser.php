<?php
/**
 * Home — About teaser ("Lerne uns kennen" / Über uns).
 * Short intro about the foundation + image + link to the About Us page.
 *
 * ACF fields (flat, prefixed):
 *   home_about_overline (text)
 *   home_about_title    (text)
 *   home_about_body     (textarea / wysiwyg)
 *   home_about_image    (image → return ID)
 *   home_about_link     (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$overline      = get_field( 'home_about_overline' );
$section_title = get_field( 'home_about_title' );
$body          = get_field( 'home_about_body' );
$img_id        = get_field( 'home_about_image' );
$cta_link      = get_field( 'home_about_link' );
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
						'overline'    => $overline ? $overline : 'ÜBER UNS',
						'title'       => $section_title ? $section_title : 'Lerne uns kennen',
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
				if ( $img_id ) {
					echo wp_get_attachment_image(
						$img_id,
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
