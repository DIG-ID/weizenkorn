<?php
/**
 * Home — Work & Training section ("Arbeiten. Lernen. Ankommen." / ARBEITEN & AUSBILDUNG).
 * Intro + job teasers (Offene Stellen / Begleitete Ausbildungsplätze / Infos
 * für Zuweisende) linking to the Open Positions page.
 * ACF group suggestion: `home_work_training`
 *   { overline, title, body, image, teasers[] { title, text, link } }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$data    = get_field( 'home_work_training' );
$body    = ( $data && ! empty( $data['body'] ) ) ? $data['body'] : '';
$image   = ( $data && ! empty( $data['image'] ) ) ? $data['image'] : 0;
$teasers = ( $data && ! empty( $data['teasers'] ) ) ? $data['teasers'] : array();
?>
<section class="section-work-training">
	<div class="theme-container">
		<div class="theme-grid items-stretch">

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-work-training__content">
				<?php
				get_template_part(
					'template-parts/components/section-heading',
					null,
					array(
						'overline'    => ( $data && ! empty( $data['overline'] ) ) ? $data['overline'] : 'ARBEITEN & AUSBILDUNG',
						'title'       => ( $data && ! empty( $data['title'] ) ) ? $data['title'] : 'Arbeiten. Lernen. Ankommen.',
						'tag'         => 'h2',
						'title_class' => 'title-main',
					)
				);
				?>

				<?php if ( $body ) : ?>
					<div class="body-text section-work-training__body"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $teasers ) ) : ?>
					<ul class="section-work-training__teasers">
						<?php foreach ( $teasers as $teaser ) : ?>
							<?php
							$t_link = ! empty( $teaser['link'] ) ? $teaser['link'] : false;
							$t_url  = $t_link && ! empty( $t_link['url'] ) ? $t_link['url'] : '';
							?>
							<li class="section-work-training__teaser">
								<?php
								if ( $t_url ) :
									?>
									<a href="<?php echo esc_url( $t_url ); ?>"><?php endif; ?>
									<span class="title-card"><?php echo esc_html( ! empty( $teaser['title'] ) ? $teaser['title'] : '' ); ?></span>
									<?php if ( ! empty( $teaser['text'] ) ) : ?>
										<span class="body-text"><?php echo esc_html( $teaser['text'] ); ?></span>
									<?php endif; ?>
								<?php
								if ( $t_url ) :
									?>
									</a><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-work-training__media overflow-hidden">
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
