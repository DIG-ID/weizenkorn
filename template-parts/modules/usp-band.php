<?php
/**
 * USP band ("Das macht uns aus") — shared module.
 * Full-bleed cream band with 3–4 icon+label items. Reused across many pages.
 * ACF group suggestion: `usp_band` { title, items[] { icon, label } }.
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.1.0
 */

$usp        = get_field( 'usp_band' );
$band_title = ( $usp && ! empty( $usp['title'] ) ) ? $usp['title'] : __( 'Das macht uns aus', 'weizenkorn' );
$band_items = ( $usp && ! empty( $usp['items'] ) ) ? $usp['items'] : array();

if ( empty( $band_items ) ) {
	return;
}
?>
<section class="usp-band" aria-label="<?php echo esc_attr( $band_title ); ?>">
	<div class="theme-container">
		<?php if ( $band_title ) : ?>
			<h2 class="title-section usp-band__title text-center"><?php echo esc_html( $band_title ); ?></h2>
		<?php endif; ?>

		<div class="usp-band__items">
			<?php
			foreach ( $band_items as $band_item ) {
				get_template_part(
					'template-parts/components/usp-item',
					null,
					array(
						'icon_id' => ! empty( $band_item['icon'] ) ? $band_item['icon'] : 0,
						'label'   => ! empty( $band_item['label'] ) ? $band_item['label'] : '',
					)
				);
			}
			?>
		</div>
	</div>
</section>
