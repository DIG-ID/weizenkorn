<?php
/**
 * Theme setup: theme supports, nav menus, sidebars, and plugin integrations.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.0.0
 */

/**
 * Registers nav menus and theme supports.
 */
function weizenkorn_theme_setup() {

	register_nav_menus(
		array(
			'mega-menu-1' => __( 'Mega Menu — Column 1 (Produkte / Arbeiten & Ausbildung)', 'weizenkorn' ),
			'mega-menu-2' => __( 'Mega Menu — Column 2 (Dienstleitungen / Über uns)', 'weizenkorn' ),
			'mega-menu-3' => __( 'Mega Menu — Column 3 (Gastronomie & Hotellerie / News / Kontakt)', 'weizenkorn' ),
			'footer-menu' => __( 'Footer Menu', 'weizenkorn' ),
		)
	);

	add_theme_support( 'menus' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' )
	);
}

add_action( 'after_setup_theme', 'weizenkorn_theme_setup' );

/**
 * Registers widgetized areas.
 */
function weizenkorn_register_sidebars() {

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'weizenkorn' ),
			'id'            => 'footer',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Header Language Switcher', 'weizenkorn' ),
			'id'            => 'header_ls',
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
}

add_action( 'widgets_init', 'weizenkorn_register_sidebars' );

/**
 * Adds an "Image" field to nav menu items (Appearance > Menus), used by the
 * mega menu columns. Value is saved as attachment ID in post meta.
 *
 * @param int $item_id Menu item ID.
 */
function weizenkorn_nav_menu_item_image_field( $item_id ) {
	$image_id = (int) get_post_meta( $item_id, '_menu_item_image', true );
	?>
	<p class="field-image description description-wide">
		<label for="edit-menu-item-image-<?php echo esc_attr( $item_id ); ?>">
			<?php esc_html_e( 'Image', 'weizenkorn' ); ?><br />
			<input
				type="hidden"
				id="edit-menu-item-image-<?php echo esc_attr( $item_id ); ?>"
				name="menu-item-image[<?php echo esc_attr( $item_id ); ?>]"
				value="<?php echo esc_attr( $image_id ); ?>"
			/>
		</label>
		<span class="weizenkorn-menu-item-image-preview">
			<?php if ( $image_id ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
			<?php endif; ?>
		</span>
		<button type="button" class="button weizenkorn-menu-item-image-select" data-title="<?php esc_attr_e( 'Select image', 'weizenkorn' ); ?>">
			<?php esc_html_e( 'Select image', 'weizenkorn' ); ?>
		</button>
		<button type="button" class="button weizenkorn-menu-item-image-remove" <?php echo $image_id ? '' : 'style="display:none;"'; ?>>
			<?php esc_html_e( 'Remove image', 'weizenkorn' ); ?>
		</button>
	</p>
	<?php
}

add_action( 'wp_nav_menu_item_custom_fields', 'weizenkorn_nav_menu_item_image_field', 10, 1 );

/**
 * Saves the nav menu item "Image" field as attachment ID post meta.
 *
 * @param int $menu_id         Nav menu ID (unused).
 * @param int $menu_item_db_id Menu item DB ID.
 */
function weizenkorn_save_nav_menu_item_image( $menu_id, $menu_item_db_id ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce ('update-nav_menu') already verified by WP core (nav-menus.php) before this action fires.
	if ( ! isset( $_POST['menu-item-image'][ $menu_item_db_id ] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- see above.
	$image_id = absint( $_POST['menu-item-image'][ $menu_item_db_id ] );

	if ( $image_id ) {
		update_post_meta( $menu_item_db_id, '_menu_item_image', $image_id );
	} else {
		delete_post_meta( $menu_item_db_id, '_menu_item_image' );
	}
}

add_action( 'wp_update_nav_menu_item', 'weizenkorn_save_nav_menu_item_image', 10, 2 );

/**
 * Adds a data-menu-image attribute to mega menu links that have an "Image"
 * set, so the menu overlay module (assets/js/menu-overlay.js) can swap the
 * preview image on hover/focus.
 *
 * @param array  $atts  HTML attributes for the menu item link.
 * @param object $item  Menu item data.
 * @param object $args  Menu display args.
 * @return array
 */
function weizenkorn_add_menu_item_image_attr( $atts, $item, $args ) {
	$mega_menu_locations = array( 'mega-menu-1', 'mega-menu-2', 'mega-menu-3' );

	if ( empty( $args->theme_location ) || ! in_array( $args->theme_location, $mega_menu_locations, true ) ) {
		return $atts;
	}

	$image_id = (int) get_post_meta( $item->ID, '_menu_item_image', true );

	if ( $image_id ) {
		$image_url = wp_get_attachment_image_url( $image_id, 'large' );

		if ( $image_url ) {
			$atts['data-menu-image'] = $image_url;
		}
	}

	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'weizenkorn_add_menu_item_image_attr', 10, 3 );

/**
 * Returns the image URL for the first mega menu item (across the 3 columns,
 * in menu order) that has one set. Used as the menu overlay's resting-state
 * image, before any item is hovered.
 *
 * @return string Image URL, or '' if no mega menu item has an image yet.
 */
function weizenkorn_get_default_mega_menu_image_url() {
	$locations = get_nav_menu_locations();

	foreach ( array( 'mega-menu-1', 'mega-menu-2', 'mega-menu-3' ) as $location ) {
		if ( empty( $locations[ $location ] ) ) {
			continue;
		}

		$items = wp_get_nav_menu_items( $locations[ $location ] );

		if ( ! $items ) {
			continue;
		}

		foreach ( $items as $item ) {
			$image_id = (int) get_post_meta( $item->ID, '_menu_item_image', true );

			if ( $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, 'large' );

				if ( $image_url ) {
					return $image_url;
				}
			}
		}
	}

	return '';
}

/**
 * Removes auto <p> wrapping from Contact Form 7 fields.
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

if ( ! function_exists( 'weizenkorn_acf_google_maps_key' ) ) :
	/**
	 * Sets the Google Maps API key for ACF map fields.
	 * Requires WEIZENKORN_GOOGLE_MAPS_API_KEY to be set in functions.php.
	 */
	function weizenkorn_acf_google_maps_key() {
		if ( defined( 'WEIZENKORN_GOOGLE_MAPS_API_KEY' ) && WEIZENKORN_GOOGLE_MAPS_API_KEY ) {
			acf_update_setting( 'google_api_key', WEIZENKORN_GOOGLE_MAPS_API_KEY );
		}
	}
endif;

add_action( 'acf/init', 'weizenkorn_acf_google_maps_key' );

/**
 * Lowers Yoast SEO metabox priority so ACF fields appear above it.
 *
 * @return string
 */
function weizenkorn_lower_yoast_metabox_priority() {
	return 'core';
}

add_filter( 'wpseo_metabox_prio', 'weizenkorn_lower_yoast_metabox_priority' );
