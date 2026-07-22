<?php
/**
 * USP band ("Das macht uns aus") — shared module.
 * Full-bleed cream band with 3–4 icon+label items. Reused across many pages.
 *
 * ACF fields (flat, prefixed):
 *   usp_band_title (text)
 *   usp_band_items (repeater) → icon (image → return ID), label (text)
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.1.0
 */

$band_title = get_field( 'usp_band_title' );

if ( ! have_rows( 'usp_band_items' ) ) {
	return;
}

if ( ! $band_title ) {
	$band_title = __( 'Das macht uns aus', 'weizenkorn' );
}
?>
<section class="usp-band" aria-label="<?php echo esc_attr( $band_title ); ?>">
	<div class="theme-container">
		<?php if ( $band_title ) : ?>
			<h2 class="title-section usp-band__title text-center"><?php echo esc_html( $band_title ); ?></h2>
		<?php endif; ?>

		<div class="usp-band__items">
			<?php
			while ( have_rows( 'usp_band_items' ) ) :
				the_row();
				get_template_part(
					'template-parts/components/usp-item',
					null,
					array(
						'icon_id' => get_sub_field( 'icon' ),
						'label'   => get_sub_field( 'label' ),
					)
				);
			endwhile;
			?>
		</div>
	</div>
</section>
