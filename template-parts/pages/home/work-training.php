<?php
/**
 * Home — Work & Training band.
 * Section heading (reusable "Section Title" clone) + a grid of page-link buttons.
 *
 * ACF structure (group "work_training"):
 *   section_title (clone → "Section Title" group; fed to the section-heading)
 *   items         (repeater) → page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

?>
<section class="section-work-training mb-28 md:mb-36 xl:mb-48">
	<div class="theme-container">
		<?php if ( have_rows( 'work_training' ) ) : ?>
			<?php
			while ( have_rows( 'work_training' ) ) :
				the_row();
				?>
				<?php if ( get_sub_field( 'section_title' ) ) : ?>
					<?php get_template_part( 'template-parts/components/section-heading', null, get_sub_field( 'section_title' ) ); ?>
				<?php endif; ?>

				<?php if ( have_rows( 'items' ) ) : ?>
					<div class="theme-grid">
						<?php
						// Wrapper spans the middle 10 cols on desktop; inside, the buttons
						// split into equal columns (1-up mobile, 2-up tablet, 4-up desktop)
						// with a constant 25px gap.
						?>
						<div class="section-work-training__list col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mt-8 md:mt-14 xl:mt-24">
							<?php
							while ( have_rows( 'items' ) ) :
								the_row();
								$wt_button = get_sub_field( 'page' );

								if ( $wt_button ) {
									get_template_part(
										'template-parts/components/button',
										null,
										array_merge( $wt_button, array( 'style' => 'secondary' ) )
									);
								}
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
