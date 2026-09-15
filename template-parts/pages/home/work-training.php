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
<section id="section-work-training" class="section-work-training mb-28 md:mb-36 xl:mb-48">
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
					<div class="section-work-training__grid theme-grid">
						<?php
						// Wrapper spans the middle 10 cols on desktop; inside, the buttons
						// split into equal columns (1-up mobile, 2-up tablet, 4-up desktop)
						// with a constant 25px gap. The tablet arrangement (2-up) now runs all
						// the way to a custom 1800px instead of switching at xl — Tailwind's
						// arbitrary-value variant (min-[1800px]:) rather than a plain @media
						// block in the SASS, so it hoists and sorts alongside the md: classes
						// it needs to beat instead of losing to them — see _pages/_home.sass
						// for the matching .theme-grid column-count override this needs.
						?>
						<div class="section-work-training__list col-span-2 md:col-span-6 min-[1800px]:col-start-2 min-[1800px]:col-span-10 grid grid-cols-1 md:grid-cols-2 min-[1800px]:grid-cols-4 gap-[25px] mt-8 md:mt-14 min-[1800px]:mt-24">
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
