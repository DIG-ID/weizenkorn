<?php
/**
 * Teaser — a heading, then an overline, a paragraph and a CTA on the left with a
 * photograph opposite.
 *
 * TWO ARRANGEMENTS
 *
 * At desktop both are the same: the text on columns 2-5 and the photograph on 7-11. They
 * part at tablet. The home page and Our Bakery keep the two side by side, three of six
 * columns each. The Services pages put the photograph under the text instead, running the
 * container's full width at the frame's own 320 height, with the paragraph on five of six
 * columns — that is the 'stacked' variant, and it is what the Fiduciary and Schreinerei
 * frames draw.
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
 *     @type string     $variant Optional. 'stacked' puts the photograph under the text at
 *                               tablet. Default: side by side there.
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

// Whole class names, never composed: Tailwind scans this file and would not emit a class
// built at runtime. Only the tablet step differs — see the docblock.
$ts_stacked = ( ! empty( $args['variant'] ) && 'stacked' === $args['variant'] );

$ts_row_class     = $ts_stacked ? ' teaser__row--stacked' : '';
$ts_content_class = $ts_stacked ? 'md:col-span-5' : 'md:col-span-3';
$ts_media_class   = $ts_stacked ? 'md:col-span-6' : 'md:col-start-4 md:col-span-3';

if ( ! get_field( $ts_prefix . 'title', $ts_ctx )
	&& ! get_field( $ts_prefix . 'subtitle', $ts_ctx )
	&& ! get_field( $ts_prefix . 'body', $ts_ctx )
	&& ! get_field( $ts_prefix . 'image', $ts_ctx ) ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so the top one does not add to whatever
// the section above declares — the gap is the larger of the two. It is here because the
// section above may declare nothing at all: the USP band is padding and colour with no
// margin, and on the Fiduciary page this section follows it directly.
?>
<section class="teaser mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
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

		<div class="teaser__row theme-grid<?php echo esc_attr( $ts_row_class ); ?>">

			<div class="teaser__content col-span-2 <?php echo esc_attr( $ts_content_class ); ?> xl:col-start-2 xl:col-span-4">
				<?php if ( get_field( $ts_prefix . 'subtitle', $ts_ctx ) ) : ?>
					<p class="teaser__overline mb-8"><?php echo esc_html( get_field( $ts_prefix . 'subtitle', $ts_ctx ) ); ?></p>
				<?php endif; ?>

				<?php if ( get_field( $ts_prefix . 'body', $ts_ctx ) ) : ?>
					<?php
					// Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. The
					// bottom margin is the distance down to the CTA; with no CTA under it the
					// paragraph is the column's last child and the row's own gap is the whole
					// distance to the photograph, which is what the tablet frame measures.
					?>
					<div class="body-text teaser__body mb-8 last:mb-0"><?php echo wp_kses_post( get_field( $ts_prefix . 'body', $ts_ctx ) ); ?></div>
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
				<div class="teaser__media col-span-2 <?php echo esc_attr( $ts_media_class ); ?> xl:col-start-7 xl:col-span-5 overflow-hidden">
					<?php
					echo wp_get_attachment_image(
						get_field( $ts_prefix . 'image', $ts_ctx ),
						'full',
						false,
						array(
							'class'   => 'w-full object-cover',
							'loading' => 'lazy',
						)
					);
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
