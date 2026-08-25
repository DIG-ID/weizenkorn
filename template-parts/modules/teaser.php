<?php
/**
 * Teaser — a heading, then an overline, a paragraph and a CTA on the left with a
 * photograph opposite.
 *
 * Named for what it does rather than for either heading it carries: "Lerne uns kennen" on
 * the home page and "Sozial" on Our Bakery are the same section pointing at a page that
 * says more.
 *
 * The title and its rule come from the shared section-heading in its title-only mode; the
 * row below is this module's own, so the CTA can sit under the paragraph rather than above
 * it, which is where the component puts its buttons.
 *
 * ACF fields (flat, prefixed) — the prefix is the caller's, so one page can carry two:
 *   {prefix}title     (text)              the display title above the rule
 *   {prefix}subtitle  (text)              the overline
 *   {prefix}body      (textarea)          the paragraph
 *   {prefix}link      (link)              the CTA
 *   {prefix}image     (image → return ID) the photograph opposite
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/teaser', null, array( 'prefix' => 'social_' ) );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Prepended to every field name. Required in practice —
 *                               without it the names are generic enough to collide.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.8.0
 */

$ts_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$ts_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! get_field( $ts_prefix . 'title', $ts_ctx )
	&& ! get_field( $ts_prefix . 'subtitle', $ts_ctx )
	&& ! get_field( $ts_prefix . 'body', $ts_ctx )
	&& ! get_field( $ts_prefix . 'image', $ts_ctx ) ) {
	return;
}
?>
<section class="teaser mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( get_field( $ts_prefix . 'title', $ts_ctx ) ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'title_heading' => 'h2',
					'title'         => get_field( $ts_prefix . 'title', $ts_ctx ),
				)
			);
		}
		?>

		<div class="teaser__row theme-grid">

			<div class="teaser__content col-span-2 md:col-span-3 xl:col-start-2 xl:col-span-4">
				<?php if ( get_field( $ts_prefix . 'subtitle', $ts_ctx ) ) : ?>
					<p class="teaser__overline mb-8"><?php echo esc_html( get_field( $ts_prefix . 'subtitle', $ts_ctx ) ); ?></p>
				<?php endif; ?>

				<?php if ( get_field( $ts_prefix . 'body', $ts_ctx ) ) : ?>
					<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
					<div class="body-text teaser__body mb-8"><?php echo wp_kses_post( get_field( $ts_prefix . 'body', $ts_ctx ) ); ?></div>
				<?php endif; ?>

				<?php
				if ( get_field( $ts_prefix . 'link', $ts_ctx ) ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge( get_field( $ts_prefix . 'link', $ts_ctx ), array( 'style' => 'primary' ) )
					);
				}
				?>
			</div>

			<?php if ( get_field( $ts_prefix . 'image', $ts_ctx ) ) : ?>
				<div class="teaser__media col-span-2 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5 overflow-hidden">
					<?php
					echo wp_get_attachment_image(
						get_field( $ts_prefix . 'image', $ts_ctx ),
						'full',
						false,
						array(
							'class'   => 'w-full h-auto object-cover md:min-h-[393px]',
							'loading' => 'lazy',
						)
					);
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
