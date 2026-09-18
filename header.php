<?php
/**
 * The header for the theme: outputs <head> and opens <body>.
 *
 * @package weizenkorn
 * @subpackage Core
 * @since 1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-MNWTLNWX');</script>
		<!-- End Google Tag Manager -->
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MNWTLNWX"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php do_action( 'wp_body_open' ); ?>
		<?php get_template_part( 'template-parts/header', 'main' ); ?>
		<?php get_template_part( 'template-parts/menu-overlay' ); ?>
		<?php
		// TEMP — LAUNCH: hidden at the client's own request, not ready to go live yet.
		// Uncomment to bring it back (home only for now — Figma: "hero section_first
		// moment on website").
		/* if ( is_front_page() ) { get_template_part( 'template-parts/components/sticky-cta' ); } */
		?>
