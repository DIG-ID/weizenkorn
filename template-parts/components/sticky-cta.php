<?php
/**
 * Fixed bottom-right promo box — title, text and arrow on load, collapsing to just the
 * arrow after a few seconds (assets/js/sticky-cta.js), re-expanding on hover or focus.
 * Home only for now, included from header.php behind an is_front_page() check.
 *
 * ACF fields (flat, "Sticky CTA" group):
 *   sticky_cta_active (true_false) the on/off switch — the content fields below stay
 *                      filled in either way, so turning it back on needs no retyping
 *   sticky_cta_title  (text)
 *   sticky_cta_text   (textarea)
 *   sticky_cta_link   (text) a plain URL — the whole box is the link, so it needs no
 *                     title or target of its own
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.4.0
 */

$weizenkorn_sticky_cta_active = get_field( 'sticky_cta_active' );
$weizenkorn_sticky_cta_title  = get_field( 'sticky_cta_title' );
$weizenkorn_sticky_cta_text   = get_field( 'sticky_cta_text' );
$weizenkorn_sticky_cta_link   = get_field( 'sticky_cta_link' );

if ( ! $weizenkorn_sticky_cta_active || ! $weizenkorn_sticky_cta_title || ! $weizenkorn_sticky_cta_link ) {
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
	<span class="sticky-cta__logo-icon" aria-hidden="true">
		<?php
		// Inline instead of icon-wheat-white.png: a raster PNG for a mark that only ever
		// needs to be plain white here — an SVG with a static white fill draws crisp at
		// any size instead of at the file's own fixed resolution.
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="83" height="45" viewBox="0 0 83 45" fill="none"><path d="M54.9705 5.76923L52.9295 5.15837V7.26244C53.6098 7.46606 54.2902 7.66968 54.9705 7.94118C70.1418 13.4389 80.959 27.9638 80.959 44.9321H83C83 26.8099 71.2303 11.3348 54.9705 5.76923ZM0 44.9321H2.04098C2.04098 27.9638 12.9262 13.371 28.0295 7.94118C28.7098 7.66968 29.3902 7.46606 30.0705 7.26244V5.15837L28.0295 5.76923C11.7016 11.3348 0 26.8099 0 44.9321ZM45.8541 40.2489C49.5959 36.8552 50.0041 35.4299 50.0041 33.2579V1.49321C49.3238 1.22172 48.6434 1.0181 47.9631 0.81448V7.94118C47.9631 9.23077 47.9631 10.1131 45.0377 12.8959V0.20362C44.3574 0.0678733 43.6771 0.0678733 42.9967 0V15C42.3844 15.7466 41.9082 16.3575 41.5 16.9683C41.1598 16.4253 40.6836 15.7466 40.0033 15V0C39.323 0 38.6426 0.135747 37.9623 0.20362V12.9638C35.0369 10.181 35.0369 9.29864 35.0369 8.00905V0.81448C34.3566 1.0181 33.6762 1.22172 32.9959 1.49321V33.2579C32.9959 35.4299 33.4041 36.7873 37.1459 40.2489C40.3434 43.1674 40.4795 45 40.4795 45H42.5205C42.5205 44.7285 42.9287 43.0317 45.8541 40.2489ZM40.4795 40.7919C39.9352 40.181 39.323 39.5023 38.5066 38.7557C35.0369 35.5656 35.0369 34.6833 35.0369 33.2579V29.7285C35.5811 30.3394 36.2615 31.0181 37.1459 31.8326C40.3434 34.7511 40.4795 36.5837 40.4795 36.5837V40.8597V40.7919ZM40.4795 32.3756C39.9352 31.7647 39.323 31.086 38.5066 30.3394C35.0369 27.1493 35.0369 26.267 35.0369 24.8416V21.3122C35.5811 21.9231 36.2615 22.6018 37.1459 23.4163C40.2754 26.267 40.4795 28.0995 40.4795 28.1674V32.3756ZM40.4795 23.9593C39.9352 23.3484 39.323 22.6697 38.5066 21.9231C35.0369 18.733 35.0369 17.8507 35.0369 16.4253V12.8959C35.5811 13.5068 36.2615 14.1855 37.1459 15C40.2754 17.8507 40.4795 19.6833 40.4795 19.7511V23.9593ZM42.5205 19.6833C42.5205 19.6154 42.7246 17.8507 45.8541 14.9321C46.7385 14.1176 47.4189 13.4389 47.9631 12.8281V16.3575C47.9631 17.7149 47.9631 18.6652 44.4934 21.8552C43.677 22.6018 43.0648 23.2805 42.5205 23.8914V19.6833ZM42.5205 28.0995C42.5205 28.0317 42.7246 26.267 45.8541 23.3484C46.7385 22.5339 47.4189 21.8552 47.9631 21.2443V24.7738C47.9631 26.1312 47.9631 27.0814 44.4934 30.2715C43.677 31.0181 43.0648 31.6968 42.5205 32.3077V28.0995ZM42.5205 36.5837C42.5205 36.5158 42.7246 34.7511 45.8541 31.8326C46.7385 31.0181 47.4189 30.3394 47.9631 29.7285V33.2579C47.9631 34.6154 47.9631 35.5656 44.4934 38.7557C43.677 39.5023 43.0648 40.181 42.5205 40.7919V36.5837Z" fill="white"/></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static inline SVG markup, not user input.
		?>
	</span>
</a>
