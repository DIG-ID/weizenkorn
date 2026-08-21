<?php
/**
 * Overview cards — a grid of preview cards for a page's own child pages, with the image,
 * title and text pulled from each child's own "Overview Card" fields. Reused by any hub
 * page that needs to list its children.
 *
 * Expects to be called from inside an already-open .theme-container — it renders the
 * grid-inset row of cards and no section or container wrapper of its own. Query and field
 * reading only; each card is template-parts/components/card-overview.php.
 *
 * ACF fields (flat, read from each CHILD page):
 *   overview_card_image  (image → return ID) required — cards without one are skipped
 *   overview_card_title  (text)              falls back to the child's post title
 *   overview_card_text   (textarea / wpautop)
 *
 * @param array $args {
 *     @type int $parent_id Optional. Post ID whose children to list. Default: current post.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.4.0
 */

$parent_id = ! empty( $args['parent_id'] ) ? (int) $args['parent_id'] : get_the_ID();

$children = get_children(
	array(
		'post_parent' => $parent_id,
		'post_type'   => 'page',
		'post_status' => 'publish',
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	)
);

$cards = array();

foreach ( $children as $child ) {
	$image = get_field( 'overview_card_image', $child->ID );

	if ( ! $image ) {
		continue;
	}

	$cards[] = array(
		'image' => $image,
		'title' => get_field( 'overview_card_title', $child->ID ) ? get_field( 'overview_card_title', $child->ID ) : get_the_title( $child->ID ),
		'text'  => get_field( 'overview_card_text', $child->ID ),
		'url'   => get_permalink( $child->ID ),
	);
}

if ( ! $cards ) {
	return;
}
?>
<div class="theme-grid">
	<?php
	// A nested .theme-grid and not flex: .theme-grid already sets display:grid, so flex
	// utilities on the same element silently lose — both are plain classes of equal
	// specificity, and grid was winning and squeezing every <li> into one track.
	?>
	<ul class="overview-cards theme-grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 xl:gap-x-[25px] gap-y-8 md:gap-y-16 list-none m-0 p-0">
		<?php foreach ( $cards as $card ) : ?>
			<li class="col-span-2 md:col-span-6 xl:col-span-4">
				<?php get_template_part( 'template-parts/components/card-overview', null, $card ); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
