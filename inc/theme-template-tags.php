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
			'svg' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M31.3711 0V31.3711H-2.28882e-05V0H31.3711ZM1.83813 29.533H29.5329V1.83815H1.83813V29.533Z" fill="currentColor"/>
			<path d="M11.2996 12.4624H8.24219V22.2475H11.2996V12.4624Z" fill="currentColor"/>
			<path d="M16.5975 22.2481V16.9603C16.5975 16.6601 16.5852 16.366 16.671 16.1577C16.9039 15.5695 17.4124 14.9568 18.3254 14.9568C19.5202 14.9568 20.0594 15.8636 20.0594 17.1871V22.2481H23.5273V16.8072C23.5273 13.7803 21.824 12.3711 19.6488 12.3711C17.8965 12.3711 16.7997 13.3269 16.5975 14.0009V12.463H13.1602C13.203 13.2779 13.1602 22.2481 13.1602 22.2481H16.5975Z" fill="currentColor"/>
			<path d="M9.66183 11.2372H9.68634C10.8383 11.2372 11.5551 10.4836 11.549 9.54C11.5245 8.57191 10.8321 7.84277 9.70472 7.84277C8.57733 7.84277 7.83594 8.57191 7.83594 9.54C7.83594 10.4897 8.55282 11.2372 9.66183 11.2372Z" fill="currentColor"/>
			</svg>',
		),
		'instagram' => array(
			'url' => get_field( 'socials_instagram', 'option' ),
			'svg' => '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M14.6871 22.6988C19.1116 22.6988 22.6983 19.112 22.6983 14.6875C22.6983 10.263 19.1116 6.67627 14.6871 6.67627C10.2625 6.67627 6.67578 10.263 6.67578 14.6875C6.67578 19.112 10.2625 22.6988 14.6871 22.6988ZM14.6871 20.0284C17.6367 20.0284 20.0279 17.6372 20.0279 14.6875C20.0279 11.7379 17.6367 9.34669 14.6871 9.34669C11.7374 9.34669 9.34621 11.7379 9.34621 14.6875C9.34621 17.6372 11.7374 20.0284 14.6871 20.0284Z" fill="currentColor"/>
			<path d="M22.6985 5.34082C21.9611 5.34082 21.3633 5.93862 21.3633 6.67603C21.3633 7.41344 21.9611 8.01125 22.6985 8.01125C23.4359 8.01125 24.0337 7.41344 24.0337 6.67603C24.0337 5.93862 23.4359 5.34082 22.6985 5.34082Z" fill="currentColor"/>
			<path fill-rule="evenodd" clip-rule="evenodd" d="M0.873176 4.37424C0 6.08794 0 8.33131 0 12.818V16.5566C0 21.0434 0 23.2868 0.873176 25.0004C1.64124 26.5078 2.86681 27.7334 4.37424 28.5014C6.08794 29.3747 8.33131 29.3747 12.818 29.3747H16.5566C21.0434 29.3747 23.2868 29.3747 25.0004 28.5014C26.5078 27.7334 27.7334 26.5078 28.5014 25.0004C29.3747 23.2868 29.3747 21.0434 29.3747 16.5566V12.818C29.3747 8.33131 29.3747 6.08794 28.5014 4.37424C27.7334 2.86681 26.5078 1.64124 25.0004 0.873176C23.2868 0 21.0434 0 16.5566 0H12.818C8.33131 0 6.08794 0 4.37424 0.873176C2.86681 1.64124 1.64124 2.86681 0.873176 4.37424ZM16.5566 2.67043H12.818C10.5306 2.67043 8.97563 2.67251 7.77372 2.7707C6.60295 2.86635 6.00424 3.03973 5.58658 3.25254C4.58163 3.76459 3.76459 4.58163 3.25254 5.58658C3.03973 6.00424 2.86635 6.60295 2.7707 7.77372C2.67251 8.97563 2.67043 10.5306 2.67043 12.818V16.5566C2.67043 18.8441 2.67251 20.399 2.7707 21.6009C2.86635 22.7718 3.03973 23.3705 3.25254 23.7881C3.76459 24.793 4.58163 25.61 5.58658 26.1221C6.00424 26.3349 6.60295 26.5084 7.77372 26.604C8.97563 26.7021 10.5306 26.7043 12.818 26.7043H16.5566C18.8441 26.7043 20.399 26.7021 21.6009 26.604C22.7718 26.5084 23.3705 26.3349 23.7881 26.1221C24.793 25.61 25.61 24.793 26.1221 23.7881C26.3349 23.3705 26.5084 22.7718 26.604 21.6009C26.7021 20.399 26.7043 18.8441 26.7043 16.5566V12.818C26.7043 10.5306 26.7021 8.97563 26.604 7.77372C26.5084 6.60295 26.3349 6.00424 26.1221 5.58658C25.61 4.58163 24.793 3.76459 23.7881 3.25254C23.3705 3.03973 22.7718 2.86635 21.6009 2.7707C20.399 2.67251 18.8441 2.67043 16.5566 2.67043Z" fill="currentColor"/>
			</svg>',
		),
		'facebook'  => array(
			'url' => get_field( 'socials_facebook', 'option' ),
			'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
				<path d="M31 0H1.24C0.554125 0 0 0 0 0V31C0 31 0.554125 31 1.24 31H31C31 31 31 30.4459 31 29.76V1.24C31 0.554125 31 0 31 0ZM29.139 29.139H21.3861V18.9953H25.4161L26.0206 14.3181H21.3861V11.3305C21.3861 9.97425 21.762 9.052 23.7034 9.052H26.1795V4.867C25.7494 4.80887 24.2808 4.681 22.568 4.681C18.9953 4.681 16.5501 6.86262 16.5501 10.8655V14.3142H12.5124V18.9914H16.554V29.139H9.517H1.86097V15.8095V1.86097H29.139V29.139Z" fill="currentColor"/>
			</svg>',
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

/**
 * Reads a cloned "Section Title" group and returns it shaped for the
 * components/section-heading part.
 *
 * Every section that carries a heading clones the same "Section Title" group, and that
 * clone can be set to either Display in the admin. The two Displays do not read alike:
 *
 *   Display: Group     the clone keeps a name, so get_field( '{prefix}section_title' )
 *                      returns the whole group as one array.
 *   Display: Seamless  the clone has no name of its own and its fields land flat, so
 *                      that call returns nothing and each field has to be read by name.
 *
 * The flat read has one trap, which is why this lives in a helper instead of being
 * repeated per section. A Seamless clone stores its nested groups — `buttons`, holding
 * the two links — under a COMPOSITE field reference (field_A_field_B) that
 * acf_get_field() cannot resolve, so get_field( '{prefix}buttons' ) comes back empty
 * even though the links are in the database. It is the same failure as a cloned
 * repeater's have_rows() returning false while the admin shows the rows. The leaves keep
 * an ordinary reference, so they are read one by one and the array is rebuilt here.
 *
 * Before this existed, each section wrote its own fallback and every one of them read
 * the title and dropped the buttons — the heading looked right and the CTA silently
 * never rendered, on any Seamless clone.
 *
 * What this does NOT read is the clone's `image`, for the collision explained at the
 * flat read below.
 *
 * @since 1.6.0
 *
 * @param string     $prefix  Field-name prefix including the trailing underscore, e.g.
 *                            'product_overview_' or 'latest_product_overview_'.
 * @param int|string $post_id Optional. ACF post id / options store. Default: current post.
 * @return array Args for components/section-heading, or an empty array when there is no
 *               heading to render.
 */
function weizenkorn_get_section_heading( $prefix, $post_id = false ) {

	// Display: Group — the whole group in one read, nothing to reassemble.
	$group = get_field( $prefix . 'section_title', $post_id );

	if ( is_array( $group ) && ! empty( $group ) ) {
		return $group;
	}

	// Display: Seamless — field by field. Names are the group's verbatim, typos included.
	$heading = array();

	/*
	 * The clone's `image` is deliberately NOT read here. Flat storage puts it at
	 * {prefix}image, which is the same meta key as a section's own image field — on
	 * craft-showcase, {prefix}image IS craft_showcase_image, the left photo. Reading it
	 * as the heading's image drew that photo twice, once wide across the top and once in
	 * its own column.
	 *
	 * The other names here are safe because no section calls its own fields `title`,
	 * `subtitle` or `description`, while `image` is the one name a section is likely to
	 * want. A section that does need a heading image reads it itself and adds it to this
	 * array — see modules/trust.php. The Display: Group path above is unaffected, since
	 * there the clone's fields are nested and cannot collide.
	 */
	$fields = array(
		'title_heading',
		'subtitle',
		'title',
		'description',
		'desciption_left',
		'description_right',
	);

	foreach ( $fields as $field ) {
		$value = get_field( $prefix . $field, $post_id );

		if ( '' !== $value && null !== $value && false !== $value ) {
			$heading[ $field ] = $value;
		}
	}

	// The two links, read as leaves for the reason in the docblock above.
	$primary   = get_field( $prefix . 'buttons_prmary', $post_id );
	$secondary = get_field( $prefix . 'buttons_secondary', $post_id );

	if ( $primary || $secondary ) {
		$heading['buttons'] = array(
			'prmary'    => $primary ? $primary : null,
			'secondary' => $secondary ? $secondary : null,
		);
	}

	// No title means there is no heading — the caller decides what that implies for the
	// rest of its section.
	if ( empty( $heading['title'] ) ) {
		return array();
	}

	if ( empty( $heading['title_heading'] ) ) {
		$heading['title_heading'] = 'h2';
	}

	return $heading;
}
