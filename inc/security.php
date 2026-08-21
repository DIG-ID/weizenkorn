<?php
/**
 * Theme security hardening.
 *
 * Complements the SG Security plugin, which is installed on every dig.id site, by
 * covering what it does not: REST API and author archive user enumeration, login error
 * disclosure, XML-RPC abuse, front-end search abuse and HTTP security headers.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Disable XML-RPC.
 *
 * The pingback.ping method can be abused for reflected DDoS and system.multicall
 * amplifies credential stuffing. Remove these three filters on a project that needs it.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Strip the pingback methods from the XML-RPC server.
 *
 * These work without authentication, so xmlrpc_enabled alone does not stop them.
 *
 * @param array $methods Registered XML-RPC methods.
 * @return array Filtered methods.
 */
function weizenkorn_remove_xmlrpc_pingback_methods( $methods ) {
	unset( $methods['pingback.ping'] );
	unset( $methods['pingback.extensions.getPingbacks'] );

	return $methods;
}
add_filter( 'xmlrpc_methods', 'weizenkorn_remove_xmlrpc_pingback_methods' );

/**
 * Remove the X-Pingback header, which advertises the endpoint.
 *
 * @param array $headers HTTP response headers.
 * @return array Filtered headers.
 */
function weizenkorn_remove_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );

	return $headers;
}
add_filter( 'wp_headers', 'weizenkorn_remove_pingback_header' );

/**
 * Block public access to the REST API users endpoints.
 *
 * Core exposes them to unauthenticated visitors, leaking real usernames.
 *
 * @param array $endpoints Registered REST API endpoints.
 * @return array Filtered endpoints.
 */
function weizenkorn_disable_rest_user_endpoints( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'] );
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}

	return $endpoints;
}
add_filter( 'rest_endpoints', 'weizenkorn_disable_rest_user_endpoints' );

/**
 * Block author archive enumeration.
 *
 * ?author=1 redirects to a URL containing the real username.
 *
 * @return void
 */
function weizenkorn_disable_author_archives() {
	if ( is_author() && ! is_user_logged_in() ) {
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'weizenkorn_disable_author_archives' );

/**
 * Disable front-end search by default.
 *
 * A 404 rather than a redirect, which would create soft-404s and point spam URLs at the
 * homepage. On a project that needs search, remove this filter and build search.php and
 * searchform.php for it — note that WooCommerce product search also uses ?s=.
 *
 * @return void
 */
function weizenkorn_disable_search() {
	if ( is_search() && ! is_admin() ) {
		global $wp_query;

		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'weizenkorn_disable_search' );

/**
 * Remove author data from oEmbed responses, which leaks usernames even when author
 * archives are blocked.
 *
 * @param array $data oEmbed response data.
 * @return array Filtered response data.
 */
function weizenkorn_remove_oembed_author( $data ) {
	unset( $data['author_name'] );
	unset( $data['author_url'] );

	return $data;
}
add_filter( 'oembed_response_data', 'weizenkorn_remove_oembed_author' );

/**
 * Remove users from core XML sitemaps.
 *
 * Yoast replaces core sitemaps on our stack; this is the safety net for a site where it
 * is disabled or misconfigured.
 *
 * @param WP_Sitemaps_Provider|false $provider Sitemap provider instance.
 * @param string                     $name     Provider name.
 * @return WP_Sitemaps_Provider|false Provider, or false to remove it.
 */
function weizenkorn_remove_users_sitemap( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}

	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'weizenkorn_remove_users_sitemap', 10, 2 );

/**
 * Use a generic login error message.
 *
 * The defaults reveal whether a username exists, letting an attacker confirm accounts.
 *
 * @return string Generic error message.
 */
function weizenkorn_generic_login_error() {
	return esc_html__( 'Login failed: invalid credentials.', 'weizenkorn' );
}
add_filter( 'login_errors', 'weizenkorn_generic_login_error' );

/**
 * Disable application passwords. Remove on a project that needs authenticated REST API
 * integrations through them.
 */
add_filter( 'wp_is_application_passwords_available', '__return_false' );

/**
 * Send standard HTTP security headers.
 *
 * Content-Security-Policy is deliberately left out — it has to be built per project (GTM,
 * embeds, payment scripts) and tested in Report-Only mode before being enforced.
 *
 * @return void
 */
function weizenkorn_security_headers() {
	if ( headers_sent() ) {
		return;
	}

	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );

	// Adjust per project if the site itself needs any of these features.
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );

	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
	}
}
add_action( 'send_headers', 'weizenkorn_security_headers' );
