<?php
/**
 * Products archive — Ranges section ("Produkte mit Qualität und Mehrwert").
 *
 * The same section as the home page's Products block: a heading with an intro and
 * the five range cards. Only the copy differs, so the cards come from the shared
 * components/range-grid and the heading from components/section-heading — this file
 * only reads the archive's own fields and hands them over.
 *
 * Layout — Figma, desktop confirmed 2026-08-11: heading on columns 2–11 with the red
 * rule under it, an intro paragraph five columns wide beneath, then the card flow on
 * the same inset span. Three thirds on the first row, 40% / 60% on the second.
 *
 * ACF fields. The archive mirrors the home page's group so both feed the same
 * component with the same field names — a group named `products` containing:
 *   section_title (clone → "Section Title") passed straight to section-heading, the
 *                 way the home page does it
 *   ranges        (repeater) → image (image → ID), title (text), text (textarea),
 *                 page (link)
 *
 * Usage — on the archive there is no post context, so pass the options store:
 *   get_template_part(
 *       'template-parts/archives/product/ranges',
 *       null,
 *       array(
 *           'post_id' => 'option',
 *           'prefix'  => 'products_archive_',
 *       )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.5.0
 */

// ACF read context: the current post normally, the options store on archives.
$rn_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so the section can serve a page or an archive.
$rn_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$rn_heading = get_field( $rn_prefix . 'products_section_title', $rn_ctx );
$rn_items   = get_field( $rn_prefix . 'products_ranges', $rn_ctx );

if ( ! $rn_heading && ! $rn_items ) {
	return;
}
?>
<section class="section-ranges mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		// Heading, rule and intro all come from the shared component, which takes the
		// whole "Section Title" group — same call as the home page's Products section.
		if ( $rn_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $rn_heading );
		}
		?>

		<?php if ( $rn_items ) : ?>
			<div class="theme-grid">
				<div class="col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 mt-8 md:mt-14 xl:mt-24">
					<?php
					get_template_part(
						'template-parts/components/range-grid',
						null,
						array( 'ranges' => $rn_items )
					);
					?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
