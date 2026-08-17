<?php
/**
 * Products archive — Ranges section ("Produkte mit Qualität und Mehrwert").
 *
 * The same card flow as the home page's Products block, rendered by the shared
 * components/range-grid, with the heading from components/section-heading.
 *
 * The cards ARE the `products` posts — one per range — not an ACF repeater. Each
 * range has its own single page, so the post is already the record; keeping a
 * repeater with the same title, text and image beside it would be two copies of
 * one thing, and they drift the moment someone edits only one of them.
 *
 * The heading stays in the archive's own fields: it belongs to the archive, not to
 * any range.
 *
 * Layout — Figma, desktop confirmed 2026-08-11: heading on columns 2–11 with the red
 * rule under it, an intro paragraph five columns wide beneath, then the card flow on
 * the same inset span. Three thirds on the first row, 40% / 60% on the second.
 *
 * The `products` post type must support:
 *   thumbnail        the card image
 *   excerpt          the card text — read raw, so an empty one simply renders no text
 *   page-attributes  the card order, dragged in the admin. Without it every post has
 *                    menu_order 0 and the order falls back to the title, which is not
 *                    the order the design uses.
 *
 * ACF fields (flat, prefixed):
 *   products_section_title (clone of the "Section Title" group) — passed straight to
 *                          section-heading, the same call the home page makes.
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
 *     @type int|string $post_id Optional. ACF post id / options store to read the
 *                               heading from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to the field name.
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

/*
 * WP_Query and not get_posts(): get_posts() sets suppress_filters to true, which
 * skips the posts_where / posts_join filters WPML scopes a query with. The cards
 * would then list every translation of every range at once.
 *
 * This is a second query on the archive rather than the main one on purpose — see
 * archive-products.php: the sections are a fixed sequence outside any loop, so the
 * main query is left alone and each section fetches only what it renders.
 */
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

/*
 * Mapped to the shape range-grid takes, so the component stays a pure renderer and
 * serves both this and the home page without knowing where the rows came from. The
 * link is the ACF link array's minimal form — a url is all the card needs, and an
 * internal permalink never wants a target.
 */
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
