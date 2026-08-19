<?php
/**
 * Home — Services section ("Massgeschneidert für Sie" / DIENSTLEISTUNGEN).
 *
 * A section heading and a slider of service cards: three per view at desktop, one per
 * view below it.
 *
 * Layout — the same arrangement as modules/stories-references, deliberately rebuilt
 * here rather than shared with it: the two sections read different fields and the
 * module is in use on the product range pages, so it is left alone. The card is this
 * section's own `.card-service`: unlike the story card it puts its copy under the
 * image rather than in an overlay, and shows it always instead of on hover.
 *
 *   viewport  columns 3–10 at desktop, 2–5 at tablet, the full width at mobile.
 *   controls  no arrows anywhere, and no bullets at desktop, where the three cards
 *             fit one view and there is nothing to page through. Bullets carry the
 *             navigation at tablet and mobile.
 *
 *
 * ACF structure (group "services"):
 *   section_title (clone → "Section Title" group; fed to the section-heading
 *                  component — subtitle, title, descriptions and CTA buttons)
 *   items         (repeater) → image (image, ID), title (text),
 *                              text (textarea), page (link)
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
					<?php
					/*
					 * How many cards there are has to be known before the render loop
					 * starts, so the set is walked once to count it. Not with
					 * get_sub_field(): on a nested repeater that returns the raw meta —
					 * the row count as a string — so count() answers 1 whatever the
					 * number of rows, and the bullets never appeared.
					 *
					 * Running the loop to the end pops it off ACF's stack, so the render
					 * loop below starts fresh.
					 */
					$service_count = 0;

					while ( have_rows( 'items' ) ) {
						the_row();
						++$service_count;
					}
					?>
					<div class="section-services__row theme-grid mt-8 md:mt-14 xl:mt-24">
						<div class="section-services__viewport">
							<div class="swiper js-services-slider">
								<div class="swiper-wrapper">
									<?php
									while ( have_rows( 'items' ) ) :
										the_row();

										$service_link   = get_sub_field( 'page' );
										$service_url    = ( is_array( $service_link ) && ! empty( $service_link['url'] ) ) ? $service_link['url'] : '';
										$service_target = ( is_array( $service_link ) && ! empty( $service_link['target'] ) ) ? $service_link['target'] : '';

										// A card with a link is the link, so "mehr" inside it is a
										// span: an <a> inside an <a> is invalid and browsers unnest it.
										$service_tag = $service_url ? 'a' : 'article';
										?>
										<div class="swiper-slide">
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

												<?php
												/*
												 * Two breakpoint behaviours from one markup. Below desktop the
												 * panel sits under the image with everything visible, and the
												 * panels come out the same height because the slides do. At
												 * desktop the panel becomes an overlay on the image, collapsed
												 * to its title until the card is hovered — see _card.sass.
												 *
												 * That is what the __reveal wrappers are for: each is a grid
												 * whose single row animates from 0fr to 1fr, so the panel opens
												 * to exactly the height of whatever is inside instead of to a
												 * guessed max-height. They are open at every breakpoint except
												 * desktop, where the collapse lives.
												 */
												?>
												<div class="card__panel">
													<?php if ( get_sub_field( 'title' ) ) : ?>
														<h3 class="card__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
													<?php endif; ?>

													<?php
													/*
													 * The bare <div> inside each __reveal is NOT redundant.
													 *
													 * grid-template-rows: 0fr behaves as minmax(auto, 0fr), so the
													 * row never gets shorter than its item's automatic minimum.
													 * overflow: hidden on the item zeroes what its CONTENT
													 * contributes, but not its own padding or margin — and
													 * .card__text has mt-2 while .card__more has pt-4. Left as
													 * direct children, the collapsed rows stayed 8px and 16px tall,
													 * so a card with a link had a taller cream bar than one
													 * without, which is exactly the bug this wrapper fixes.
													 *
													 * The wrapper has no spacing of its own, so it collapses to
													 * nothing and the spacing rides inside it, clipped.
													 */
													?>
													<?php if ( get_sub_field( 'text' ) ) : ?>
														<div class="card__reveal">
															<div>
																<div class="card__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
															</div>
														</div>
													<?php endif; ?>

													<?php if ( $service_url ) : ?>
														<?php
														// --bottom carries the mt-auto that pushes "mehr" to the
														// panel's bottom edge below desktop, where the panel is
														// taller than its content.
														?>
														<div class="card__reveal card__reveal--bottom">
															<div>
																<span class="card__more"><?php echo esc_html__( 'mehr', 'weizenkorn' ); ?></span>
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
						</div>

						<?php if ( $service_count > 1 ) : ?>
							<?php
							/*
							 * A grid item in the row below the slider, so it spans the whole
							 * container and centres on it. Hidden from xl in the stylesheet.
							 */
							?>
							<div class="section-services__pagination js-services-pagination"></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php
			endwhile;
			?>
		<?php endif; ?>
	</div>
</section>
