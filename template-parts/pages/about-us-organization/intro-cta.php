<?php
/**
 * About Us — Organization page — "Warum Weizenkorn?" section. A title (the
 * shared section-heading, title only), then a two-column row: an optional
 * iframe embed on the left, the description and an optional button on the
 * right.
 *
 * A page-specific fork of template-parts/modules/intro-cta.php rather than
 * adding the iframe there: that shared module deliberately leaves the left
 * column empty on every other page that uses it (Supported Jobs, Supported
 * Apprenticeships, For Social Offices & Partners) — see its own docblock —
 * so the embed is a one-off for this page only, not a new option on the
 * shared shape.
 *
 * At tablet both columns are a plain md:col-span-3 (no col-start) — 3 of 6
 * each, auto-flowing side by side to fill the whole tablet grid edge to
 * edge, rather than mirroring desktop's inset positioning. At desktop they
 * mirror each other around the grid's centre (xl:col-start-2/col-span-5 +
 * xl:col-start-7/col-span-5), the same split the shared module's own
 * right-hand column already uses.
 *
 * ACF fields (flat, prefixed):
 *   organization_why_intro_title  (text)
 *   organization_why_intro_text   (textarea / wpautop)
 *   organization_why_intro_button (link)
 *   organization_why_iframe_url   (url) — optional; no iframe renders without one.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.12.0
 */

$oic_title = get_field( 'organization_why_intro_title' );

if ( ! $oic_title ) {
	return;
}

$oic_text   = get_field( 'organization_why_intro_text' );
$oic_button = get_field( 'organization_why_intro_button' );
$oic_iframe = get_field( 'organization_why_iframe_url' );
?>
<section class="intro-cta mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $oic_title ) ); ?>

		<?php if ( $oic_iframe || $oic_text || $oic_button ) : ?>
			<div class="theme-grid gap-y-8">
				<?php if ( $oic_iframe ) : ?>
					<div class="intro-cta__iframe col-span-2 md:col-span-3 xl:col-start-2 xl:col-span-5">
						<?php
						/*
						 * Attributes fixed here rather than left to a raw-HTML field: only the
						 * URL is admin-editable, so there is no risk of an arbitrary <iframe>
						 * (or worse) being pasted in and surviving wp_kses_post() — which strips
						 * iframes anyway, the reason this is its own url field and not folded
						 * into $oic_text.
						 */
						?>
						<iframe
							src="<?php echo esc_url( $oic_iframe ); ?>"
							class="fp-iframe w-full h-[400px] border border-[lightgray]"
							scrolling="no"
							allow="autoplay; fullscreen; clipboard-write"
							allowfullscreen
						></iframe>
					</div>
				<?php endif; ?>

				<?php if ( $oic_text || $oic_button ) : ?>
					<div class="intro-cta__col col-span-2 md:col-span-3 xl:col-start-7 xl:col-span-5 flex flex-col items-start gap-8">
						<?php if ( $oic_text ) : ?>
							<div class="body-text"><?php echo wp_kses_post( $oic_text ); ?></div>
						<?php endif; ?>

						<?php if ( $oic_button ) : ?>
							<?php get_template_part( 'template-parts/components/button', null, array_merge( $oic_button, array( 'style' => 'primary' ) ) ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
