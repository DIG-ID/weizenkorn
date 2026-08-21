<?php
/**
 * USP band — a full-bleed cream band with 3-4 icon and label items. Reused across many
 * pages.
 *
 * ACF fields (flat, prefixed):
 *   usp_band_title (text)
 *   usp_band_items (repeater) → icon (image → return ID), label (text)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/usp-band' );
 *
 * A CPT archive has no post context, so pass the options store plus the archive's prefix:
 *   get_template_part(
 *       'template-parts/modules/usp-band',
 *       null,
 *       array( 'post_id' => 'option', 'prefix' => 'products_archive_' )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read the fields
 *                               from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.1.0
 */

$usp_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $usp_prefix . 'usp_band_items', $usp_ctx ) ) {
	return;
}
?>
<section class="usp-band bg-brand-cream text-brand-dark py-16 md:py-24 xl:py-32">
	<div class="theme-container">
		<div class="usp-band__title-wrap mb-8 md:mb-16 xl:mb-24">
			<h3 class="title-usp usp-band__title text-center">
				<?php
				// Falls back to the designed default so the band always has its heading.
				echo esc_html( get_field( $usp_prefix . 'usp_band_title', $usp_ctx ) ? get_field( $usp_prefix . 'usp_band_title', $usp_ctx ) : __( 'Das macht uns aus', 'weizenkorn' ) );
				?>
			</h3>
		</div>

		<div class="usp-band__items gap-12 md:gap-24 xl:gap-52 flex flex-col md:flex-row items-center justify-center">
			<?php
			while ( have_rows( $usp_prefix . 'usp_band_items', $usp_ctx ) ) :
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
