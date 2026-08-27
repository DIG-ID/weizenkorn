<?php
/**
 * For Social Offices & Partners — "Wir sind für Sie da" section (Figma
 * desktop node 2118:783). A grid of contact-person cards — three per row
 * at desktop, two at tablet, one at mobile — each a photo, a name with an
 * optional mail icon, and a role/department line.
 *
 * The photo is optional: without one the card falls back to the Figma
 * placeholder's own graphic (a silhouette over the brand's wheat sprout)
 * on a neutral background, traced into weizenkorn_the_svg_icon()'s
 * 'avatar-placeholder'.
 *
 * The mail icon shows only when the row's email field is filled in — a
 * card with no email address has nothing to link the icon to.
 *
 * ACF fields (flat, prefixed):
 *   social_offices_team_title (text)
 *   social_offices_team_items (repeater) → image (image, ID, optional),
 *                              name (text), role (text), email (email,
 *                              optional)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.9.0
 */

$team_title = get_field( 'social_offices_team_title' );

if ( ! $team_title || ! have_rows( 'social_offices_team_items' ) ) {
	return;
}
?>
<section class="section-team mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $team_title ) ); ?>

		<div class="theme-grid mt-8 md:mt-14 xl:mt-16 gap-y-8 md:gap-y-10 xl:gap-y-12">
			<?php
			while ( have_rows( 'social_offices_team_items' ) ) :
				the_row();

				if ( ! get_sub_field( 'name' ) ) {
					continue;
				}

				$team_image = get_sub_field( 'image' );
				$team_email = get_sub_field( 'email' );
				?>
				<div class="card-team col-span-2 md:col-span-3 xl:col-span-4">
					<div class="card-team__media">
						<?php if ( $team_image ) : ?>
							<?php
							echo wp_get_attachment_image(
								$team_image,
								'large',
								false,
								array(
									'class'   => 'w-full h-full object-cover',
									'loading' => 'lazy',
								)
							);
							?>
						<?php else : ?>
							<span class="card-team__placeholder flex items-center justify-center w-full h-full text-white" aria-hidden="true">
								<?php weizenkorn_the_svg_icon( 'avatar-placeholder' ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="card-team__caption border border-brand-red bg-white">
						<div class="flex items-center justify-between gap-4">
							<h3 class="title-card"><?php echo esc_html( get_sub_field( 'name' ) ); ?></h3>

							<?php if ( $team_email ) : ?>
								<a href="mailto:<?php echo esc_attr( $team_email ); ?>" class="shrink-0" aria-label="<?php echo esc_attr( get_sub_field( 'name' ) ); ?>">
									<?php weizenkorn_the_svg_icon( 'mail' ); ?>
								</a>
							<?php endif; ?>
						</div>

						<?php if ( get_sub_field( 'role' ) ) : ?>
							<p class="body-text mt-2"><?php echo esc_html( get_sub_field( 'role' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
