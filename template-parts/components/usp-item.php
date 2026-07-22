<?php
/**
 * USP item — icon + label. Used inside the USP band module.
 *
 * Usage:
 *   get_template_part( 'template-parts/components/usp-item', null, array(
 *       'icon_id' => 123,        // attachment id (SVG/PNG)
 *       'label'   => 'Nachhaltig',
 *   ) );
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.1.0
 */

$icon_id = isset( $args['icon_id'] ) ? (int) $args['icon_id'] : 0;
$label   = isset( $args['label'] ) ? $args['label'] : '';

if ( ! $label ) {
	return;
}
?>
<div class="usp-band__item">
	<?php if ( $icon_id ) : ?>
		<span class="usp-band__icon" aria-hidden="true">
			<?php echo wp_get_attachment_image( $icon_id, 'full', false, array( 'class' => 'w-full h-full object-contain' ) ); ?>
		</span>
	<?php endif; ?>
	<span class="title-card usp-band__label"><?php echo esc_html( $label ); ?></span>
</div>
