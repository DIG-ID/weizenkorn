<?php
/**
 * Generic card — image + title + text + arrow link.
 * Used for product ranges, services, venues/standorte and cross-links.
 *
 * Usage:
 *   get_template_part( 'template-parts/components/card', null, array(
 *       'image_id' => 123,
 *       'title'    => 'Kerzen',
 *       'text'     => 'Handgefertigte Kerzen für besondere Momente…',
 *       'url'      => '#',
 *       'variant'  => 'range',   // range | venue | cross-link
 *   ) );
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.1.0
 */

$card_image_id = isset( $args['image_id'] ) ? (int) $args['image_id'] : 0;
$card_title    = isset( $args['title'] ) ? $args['title'] : '';
$card_text     = isset( $args['text'] ) ? $args['text'] : '';
$card_url      = isset( $args['url'] ) ? $args['url'] : '';
$card_variant  = isset( $args['variant'] ) ? $args['variant'] : 'range';

if ( ! $card_title ) {
	return;
}

$card_element = $card_url ? 'a' : 'div';
$card_href    = $card_url ? ' href="' . esc_url( $card_url ) . '"' : '';
?>
<<?php echo esc_html( $card_element ); ?> class="card card--<?php echo esc_attr( $card_variant ); ?> flex flex-col"<?php echo $card_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_url applied above. ?>>
	<?php if ( $card_image_id ) : ?>
		<figure class="card__media overflow-hidden">
			<?php
			echo wp_get_attachment_image(
				$card_image_id,
				'large',
				false,
				array(
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<div class="card__body">
		<h3 class="title-card card__title"><?php echo esc_html( $card_title ); ?></h3>

		<?php if ( $card_text ) : ?>
			<div class="body-text card__text"><?php echo wp_kses_post( $card_text ); ?></div>
		<?php endif; ?>

		<?php if ( $card_url ) : ?>
			<span class="link-inline card__cta" aria-hidden="true"><?php echo esc_html__( 'mehr', 'weizenkorn' ); ?> &rarr;</span>
		<?php endif; ?>
	</div>
</<?php echo esc_html( $card_element ); ?>>
