<?php
/**
 * Booking — a title, an overline opposite the button, and the button that takes the
 * visitor to the venue's own booking page.
 *
 * A module and not a page part because every gastronomy venue repeats it with its own
 * wording — "Catering Anfragen" on Cantina e9, and so on.
 *
 * The section-heading component is given the title alone — its title-only mode, the one
 * the About teaser uses — and this module lays out the rest itself. Not because the clone
 * lacks the fields: `subtitle` is exactly the overline the design asks for. It is where
 * the component puts them, stacking the subtitle and the buttons together in the left
 * intro column, while this design has the button left and the overline opposite it on the
 * same row.
 *
 * So the overline is read off the heading array and placed here, and the button stays its
 * own `booking_button` field rather than the clone's `buttons.prmary` — the two are
 * interchangeable, and the separate field is what the venues already have filled in.
 *
 * ACF fields (flat, prefixed) — the `booking` group. The group name produces the prefix,
 * so renaming it orphans whatever is stored:
 *   booking_section_title  (clone of "Section Title") the title, and the overline in
 *                          `subtitle`. Clone the GROUP, never a repeater inside one.
 *   booking_button         (link) the booking CTA, e.g. "Jetzt Verfügbarkeit prüfen"
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/booking' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.7.0
 */

$bk_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$bk_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$bk_heading = weizenkorn_get_section_heading( $bk_prefix . 'booking_', $bk_ctx );

if ( ! $bk_heading && ! get_field( $bk_prefix . 'booking_button', $bk_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="booking mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		// Title and rule only: the whole array would have the component draw the overline in
		// its left column, above the button instead of beside it.
		if ( ! empty( $bk_heading['title'] ) ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'title'         => $bk_heading['title'],
					'title_heading' => ! empty( $bk_heading['title_heading'] ) ? $bk_heading['title_heading'] : 'h2',
				)
			);
		}
		?>

		<?php if ( get_field( $bk_prefix . 'booking_button', $bk_ctx ) || ! empty( $bk_heading['subtitle'] ) ) : ?>
			<?php
			// A sibling grid and not a nested one: .theme-grid carries the columns, and only its
			// direct children can be placed on them.
			//
			// No top margin at mobile — what the section-heading leaves under the rule is the
			// whole gap there. items-center only from tablet up, where the two share a row.
			?>
			<div class="booking__row theme-grid gap-y-6 md:mt-4 md:items-center xl:mt-6">

				<?php if ( get_field( $bk_prefix . 'booking_button', $bk_ctx ) ) : ?>
					<div class="booking__action col-span-2 md:col-span-3 xl:col-start-2 xl:col-span-4">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array_merge(
								get_field( $bk_prefix . 'booking_button', $bk_ctx ),
								array( 'style' => 'primary' )
							)
						);
						?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $bk_heading['subtitle'] ) ) : ?>
					<p class="booking__note label-overline text-brand-dark col-span-2 md:col-start-5 md:col-span-2 xl:col-start-7 xl:col-span-5">
						<?php echo esc_html( $bk_heading['subtitle'] ); ?>
					</p>
				<?php endif; ?>

			</div>
		<?php endif; ?>

	</div>
</section>
