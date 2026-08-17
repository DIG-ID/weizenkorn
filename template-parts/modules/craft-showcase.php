<?php
/**
 * Craft showcase — a heading and two image columns: the left one captioned with a
 * paragraph under its image, the right one a single taller image.
 *
 * Named for what it says rather than for any one page's heading, because more than one
 * page uses it: "Liebe zum Detail" on Holzspielwaren and "Zeitloses Design, nachhaltig
 * gefertigt" elsewhere, both of them statements about how the things are made. That is
 * the bet this name takes — a page that wants this layout for something other than
 * craft would need renaming, or its own module.
 *
 * Not `feature`: the Figma analysis already applies that word to at least four
 * different blocks and defines none of them.
 *
 * Layout — Figma 2882:2823, desktop confirmed 2026-08-14 (canvas 1920 / container
 * 1820):
 *
 *   heading   title on column 2 with the red rule full width under it, from the
 *             shared section-heading. This heading has no overline, so the component
 *             renders nothing after its title block and the frame's 32px gap to the
 *             content is that block's own bottom margin — hence no top margin here at
 *             desktop.
 *   content   the three parts are direct items of the page grid, each with its own
 *             columns and row — no wrapper columns, so the paragraph can take a span of
 *             its own instead of a max-width inside a cell.
 *   desktop   image on columns 2–6 row 1, paragraph on 2–5 row 2, second image on 7–11
 *             across both rows. The frame's 585px paragraph against the 741px image is
 *             four columns against five, and its 553px second image comes out level with
 *             the left pair by spanning their two rows rather than carrying a height.
 *   tablet    all three full width and stacked, the paragraph on five of six columns —
 *             the frame's 579px.
 *
 *   tablet / mobile — no Figma frame yet. The columns stack full width and the images
 *   keep their ratios. Interim values.
 *
 * ACF fields (flat, prefixed) — the `craft_showcase` group. The group name is
 * what produces this prefix, so renaming it renames all of these and orphans whatever
 * is stored:
 *   craft_showcase_section_title    (clone of "Section Title") the heading.
 *   craft_showcase_image            (image → ID) the left, captioned image.
 *   craft_showcase_text             (textarea)   the paragraph under it.
 *   craft_showcase_image_secondary  (image → ID) the right, taller image.
 *
 * Usage — on a range page the fields come from the current post:
 *   get_template_part( 'template-parts/modules/craft-showcase' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

// ACF read context: the current post normally, the options store on archives.
$cs_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several contexts.
$cs_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

/*
 * Both shapes of the "Section Title" clone are accepted, as in the other modules:
 * Display: Group stores an extra key and get_field() returns the group as an array,
 * Display: Seamless stores only the flat fields. Neither is wrong.
 */
$cs_heading = get_field( $cs_prefix . 'craft_showcase_section_title', $cs_ctx );

if ( ! $cs_heading && get_field( $cs_prefix . 'craft_showcase_title', $cs_ctx ) ) {
	$cs_tag = get_field( $cs_prefix . 'craft_showcase_title_heading', $cs_ctx );

	$cs_heading = array(
		'title'         => get_field( $cs_prefix . 'craft_showcase_title', $cs_ctx ),
		'title_heading' => $cs_tag ? $cs_tag : 'h2',
		'subtitle'      => get_field( $cs_prefix . 'craft_showcase_subtitle', $cs_ctx ),
	);
}

if ( ! $cs_heading
	&& ! get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx )
	&& ! get_field( $cs_prefix . 'craft_showcase_image_secondary', $cs_ctx ) ) {
	return;
}
?>
<?php
/*
 * The frames give this section 96 / 128 / 196px above it. Adjacent siblings' vertical
 * margins collapse to the larger of the two, so these do not add to the previous
 * section's bottom margin — the gap is these values, not their sum.
 *
 * Desktop uses the scale's 48 (192px) rather than the frame's 196: 196 is not a step
 * (48 is 192, 52 is 208) and the 4px is not worth an arbitrary value here. Mobile and
 * tablet are exact — 24 = 96px, 32 = 128px.
 */
?>
<section class="craft-showcase mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $cs_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $cs_heading );
		}
		?>

		<?php
		/*
		 * The frames put 32px between the rule and the content at both tablet and
		 * desktop. The section-heading already carries 16 / 24 / 32px of its own there,
		 * so only the difference is added — nothing at desktop, where its 32 is the whole
		 * gap.
		 */
		?>
		<div class="craft-showcase__row theme-grid mt-4 md:mt-2 xl:mt-0">

			<?php if ( get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx ) ) : ?>
				<figure class="craft-showcase__media">
					<?php
					echo wp_get_attachment_image(
						get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx ),
						'large',
						false,
						array(
							'class'   => 'w-full h-full object-cover',
							'loading' => 'lazy',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( get_field( $cs_prefix . 'craft_showcase_text', $cs_ctx ) ) : ?>
				<div class="craft-showcase__text text-brand-dark">
					<?php echo wp_kses_post( get_field( $cs_prefix . 'craft_showcase_text', $cs_ctx ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( get_field( $cs_prefix . 'craft_showcase_image_secondary', $cs_ctx ) ) : ?>
				<figure class="craft-showcase__media craft-showcase__media--side">
					<?php
					echo wp_get_attachment_image(
						get_field( $cs_prefix . 'craft_showcase_image_secondary', $cs_ctx ),
						'large',
						false,
						array(
							'class'   => 'w-full h-full object-cover',
							'loading' => 'lazy',
						)
					);
					?>
				</figure>
			<?php endif; ?>

		</div>
	</div>
</section>
