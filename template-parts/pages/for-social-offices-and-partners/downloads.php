<?php
/**
 * For Social Offices & Partners — "Downloads & Unterlagen" section (Figma
 * desktop node 2118:783). A row of document cards — same shape as
 * template-parts/modules/numbered-steps.php (fixed height, justify-between
 * pins the title row to the top and the text to the bottom), but each card
 * is a download link instead of static text: a title with the shared
 * arrow-download icon, and a short description below it.
 *
 * Not the numbered-steps module: these cards have no number, and each is
 * a link (whole card, to the file) rather than plain text.
 *
 * Prepared for the empty state per the brief: with no documents, the
 * section still shows its title and falls back to an editable message
 * instead of an empty grid.
 *
 * ACF fields (flat, prefixed):
 *   social_offices_downloads_title         (text)
 *   social_offices_downloads_items         (repeater) → title (text),
 *                                          file (file, return url),
 *                                          text (textarea / wpautop)
 *   social_offices_downloads_empty_message (textarea, plain — no wpautop,
 *                                          it's one sentence) shown
 *                                          instead of the grid when there
 *                                          are no items.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.9.0
 */

$dl_title = get_field( 'social_offices_downloads_title' );

if ( ! $dl_title ) {
	return;
}

$dl_has_items = have_rows( 'social_offices_downloads_items' );
?>
<section class="section-downloads mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $dl_title ) ); ?>

		<?php if ( $dl_has_items ) : ?>
			<div class="theme-grid mt-8 md:mt-14 xl:mt-16 gap-y-5">
				<?php
				while ( have_rows( 'social_offices_downloads_items' ) ) :
					the_row();

					$dl_file = get_sub_field( 'file' );

					if ( ! get_sub_field( 'title' ) || ! $dl_file ) {
						continue;
					}
					?>
					<a href="<?php echo esc_url( $dl_file ); ?>" download class="card-download bg-brand-cream col-span-2 md:col-span-3 xl:col-span-3">
						<span class="card-download__title title-card flex items-center justify-between gap-4">
							<?php echo esc_html( get_sub_field( 'title' ) ); ?>
							<span class="text-brand-red shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-download' ); ?></span>
						</span>

						<?php if ( get_sub_field( 'text' ) ) : ?>
							<div class="card-download__text body-text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
						<?php endif; ?>
					</a>
					<?php
				endwhile;
				?>
			</div>
		<?php else : ?>
			<?php
			$dl_empty_message = get_field( 'social_offices_downloads_empty_message' );

			if ( ! $dl_empty_message ) {
				$dl_empty_message = 'Die passenden Unterlagen stellen wir Ihnen gerne auf Anfrage bereit. Kontaktieren Sie uns einfach direkt.';
			}
			?>
			<div class="theme-grid mt-8 md:mt-14 xl:mt-16">
				<div class="body-text col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10"><?php echo esc_html( $dl_empty_message ); ?></div>
			</div>
		<?php endif; ?>
	</div>
</section>
