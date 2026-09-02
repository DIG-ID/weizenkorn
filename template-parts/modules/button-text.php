<?php
/**
 * Button + text — a title (shared section-heading) then a button in the
 * left column beside a paragraph in the right column, side by side from
 * tablet up and stacked at mobile. Confirmed against Figma for the
 * Organization page's "Organigramm" (a "PDF herunterladen" download) and
 * "Transparency" (a "Mehr erfahren" link) sections — same shape, different
 * link and text (desktop node 180:4967, tablet 4065:6138, mobile
 * 4065:6258).
 *
 * Not the same shape as intro-cta.php, which stacks text and button
 * together in ONE column instead of side by side — that module's own
 * docblock covers the pages that use IT instead.
 *
 * button.php reads the download arrow off a link's own .pdf extension, not
 * a style, so Organigramm needs no icon override for its own button to get
 * the right one automatically.
 *
 * ACF fields (flat, prefixed):
 *   {prefix}bt_title  (text)
 *   {prefix}bt_button (link)
 *   {prefix}bt_text   (textarea / wpautop)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/button-text', null, array( 'prefix' => 'organigramm_' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.12.0
 */

$bt_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$bt_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$bt_title = get_field( $bt_prefix . 'bt_title', $bt_ctx );

if ( ! $bt_title ) {
	return;
}

$bt_button = get_field( $bt_prefix . 'bt_button', $bt_ctx );
$bt_text   = get_field( $bt_prefix . 'bt_text', $bt_ctx );
?>
<section class="button-text mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $bt_title ) ); ?>

		<?php if ( $bt_button || $bt_text ) : ?>
			<div class="theme-grid mt-8 xl:mt-12">
				<?php if ( $bt_button ) : ?>
					<div class="button-text__button col-span-2 md:col-span-2 xl:col-start-2 xl:col-span-2">
						<?php get_template_part( 'template-parts/components/button', null, array_merge( $bt_button, array( 'style' => 'primary' ) ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $bt_text ) : ?>
					<div class="button-text__text body-text text-brand-dark col-span-2 mt-8 md:col-start-4 md:col-span-3 md:mt-0 xl:col-start-7 xl:col-span-5">
						<?php echo wp_kses_post( $bt_text ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
