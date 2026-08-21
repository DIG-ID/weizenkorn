<?php
/**
 * Stories & references — a heading and a slider of story cards: an image with the person's
 * name on a cream bar across the bottom, which grows on hover to show a short text and an
 * optional "mehr" link.
 *
 * The arrows and the viewport are siblings in the one .theme-grid, each pinned to row 1,
 * rather than an overlay with a grid of its own. Their column placement lives in the SASS
 * as a single grid-column declaration: col-start-* and col-span-* are two utilities and
 * the span one compiles to the shorthand, which resets the start.
 *
 * ACF fields (flat, prefixed) — the `stories_references` group. The group name produces
 * the prefix, so renaming it orphans whatever is stored:
 *   stories_references_section_title  (clone of "Section Title") the heading.
 *   stories_references_items          (repeater) one per story:
 *     → image  (image → ID)      the card image
 *     → title  (text)            the name shown on the bar
 *     → text   (textarea)        the copy the hover reveals
 *     → link   (link, optional)  the card's destination, and its Title is the label shown
 *                                on the card. Typed in ACF rather than as a theme string,
 *                                because it is content and each language needs its own. A
 *                                link with no Title still makes the card clickable, it
 *                                just shows no label.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/stories-references' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

$sr_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$sr_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $sr_prefix . 'stories_references_items', $sr_ctx ) ) {
	return;
}

/*
 * Whether the controls are worth showing depends on the count, and the answer differs by
 * breakpoint because the number of cards in view does. Two or three cards need controls
 * below xl and none at xl, where all three fit one view — the same markup present below
 * and gone above, which only CSS can do. Hence a class rather than a conditional render.
 */
$sr_count = 0;

while ( have_rows( $sr_prefix . 'stories_references_items', $sr_ctx ) ) {
	the_row();
	++$sr_count;
}

$sr_has_controls = ( $sr_count > 1 );
$sr_fits_xl      = ( $sr_count <= 3 );
?>
<section class="stories-references<?php echo $sr_fits_xl ? ' stories-references--fits-xl' : ''; ?> mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
		$sr_heading = weizenkorn_get_section_heading( $sr_prefix . 'stories_references_', $sr_ctx );

		if ( $sr_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $sr_heading );
		}
		?>

		<?php
		// This heading has no overline, so the gap is the title block's own bottom margin
		// plus what is added here.
		?>
		<div class="stories-references__row theme-grid mt-4 md:mt-8 xl:mt-16">

			<?php if ( $sr_has_controls ) : ?>
				<button type="button" class="stories-references__nav stories-references__nav--prev js-stories-prev" aria-label="<?php echo esc_attr_x( 'Previous story', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>
			<?php endif; ?>

			<div class="stories-references__viewport">
				<div class="swiper js-stories-slider">
					<div class="swiper-wrapper">
						<?php
						while ( have_rows( $sr_prefix . 'stories_references_items', $sr_ctx ) ) :
							the_row();

							$sr_link   = get_sub_field( 'link' );
							$sr_url    = ( is_array( $sr_link ) && ! empty( $sr_link['url'] ) ) ? $sr_link['url'] : '';
							$sr_target = ( is_array( $sr_link ) && ! empty( $sr_link['target'] ) ) ? $sr_link['target'] : '';

							// The label is the link's own title, typed in ACF because it is content
							// and translators need it. No title, no label — the card still links.
							$sr_label = ( is_array( $sr_link ) && ! empty( $sr_link['title'] ) ) ? $sr_link['title'] : '';

							// A card with a link IS the link, so "mehr" inside it is a span: an <a>
							// inside an <a> is invalid and browsers unnest it.
							$sr_tag = $sr_url ? 'a' : 'article';
							?>
							<div class="swiper-slide">
								<<?php echo esc_html( $sr_tag ); ?> class="card-story"<?php echo $sr_url ? ' href="' . esc_url( $sr_url ) . '"' : ''; ?><?php echo ( '_blank' === $sr_target ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>

									<?php if ( get_sub_field( 'image' ) ) : ?>
										<?php
										echo wp_get_attachment_image(
											get_sub_field( 'image' ),
											'large',
											false,
											array(
												'class'   => 'card__media',
												'loading' => 'lazy',
											)
										);
										?>
									<?php endif; ?>

									<div class="card__panel">
										<div class="card__head">
											<?php if ( get_sub_field( 'title' ) ) : ?>
												<h3 class="card__title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
											<?php endif; ?>

											<?php if ( get_sub_field( 'text' ) ) : ?>
												<div class="card__reveal">
													<div class="card__text"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></div>
												</div>
											<?php endif; ?>
										</div>

										<?php if ( $sr_label ) : ?>
											<div class="card__reveal">
												<span class="card__more"><?php echo esc_html( $sr_label ); ?></span>
											</div>
										<?php endif; ?>
									</div>
								</<?php echo esc_html( $sr_tag ); ?>>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
			</div>

			<?php if ( $sr_has_controls ) : ?>
				<button type="button" class="stories-references__nav stories-references__nav--next js-stories-next" aria-label="<?php echo esc_attr_x( 'Next story', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>

				<?php
				/*
				 * The bullets are a grid item too, in the row below the slider. Keeping
				 * them inside the same grid is what lets them span the full container and
				 * centre on it while the arrows hold the outer columns of the row above.
				 */
				?>
				<div class="stories-references__pagination js-stories-pagination"></div>
			<?php endif; ?>

		</div>

	</div>
</section>
