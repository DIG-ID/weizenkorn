<?php
/**
 * Range grid — the product-range card flow, shared by the home page's Products
 * section and the products archive's Ranges section. Identical markup in both; only
 * the content differs.
 *
 * Layout — Figma, desktop confirmed 2026-08-11. Five cards across two rows: three
 * thirds on top, then 40% / 60%. Media is a fixed height per breakpoint so the rows
 * stay aligned even though the second row's cards differ in width. See
 * _components/_range-grid.sass for the flow and _card.sass for the card itself.
 *
 * Like every component here it never calls get_field(): the caller reads its own
 * fields and passes the rows in, which is what lets one file serve a page group and
 * an archive's options store without knowing about either.
 *
 * Usage:
 *   get_template_part(
 *       'template-parts/components/range-grid',
 *       null,
 *       array( 'ranges' => get_sub_field( 'ranges' ) )
 *   );
 *
 * @param array $args {
 *     @type array $ranges Rows as ACF returns them. Each row:
 *                         image (int, attachment id), title (string),
 *                         text (string), page (array, ACF link).
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.5.0
 */

$rg_ranges = ( isset( $args['ranges'] ) && is_array( $args['ranges'] ) ) ? $args['ranges'] : array();

if ( ! $rg_ranges ) {
	return;
}
?>
<div class="range-grid">
	<?php
	foreach ( $rg_ranges as $rg_range ) :
		$rg_link   = ( ! empty( $rg_range['page'] ) && is_array( $rg_range['page'] ) ) ? $rg_range['page'] : array();
		$rg_url    = ! empty( $rg_link['url'] ) ? $rg_link['url'] : '';
		$rg_target = ! empty( $rg_link['target'] ) ? $rg_link['target'] : '';
		$rg_tag    = $rg_url ? 'a' : 'div';
		?>
		<<?php echo esc_html( $rg_tag ); ?> class="card-range"<?php echo $rg_url ? ' href="' . esc_url( $rg_url ) . '"' : ''; ?><?php echo ( '_blank' === $rg_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
			<?php if ( ! empty( $rg_range['image'] ) ) : ?>
				<figure class="card__media">
					<?php echo wp_get_attachment_image( $rg_range['image'], 'large', false, array( 'loading' => 'lazy' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="card__body">
				<div class="card__head">
					<h3 class="title-card card__title"><?php echo esc_html( ! empty( $rg_range['title'] ) ? $rg_range['title'] : '' ); ?></h3>
					<?php if ( $rg_url ) : ?>
						<span class="card__arrow" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $rg_range['text'] ) ) : ?>
					<div class="body-text card__text"><?php echo wp_kses_post( $rg_range['text'] ); ?></div>
				<?php endif; ?>
			</div>
		</<?php echo esc_html( $rg_tag ); ?>>
		<?php
	endforeach;
	?>
</div>
