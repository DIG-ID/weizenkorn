<?php
/**
 * Header nav content: menu toggle, centered logo, WPML language switcher.
 * Shared between the normal header row and the sticky header bar.
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.1.3
 *
 * @var array $args {
 *     @type int $logo_id Attachment ID of the site logo (0 = fallback to site name).
 * }
 */

$defaults = array(
	'logo_id' => 0,
);

$args = wp_parse_args( $args, $defaults );
?>
<button type="button" class="header-main__menu-toggle" aria-expanded="false" aria-controls="menu-overlay" aria-label="<?php esc_attr_e( 'Open menu', 'weizenkorn' ); ?>">
	<span class="menu-toggle__bars" aria-hidden="true">
		<span class="bar bar--top"></span>
		<span class="bar bar--middle"></span>
		<span class="bar bar--bottom"></span>
	</span>
</button>

<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-main__logo" rel="home">
	<?php
	if ( $args['logo_id'] ) {
		echo wp_get_attachment_image( $args['logo_id'], 'full', false, array( 'class' => 'header-main__logo-image' ) );
	} else {
		bloginfo( 'name' );
	}
	?>
</a>

<nav class="header-main__lang" aria-label="<?php esc_attr_e( 'Language switcher', 'weizenkorn' ); ?>">
	<?php do_action( 'wpml_add_language_selector' ); ?>
</nav>
