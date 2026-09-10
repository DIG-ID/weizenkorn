<?php
/**
 * About Us — Organization page — "Jahresbericht - Rückblick mit Ausblick" section
 * (Figma node 4950:6866 onward, desktop only — no tablet/mobile frame exists for this
 * section yet, so the responsive behaviour below is common-sense: everything simply
 * stacks full-width, one block per row, matching how every other section on this page
 * already collapses at those breakpoints). A title (the shared section-heading, title
 * only), then the iframe embed alone across the top, a repeater of report-download
 * buttons and the description side by side in a second row below it.
 *
 * This is where the "Warum Weizenkorn?" iframe moved TO — the client added this whole
 * section and swapped the embedded document at the same time, and Figma now shows the
 * iframe here instead; see template-parts/modules/intro-cta.php's own docblock (the page
 * template calls it with the 'organization_why_' prefix) for the section that lost it.
 *
 * Desktop grid (Figma, confirmed against its own grid overlay): the iframe alone across
 * xl:col-start-3/col-span-8 — the middle 8 of the grid's 12 columns, NOT the standard
 * xl:col-start-2/col-span-10 inset most sections use, so it leaves one column bare on
 * each side. Buttons and text sit in a second row below it: buttons at
 * xl:col-start-3/col-span-4 (columns 3-6, under the iframe's own left edge), text at
 * xl:col-start-7/col-span-5 (columns 7-11 — the same col-start-7 the rest of this theme
 * already uses for a "right column" description, reaching one column further right than
 * the iframe's own right edge). All three are independent theme-grid children in that
 * same order, not a nested sub-grid: the buttons' and text's own col-start-3/col-start-7
 * fall inside columns the iframe's col-span-8 has already claimed in row 1, so CSS
 * Grid's own auto-placement bumps both down to row 2 with no explicit grid-row needed.
 * gap-y-8 on the wrapper is what spaces the two rows apart: .theme-grid itself carries
 * no row gap by design (see _layout/_grid.sass). That alone is only 32px, short of the
 * 96px Figma actually wants between the iframe and the row below it — the iframe's own
 * mb-16 makes up the other 64px (32 + 64 = 96) rather than raising gap-y-8 itself, which
 * would also inflate the class name's own "8" past what it still says.
 *
 * Below xl, where everything stacks to one column, the text reads BEFORE the buttons
 * (order-1 vs order-2) — the opposite of the desktop row's left-to-right buttons/text
 * order and of these two blocks' own DOM order here, a deliberate one-off for this
 * section rather than the source order the rest of the page's stacked sections keep.
 * xl:order-none resets both at desktop, where the explicit col-start values above
 * already fully determine position regardless of order.
 *
 * The buttons are a repeater rather than a fixed set: Figma shows several
 * "Jahresbericht 2020" buttons as a placeholder/example (one per year the client
 * publishes a report for), not a fixed count — add or remove rows to match how many
 * years are actually published. Each row is a single ACF Link field, same as every
 * other button-from-a-repeater shape in this theme, rendered with the shared button
 * component in its 'primary' style (Figma "buttons-default").
 *
 * ACF fields (flat, prefixed):
 *   organization_jahresbericht_title      (text)
 *   organization_jahresbericht_text       (textarea / wpautop)
 *   organization_jahresbericht_iframe_url (url) — optional; no iframe renders without one,
 *                                          same fp-iframe markup as the old "Warum
 *                                          Weizenkorn?" embed.
 *   organization_jahresbericht_buttons    (repeater) → link (link) — one button per row,
 *                                          skipped if empty.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.16.2
 */

$oj_title = get_field( 'organization_jahresbericht_title' );

if ( ! $oj_title ) {
	return;
}

$oj_text    = get_field( 'organization_jahresbericht_text' );
$oj_iframe  = get_field( 'organization_jahresbericht_iframe_url' );
$oj_buttons = have_rows( 'organization_jahresbericht_buttons' );
?>
<section class="jahresbericht mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $oj_title ) ); ?>

		<?php if ( $oj_iframe || $oj_buttons || $oj_text ) : ?>
			<div class="theme-grid gap-y-8">
				<?php if ( $oj_iframe ) : ?>
					<div class="jahresbericht__iframe col-span-2 md:col-span-6 xl:col-start-3 xl:col-span-8 mb-16">
						<?php
						/*
						 * Same reasoning as the old "Warum Weizenkorn?" embed this replaced: the URL
						 * is the only admin-editable part, so there is no risk of an arbitrary
						 * <iframe> surviving wp_kses_post() (which strips iframes outright) — that is
						 * why this is its own url field and not folded into $oj_text.
						 */
						?>
						<iframe
							src="<?php echo esc_url( $oj_iframe ); ?>"
							class="fp-iframe w-full h-[400px] border border-[lightgray]"
							scrolling="no"
							allow="autoplay; fullscreen; clipboard-write"
							allowfullscreen
						></iframe>
					</div>
				<?php endif; ?>

				<?php if ( $oj_buttons ) : ?>
					<div class="jahresbericht__buttons order-2 xl:order-none col-span-2 md:col-span-6 xl:col-start-3 xl:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-4">
						<?php
						while ( have_rows( 'organization_jahresbericht_buttons' ) ) :
							the_row();

							$oj_button = get_sub_field( 'link' );

							if ( ! $oj_button ) {
								continue;
							}

							get_template_part( 'template-parts/components/button', null, array_merge( $oj_button, array( 'style' => 'primary' ) ) );
						endwhile;
						?>
					</div>
				<?php endif; ?>

				<?php if ( $oj_text ) : ?>
					<div class="jahresbericht__text order-1 xl:order-none col-span-2 md:col-span-6 xl:col-start-7 xl:col-span-5 body-text">
						<?php echo wp_kses_post( $oj_text ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
