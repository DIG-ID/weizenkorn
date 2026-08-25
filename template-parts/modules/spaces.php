<?php
/**
 * Spaces — a heading and a bento of rooms, each photograph carrying the room's name on a
 * bar across it.
 *
 * Its own module and not a photo-mosaic variant: that one is placement and nothing else,
 * by its own docblock — no label, no hover, no link. A room here is named, which is the
 * whole point of the section. Nor is it product-overview, whose cards live on a hover
 * reveal and a link that these do not have.
 *
 * The arrangement comes from each tile's position, not from a field, so adding a room
 * keeps the rhythm — see _modules/_spaces.sass.
 *
 * ACF fields (flat, prefixed) — the `spaces` group. The group name produces the prefix, so
 * renaming it orphans whatever is stored:
 *   spaces_section_title  (clone of "Section Title") the overline, the rule and the
 *                         paragraph opposite. Clone the GROUP, and clone the shared
 *                         "Section Title" itself — never another page's clone of it.
 *   spaces_items          (repeater) one row per room:
 *     → image  (image → ID)
 *     → title  (text)      the name on the bar
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/spaces' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.8.0
 */

$sp_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$sp_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$sp_heading = weizenkorn_get_section_heading( $sp_prefix . 'spaces_', $sp_ctx );

if ( ! $sp_heading && ! have_rows( $sp_prefix . 'spaces_items', $sp_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="spaces mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		/*
		 * The slot above the rule holds an overline and this section has no display title at
		 * all, the same way the gastronomy photo mosaic reads. Always, not per caller: it is
		 * what the section is, not a variant of it.
		 */
		if ( $sp_heading ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array_merge( $sp_heading, array( 'title_style' => 'overline' ) )
			);
		}
		?>

		<?php if ( have_rows( $sp_prefix . 'spaces_items', $sp_ctx ) ) : ?>
			<div class="spaces__grid theme-grid mt-12 md:mt-8 xl:mt-24">
				<?php
				while ( have_rows( $sp_prefix . 'spaces_items', $sp_ctx ) ) :
					the_row();

					if ( ! get_sub_field( 'image' ) ) {
						continue;
					}
					?>
					<?php
					/*
					 * A <figure> with its <figcaption>, because that is what this is: a
					 * photograph and the name of what it shows. The bar sits over the
					 * photograph's bottom edge rather than under it, so a tile's height is the
					 * photograph's and the row stays level.
					 */
					?>
					<figure class="spaces__item">
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

						<?php if ( get_sub_field( 'title' ) ) : ?>
							<figcaption class="spaces__caption"><?php echo esc_html( get_sub_field( 'title' ) ); ?></figcaption>
						<?php endif; ?>
					</figure>
					<?php
				endwhile;
				?>
			</div>
		<?php endif; ?>

	</div>
</section>
