<?php
/**
 * Fixed bottom-right promo box — title, text and arrow on load, collapsing to just the
 * arrow after a few seconds (assets/js/sticky-cta.js), re-expanding on hover or focus.
 * Home only for now, included from header.php behind an is_front_page() check.
 *
 * ACF fields (flat, "Sticky CTA" group):
 *   sticky_cta_title (text)
 *   sticky_cta_text  (textarea)
 *   sticky_cta_link  (text) a plain URL — the whole box is the link, so it needs no title
 *                    or target of its own
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.4.0
 */

$weizenkorn_sticky_cta_title = get_field( 'sticky_cta_title' );
$weizenkorn_sticky_cta_text  = get_field( 'sticky_cta_text' );
$weizenkorn_sticky_cta_link  = get_field( 'sticky_cta_link' );

if ( ! $weizenkorn_sticky_cta_title || ! $weizenkorn_sticky_cta_link ) {
	return;
}
?>
<a
	href="<?php echo esc_url( $weizenkorn_sticky_cta_link ); ?>"
	class="sticky-cta"
	id="sticky-cta"
>
	<span class="sticky-cta__content">
		<strong class="sticky-cta__title"><?php echo esc_html( $weizenkorn_sticky_cta_title ); ?></strong>
		<?php if ( $weizenkorn_sticky_cta_text ) : ?>
			<span class="sticky-cta__text"><?php echo esc_html( $weizenkorn_sticky_cta_text ); ?></span>
		<?php endif; ?>
	</span>
	<span class="sticky-cta__icon" aria-hidden="true">
		<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
	</span>
	<img
		class="sticky-cta__logo-icon"
		src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icon-wheat-white.png' ); ?>"
		alt=""
		width="86"
		height="45"
	/>
</a>
