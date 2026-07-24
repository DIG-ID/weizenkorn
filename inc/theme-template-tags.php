<?php
/**
 * Template tags and reusable output functions.
 *
 * @package weizenkorn
 * @subpackage Helpers
 * @since 1.0.0
 */

/**
 * Opens the main content wrapper.
 */
function weizenkorn_before_main_content() {
	?>
	<main id="main-content" class="main-content">
	<?php
}

add_action( 'before_main_content', 'weizenkorn_before_main_content' );

/**
 * Closes the main content wrapper.
 */
function weizenkorn_after_main_content() {
	?>
	</main><!-- #main-content -->
	<?php
}

add_action( 'after_main_content', 'weizenkorn_after_main_content' );

/**
 * Opens the post article wrapper.
 */
function weizenkorn_before_post_content() {
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
}

add_action( 'before_post_content', 'weizenkorn_before_post_content' );

/**
 * Closes the post article wrapper.
 */
function weizenkorn_after_post_content() {
	?>
	</article><!-- #article -->
	<?php
}

add_action( 'after_post_content', 'weizenkorn_after_post_content' );

/**
 * Outputs the site logo.
 * Uses the WP custom logo if set; falls back to the site name as a link.
 */
function weizenkorn_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>
		<?php
	}
}

add_action( 'theme_logo', 'weizenkorn_logo' );

/**
 * Gets the site logo attachment ID from the "Theme Options" ACF options page.
 * Shared by the header and footer (both display the same logo).
 *
 * @return int Attachment ID, or 0 if none is set.
 */
function weizenkorn_get_logo_id() {
	$general = get_field( 'general', 'option' );
	return ! empty( $general['logo'] ) ? (int) $general['logo'] : 0;
}

/**
 * Outputs the Yoast breadcrumbs.
 */
function weizenkorn_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	}
}

add_action( 'breadcrumbs', 'weizenkorn_breadcrumbs' );

/**
 * Outputs social media links from the "Theme Options" ACF options page.
 * URLs are managed in the WP admin under Theme Options > General.
 */
