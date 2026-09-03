<?php
/**
 * Donation project card ("news-card" in Figma) — a photo, then a bordered
 * white box with a year + arrow on one line, a title and an excerpt.
 * Receives its data via $args; never calls get_field() itself — the
 * caller (template-parts/pages/about-us-donate/projects.php) reads the repeater row.
 *
 * The link is optional: there's no "donation project" post type to link
 * to yet, so a row with no URL renders as a plain <div> with no arrow,
 * rather than a dead link.
 *
 * @param array $args {
 *     @type int    $image Optional. Attachment ID.
 *     @type string $year  Optional. e.g. "2026".
 *     @type string $title Required.
 *     @type string $text  Optional. Plain excerpt text.
 *     @type string $url   Optional. Card becomes an <a> only when set.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.13.0
 */

if ( empty( $args['title'] ) ) {
	return;
}

$cdp_tag  = ! empty( $args['url'] ) ? 'a' : 'div';
$cdp_href = ! empty( $args['url'] ) ? ' href="' . esc_url( $args['url'] ) . '"' : '';
?>
<?php echo '<' . esc_html( $cdp_tag ) . $cdp_href . ' class="card-donation-project border border-brand-red bg-white no-underline flex flex-col h-full">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- tag name is always 'a' or 'div', href built above with esc_url(). ?>

	<?php if ( ! empty( $args['image'] ) ) : ?>
		<div class="card-donation-project__media aspect-[490/320] overflow-hidden">
			<?php
			echo wp_get_attachment_image(
				$args['image'],
				'large',
				false,
				array(
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="card-donation-project__body px-5 py-4 flex flex-col gap-2 flex-1">
		<?php if ( ! empty( $args['year'] ) || ! empty( $args['url'] ) ) : ?>
			<div class="card-donation-project__meta flex items-center justify-between">
				<?php if ( ! empty( $args['year'] ) ) : ?>
					<span class="label-overline text-brand-red"><?php echo esc_html( $args['year'] ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $args['url'] ) ) : ?>
					<span class="text-brand-red shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<h3 class="card-donation-project__title title-card text-brand-dark m-0"><?php echo esc_html( $args['title'] ); ?></h3>

		<?php if ( ! empty( $args['text'] ) ) : ?>
			<p class="card-donation-project__text body-text text-brand-dark m-0"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
	</div>

<?php echo '</' . esc_html( $cdp_tag ) . '>'; ?>
