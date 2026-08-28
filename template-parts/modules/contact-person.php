<?php
/**
 * Contact person — a title, a contact-person card (photo + name/role +
 * phone/email) — richer than the plain phone/email row
 * template-parts/modules/cta-form.php renders on its own (Work & Training,
 * Supported Jobs) — then the shared cta-form module for the red form band
 * below it, called with no title of its own so only the band renders.
 *
 * Shared by Supported Apprenticeships' and the Open Positions archive's own
 * "Bereit für Weizenkorn?" / "Dürfen wir weiterhelfen?" sections — the same
 * shape, confirmed against Figma (literally the same component, reused).
 *
 * ACF fields (flat, prefixed):
 *   {prefix}contact_title (text)
 *   {prefix}contact_image (image, ID)
 *   {prefix}contact_name  (text) e.g. "Patrizia Hofer, Leiterin Sozialdienst"
 *   {prefix}contact_phone (text)
 *   {prefix}contact_email (email)
 *
 * cta-form is called with the SAME prefix, so it resolves
 * {prefix}cta_shortcode/cta_title/cta_phone/cta_email under that context —
 * leave cta_title/cta_phone/cta_email unset there so only the band renders.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/contact-person', null, array( 'prefix' => 'apprenticeships_' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.10.0
 */

$cp_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$cp_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$cp_title = get_field( $cp_prefix . 'contact_title', $cp_ctx );

if ( ! $cp_title ) {
	return;
}

$cp_image = get_field( $cp_prefix . 'contact_image', $cp_ctx );
$cp_name  = get_field( $cp_prefix . 'contact_name', $cp_ctx );
$cp_phone = get_field( $cp_prefix . 'contact_phone', $cp_ctx );
$cp_email = get_field( $cp_prefix . 'contact_email', $cp_ctx );
?>
<section class="contact-person mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $cp_title ) ); ?>

		<?php if ( $cp_image || $cp_name || $cp_phone || $cp_email ) : ?>
			<div class="theme-grid mt-8 xl:mt-12">
				<div class="contact-person__row col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-8 flex flex-col md:flex-row gap-6 md:gap-8 xl:gap-10">
					<?php if ( $cp_image ) : ?>
						<div class="contact-person__media shrink-0 w-full md:w-[340px] xl:w-[594px] overflow-hidden">
							<?php
							echo wp_get_attachment_image(
								$cp_image,
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

					<?php if ( $cp_name || $cp_phone || $cp_email ) : ?>
						<div class="flex flex-col justify-center gap-4">
							<?php if ( $cp_name ) : ?>
								<p class="label-overline"><?php echo esc_html( $cp_name ); ?></p>
							<?php endif; ?>

							<?php if ( $cp_phone ) : ?>
								<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $cp_phone ) ); ?>" class="body-text flex items-center gap-4">
									<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'phone' ); ?></span>
									<?php echo esc_html( $cp_phone ); ?>
								</a>
							<?php endif; ?>

							<?php if ( $cp_email ) : ?>
								<a href="mailto:<?php echo esc_attr( $cp_email ); ?>" class="body-text flex items-center gap-4">
									<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'mail' ); ?></span>
									<?php echo esc_html( $cp_email ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part(
	'template-parts/modules/cta-form',
	null,
	array(
		'post_id' => $cp_ctx,
		'prefix'  => $cp_prefix,
	)
); ?>
