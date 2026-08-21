<?php
/**
 * Trust — a reassurance block: heading, wide image, and a lead paired with a list of
 * commitments.
 *
 * Not `intro-panel`, which is reserved for the plain interior intro — a big title and a
 * paragraph — still to build on the other range templates.
 *
 * The shared "Section Title" clone carries the title and the image; everything below the
 * rule is this section's own. The clone's subtitle and descriptions could have carried it,
 * but they put a layout that is always the same two columns at the mercy of the clone's
 * left|right|both switch, and an empty clone took the whole section down with it.
 *
 * The image comes from the clone but is drawn here, not by the component: the design
 * insets it to the title's own columns, where the component draws its own image at the
 * full container width.
 *
 * ACF fields (flat, prefixed) — the `trust` group. The group name produces the prefix, so
 * renaming it renames all of these and orphans whatever is stored:
 *   trust_section_title  (clone of "Section Title") the title and the image — leave
 *                        subtitle, descriptions and buttons off. Clone the GROUP, never a
 *                        repeater inside one: a cloned repeater stores a composite field
 *                        reference that have_rows() cannot resolve, and the admin keeps
 *                        showing the rows while the page renders nothing.
 *   trust_overline       (text)     red line above the lead
 *   trust_lead           (textarea) the bold line under the overline
 *   trust_list           (wysiwyg)  the commitments, written as a list
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/trust' );
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
 * @since 1.6.0
 */

$tr_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$tr_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
//
// Only the title travels to the component. Anything else the clone happens to carry is
// dropped rather than trusted to be empty, so a stray subtitle left in the admin cannot
// draw a second overline above this section's own.
$tr_heading = weizenkorn_get_section_heading( $tr_prefix . 'trust_', $tr_ctx );

$tr_title = ! empty( $tr_heading['title'] )
	? array(
		'title'         => $tr_heading['title'],
		'title_heading' => ! empty( $tr_heading['title_heading'] ) ? $tr_heading['title_heading'] : 'h2',
	)
	: array();

// The clone's image in either Display: Group nests it in the array the helper returns,
// Seamless stores it flat at trust_image — unambiguous here, the section having no image
// field of its own. See the helper's note on why it leaves that key to the caller.
$tr_image = ! empty( $tr_heading['image'] )
	? (int) $tr_heading['image']
	: (int) get_field( $tr_prefix . 'trust_image', $tr_ctx );

// Every part is optional and the section is whatever is filled in; only an entirely
// empty group returns.
if (
	! $tr_title
	&& ! $tr_image
	&& ! get_field( $tr_prefix . 'trust_overline', $tr_ctx )
	&& ! get_field( $tr_prefix . 'trust_lead', $tr_ctx )
	&& ! get_field( $tr_prefix . 'trust_list', $tr_ctx )
) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="trust mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $tr_title ) {
			get_template_part( 'template-parts/components/section-heading', null, $tr_title );
		}
		?>

		<?php
		/*
		 * One grid for the image and the two columns under it. The image spans the row, so
		 * the lead and the list are placed on the next one by their own column starts — no
		 * nested grid and no second .theme-grid.
		 *
		 * The top margin only exists at desktop: the section-heading already carries the
		 * whole gap below the rule at mobile.
		 */
		?>
		<div class="trust__row theme-grid gap-y-4 md:gap-y-8 xl:mt-6">

			<?php if ( $tr_image ) : ?>
				<div class="trust__media col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php
					echo wp_get_attachment_image(
						$tr_image,
						'full',
						false,
						array(
							'class'   => 'w-full h-full object-cover',
							'loading' => 'lazy',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<?php if ( get_field( $tr_prefix . 'trust_overline', $tr_ctx ) || get_field( $tr_prefix . 'trust_lead', $tr_ctx ) ) : ?>
				<div class="trust__intro col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-4">

					<?php if ( get_field( $tr_prefix . 'trust_overline', $tr_ctx ) ) : ?>
						<p class="trust__overline text-brand-red">
							<?php echo esc_html( get_field( $tr_prefix . 'trust_overline', $tr_ctx ) ); ?>
						</p>
					<?php endif; ?>

					<?php if ( get_field( $tr_prefix . 'trust_lead', $tr_ctx ) ) : ?>
						<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
						<div class="trust__lead text-brand-dark">
							<?php echo wp_kses_post( get_field( $tr_prefix . 'trust_lead', $tr_ctx ) ); ?>
						</div>
					<?php endif; ?>

				</div>
			<?php endif; ?>

			<?php if ( get_field( $tr_prefix . 'trust_list', $tr_ctx ) ) : ?>
				<div class="trust__list text-brand-dark col-span-2 md:col-span-6 xl:col-start-8 xl:col-span-4">
					<?php echo wp_kses_post( get_field( $tr_prefix . 'trust_list', $tr_ctx ) ); ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
