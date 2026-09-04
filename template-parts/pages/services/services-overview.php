<?php
/**
 * Services — "Dienstleistungen mit Mehrwert" overview section (Figma
 * "Frame 1000006007" on Services_desktop). Section heading (reusable
 * "Section Title" component, fed from this page's own fields) + a grid of
 * up to 3 cards (Schreinerei/Kreativatelier/Treuhand), via a plain ACF
 * repeater on this page.
 *
 * Was originally read off each child page's own "Overview Card" fields
 * (get_children() + template-parts/modules/overview-cards.php) — changed
 * after the fact, at the client's own request, to be editable here instead.
 * overview-cards.php is untouched and still works for a future hub page
 * that wants the original child-page-driven behaviour; this section just no
 * longer calls it. The card markup itself is still
 * template-parts/components/card-overview.php, called directly here row by
 * row, same image/title-arrow/text shape either way.
 *
 * ACF fields (flat, prefixed):
 *   services_overview_title    (text)
 *   services_overview_subtitle (text)
 *   services_overview_text     (textarea / wpautop)
 *   services_overview_cards    (repeater, up to 3) → image (image, ID),
 *                              title (text), text (textarea / wpautop),
 *                              link (link, optional)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.4.0
 */

$services_overview_title = get_field( 'services_overview_title' );

if ( ! $services_overview_title ) {
	return;
}
?>
<section class="section-services-overview my-24 md:my-32 xl:my-48">
	<div class="theme-container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'title'             => $services_overview_title,
				'subtitle'          => get_field( 'services_overview_subtitle' ),
				'description'       => 'right',
				'description_right' => get_field( 'services_overview_text' ),
			)
		);
		?>

		<?php if ( have_rows( 'services_overview_cards' ) ) : ?>
			<div class="mt-8 md:mt-14 xl:mt-24 theme-grid">
				<?php
				/*
				 * A nested .theme-grid and not flex: .theme-grid already sets display:grid, so
				 * flex utilities on the same element silently lose — both are plain classes of
				 * equal specificity, and grid was winning and squeezing every <li> into one
				 * track (same reasoning as overview-cards.php's own version of this grid).
				 */
				?>
				<ul class="overview-cards theme-grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 xl:gap-x-[25px] gap-y-8 md:gap-y-16 list-none m-0 p-0">
					<?php
					while ( have_rows( 'services_overview_cards' ) ) :
						the_row();

						$sc_image = get_sub_field( 'image' );

						if ( ! $sc_image ) {
							continue;
						}

						$sc_link = get_sub_field( 'link' );
						?>
						<li class="col-span-2 md:col-span-6 xl:col-span-4">
							<?php
							get_template_part(
								'template-parts/components/card-overview',
								null,
								array(
									'image'          => $sc_image,
									'title'          => get_sub_field( 'title' ),
									'text'           => get_sub_field( 'text' ),
									'url'            => $sc_link ? $sc_link['url'] : '',
									// The default 50% cap is for card-overview's wider callers
									// (offer-grid.php, perspectives.php's half/full-row cards) —
									// at 1 of 3 columns this card is already narrow, so the text
									// should fill it rather than shrink to a sliver of a sliver.
									'text_max_width' => '',
								)
							);
							?>
						</li>
						<?php
					endwhile;
					?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
