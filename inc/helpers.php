<?php
/**
 * General-purpose helper functions.
 *
 * @package weizenkorn
 * @subpackage Helpers
 * @since 1.0.0
 */

/**
 * Outputs one or more values to the browser console.
 *
 * Development/debugging helper. No-op unless WP_DEBUG is enabled, so stray
 * calls can never leak data (queries, user info, API responses) to visitors
 * in production.
 *
 * @param mixed ...$data Values to log.
 */
function weizenkorn_console_log( ...$data ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	$json = wp_json_encode( $data );
	add_action(
		'shutdown',
		function () use ( $json ) {
			wp_print_inline_script_tag( "console.log({$json});" );
		}
	);
}

/**
 * Turns a display phone number into a dialable tel: href value.
 *
 * A plain `preg_replace( '/[^0-9+]/', '', $phone )` is not enough on its own: DACH-region
 * numbers are conventionally written with the domestic trunk prefix in parentheses, e.g.
 * "+41 (0)61 686 91 31" — that "0" is a real digit, so a digits-and-plus-only filter keeps
 * it and produces an undialable "+41061686 9131". The parenthesised part has to go first.
 *
 * @param string $phone Display phone number, e.g. from an ACF text field.
 * @return string Digits and a leading + only, safe for `tel:` — never escaped, still needs
 *                esc_attr() at the point of output like any other href.
 */
function weizenkorn_phone_to_tel( $phone ) {
	$phone = preg_replace( '/\([^)]*\)/', '', $phone );
	return preg_replace( '/[^0-9+]/', '', $phone );
}
