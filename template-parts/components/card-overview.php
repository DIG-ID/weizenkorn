<?php
/**
 * Overview card — image, title, arrow and short text. Receives its data via $args; never
 * calls get_field(), the calling module reads the fields and passes them in per card.
 *
 * Without a URL the card is still drawn, as a plain block rather than a link, and the
 * arrow is dropped — an arrow is a promise that clicking leads somewhere. The `group`
 * class goes with it, so the title's hover underline does not fire on something that is
 * not clickable.
 *
 * @param array $args {
 *     @type int    $image        Attachment ID. Required — nothing is drawn without it.
 *     @type string $title
 *     @type string $text         Optional. Rich text (wpautop already applied).
 *     @type string $url          Optional. Omit for a card that links nowhere.
 *     @type string $media_height Optional. Tailwind height classes for the media box.
 *                                 Default: 256px at mobile, 512px at tablet, 400px at
 *                                 desktop — the Gastronomie/Home venue bento's own values.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.4.0
 */

if ( empty( $args['image'] ) ) {
	return;
}

$media_height = ! empty( $args['media_height'] ) ? $args['media_height'] : 'h-[256px] md:h-[512px] xl:h-[400px]';
$card_url     = ! empty( $args['url'] ) ? $args['url'] : '';
?>
<?php if ( $card_url ) : ?>
<a href="<?php echo esc_url( $card_url ); ?>" class="card-overview group flex flex-col gap-3.5 md:gap-4">
<?php else : ?>
<div class="card-overview flex flex-col gap-3.5 md:gap-4">
<?php endif; ?>

	<div class="card-overview__media overflow-hidden <?php echo esc_attr( $media_height ); ?>">
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

	<div class="card-overview__text-group flex flex-col gap-3.5 md:gap-[10px] xl:gap-4">
		<div class="flex items-center justify-between gap-4">
			<h3 class="title-card group-hover:underline group-focus-visible:underline underline-offset-4"><?php echo esc_html( $args['title'] ); ?></h3>

			<?php if ( $card_url ) : ?>
				<span class="text-brand-red shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $args['text'] ) ) : ?>
			<div class="body-text xl:max-w-[50%]"><?php echo wp_kses_post( $args['text'] ); ?></div>
		<?php endif; ?>
	</div>

<?php if ( $card_url ) : ?>
</a>
<?php else : ?>
</div>
<?php endif; ?>
