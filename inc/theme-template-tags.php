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
 * Outputs the site logo, falling back to the site name as a link.
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
 *
 * Shared by the header and the footer, which show the same logo.
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
 * Drawn with stroke="currentColor", so it follows the surrounding text or button colour.
 *
 * @since 1.1.0
 *
 * @param string $name Icon name: 'arrow-right', 'arrow-down', 'arrow-download', 'phone',
 *                     'mail' or 'avatar-placeholder'.
 */
function weizenkorn_the_svg_icon( $name ) {

	$icons = array(
		'arrow-right'        => '<svg width="24" height="19" viewBox="0 0 23.7301 18.632" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 9.31602H22M10 17.816L22 9.31602L10 0.816024" stroke="currentColor" stroke-width="2" /></svg>',
		'arrow-down'         => '<svg width="19" height="24" viewBox="0 0 18.632 23.7301" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M9.31602 4.33488e-08L9.31602 22M0.816024 10L9.31602 22L17.816 10" stroke="currentColor" stroke-width="2" /></svg>',
		'arrow-download'     => '<svg width="21" height="26" viewBox="0 0 21 25.5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M10.5 0V20.5M2 8.5L10.5 20.5L19 8.5M0 24.5H21" stroke="currentColor" stroke-width="2" /></svg>',
		'phone'              => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>',
		'mail'               => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="2" /><path d="M2 7l10 6 10-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>',
		// The team section's no-photo fallback (a silhouette + the brand's wheat sprout),
		// traced from the Figma placeholder asset — cropped to its own bounds and coloured
		// via currentColor rather than the original's hardcoded white, so the caller sets
		// the colour with a text-* class instead of a second copy of the artwork.
		'avatar-placeholder' => '<svg width="95" height="122" viewBox="192 118 104 122" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="244.5" cy="149" r="26" stroke="currentColor" stroke-width="2" /><path d="M259.918 191.538L257.582 190.846V193.231C258.361 193.462 259.139 193.692 259.918 194C277.283 200.231 289.664 216.692 289.664 235.923H292C292 215.385 278.529 197.846 259.918 191.538ZM197 235.923H199.336C199.336 216.692 211.795 200.154 229.082 194C229.861 193.692 230.639 193.462 231.418 193.231V190.846L229.082 191.538C210.393 197.846 197 215.385 197 235.923ZM249.484 230.615C253.766 226.769 254.234 225.154 254.234 222.692V186.692C253.455 186.385 252.676 186.154 251.898 185.923V194C251.898 195.462 251.898 196.462 248.549 199.615V185.231C247.771 185.077 246.992 185.077 246.213 185V202C245.512 202.846 244.967 203.538 244.5 204.231C244.111 203.615 243.566 202.846 242.787 202V185C242.008 185 241.23 185.154 240.451 185.231V199.692C237.102 196.538 237.102 195.538 237.102 194.077V185.923C236.324 186.154 235.545 186.385 234.766 186.692V222.692C234.766 225.154 235.234 226.692 239.516 230.615C243.176 233.923 243.332 236 243.332 236H245.668C245.668 235.692 246.135 233.769 249.484 230.615ZM243.332 231.231C242.709 230.538 242.008 229.769 241.074 228.923C237.102 225.308 237.102 224.308 237.102 222.692V218.692C237.725 219.385 238.504 220.154 239.516 221.077C243.176 224.385 243.332 226.462 243.332 226.462V231.308V231.231ZM243.332 221.692C242.709 221 242.008 220.231 241.074 219.385C237.102 215.769 237.102 214.769 237.102 213.154V209.154C237.725 209.846 238.504 210.615 239.516 211.538C243.098 214.769 243.332 216.846 243.332 216.923V221.692ZM243.332 212.154C242.709 211.462 242.008 210.692 241.074 209.846C237.102 206.231 237.102 205.231 237.102 203.615V199.615C237.725 200.308 238.504 201.077 239.516 202C243.098 205.231 243.332 207.308 243.332 207.385V212.154ZM245.668 207.308C245.668 207.231 245.902 205.231 249.484 201.923C250.496 201 251.275 200.231 251.898 199.538V203.538C251.898 205.077 251.898 206.154 247.926 209.769C246.992 210.615 246.291 211.385 245.668 212.077V207.308ZM245.668 216.846C245.668 216.769 245.902 214.769 249.484 211.462C250.496 210.538 251.275 209.769 251.898 209.077V213.077C251.898 214.615 251.898 215.692 247.926 219.308C246.992 220.154 246.291 220.923 245.668 221.615V216.846ZM245.668 226.462C245.668 226.385 245.902 224.385 249.484 221.077C250.496 220.154 251.275 219.385 251.898 218.692V222.692C251.898 224.231 251.898 225.308 247.926 228.923C246.992 229.769 246.291 230.538 245.668 231.231V226.462Z" fill="currentColor" /></svg>',
	);

	if ( empty( $icons[ $name ] ) ) {
		return;
	}

	echo $icons[ $name ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG markup, not user input.
}

/**
 * Returns the date() format string for the current WPML language.
 *
 * Only the day/month/year ORDER and punctuation are decided here — the month NAME itself
 * is translated automatically by date_i18n() (what get_the_date() calls under the hood)
 * from WordPress core's own locale files, once WPML has switched the site's locale for
 * that language. So a German date reads "27. August 2026", and once English/French are
 * added they read "August 27, 2026" / "27 août 2026" with no further translation work.
 *
 * Defaults to German (the site's only language today, and Swiss German convention) when
 * WPML is inactive or the current language isn't one of the formats listed here.
 *
 * @since 1.10.0
 *
 * @return string A date() format string.
 */
function weizenkorn_get_date_format() {
	$formats = array(
		'de' => 'j. F Y',
		'en' => 'F j, Y',
		'fr' => 'j F Y',
	);

	$language = apply_filters( 'wpml_current_language', null );

	return $formats[ $language ] ?? $formats['de'];
}

/**
 * Reads a URL out of an ACF file field, whatever shape it was set up to return.
 *
 * A File field can hand back an array, an attachment ID or a plain URL, and a field that
 * holds a URL may have been typed as Text instead — the Xyloba video fields are one of
 * each. All four end up here as a URL, so a template never has to know which.
 *
 * @since 1.9.0
 *
 * @param mixed $value Raw field value.
 * @return string URL, or an empty string when there is nothing usable.
 */
function weizenkorn_get_file_url( $value ) {

	if ( is_array( $value ) ) {
		return ! empty( $value['url'] ) ? $value['url'] : '';
	}

	if ( is_numeric( $value ) ) {
		return (string) wp_get_attachment_url( (int) $value );
	}

	return is_string( $value ) ? trim( $value ) : '';
}

/**
 * Reads a cloned "Section Title" group and returns it shaped for the
 * components/section-heading part.
 *
 * The clone can be set to either Display in the admin, and the two do not read alike:
 * Group keeps a name, so one get_field() returns the whole group, while Seamless lands
 * its fields flat and each has to be read by name.
 *
 * The flat read has one trap, which is the reason this is a helper and not repeated per
 * section. A Seamless clone stores its nested `buttons` group under a COMPOSITE field
 * reference that acf_get_field() cannot resolve, so get_field( '{prefix}buttons' ) comes
 * back empty even though the links are in the database — the same failure as a cloned
 * repeater's have_rows() returning false while the admin shows the rows. The leaves keep
 * an ordinary reference, so they are read one by one and the array rebuilt here.
 *
 * Before this existed, every section's own fallback read the title and dropped the
 * buttons: the heading looked right and the CTA silently never rendered.
 *
 * The clone's `image` is deliberately NOT read — see the flat read below.
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
