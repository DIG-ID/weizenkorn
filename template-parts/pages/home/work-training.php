<?php
/**
 * Home — Work & Training section ("Arbeiten. Lernen. Ankommen.").
 * Intro + job teasers (Offene Stellen / Begleitete Ausbildungsplätze / Infos
 * für Zuweisende) linking to the Open Positions page.
 *
 * ACF fields (flat, prefixed):
 *   home_work_overline (text)
 *   home_work_title    (text)
 *   home_work_body     (textarea / wysiwyg)
 *   home_work_image    (image → return ID)
 *   home_work_teasers  (repeater) → title (text), text (textarea), link (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

$overline      = get_field( 'home_work_overline' );
$section_title = get_field( 'home_work_title' );
$body          = get_field( 'home_work_body' );
$img_id        = get_field( 'home_work_image' );
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
						'overline'    => $overline ? $overline : 'ARBEITEN & AUSBILDUNG',
						'title'       => $section_title ? $section_title : 'Arbeiten. Lernen. Ankommen.',
						'tag'         => 'h2',
						'title_class' => 'title-main',
					)
				);
				?>

				<?php if ( $body ) : ?>
					<div class="body-text section-work-training__body"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>

				<?php if ( have_rows( 'home_work_teasers' ) ) : ?>
					<ul class="section-work-training__teasers">
						<?php
						while ( have_rows( 'home_work_teasers' ) ) :
							the_row();
							$teaser_title = get_sub_field( 'title' );
							$teaser_text  = get_sub_field( 'text' );
							$teaser_link  = get_sub_field( 'link' );
							$teaser_url   = ( $teaser_link && ! empty( $teaser_link['url'] ) ) ? $teaser_link['url'] : '';
							?>
							<li class="section-work-training__teaser">
								<?php if ( $teaser_url ) : ?>
									<a href="<?php echo esc_url( $teaser_url ); ?>">
								<?php endif; ?>
									<span class="title-card"><?php echo esc_html( $teaser_title ); ?></span>
									<?php if ( $teaser_text ) : ?>
										<span class="body-text"><?php echo esc_html( $teaser_text ); ?></span>
									<?php endif; ?>
								<?php if ( $teaser_url ) : ?>
									</a>
								<?php endif; ?>
							</li>
							<?php
						endwhile;
						?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="col-span-2 md:col-span-3 xl:col-span-6 section-work-training__media overflow-hidden">
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
