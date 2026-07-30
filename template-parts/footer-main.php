<?php
/**
 * Site footer: logo + socials, contact info, sitemap, newsletter (MC4WP) and
 * the legal/copyright bar. Source: Figma "Home_desktop" footer.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.2.1
 */

?>
<footer id="footer-main" class="footer-main">

	<div class="theme-container">
		<div class="theme-grid footer-main__grid">

			<div class="footer-main__brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-main__logo" rel="home">
					<?php
					if ( weizenkorn_get_logo_id() ) {
						echo wp_get_attachment_image( weizenkorn_get_logo_id(), 'full', false, array( 'class' => 'footer-main__logo-image' ) );
					} else {
						bloginfo( 'name' );
					}
					?>
				</a>

				<?php do_action( 'socials' ); ?>
			</div>

			<div class="footer-main__card footer-main__address">
				<h2 class="footer-main__heading"><?php esc_html_e( 'Kontakt', 'weizenkorn' ); ?></h2>

				<div class="footer-main__address-body">
					<?php if ( get_field( 'general_address', 'option' ) ) : ?>
						<div class="footer-main__address-line"><?php echo wp_kses_post( get_field( 'general_address', 'option' ) ); ?></div>
					<?php endif; ?>

					<?php if ( get_field( 'general_phone', 'option' ) ) : ?>
						<p class="footer-main__contact-line">
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', get_field( 'general_phone', 'option' ) ) ); ?>"><?php echo esc_html( get_field( 'general_phone', 'option' ) ); ?></a>
						</p>
					<?php endif; ?>

					<?php if ( get_field( 'general_email', 'option' ) ) : ?>
						<p class="footer-main__contact-line">
							<a href="mailto:<?php echo esc_attr( get_field( 'general_email', 'option' ) ); ?>"><?php echo esc_html( get_field( 'general_email', 'option' ) ); ?></a>
						</p>
					<?php endif; ?>
				</div>
			</div>

			<nav class="footer-main__card footer-main__sitemap" aria-label="<?php esc_attr_e( 'Footer menu', 'weizenkorn' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-menu',
						'container'      => false,
						'menu_class'     => 'footer-main__sitemap-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>

			<?php if ( get_field( 'general_newsletter_shortcode', 'option' ) ) : ?>
				<div class="footer-main__card footer-main__newsletter">
					<h2 class="footer-main__heading"><?php esc_html_e( 'Newsletter', 'weizenkorn' ); ?></h2>
					<div class="newsletter-form__wrapper px-4 xl:px-0">
						<?php echo do_shortcode( get_field( 'general_newsletter_shortcode', 'option' ) ); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<div class="theme-container">
		<hr class="footer-main__divider" />

		<div class="footer-main__legal">
			<div class="theme-grid">
				<div class="footer-main__legal-copyright pt-7 md:pt-5 xl:pt-0">
					<p class="footer-main__copyright">
						<?php
						printf(
							/* translators: 1: current year, 2: site name. */
							esc_html__( '©%1$s %2$s. All rights reserved.', 'weizenkorn' ),
							esc_html( gmdate( 'Y' ) ),
							esc_html( get_bloginfo( 'name' ) )
						);
						?>
					</p>
				</div>
				<div class="footer-main__legal-nav">
					<nav class="footer-main__legal-menu" aria-label="<?php esc_attr_e( 'Legal menu', 'weizenkorn' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'copyright-menu',
								'container'      => false,
								'menu_class'     => 'footer-main__legal-menu-list',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				</div>
			</div>
		</div>
	</div>

</footer>
