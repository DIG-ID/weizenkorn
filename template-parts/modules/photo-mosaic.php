<?php
/**
 * Photo mosaic — a heading and a bento grid of photographs.
 *
 * ACF fields (flat, prefixed) — the `photo_mosaic` group:
 *   photo_mosaic_section_title  (clone of "Section Title") title and `buttons.prmary`.
 *                               The gastronomy variant puts the overline text in the
 *                               title and has no button.
 *   photo_mosaic_items          (repeater) one row per photo:
 *     → image  (image → ID)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/photo-mosaic' );
 *   get_template_part( 'template-parts/modules/photo-mosaic', null, array( 'variant' => 'gastronomy' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 *     @type string     $variant Optional. 'gastronomy' for the venue-page arrangement.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.6.0
 */

$pm_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$pm_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Whitelisted, so the modifier class below cannot be whatever a caller hands over.
$pm_variant = ( ! empty( $args['variant'] ) && 'gastronomy' === $args['variant'] ) ? 'gastronomy' : '';

// Both variants want 32 / 56 / 96px here, but the default gets 32 of it from the
// section-heading's own button row. The gastronomy variant has no button.
$pm_grid_gap = $pm_variant ? 'mt-8 md:mt-14 xl:mt-24' : 'md:mt-6 xl:mt-16';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$pm_heading = weizenkorn_get_section_heading( $pm_prefix . 'photo_mosaic_', $pm_ctx );

if ( ! $pm_heading && ! have_rows( $pm_prefix . 'photo_mosaic_items', $pm_ctx ) ) {
	return;
}
?>
<section class="photo-mosaic<?php echo $pm_variant ? ' photo-mosaic--' . esc_attr( $pm_variant ) : ''; ?> mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $pm_heading ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				$pm_variant
					? array_merge( $pm_heading, array( 'title_style' => 'overline' ) )
					: $pm_heading
			);
		}
		?>

		<?php if ( have_rows( $pm_prefix . 'photo_mosaic_items', $pm_ctx ) ) : ?>
			<div class="photo-mosaic__grid theme-grid <?php echo esc_attr( $pm_grid_gap ); ?>">
				<?php
				while ( have_rows( $pm_prefix . 'photo_mosaic_items', $pm_ctx ) ) :
					the_row();

					if ( ! get_sub_field( 'image' ) ) {
						continue;
					}
					?>
					<figure class="photo-mosaic__item">
						<?php
						echo wp_get_attachment_image(
							get_sub_field( 'image' ),
							'large',
							false,
							array(
								'class'   => 'w-full h-full object-cover',
								'loading' => 'lazy',
							)
						);
						?>
					</figure>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
