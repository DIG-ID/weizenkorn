<?php
/**
 * Catering — a heading, a wide photo, and an overline paired with a paragraph opposite it.
 *
 * There is no layout in this file. Every part of the section is the shared "Section Title"
 * group, and the component already draws it in the columns the design uses: the title on
 * column 2 with the rule full width, the photo at the container's width, then the subtitle
 * on the left and the right description opposite — and stacked at mobile. So this module
 * reads the clone, hands it over, and owns nothing but the section's vertical rhythm.
 *
 * ACF fields (flat, prefixed) — the `catering` group. The group name produces the prefix,
 * so renaming it orphans whatever is stored:
 *   catering_section_title  (clone of "Section Title") the whole section. Put the overline
 *                           in `subtitle`, the paragraph in `description_right`, the photo
 *                           in `image`, and set `description` to "right" — without that the
 *                           paragraph does not render. Clone the GROUP, never a repeater
 *                           inside one.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/catering' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name, so one page can
 *                               carry two of these.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.8.0
 */

$ct_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$ct_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$ct_heading = weizenkorn_get_section_heading( $ct_prefix . 'catering_', $ct_ctx );

/*
 * The helper leaves the clone's `image` for the caller, for the key collision its own note
 * explains. Reading it here is unambiguous: this section has no image field of its own, so
 * a Seamless clone's flat catering_image can only be the heading's.
 */
if ( $ct_heading && empty( $ct_heading['image'] ) ) {
	$ct_image = (int) get_field( $ct_prefix . 'catering_image', $ct_ctx );

	if ( $ct_image ) {
		$ct_heading['image'] = $ct_image;
	}
}

if ( ! $ct_heading ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="catering mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, $ct_heading ); ?>
	</div>
</section>