function weizenkorn_socials() {

	$socials = array(
		'linkedin'  => array(
			'url' => get_field( 'socials_linkedin', 'option' ),
			'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 13 13" fill="none" aria-hidden="true"><path d="M2.73438 12.6904H0.191406V4.51465H2.73438V12.6904ZM1.44922 3.4209C0.65625 3.4209 0 2.7373 0 1.91699C0 1.12402 0.65625 0.467773 1.44922 0.467773C2.26953 0.467773 2.92578 1.12402 2.92578 1.91699C2.92578 2.7373 2.26953 3.4209 1.44922 3.4209ZM9.70703 12.6904V8.72559C9.70703 7.76855 9.67969 6.56543 8.36719 6.56543C7.05469 6.56543 6.86328 7.57715 6.86328 8.64355V12.6904H4.32031V4.51465H6.75391V5.63574H6.78125C7.13672 5.00684 7.95703 4.32324 9.1875 4.32324C11.7578 4.32324 12.25 6.01855 12.25 8.20605V12.6904H9.70703Z" fill="currentColor"/></svg>',
		),
		'instagram' => array(
			'url' => get_field( 'socials_instagram', 'option' ),
			'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none" aria-hidden="true"><path d="M11.5257 5.57031C14.76 5.57031 17.4297 8.23996 17.4297 11.4743C17.4297 14.76 14.76 17.3783 11.5257 17.3783C8.23996 17.3783 5.62165 14.76 5.62165 11.4743C5.62165 8.23996 8.23996 5.57031 11.5257 5.57031ZM11.5257 15.3248C13.6306 15.3248 15.3248 13.6306 15.3248 11.4743C15.3248 9.36942 13.6306 7.67522 11.5257 7.67522C9.36942 7.67522 7.67522 9.36942 7.67522 11.4743C7.67522 13.6306 9.42076 15.3248 11.5257 15.3248ZM19.0212 5.36496C19.0212 4.59487 18.4051 3.97879 17.635 3.97879C16.865 3.97879 16.2489 4.59487 16.2489 5.36496C16.2489 6.13504 16.865 6.75112 17.635 6.75112C18.4051 6.75112 19.0212 6.13504 19.0212 5.36496ZM22.923 6.75112C23.0257 8.65067 23.0257 14.3493 22.923 16.2489C22.8203 18.0971 22.4096 19.6886 21.0748 21.0748C19.74 22.4096 18.0971 22.8203 16.2489 22.923C14.3493 23.0257 8.65067 23.0257 6.75112 22.923C4.9029 22.8203 3.31138 22.4096 1.92522 21.0748C0.590402 19.6886 0.179688 18.0971 0.0770089 16.2489C-0.0256696 14.3493 -0.0256696 8.65067 0.0770089 6.75112C0.179688 4.9029 0.590402 3.26004 1.92522 1.92522C3.31138 0.590402 4.9029 0.179688 6.75112 0.0770089C8.65067 -0.0256696 14.3493 -0.0256696 16.2489 0.0770089C18.0971 0.179688 19.74 0.590402 21.0748 1.92522C22.4096 3.26004 22.8203 4.9029 22.923 6.75112ZM20.4587 18.2511C21.0748 16.7623 20.9208 13.1685 20.9208 11.4743C20.9208 9.83147 21.0748 6.23772 20.4587 4.69754C20.048 3.7221 19.2779 2.90067 18.3025 2.54129C16.7623 1.92522 13.1685 2.07924 11.5257 2.07924C9.83147 2.07924 6.23772 1.92522 4.74888 2.54129C3.7221 2.95201 2.95201 3.7221 2.54129 4.69754C1.92522 6.23772 2.07924 9.83147 2.07924 11.4743C2.07924 13.1685 1.92522 16.7623 2.54129 18.2511C2.95201 19.2779 3.7221 20.048 4.74888 20.4587C6.23772 21.0748 9.83147 20.9208 11.5257 20.9208C13.1685 20.9208 16.7623 21.0748 18.3025 20.4587C19.2779 20.048 20.0993 19.2779 20.4587 18.2511Z" fill="currentColor"/></svg>',
		),
		'facebook'  => array(
			'url' => get_field( 'socials_facebook', 'option' ),
			'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none" aria-hidden="true"><path d="M22 11.5426C22 5.168 17.0755 0 11.0004 0C4.92522 0 0 5.168 0 11.5426C0 16.9556 3.55126 21.4975 8.34235 22.7451V15.0696H6.07369V11.5426H8.34235V10.0226C8.34235 6.09387 10.0368 4.27332 13.7127 4.27332C14.4095 4.27332 15.6124 4.41635 16.1039 4.56013V7.75771C15.8444 7.7288 15.3934 7.71434 14.8329 7.71434C13.029 7.71434 12.3323 8.431 12.3323 10.2949V11.5426H15.9256L15.3086 15.0696H12.333V23C17.7795 22.31 22.0007 17.4432 22.0007 11.5426H22Z" fill="currentColor"/></svg>',
		),
	);

	$html = '<div class="social-links">';

	foreach ( $socials as $platform => $social_data ) {
		if ( ! empty( $social_data['url'] ) ) {
			$html .= sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer" class="social-link social-link--%s" aria-label="%s">%s</a>',
				esc_url( $social_data['url'] ),
				esc_attr( $platform ),
				esc_attr( ucfirst( $platform ) ),
				$social_data['svg']
			);
		}
	}

	$html .= '</div>';

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action( 'socials', 'weizenkorn_socials' );

/**
 * Outputs an inline SVG icon by name.
 *
 * Source: Figma "Design System" page, Icons > Arrows section (large variants
 * only — the smaller "mobile" variants shown underneath each arrow are not
 * used here). Uses stroke="currentColor" so the icon follows the surrounding
 * text/button color (e.g. white on a filled button, red on hover).
 *
 * @since 1.1.0
 *
 * @param string $name Icon name: 'arrow-right', 'arrow-down' or 'arrow-download'.
 */
function weizenkorn_the_svg_icon( $name ) {

	$icons = array(
		'arrow-right'    => '<svg width="24" height="19" viewBox="0 0 23.7301 18.632" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 9.31602H22M10 17.816L22 9.31602L10 0.816024" stroke="currentColor" stroke-width="2" /></svg>',
		'arrow-down'     => '<svg width="19" height="24" viewBox="0 0 18.632 23.7301" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M9.31602 4.33488e-08L9.31602 22M0.816024 10L9.31602 22L17.816 10" stroke="currentColor" stroke-width="2" /></svg>',
		'arrow-download' => '<svg width="21" height="26" viewBox="0 0 21 25.5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M10.5 0V20.5M2 8.5L10.5 20.5L19 8.5M0 24.5H21" stroke="currentColor" stroke-width="2" /></svg>',
	);

	if ( empty( $icons[ $name ] ) ) {
		return;
	}

	echo $icons[ $name ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG markup, not user input.
}
