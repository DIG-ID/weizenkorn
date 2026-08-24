<?php
/**
 * Work & Training — "Dürfen wir weiterhelfen?" section (Figma "Frame
 * 1000006313" on Work & Training_desktop). Title + a phone/email contact row
 * (desktop: stacked in the right column beside an empty left one; tablet:
 * side by side, full width; mobile: stacked, full width), then the shared
 * cta-form module for the red form band below it.
 *
 * The heading here is title-only (template-parts/components/section-heading
 * has no slot for a phone/email row — its "description" columns are
 * wysiwyg text, and wp_kses_post strips the inline SVG icons), so the
 * contact row is this file's own markup, reusing section-heading's own
 * xl:col-start-7 xl:col-span-5 right-column convention for alignment.
 *
 * cta-form.php is called with none of its own heading fields set
 * (cta_title / cta_section_title), so it renders only the red form band —
 * this file's own heading above covers the "Dürfen wir weiterhelfen?"
 * title, and the shortcode falls back to the site-wide default form.
 *
 * ACF fields (flat, prefixed):
 *   work_training_contact_title (text)
 *   work_training_contact_phone (text)
 *   work_training_contact_email (email)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.7.0
 */

$wtc_title = get_field( 'work_training_contact_title' );
$wtc_phone = get_field( 'work_training_contact_phone' );
$wtc_email = get_field( 'work_training_contact_email' );

if ( ! $wtc_title ) {
	return;
}
?>
<section class="section-work-contact mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $wtc_title ) ); ?>

		<?php if ( $wtc_phone || $wtc_email ) : ?>
			<div class="theme-grid">
				<div class="section-work-contact__row text-brand-dark flex flex-col gap-4 md:flex-row md:gap-10 xl:flex-col xl:gap-4 col-span-2 md:col-span-6 xl:col-start-7 xl:col-span-5">
					<?php if ( $wtc_phone ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $wtc_phone ) ); ?>" class="body-text flex items-center gap-4">
							<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'phone' ); ?></span>
							<?php echo esc_html( $wtc_phone ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $wtc_email ) : ?>
						<a href="mailto:<?php echo esc_attr( $wtc_email ); ?>" class="body-text flex items-center gap-4">
							<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'mail' ); ?></span>
							<?php echo esc_html( $wtc_email ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/modules/cta-form' ); ?>
