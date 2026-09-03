<?php
/**
 * Values grid — the heading, then the values two to a row: an icon beside a title and a
 * short paragraph, with a rule under each row but the last.
 *
 * The rule is on the item and not on a row of its own, because a CSS grid has no row
 * element to hang it on. Every item carries it and the last row's items drop it, which is
 * what nth-last-child does below — see the note in _values-grid.sass about the count.
 *
 * ACF fields (flat, prefixed):
 *   {prefix}values_grid_section_title  (clone of "Section Title") the title and its rule.
 *   {prefix}values_grid_items          (repeater) one row per value:
 *                                      → icon  (image → ID)
 *                                      → title (text)
 *                                      → text  (textarea / wpautop)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/values-grid' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store. Default: current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.11.0
 */

$vg_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$vg_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$vg_heading = weizenkorn_get_section_heading( $vg_prefix . 'values_grid_', $vg_ctx );

if ( ! $vg_heading && ! have_rows( $vg_prefix . 'values_grid_items', $vg_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="values-grid mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $vg_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $vg_heading );
		}
		?>

		<?php if ( have_rows( $vg_prefix . 'values_grid_items', $vg_ctx ) ) : ?>
			<div class="theme-grid mt-8 md:mt-[52px] xl:mt-[83px]">
				<?php
				// A nested grid over the inset, re-divided into twelve: an item spanning six
				// comes out at the frame's 747, where six of the page's own twelve would be
				// half the container.
				?>
				<div class="values-grid__list theme-grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php
					while ( have_rows( $vg_prefix . 'values_grid_items', $vg_ctx ) ) :
						the_row();

						$vg_icon  = get_sub_field( 'icon' );
						$vg_title = get_sub_field( 'title' );
						$vg_text  = get_sub_field( 'text' );
						?>
						<div class="values-grid__item col-span-2 md:col-start-2 md:col-span-4 xl:col-start-auto xl:col-span-6">
							<?php if ( $vg_icon ) : ?>
								<div class="values-grid__icon shrink-0">
									<?php
									echo wp_get_attachment_image(
										$vg_icon,
										'full',
										false,
										array(
											'class'   => 'w-full h-full object-contain',
											'loading' => 'lazy',
										)
									);
									?>
								</div>
							<?php endif; ?>

							<div class="values-grid__text text-brand-dark">
								<?php if ( $vg_title ) : ?>
									<h3 class="values-grid__title title-card"><?php echo esc_html( $vg_title ); ?></h3>
								<?php endif; ?>

								<?php if ( $vg_text ) : ?>
									<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
									<div class="body-text"><?php echo wp_kses_post( $vg_text ); ?></div>
								<?php endif; ?>
							</div>
						</div>
						<?php
					endwhile;
					?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
