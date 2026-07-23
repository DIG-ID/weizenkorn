<?php
/**
 * Fullscreen mega menu overlay (mega-menu-1/2/3 nav locations).
 * Opened/closed by the header's menu-toggle button (assets/js/menu-overlay.js).
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.4
 */

$weizenkorn_default_menu_image = weizenkorn_get_default_mega_menu_image_url();
?>
<div id="menu-overlay" class="menu-overlay" aria-hidden="true">
	<div class="theme-container">

		<hr class="menu-overlay__divider" />

		<div class="theme-grid menu-overlay__grid">

			<nav class="menu-overlay__col menu-overlay__col--1" aria-label="<?php esc_attr_e( 'Menu column 1', 'weizenkorn' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mega-menu-1',
						'container'      => false,
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>

			<nav class="menu-overlay__col menu-overlay__col--2" aria-label="<?php esc_attr_e( 'Menu column 2', 'weizenkorn' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mega-menu-2',
						'container'      => false,
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>

			<nav class="menu-overlay__col menu-overlay__col--3" aria-label="<?php esc_attr_e( 'Menu column 3', 'weizenkorn' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'mega-menu-3',
						'container'      => false,
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>

			<?php if ( $weizenkorn_default_menu_image ) : ?>
				<div class="menu-overlay__image">
					<img
						class="menu-overlay__image-el"
						src="<?php echo esc_url( $weizenkorn_default_menu_image ); ?>"
						data-default-src="<?php echo esc_url( $weizenkorn_default_menu_image ); ?>"
						alt=""
					/>
				</div>
			<?php endif; ?>

		</div>

	</div>
</div>
