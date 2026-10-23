<?php
/**
 * Job card — a category banner with an arrow, then the employment type, the
 * post title and a short excerpt, inside a bordered box. Receives its data
 * via $args; never calls get_field() or queries posts itself — the caller
 * (a slider, a grid) builds the list.
 *
 * Reused by the Open Positions single template's "Weitere
 * Stellenausschreibungen" related-jobs slider and the archive's own
 * "Aktuell offene Stellen" grid — the same card, the same Figma
 * component, in both places.
 *
 * Banner and label swapped in 1.19.11 (Figma node 6066:7129): the banner now
 * carries the location (e.g. "DasBreiteHotel") and the employment type
 * (Arbeitsstellen / Ausbildungsplätze) sits under it as the label line. The
 * banner's own fill follows the employment type — see
 * weizenkorn_get_job_banner_style().
 *
 * @param array $args {
 *     @type string $location Optional. The banner's label, e.g. "DasBreiteHotel". No
 *                            banner without one.
 *     @type string $banner   Optional. 'solid' (red fill, white text — Arbeitsstellen) or
 *                            'outline' (white fill, red text — Ausbildungsplätze, filling
 *                            red on hover). Default 'outline'.
 *     @type string $category Optional. The smaller label under the banner, e.g.
 *                            "Arbeitsstellen".
 *     @type string $title    Required.
 *     @type string $text     Optional. Plain excerpt text.
 *     @type string $url      Required.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.10.0
 */

if ( empty( $args['title'] ) || empty( $args['url'] ) ) {
	return;
}
$card_job_banner = ( ! empty( $args['banner'] ) && 'solid' === $args['banner'] ) ? 'solid' : 'outline';
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" class="card-job card-job--<?php echo esc_attr( $card_job_banner ); ?> border border-brand-red no-underline flex flex-col">
	<?php if ( ! empty( $args['location'] ) ) : ?>
		<span class="card-job__banner flex items-center justify-between gap-4">
			<?php echo esc_html( $args['location'] ); ?>
			<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
		</span>
	<?php endif; ?>

	<div class="card-job__body flex flex-col">
		<?php if ( ! empty( $args['category'] ) ) : ?>
			<p class="card-job__category label-overline text-brand-dark"><?php echo esc_html( $args['category'] ); ?></p>
		<?php endif; ?>

		<?php
		/*
		 * Figma: ~110px between the label line and the title, tightened to a 96px
		 * (mt-24) Tailwind step; no label, no forced gap (mt-0) — the title just
		 * sits at the top of the body. Always explicit either way: _card.sass only
		 * resets this element's margin-bottom, not its top, so an h3's own browser
		 * default top margin would otherwise show through whenever mt-24 is left off.
		 */
		$card_job_title_margin = ! empty( $args['category'] ) ? 'mt-24' : 'mt-0';
		?>
		<h3 class="card-job__title text-brand-red <?php echo esc_attr( $card_job_title_margin ); ?>"><?php echo esc_html( $args['title'] ); ?></h3>

		<?php if ( ! empty( $args['text'] ) ) : ?>
			<p class="card-job__text text-brand-dark mt-5"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
	</div>
</a>
