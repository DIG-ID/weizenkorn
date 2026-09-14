<?php
/**
 * Supported Apprenticeships — "Unsere Ausbildung" section (Figma desktop
 * node 4018:7957). A 2×2 bento of programme cards at desktop, stacked
 * single column at tablet/mobile (confirmed against the tablet frame) —
 * each a photo with a cream caption band overlapping its bottom edge: a
 * title, and a paragraph that reveals on hover rather than always
 * showing, including the caption's own position (absolute, over the
 * photo, so the card's height never changes when the text appears): a
 * grid track animates from 0fr to 1fr, which transitions where
 * height: auto cannot.
 *
 * Below xl there is no reliable hover, so the card gets an explicit +/−
 * tap target instead — same mechanism as template-parts/components/card-preview.php
 * (assets/js/card-program.js toggles an `is-open` class plus
 * aria-expanded; _pages/_supported-apprenticeships.sass opens the same
 * grid-rows reveal off either :hover/:focus-within at xl or .is-open
 * below it).
 *
 * Figma's own bento is asymmetric — a taller caption band and a slightly
 * shorter photo on the one card with both title and text — but a plain
 * content-driven caption height reads the same without hard-coding two
 * different image ratios into one grid.
 *
 * ACF fields (flat, prefixed):
 *   apprenticeships_programs_title (text)
 *   apprenticeships_programs_items (repeater) → image (image, ID),
 *                                   title (text), text (textarea /
 *                                   wpautop, optional)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.9.0
 */

$app_title = get_field( 'apprenticeships_programs_title' );

if ( ! $app_title || ! have_rows( 'apprenticeships_programs_items' ) ) {
	return;
}
?>
<section class="section-programs mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $app_title ) ); ?>

		<div class="theme-grid mt-8 md:mt-14 xl:mt-16 gap-y-5">
			<?php
			while ( have_rows( 'apprenticeships_programs_items' ) ) :
				the_row();

				if ( ! get_sub_field( 'image' ) ) {
					continue;
				}
				?>
				<?php $program_text = get_sub_field( 'text' ); ?>
				<div class="card-program js-card-program col-span-2 md:col-span-6 xl:col-span-6">
					<div class="card-program__media">
						<?php
						echo wp_get_attachment_image(
							get_sub_field( 'image' ),
							'large',
							false,
							array(
								'class'   => 'w-full h-full object-cover',
								'loading' => 'lazy',
							)
						);
						?>

						<?php if ( get_sub_field( 'title' ) ) : ?>
							<div class="card-program__caption bg-brand-cream">
								<?php if ( $program_text ) : ?>
									<?php $program_reveal_id = wp_unique_id( 'card-program-text-' ); ?>
									<div class="card-program__head">
										<h3 class="title-card card-program__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>

										<?php
										// xl:hidden — desktop opens on hover/focus-within instead, same as
										// card-preview.php's own toggle button.
										?>
										<button
											type="button"
											class="card-program__toggle js-card-program-toggle xl:hidden"
											aria-expanded="false"
											aria-controls="<?php echo esc_attr( $program_reveal_id ); ?>"
										>
											<span class="sr-only"><?php esc_html_e( 'Toggle description', 'weizenkorn' ); ?></span>
											<?php weizenkorn_the_svg_icon( 'toggle' ); ?>
										</button>
									</div>

									<div class="card-program__text" id="<?php echo esc_attr( $program_reveal_id ); ?>">
										<div class="card-program__text-inner body-text"><?php echo wp_kses_post( $program_text ); ?></div>
									</div>
								<?php else : ?>
									<h3 class="title-card"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
