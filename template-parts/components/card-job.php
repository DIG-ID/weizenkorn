<?php
/**
 * Job card — a red category banner with an arrow, then the post title and
 * a short excerpt, inside a bordered box. Receives its data via $args;
 * never calls get_field() or queries posts itself — the caller (a slider,
 * a grid) builds the list.
 *
 * Reused by the Open Positions single template's "Weitere
 * Stellenausschreibungen" related-jobs slider, and meant for the
 * archive's own "Aktuell offene Stellen" grid once that gets built — the
 * same card, the same Figma component, in both places.
 *
 * @param array $args {
 *     @type string $category Optional. The red banner's label, e.g. "Arbeitsstellen".
 *                            No banner without one.
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
		<span class="card-job__category bg-brand-red text-white flex items-center justify-between gap-4">
			<?php echo esc_html( $args['category'] ); ?>
			<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
		</span>
	<?php endif; ?>

	<div class="card-job__body flex flex-col gap-2">
		<h3 class="card-job__title title-card text-brand-dark"><?php echo esc_html( $args['title'] ); ?></h3>

		<?php if ( ! empty( $args['text'] ) ) : ?>
			<p class="card-job__text body-text text-brand-dark"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
	</div>
</a>
