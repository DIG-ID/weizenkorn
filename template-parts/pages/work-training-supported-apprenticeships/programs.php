<?php
/**
 * Supported Apprenticeships — "Unsere Ausbildung" section (Figma desktop
 * node 4018:7957). A 2×2 bento of programme cards at desktop, stacked
 * single column at tablet/mobile (confirmed against the tablet frame) —
 * each a photo with a cream caption band overlapping its bottom edge: a
 * title, and a paragraph that reveals on hover rather than always
 * showing — same mechanic as template-parts/modules/process-steps.php,
 * including the caption's own position (absolute, over the photo, so the
 * card's height never changes when the text appears): a grid track
 * animates from 0fr to 1fr, which transitions where height: auto cannot,
 * gated behind the hover media feature since that state is unreachable by
 * touch — there the text simply shows.
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
				<div class="card-program col-span-2 md:col-span-6 xl:col-span-6">
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
								<h3 class="title-card"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>

								<?php if ( get_sub_field( 'text' ) ) : ?>
									<div class="card-program__text">
										<div class="card-program__text-inner body-text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
									</div>
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
