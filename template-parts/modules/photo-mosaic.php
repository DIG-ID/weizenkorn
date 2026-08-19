<?php
/**
 * Photo mosaic — a heading and a bento grid of photographs.
 *
 * Xyloba's "Kreativ. Lehrreich. Begeisternd.": a title, a button to an external site,
 * and five photos with the first one twice as tall as the rest. The gastronomy pages
 * use the same section with a different arrangement, which is why the layout is a
 * variant rather than the module's only shape.
 *
 * There is no card here: no label, no hover, no link per image. It is photography, so
 * the section is placement and nothing else.
 *
 * Layout — Figma 2882:5176, desktop confirmed 2026-08-17 (canvas 1920 / container
 * 1820):
 *
 *   heading   title on column 2 with the red rule full width under it, then the
 *             button 32px below the rule — all three from the shared section-heading,
 *             which already takes a primary button. 96px above the mosaic.
 *   mosaic    three columns of four grid columns each (588–591px in the frame against
 *             the grid's 593.33), across the full container rather than the usual
 *             inset. Two rows, 369px then 400px, 20px apart.
 *   items     only the first needs placing: it spans both rows, and the grid's own
 *             auto-placement drops the other four into the two columns beside it in
 *             the order they are entered. Adding a sixth continues the pattern into a
 *             third row without a rule of its own.
 *
 *   tablet    Figma 2882:5216, confirmed 2026-08-17. The first photo takes all six
 *             columns at 825px tall, the rest are three columns each, two per row.
 *   mobile    Figma 2882:5226, confirmed 2026-08-17. Everything full width and
 *             stacked, the first photo 377px tall and the rest 218px.
 *
 * ACF fields (flat, prefixed) — the `photo_mosaic` group:
 *   photo_mosaic_section_title  (clone of "Section Title") the title and the button;
 *                               `buttons.prmary` is the external link.
 *   photo_mosaic_items          (repeater) one row per photo:
 *     → image  (image → ID)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/photo-mosaic' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.6.0
 */

// ACF read context: the current post normally, the options store on archives.
$pm_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several contexts.
$pm_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

/*
 * The heading comes from the shared helper, which reads a cloned "Section Title" group
 * in either of its Displays and rebuilds the links a Seamless clone hides behind a
 * composite field reference. See weizenkorn_get_section_heading() in
 * inc/theme-template-tags.php for why that read is not a plain get_field().
 */
$pm_heading = weizenkorn_get_section_heading( $pm_prefix . 'photo_mosaic_', $pm_ctx );

if ( ! $pm_heading && ! have_rows( $pm_prefix . 'photo_mosaic_items', $pm_ctx ) ) {
	return;
}
?>
<section class="photo-mosaic mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $pm_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $pm_heading );
		}
		?>

		<?php if ( have_rows( $pm_prefix . 'photo_mosaic_items', $pm_ctx ) ) : ?>
			<?php
			/*
			 * The frames put 32 / 56 / 96px between the heading and the mosaic. The
			 * section-heading's button row already carries 32px below itself at every
			 * breakpoint, so only the difference is added — nothing at mobile, where its 32
			 * is the whole gap.
			 */
			?>
			<div class="photo-mosaic__grid theme-grid md:mt-6 xl:mt-16">
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
