<?php
/**
 * Work & Training — "Ihre Perspektiven bei uns" section (Figma "Frame
 * 1000006298" on Work & Training_desktop). Title, then 3 offer cards: the
 * first two side by side, the third full width below. Each card is the
 * shared template-parts/components/card-overview.php component — same
 * image/title/arrow/text shape as the Services overview cards, just a flat
 * 200/224/400px media height here (mobile/tablet/desktop) instead of that
 * bento's own values, passed through card-overview's $args['media_height'].
 *
 * ACF fields (flat, prefixed):
 *   work_training_perspectives_title (text)
 *   work_training_perspectives_items (repeater, up to 3) → image (image, ID),
 *                                     title (text), text (textarea / wpautop),
 *                                     page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.7.0
 */

$wtp_title = get_field( 'work_training_perspectives_title' );

if ( ! $wtp_title || ! have_rows( 'work_training_perspectives_items' ) ) {
	return;
}
?>
<section class="section-work-perspectives mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array( 'title' => $wtp_title )
		);
		?>

		<div class="theme-grid gap-y-5 mt-8 md:mt-14 xl:mt-24">
			<?php
			while ( have_rows( 'work_training_perspectives_items' ) ) :
				the_row();

				$wtp_link = get_sub_field( 'page' );
				$wtp_url  = ( is_array( $wtp_link ) && ! empty( $wtp_link['url'] ) ) ? $wtp_link['url'] : '';

				if ( ! get_sub_field( 'image' ) || ! $wtp_url ) {
					continue;
				}
				?>
				<?php
				/*
				 * Every card spans the full row below xl (stacked); at xl the first
				 * two sit side by side (6 of 12 each) and the third takes the full
				 * row (12 of 12) — matches the design exactly, no row-span tricks
				 * needed since neither card spans more than one row.
				 */
				?>
				<div class="col-span-2 md:col-span-3 xl:col-span-6 last:xl:col-span-12">
					<?php
					get_template_part(
						'template-parts/components/card-overview',
						null,
						array(
							'image'        => get_sub_field( 'image' ),
							'title'        => get_sub_field( 'title' ),
							'text'         => get_sub_field( 'text' ),
							'url'          => $wtp_url,
							'media_height' => 'h-[200px] md:h-[224px] xl:h-[400px]',
						)
					);
					?>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
