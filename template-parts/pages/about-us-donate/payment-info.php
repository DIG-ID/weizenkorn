<?php
/**
 * Donate page — "Jetzt Spenden" section. A title, then a row of payment
 * methods (a QR code + a label each — "via Banküberweisung", "via TWINT"),
 * the foundation's own bank account details, and a note on tax
 * deductibility.
 *
 * Desktop's own Figma frame (node 4093:5351 and its siblings) only shows
 * one payment method (Bank), but both the tablet and mobile frames show
 * two side by side (Bank, TWINT) in an otherwise identical layout — read as
 * the desktop frame simply not having been updated with the second one
 * yet, so donate_payment_methods is a repeater (any number of methods)
 * rather than two fixed fields, and this renders however many the client
 * fills in.
 *
 * At desktop the three pieces (methods, bank details, tax note) sit in one
 * row via explicit column starts; at tablet the methods repeater alone
 * fills the row (each method side by side inside it) and bank
 * details/tax note wrap to a second row beneath it; at mobile everything
 * stacks — all confirmed against the three Figma frames.
 *
 * ACF fields (flat, prefixed):
 *   donate_payment_title        (text)
 *   donate_payment_methods      (repeater) → qr_code (image), label (text)
 *   donate_payment_bank_details (textarea / wpautop)
 *   donate_payment_tax_note     (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.13.0
 */

$pi_title = get_field( 'donate_payment_title' );

if ( ! $pi_title ) {
	return;
}

$pi_bank_details = get_field( 'donate_payment_bank_details' );
$pi_tax_note     = get_field( 'donate_payment_tax_note' );
$pi_has_methods  = have_rows( 'donate_payment_methods' );
?>
<section class="payment-info mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $pi_title ) ); ?>

		<?php if ( $pi_has_methods || $pi_bank_details || $pi_tax_note ) : ?>
			<div class="theme-grid mt-8 xl:mt-12">
				<?php if ( $pi_has_methods ) : ?>
					<div class="payment-info__methods col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-5 flex flex-col md:flex-row gap-8 md:gap-6">
						<?php
						while ( have_rows( 'donate_payment_methods' ) ) :
							the_row();

							$pi_qr = get_sub_field( 'qr_code' );

							if ( ! $pi_qr ) {
								continue;
							}
							?>
							<div class="payment-info__method flex flex-col items-center text-center gap-4">
								<?php
								echo wp_get_attachment_image(
									$pi_qr,
									'medium',
									false,
									array( 'class' => 'w-[186px] h-[186px] object-contain' )
								);
								?>
								<?php if ( get_sub_field( 'label' ) ) : ?>
									<span class="body-text text-brand-dark"><?php echo esc_html( get_sub_field( 'label' ) ); ?></span>
								<?php endif; ?>
							</div>
							<?php
						endwhile;
						?>
					</div>
				<?php endif; ?>

				<?php if ( $pi_bank_details ) : ?>
					<div class="payment-info__bank body-text text-brand-dark col-span-2 mt-12 md:col-span-3 md:mt-12 xl:col-start-7 xl:col-span-3 xl:mt-0">
						<?php echo wp_kses_post( $pi_bank_details ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $pi_tax_note ) : ?>
					<div class="payment-info__tax-note body-text text-brand-dark col-span-2 mt-8 md:col-span-3 md:mt-12 xl:col-start-10 xl:col-span-3 xl:mt-0">
						<?php echo wp_kses_post( $pi_tax_note ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
