<?php
/**
 * Trust — a reassurance block: heading, wide image, and a lead paired with a list of
 * commitments.
 *
 * Holzmanufaktur's "Ihr Projekt in guten Händen": the title with its red rule, a wide
 * workshop photo, then a red overline and a lead line on the left with a bullet list
 * opposite — personal advice, regional materials, delivery on time.
 *
 * Not `intro-panel`, though the two are built the same way. The Figma analysis reserves
 * that name for the plain interior intro — a big title and a paragraph, counted on 19
 * pages and still to build on the Kerzen, Holzspielwaren and Living Collection templates.
 * This section is a different thing wearing the same component: it carries an image and
 * two columns, and it exists to answer "can I trust these people with my project". Naming
 * it for that keeps `intro-panel` free for what actually needs it.
 *
 * WHAT COMES FROM WHERE
 *
 * The shared "Section Title" clone carries the title and the image. Everything below the
 * rule is this section's own: the clone's subtitle and two description fields could have
 * carried it, but they put the section at the mercy of the clone's `description` =
 * left|right|both switch for a layout that is always the same two columns — and an empty
 * clone took the whole section down with it.
 *
 * The image comes from the clone but is drawn here, not by the component: the design
 * insets it to columns 2–11 with the title, where the component draws its own image at
 * the full container width.
 *
 * Desktop — Figma 3974:6738, confirmed 2026-08-19 (canvas 1920 / container 1820):
 *
 *   image     columns 2–11 at 1517 x 450, the title's own inset rather than the
 *             container's full width
 *   lead      columns 2–5: red overline, then the bold line under it
 *   list      columns 8–11, opposite the lead
 *   type      20/30 throughout, as at tablet, with the markers 30px in
 *
 * Tablet — Figma 2882:4306, confirmed 2026-08-19 (canvas 834 / container 703):
 *
 *   stacked   image, then lead, then list, each the full container width and 32px apart
 *   image     703 x 346
 *   type      all three blocks at 20/30 — overline and lead bold, the list regular with
 *             disc markers 30px in and its items one leading apart
 *
 * Mobile — Figma 2882:4588, confirmed 2026-08-19 (canvas 393 / container 320):
 *
 *   stacked   the same three blocks, 16px apart rather than 32
 *   image     320 x 183
 *   type      14/22 throughout, markers 21px in. The 16px from the red rule down to the
 *             image is the margin the section-heading already carries, so the row adds
 *             nothing of its own.
 *
 * ACF fields (flat, prefixed) — the `trust` group. The group name produces the prefix,
 * so renaming it renames all of these and orphans whatever is stored:
 *   trust_section_title  (clone of "Section Title") the title and the image — leave
 *                        subtitle, descriptions and buttons off. Clone the GROUP, never a
 *                        repeater inside one: a cloned repeater stores a composite field
 *                        reference that have_rows() cannot resolve, and the admin keeps
 *                        showing the rows while the page renders nothing.
 *                        On a Seamless clone the image lands flat at trust_image, which
 *                        is where the read below looks when the group read comes back
 *                        without one.
 *   trust_overline       (text)         red line above the lead
 *   trust_lead           (textarea)     the bold line under the overline
 *   trust_list           (wysiwyg)      the commitments, written as a list — .body-text
 *                                       gives editor lists their markers back, since
 *                                       _base-styles strips them
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

// ACF read context: the current post normally, the options store on archives.
$tr_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several contexts.
$tr_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

/*
 * The heading comes from the shared helper, which reads a cloned "Section Title" group
 * in either of its Displays and rebuilds the links a Seamless clone hides behind a
 * composite field reference. See weizenkorn_get_section_heading() in
 * inc/theme-template-tags.php for why that read is not a plain get_field().
 *
 * Only the title travels to the component. Anything else the clone happens to carry is
 * dropped here rather than trusted to be empty, so a stray subtitle left over in the
 * admin cannot draw a second overline above this section's own.
 */
$tr_heading = weizenkorn_get_section_heading( $tr_prefix . 'trust_', $tr_ctx );

$tr_title = ! empty( $tr_heading['title'] )
	? array(
		'title'         => $tr_heading['title'],
		'title_heading' => ! empty( $tr_heading['title_heading'] ) ? $tr_heading['title_heading'] : 'h2',
	)
	: array();

/*
 * The clone's image, in either Display. Group nests it in the array the helper returns;
 * Seamless stores it flat at {prefix}image — trust_image — which is unambiguous here
 * because the section has no image field of its own. See the helper's note on why it
 * leaves that key for the caller to read.
 */
$tr_image = ! empty( $tr_heading['image'] )
	? (int) $tr_heading['image']
	: (int) get_field( $tr_prefix . 'trust_image', $tr_ctx );

/*
 * Every part is optional and the section is whatever is filled in — a title alone still
 * reads, and so does the image with the two columns under a heading set elsewhere. Only
 * an entirely empty group returns.
 */
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
/*
 * 96 / 128 / 192px above and below, the rhythm the other modules carry. Adjacent
 * siblings' vertical margins collapse to the larger of the two, so this does not add to
 * the previous section's bottom margin — the gap is these values, not their sum.
 */
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
		 * the lead and the list are placed on the next one by their own column starts —
		 * no nested grid and no second .theme-grid.
		 *
		 * The row gap is the frames' distance from the image down to the text, 16 → 32, and
		 * the same 32 holds at desktop. The top margin only exists at desktop: the frames
		 * put 16 / 56px between the red rule and the image, and the section-heading already
		 * carries 16 / 32 of it, so mobile needs nothing and desktop needs 24. Tablet is
		 * unmeasured — its frame starts below the heading.
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
