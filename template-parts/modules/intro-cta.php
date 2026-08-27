<?php
/**
 * Intro + CTA — a title (the shared section-heading, title only) then a description
 * with a primary button beneath it, both in the right column.
 *
 * Not a plain section-heading call: that component's own buttons_prmary always renders
 * in the LEFT column next to the title (see template-parts/components/section-heading.php),
 * but this shape keeps the left column empty and stacks the description and button
 * together on the right instead — confirmed against Figma for both pages that use it
 * (Supported Jobs' "Arbeiten mit IV-Rente", Supported Apprenticeships' "Auf der Suche
 * nach der passenden Ausbildungsstelle?"), and again for For Social Offices & Partners'
 * text-only "Was uns wichtig ist" (no button — the column works with either).
 *
 * The column is a half-width slice at tablet too (md:col-start-4 md:col-span-3, not the
 * full 6), confirmed against all three pages' tablet frames.
 *
 * ACF fields (flat, prefixed):
 *   {prefix}intro_title  (text)
 *   {prefix}intro_text   (textarea / wpautop)
 *   {prefix}intro_button (link)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/intro-cta', null, array( 'prefix' => 'supported_jobs_' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.9.0
 */

$ic_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$ic_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$ic_title = get_field( $ic_prefix . 'intro_title', $ic_ctx );

if ( ! $ic_title ) {
	return;
}

$ic_text   = get_field( $ic_prefix . 'intro_text', $ic_ctx );
$ic_button = get_field( $ic_prefix . 'intro_button', $ic_ctx );
?>
<section class="intro-cta mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $ic_title ) ); ?>

		<?php if ( $ic_text || $ic_button ) : ?>
			<div class="theme-grid">
				<div class="intro-cta__col col-span-2 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5 flex flex-col items-start gap-8">
					<?php if ( $ic_text ) : ?>
						<div class="body-text"><?php echo wp_kses_post( $ic_text ); ?></div>
					<?php endif; ?>

					<?php if ( $ic_button ) : ?>
						<?php get_template_part( 'template-parts/components/button', null, array_merge( $ic_button, array( 'style' => 'primary' ) ) ); ?>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
