<?php
/**
 * For Social Offices & Partners — "Angebote im Überblick" section. A page-specific fork
 * of template-parts/modules/offer-links.php rather than a new arg on that shared module:
 * this page's own Figma frame narrows the desktop text column to the grid's own LAST 4
 * columns (xl:col-start-9/col-span-4, reaching column 12) instead of the shared module's
 * 5 (xl:col-start-7/col-span-5), leaving columns 7-8 an empty gutter between the buttons
 * and the text — every other caller (About Us' "Spenden", the Open Positions archive's
 * "Weizenkorn mitgestalten") keeps the standard 5+5 split, so the shared module is left
 * untouched. The buttons column itself is unchanged (xl:col-start-2/col-span-5).
 *
 * Below xl this reads exactly like the shared module (buttons stacked/side-by-side per
 * the same md:/mobile classes, text below/opposite at the same width) — only at xl do the
 * two buttons themselves also leave the shared module's shape: rather than one flex row
 * sharing a 5-column box, each is its OWN grid item with an explicit column-start (2 and
 * 5), .offer-links__list going xl:contents there so its two children promote to be direct
 * children of the outer .theme-grid instead — same trick as _modules/_menu-overlay.sass's
 * own &__col.
 *
 * Column 8 stays an intentional single-column gutter between the second button and the
 * text at column 9.
 *
 * ACF fields (flat, prefixed 'social_offices_' — same names the shared module would have
 * read here, so no data migration was needed switching to this fork):
 *   social_offices_offers_title (text)
 *   social_offices_offers_items (repeater) → title (text), link (link)
 *   social_offices_offers_text  (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.18.0
 */

$sol_title = get_field( 'social_offices_offers_title' );

if ( ! $sol_title ) {
	return;
}

$sol_text = get_field( 'social_offices_offers_text' );
?>
<section class="offer-links mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $sol_title ) ); ?>

		<?php if ( have_rows( 'social_offices_offers_items' ) || $sol_text ) : ?>
			<div class="theme-grid mt-8 xl:mt-12">
				<?php if ( have_rows( 'social_offices_offers_items' ) ) : ?>
					<?php
					// col-start-2, then col-start-5 for every item after the first — the Figma
					// frame only ever shows two, but a third row falls in beside the second rather
					// than vanishing if one is ever added.
					$sol_item_cols = array( 'xl:col-start-2 xl:col-span-3', 'xl:col-start-5 xl:col-span-3' );
					$sol_item_i    = 0;
					?>
					<div class="offer-links__list col-span-2 md:col-start-1 md:col-span-3 flex flex-col items-start xl:contents gap-8">
						<?php
						while ( have_rows( 'social_offices_offers_items' ) ) :
							the_row();

							$sol_link = get_sub_field( 'link' );

							if ( ! get_sub_field( 'title' ) && ! $sol_link ) {
								continue;
							}

							$sol_cols = isset( $sol_item_cols[ $sol_item_i ] ) ? $sol_item_cols[ $sol_item_i ] : $sol_item_cols[1];
							++$sol_item_i;
							?>
							<div class="offer-links__item flex flex-col items-start gap-6 <?php echo esc_attr( $sol_cols ); ?>">
								<?php if ( get_sub_field( 'title' ) ) : ?>
									<p class="label-overline"><?php echo esc_html( get_sub_field( 'title' ) ); ?></p>
								<?php endif; ?>

								<?php if ( $sol_link ) : ?>
									<?php get_template_part( 'template-parts/components/button', null, array_merge( $sol_link, array( 'style' => 'primary' ) ) ); ?>
								<?php endif; ?>
							</div>
							<?php
						endwhile;
						?>
					</div>
				<?php endif; ?>

				<?php if ( $sol_text ) : ?>
					<div class="col-span-2 mt-4 md:mt-0 md:col-start-4 md:col-span-3 xl:col-start-9 xl:col-span-4">
						<div class="body-text"><?php echo wp_kses_post( $sol_text ); ?></div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
