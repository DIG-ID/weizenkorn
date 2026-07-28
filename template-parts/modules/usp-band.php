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
<section class="usp-band bg-brand-cream text-brand-dark py-16 md:py-24 xl:py-32" aria-label="<?php echo esc_attr( $band_title ); ?>">
	<div class="theme-container">
		<?php if ( $band_title ) : ?>
			<div class="usp-band__title-wrap mb-8 md:mb-16 xl:mb-24">
				<h3 class="title-usp usp-band__title text-center"><?php echo esc_html( $band_title ); ?></h3>
			</div>
		<?php endif; ?>

		<div class="usp-band__items gap-12 md:gap-24 xl:gap-52 flex flex-col md:flex-row items-center justify-center">
			<?php
			while ( have_rows( 'usp_band_items' ) ) :
				the_row();
				?>
				<div class="usp-band__item flex flex-col items-center text-center gap-4 xl:gap-6">
					<?php if ( get_sub_field( 'icon' ) ) : ?>
						<span class="usp-band__icon w-16 h-16 md:w-20 md:h-20 xl:w-24 xl:h-24" aria-hidden="true">
							<?php echo wp_get_attachment_image( get_sub_field( 'icon' ), 'full', false, array( 'class' => 'w-full h-full object-contain' ) ); ?>
						</span>
					<?php endif; ?>
					<?php if ( get_sub_field( 'label' ) ) : ?>
						<span class="label-usp usp-band__label"><?php echo esc_html( get_sub_field( 'label' ) ); ?></span>
					<?php endif; ?>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
