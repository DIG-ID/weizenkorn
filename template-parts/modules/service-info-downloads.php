<?php
/**
 * Service / Info / Downloads — a heading and a row of outlined buttons, one per document
 * or page the reader can open.
 *
 * There is no button markup here. Each row is an ACF Link handed to the shared button
 * component in its `secondary` style, which is the frame's box: transparent, with the
 * red border and the red uppercase label. The component also picks the arrow — down for a
 * URL that ends in a document extension, right for anything else — so a PDF and a page can
 * sit in the same row without a field to tell them apart.
 *
 * THE GRID
 *
 * At desktop the buttons are not column spans. The frame puts three of them at 0, 614 and
 * 1228 across an inset of 1515 — 3 x 287 with the 654 that is left over split into two
 * equal gaps — which is a fixed width spread with space-between, not a grid. Read as
 * columns it also breaks: two of twelve is 287 only near 1920, and at 1280 it comes out at
 * 180, narrower than the same button at tablet, with the arrow pushed out through the
 * border. So the row turns into a flex row at xl and the button keeps the frame's width.
 *
 * At tablet the three are adjacent instead — two of six columns each, which fills the
 * container exactly. At mobile each one takes the full width and they stack.
 *
 * ACF fields (flat, prefixed) — the `service_info_downloads` group. The group name
 * produces the prefix, so renaming it orphans whatever is stored:
 *   service_info_downloads_section_title  (clone of "Section Title") the title and its red
 *                                         rule. Clone the GROUP, never a repeater inside
 *                                         one.
 *   service_info_downloads_items          (repeater) one row per button:
 *                                         → link (link) required — the label is the link's
 *                                           own title
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/service-info-downloads' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.9.0
 */

$sid_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$sid_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$sid_heading = weizenkorn_get_section_heading( $sid_prefix . 'service_info_downloads_', $sid_ctx );

if ( ! $sid_heading && ! have_rows( $sid_prefix . 'service_info_downloads_items', $sid_ctx ) ) {
	return;
}

?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="service-info-downloads mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $sid_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $sid_heading );
		}
		?>

		<?php if ( have_rows( $sid_prefix . 'service_info_downloads_items', $sid_ctx ) ) : ?>
			<div class="service-info-downloads__row theme-grid mt-4 md:mt-14 xl:mt-24">
				<?php
				while ( have_rows( $sid_prefix . 'service_info_downloads_items', $sid_ctx ) ) :
					the_row();

					$sid_link = get_sub_field( 'link' );

					if ( empty( $sid_link['url'] ) || empty( $sid_link['title'] ) ) {
						continue;
					}
					?>
					<div class="service-info-downloads__item col-span-2 md:col-span-2">
						<?php
						// The arrow is named rather than inferred: every button in this section
						// fetches a document, and the component would otherwise read the URL and
						// point right at whatever is not yet a file — a page being filled in with
						// placeholder links draws the wrong arrow.
						get_template_part(
							'template-parts/components/button',
							null,
							array_merge(
								$sid_link,
								array(
									'style' => 'secondary',
									'icon'  => 'arrow-download',
								)
							)
						);
						?>
					</div>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
