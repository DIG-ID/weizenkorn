<?php
/**
 * Products archive — Ranges section.
 *
 * The same card flow as the home page's Products block, rendered by the shared
 * components/range-grid with the heading from components/section-heading.
 *
 * The cards ARE the `products` posts — one per range — not an ACF repeater. Each range has
 * its own single page, so the post is already the record; a repeater with the same title,
 * text and image beside it would be two copies of one thing, and they drift the moment
 * someone edits only one.
 *
 * The `products` post type must support:
 *   thumbnail        the card image
 *   excerpt          the card text — read raw, so an empty one renders no text
 *   page-attributes  the card order, dragged in the admin. Without it every post has
 *                    menu_order 0 and the order falls back to the title.
 *
 * ACF fields (flat, prefixed):
 *   products_section_title (clone of "Section Title") passed straight to section-heading.
 *
 * Usage — on the archive there is no post context, so pass the options store:
 *   get_template_part(
 *       'template-parts/archives/product/ranges',
 *       null,
 *       array( 'post_id' => 'option', 'prefix' => 'products_archive_' )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read the heading
 *                               from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to the field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.5.0
 */

$rn_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$rn_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$rn_heading = get_field( $rn_prefix . 'products_section_title', $rn_ctx );

// WP_Query and not get_posts(): get_posts() sets suppress_filters, which skips the
// posts_where / posts_join filters WPML scopes a query with, and the cards would list
// every translation of every range at once.
//
// A second query on the archive on purpose — see archive-products.php: the sections are
// a fixed sequence outside any loop, so the main query is left alone.
$rn_query = new WP_Query(
	array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'no_found_rows'  => true,
	)
);

// Mapped to the shape range-grid takes, so the component stays a pure renderer and
// serves both this and the home page. The link is the ACF link array's minimal form —
// a url is all the card needs, and an internal permalink never wants a target.
$rn_items = array();

foreach ( $rn_query->posts as $rn_post ) {
	$rn_items[] = array(
		'image' => get_post_thumbnail_id( $rn_post ),
		'title' => get_the_title( $rn_post ),
		'text'  => $rn_post->post_excerpt,
		'page'  => array( 'url' => get_permalink( $rn_post ) ),
	);
}

if ( ! $rn_heading && ! $rn_items ) {
	return;
}
?>
<section class="section-ranges mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
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
