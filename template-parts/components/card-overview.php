<?php
/**
 * Overview card — image, title, arrow and short text, linking through to a
 * page (Figma "Frame 1000006007" on Services_desktop). Receives its data via
 * $args; never calls get_field() — template-parts/modules/overview-cards.php
 * reads each child page's fields and passes them in per card.
 *
 * @param array $args {
 *     @type int    $image        Attachment ID.
 *     @type string $title
 *     @type string $text         Optional. Rich text (wpautop already applied).
 *     @type string $url
 *     @type string $media_height Optional. Tailwind height classes for the media box.
 *                                 Default: '256px' at mobile, 512px at tablet, 400px at
 *                                 desktop — the Gastronomie/Home venue bento's own values.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.4.0
 */

if ( empty( $args['image'] ) || empty( $args['url'] ) ) {
	return;
}

$media_height = ! empty( $args['media_height'] ) ? $args['media_height'] : 'h-[256px] md:h-[512px] xl:h-[400px]';
?>
<a href="<?php echo esc_url( $args['url'] ); ?>" class="card-overview group flex flex-col gap-3.5 md:gap-4">
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
			<span class="text-brand-red shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
		</div>

		<?php if ( ! empty( $args['text'] ) ) : ?>
			<div class="body-text"><?php echo wp_kses_post( $args['text'] ); ?></div>
		<?php endif; ?>
	</div>
</a>
