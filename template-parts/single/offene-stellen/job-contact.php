<?php
/**
 * Open Positions single post — "Wir freuen uns auf Ihre Bewerbung."
 * section (Figma desktop node 4450:6539). A title, then a bordered box:
 * an intro line, and two columns of contact details underneath it.
 *
 * The two contact columns are free-form text (name(s), phone, email,
 * opening hours, address don't split into the same fields from one
 * posting to the next), so they're a plain textarea each rather than a
 * set of named sub-fields.
 *
 * ACF fields (flat, unprefixed):
 *   offene_stellen_contact_title (text)
 *   offene_stellen_contact_intro (text)
 *   offene_stellen_contact_left  (textarea, new_lines: br)
 *   offene_stellen_contact_right (textarea, new_lines: br)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

$jc_title = get_field( 'offene_stellen_contact_title' );

if ( ! $jc_title ) {
	return;
}

$jc_intro = get_field( 'offene_stellen_contact_intro' );
$jc_left  = get_field( 'offene_stellen_contact_left' );
$jc_right = get_field( 'offene_stellen_contact_right' );
?>
<section class="job-contact mt-10 md:mt-16 xl:mt-20">
	<div class="theme-container">
		<div class="theme-grid">
			<div class="col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-7">
				<h2 class="job-contact__title text-brand-red font-primary font-bold uppercase tracking-[0.5px] text-[14px] xl:text-[15px] mb-4">
					<?php echo esc_html( $jc_title ); ?>
				</h2>

				<div class="job-contact__box border border-brand-red px-6 py-6 md:px-8 md:py-8">
					<?php if ( $jc_intro ) : ?>
						<p class="body-text text-brand-dark mb-8"><?php echo esc_html( $jc_intro ); ?></p>
					<?php endif; ?>

					<?php
					/*
					 * xl:col-span-4/xl:col-start-6+col-span-5 approximate Figma's own
					 * pixel gap between the two columns (node 4481:6973/4574:6576) — a
					 * deliberate empty track between them, not just the grid's usual
					 * 20px gutter, which is all md:col-span-3 alone leaves once this
					 * grid's own xl:grid-cols-12 outgrows the tablet math it was written
					 * for. Both columns are free-text (name/phone/email/hours vary per
					 * posting), so this is an approximation, not an exact px match.
					 */
					?>
					<?php if ( $jc_left || $jc_right ) : ?>
						<div class="theme-grid">
							<?php if ( $jc_left ) : ?>
								<div class="body-text text-brand-dark col-span-2 md:col-span-3 xl:col-span-4"><?php echo wp_kses_post( $jc_left ); ?></div>
							<?php endif; ?>

							<?php if ( $jc_right ) : ?>
								<div class="body-text text-brand-dark col-span-2 md:col-span-3 xl:col-start-6 xl:col-span-5"><?php echo wp_kses_post( $jc_right ); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
