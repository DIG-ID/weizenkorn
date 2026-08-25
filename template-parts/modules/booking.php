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
 * The two are arranged three different ways, which is why they carry explicit rows at
 * desktop rather than relying on auto-placement:
 *
 *   mobile   stacked, button first and the overline under it
 *   tablet   side by side, button at the container's left edge and the overline
 *            opposite, the two centred against each other
 *   desktop  with one button, both sit in the right-hand column, overline on top and the
 *            button beneath it — the reverse of the source order, hence the row-starts.
 *            With two, the pair moves to the left on columns 2 and 4 and shares the
 *            overline's row instead. Which arrangement applies follows from how many
 *            buttons are filled, so a venue changes it by filling a field.
 *
 *   With two buttons the tablet stacks them on columns 1-3, rows 1 and 2, with the
 *   overline on columns 5-6 beside the first.
 *
 * ACF fields (flat, prefixed) — the `booking` group. The group name produces the prefix,
 * so renaming it orphans whatever is stored:
 *   booking_section_title  (clone of "Section Title") the title, and the overline in
 *                          `subtitle`. Clone the GROUP, never a repeater inside one.
 *
 *                          `description_right` is read as a fallback for the overline, and
 *                          stripped of the markup its WYSIWYG adds. The overline belongs in
 *                          Subtitle — it is that field's own type — but "Description Right"
 *                          is where an editor reaches for a line of copy, and a venue whose
 *                          text sits there should not silently render nothing.
 *   booking_button         (link) the booking CTA, e.g. "Jetzt Verfügbarkeit prüfen"
 *   booking_button_2       (link) optional second CTA — Cantina e9 pairs "Tisch
 *                          reservieren" with "Catering Anfragen". Filling it changes the
 *                          desktop arrangement, see below.
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

/*
 * Subtitle first, then Description Right. The fallback is stripped rather than escaped as
 * HTML: it comes from a WYSIWYG, so it arrives wrapped in <p>, and the overline below is a
 * <p> itself — nesting them is invalid and the browser would close the outer one early,
 * dropping the classes off the text.
 */
$bk_note = ! empty( $bk_heading['subtitle'] )
	? $bk_heading['subtitle']
	: wp_strip_all_tags( ! empty( $bk_heading['description_right'] ) ? $bk_heading['description_right'] : '' );

$bk_note = trim( $bk_note );

/*
 * Collected rather than read one by one, so a venue that filled only the second field
 * still gets a button, and the placement below can count them.
 */
$bk_buttons = array_values(
	array_filter(
		array(
			get_field( $bk_prefix . 'booking_button', $bk_ctx ),
			get_field( $bk_prefix . 'booking_button_2', $bk_ctx ),
		)
	)
);

if ( ! $bk_heading && ! $bk_buttons ) {
	return;
}

/*
 * The frames give the two cases different desktop arrangements: one button sits under the
 * overline on the right, while a pair moves to the left on columns 2 and 4 and shares the
 * overline's row. Below desktop both read the same — the buttons on the left or stacked,
 * the overline opposite or beneath.
 */
$bk_pair = count( $bk_buttons ) > 1;

$bk_note_class = $bk_pair
	? 'md:col-start-5 md:col-span-2 md:row-start-1 xl:col-start-7 xl:col-span-5 xl:row-start-1'
	: 'md:col-start-5 md:col-span-2 xl:col-start-7 xl:col-span-5 xl:row-start-1';

/*
 * Two columns wide in every case, which is what the frames draw and what the button fills
 * (see _modules/_booking.sass). Equal cells, so a pair comes out the same width whatever
 * the labels are.
 *
 * A pair sits side by side only at desktop, on columns 2 and 4. At tablet the two stack on
 * columns 1-3 with the overline opposite — the frame draws them side by side there, but at
 * two columns each the long labels crowd, so they are given the wider span and their own
 * rows instead.
 */
$bk_action_classes = $bk_pair
	? array(
		'md:col-span-3 md:row-start-1 xl:col-start-2 xl:col-span-2 xl:row-start-1',
		'md:col-span-3 md:row-start-2 xl:col-start-4 xl:col-span-2 xl:row-start-1',
	)
	: array( 'md:col-span-2 xl:col-start-7 xl:col-span-2 xl:row-start-2' );

/*
 * A pair needs no top margin at either breakpoint — the section-heading's own margin under
 * the rule is already the whole gap both frames show — and its row is top-aligned, the
 * overline sitting against the top of the taller buttons. The single button keeps the
 * tablet's extra 16px and its centred row.
 */
$bk_row_class = $bk_pair
	? 'gap-y-6 md:items-start xl:gap-y-8'
	: 'gap-y-6 md:mt-4 md:items-center xl:mt-0 xl:items-start xl:gap-y-8';
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

		<?php if ( $bk_buttons || $bk_note ) : ?>
			<?php
			// A sibling grid and not a nested one: .theme-grid carries the columns, and only its
			// direct children can be placed on them. The row's own utilities depend on how many
			// buttons there are — see $bk_row_class above.
			?>
			<div class="booking__row theme-grid <?php echo esc_attr( $bk_row_class ); ?>">

				<?php foreach ( $bk_buttons as $bk_index => $bk_button ) : ?>
					<div class="booking__action col-span-2 <?php echo esc_attr( $bk_action_classes[ $bk_index ] ); ?>">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array_merge( $bk_button, array( 'style' => 'primary' ) )
						);
						?>
					</div>
				<?php endforeach; ?>

				<?php if ( $bk_note ) : ?>
					<p class="booking__note text-brand-dark col-span-2 <?php echo esc_attr( $bk_note_class ); ?>">
						<?php echo esc_html( $bk_note ); ?>
					</p>
				<?php endif; ?>

			</div>
		<?php endif; ?>

	</div>
</section>
