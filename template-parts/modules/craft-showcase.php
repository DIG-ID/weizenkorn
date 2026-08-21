<?php
/**
 * Craft showcase — a heading and two image columns: the left one captioned with a
 * paragraph under its image, the right one a single taller image.
 *
 * Named for what it says rather than for any one page's heading, more than one page using
 * it. Not `feature`: the Figma analysis applies that word to at least four different
 * blocks and defines none of them.
 *
 * The three parts are direct items of the page grid, each with its own columns and row —
 * no wrapper columns, so the paragraph takes a span of its own rather than a max-width
 * inside a cell. At desktop the second image comes out level with the left pair by
 * spanning their two rows rather than carrying a height.
 *
 * ACF fields (flat, prefixed) — the `craft_showcase` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   craft_showcase_section_title    (clone of "Section Title") the heading.
 *   craft_showcase_image            (image → ID) the left, captioned image.
 *   craft_showcase_text             (textarea)   the paragraph under it.
 *   craft_showcase_image_secondary  (image → ID) the right, taller image.
 *
 * Usage:
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

$cs_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$cs_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$cs_heading = weizenkorn_get_section_heading( $cs_prefix . 'craft_showcase_', $cs_ctx );

if ( ! $cs_heading
	&& ! get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx )
	&& ! get_field( $cs_prefix . 'craft_showcase_image_secondary', $cs_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so these do not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="craft-showcase mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $cs_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $cs_heading );
		}
		?>

		<?php
		// The section-heading already carries part of this gap, so only the difference is
		// added — nothing at desktop, where its own margin is the whole gap.
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
