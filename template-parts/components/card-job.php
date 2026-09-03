<?php
/**
 * Job card — a red category banner with an arrow, then the location, the
 * post title and a short excerpt, inside a bordered box. Receives its
 * data via $args; never calls get_field() or queries posts itself — the
 * caller (a slider, a grid) builds the list.
 *
 * Reused by the Open Positions single template's "Weitere
 * Stellenausschreibungen" related-jobs slider and the archive's own
 * "Aktuell offene Stellen" grid — the same card, the same Figma
 * component, in both places. The location line (e.g. "DasBreiteHotel")
 * only showed in the archive's own frame, not the single post's smaller
 * slider version, but both read the same offene_stellen_location field,
 * so it renders wherever it's passed rather than being hard-coded to one
 * caller.
 *
 * @param array $args {
 *     @type string $category Optional. The banner's label, e.g. "Arbeitsstellen" — red
 *                            text on a transparent background at rest, filling solid
 *                            red with white text on hover (the whole card, one <a>).
 *                            No banner without one.
 *     @type string $location Optional. The smaller red label above the title, e.g.
 *                            "DasBreiteHotel".
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
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" class="card-job border border-brand-red no-underline flex flex-col">
	<?php if ( ! empty( $args['category'] ) ) : ?>
		<span class="card-job__category flex items-center justify-between gap-4">
			<?php echo esc_html( $args['category'] ); ?>
			<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
		</span>
	<?php endif; ?>

	<div class="card-job__body flex flex-col">
		<?php if ( ! empty( $args['location'] ) ) : ?>
			<p class="card-job__location label-overline text-brand-red"><?php echo esc_html( $args['location'] ); ?></p>
		<?php endif; ?>

		<?php
		/*
		 * Figma: 101px between the location line and the title, tightened to a 96px
		 * (mt-24) Tailwind step; no location, no forced gap (mt-0) — the title just
		 * sits at the top of the body. Always explicit either way: _card.sass only
		 * resets this element's margin-bottom, not its top, so an h3's own browser
		 * default top margin would otherwise show through whenever mt-24 is left off.
		 */
		$card_job_title_margin = ! empty( $args['location'] ) ? 'mt-24' : 'mt-0';
		?>
		<h3 class="card-job__title text-brand-dark <?php echo esc_attr( $card_job_title_margin ); ?>"><?php echo esc_html( $args['title'] ); ?></h3>

		<?php if ( ! empty( $args['text'] ) ) : ?>
			<p class="card-job__text text-brand-dark mt-5"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
	</div>
</a>
