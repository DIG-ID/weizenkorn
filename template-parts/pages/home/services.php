<?php
/**
 * Home — Services section ("Massgeschneidert für Sie" / DIENSTLEISTUNGEN).
 * Section heading (reusable "Section Title" clone) + grid of service cards.
 *
 * ACF structure (group "services"):
 *   section_title (clone → "Section Title" group; fed to the section-heading
 *                  component — subtitle, title, descriptions and CTA buttons)
 *   items         (repeater) → image (image, ID), title (text),
 *                                   text (textarea), page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

?>
<section class="section-services my-24 md:my-32 xl:my-48">
	<div class="theme-container">
		<?php if ( have_rows( 'services' ) ) : ?>
			<?php
			while ( have_rows( 'services' ) ) :
				the_row();
				?>
				<?php if ( get_sub_field( 'section_title' ) ) : ?>
					<?php get_template_part( 'template-parts/components/section-heading', null, get_sub_field( 'section_title' ) ); ?>
				<?php endif; ?>

				<?php if ( have_rows( 'items' ) ) : ?>
					<div class="theme-grid">
						<div class="theme-grid section-services__grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 mt-8 md:mt-14 xl:mt-24 gap-8 md:gap-5">
							<?php
							while ( have_rows( 'items' ) ) :
								the_row();
								$service_link   = get_sub_field( 'page' );
								$service_url    = ( is_array( $service_link ) && ! empty( $service_link['url'] ) ) ? $service_link['url'] : '';
								$service_target = ( is_array( $service_link ) && ! empty( $service_link['target'] ) ) ? $service_link['target'] : '';
								$service_tag    = $service_url ? 'a' : 'div';
								?>
								<div class="col-span-2 md:col-span-2 xl:col-span-4">
									<?php
									// Card layout differs by breakpoint:
									// - mobile/tablet: image (fixed height) in flow, cream panel
									// pulled up over the image (−mt) and extending below it,
									// always showing the description + "mehr".
									// - desktop (xl): image fills a fixed-ratio card; the cream
									// panel is an absolute overlay whose text is collapsed and
									// reveals on hover (rectangle grows taller).
									?>
									<<?php echo esc_html( $service_tag ); ?> class="card-service"<?php echo $service_url ? ' href="' . esc_url( $service_url ) . '"' : ''; ?><?php echo ( '_blank' === $service_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
										<?php if ( get_sub_field( 'image' ) ) : ?>
											<?php
											echo wp_get_attachment_image(
												get_sub_field( 'image' ),
												'large',
												false,
												array(
													'class'   => 'card__media',
													'loading' => 'lazy',
												)
											);
											?>
										<?php endif; ?>

										<div class="card__panel">
											<div class="card__text-group">
												<h3 class="title-card card__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>

												<?php if ( get_sub_field( 'text' ) ) : ?>
													<div class="card__reveal">
														<div>
															<div class="body-text card__text pt-2"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
														</div>
													</div>
												<?php endif; ?>
											</div>

											<?php if ( $service_url ) : ?>
												<div class="card__reveal">
													<div>
														<span class="body-text link-inline card__more"><?php echo esc_html__( 'mehr', 'weizenkorn' ); ?></span>
													</div>
												</div>
											<?php endif; ?>
										</div>
									</<?php echo esc_html( $service_tag ); ?>>
								</div>
								<?php
							endwhile;
							?>
						</div>
					</div>
				<?php endif; ?>
				<?php
			endwhile;
			?>
		<?php endif; ?>
	</div>
</section>
