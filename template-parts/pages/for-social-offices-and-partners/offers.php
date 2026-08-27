<?php
/**
 * For Social Offices & Partners — "Angebote im Überblick" section (Figma
 * desktop node 2118:783). Title, then a left column of offer links (each
 * a small label above its own "Mehr erfahren" button — stacked at
 * mobile/tablet, side by side at desktop) and a description on the
 * right, on the same columns section-heading's own right-description
 * uses. Not a fit for that component: its buttons_prmary slot is one
 * button with no label of its own, not a repeater of labelled links.
 *
 * ACF fields (flat, prefixed):
 *   social_offices_offers_title (text)
 *   social_offices_offers_items (repeater) → title (text), link (link)
 *   social_offices_offers_text  (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.9.0
 */

$off_title = get_field( 'social_offices_offers_title' );

if ( ! $off_title ) {
	return;
}

$off_text = get_field( 'social_offices_offers_text' );
?>
<section class="section-offers mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $off_title ) ); ?>

		<?php if ( have_rows( 'social_offices_offers_items' ) || $off_text ) : ?>
			<div class="theme-grid mt-8 xl:mt-12">
				<?php if ( have_rows( 'social_offices_offers_items' ) ) : ?>
					<div class="section-offers__list col-span-2 md:col-start-1 md:col-span-3 xl:col-start-2 xl:col-span-5 flex flex-col xl:flex-row gap-8 xl:gap-16">
						<?php
						while ( have_rows( 'social_offices_offers_items' ) ) :
							the_row();

							$off_link = get_sub_field( 'link' );

							if ( ! get_sub_field( 'title' ) && ! $off_link ) {
								continue;
							}
							?>
							<div class="section-offers__item flex flex-col gap-6">
								<?php if ( get_sub_field( 'title' ) ) : ?>
									<p class="label-overline"><?php echo esc_html( get_sub_field( 'title' ) ); ?></p>
								<?php endif; ?>

								<?php if ( $off_link ) : ?>
									<?php get_template_part( 'template-parts/components/button', null, array_merge( $off_link, array( 'style' => 'primary' ) ) ); ?>
								<?php endif; ?>
							</div>
							<?php
						endwhile;
						?>
					</div>
				<?php endif; ?>

				<?php if ( $off_text ) : ?>
					<div class="col-span-2 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5">
						<div class="body-text"><?php echo wp_kses_post( $off_text ); ?></div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
