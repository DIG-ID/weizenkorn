<?php
/**
 * Supported Apprenticeships — "Bereit für Weizenkorn?" section (Figma
 * desktop node 4018:7961). Title, a contact-person card (photo + name/role
 * + phone/email) — richer than the plain phone/email row
 * template-parts/modules/cta-form.php renders on its own (Work & Training,
 * Supported Jobs) — then the shared cta-form module for the red form band
 * below it, called with no title of its own so only the band renders.
 *
 * A "contact-person" module doesn't exist yet (see HANDOVER.md's "Ainda
 * por construir" list) — this is the first page needing this shape, so it
 * stays page-specific until a second page needs it too.
 *
 * ACF fields (flat, prefixed):
 *   apprenticeships_contact_title (text)
 *   apprenticeships_contact_image (image, ID)
 *   apprenticeships_contact_name  (text) e.g. "Patrizia Hofer, Leiterin
 *                                 Sozialdienst"
 *   apprenticeships_contact_phone (text)
 *   apprenticeships_contact_email (email)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.9.0
 */

$acp_title = get_field( 'apprenticeships_contact_title' );

if ( ! $acp_title ) {
	return;
}

$acp_image = get_field( 'apprenticeships_contact_image' );
$acp_name  = get_field( 'apprenticeships_contact_name' );
$acp_phone = get_field( 'apprenticeships_contact_phone' );
$acp_email = get_field( 'apprenticeships_contact_email' );
?>
<section class="section-contact-person mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $acp_title ) ); ?>

		<?php if ( $acp_image || $acp_name || $acp_phone || $acp_email ) : ?>
			<div class="theme-grid mt-8 xl:mt-12">
				<div class="section-contact-person__row col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-8 flex flex-col md:flex-row gap-6 md:gap-8 xl:gap-10">
					<?php if ( $acp_image ) : ?>
						<div class="section-contact-person__media shrink-0 w-full md:w-[340px] xl:w-[594px] overflow-hidden">
							<?php
							echo wp_get_attachment_image(
								$acp_image,
								'large',
								false,
								array(
									'class'   => 'w-full h-full object-cover',
									'loading' => 'lazy',
								)
							);
							?>
						</div>
					<?php endif; ?>

					<?php if ( $acp_name || $acp_phone || $acp_email ) : ?>
						<div class="flex flex-col justify-center gap-4">
							<?php if ( $acp_name ) : ?>
								<p class="label-overline"><?php echo esc_html( $acp_name ); ?></p>
							<?php endif; ?>

							<?php if ( $acp_phone ) : ?>
								<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $acp_phone ) ); ?>" class="body-text flex items-center gap-4">
									<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'phone' ); ?></span>
									<?php echo esc_html( $acp_phone ); ?>
								</a>
							<?php endif; ?>

							<?php if ( $acp_email ) : ?>
								<a href="mailto:<?php echo esc_attr( $acp_email ); ?>" class="body-text flex items-center gap-4">
									<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'mail' ); ?></span>
									<?php echo esc_html( $acp_email ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/modules/cta-form', null, array( 'prefix' => 'apprenticeships_' ) ); ?>
