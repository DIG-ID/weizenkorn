<?php
/**
 * Stories & references — "Geschichten & Referenzen".
 *
 * A heading and a slider of story cards: an image with the person's name on a cream
 * bar across the bottom, which grows on hover to show a short text and an optional
 * "mehr" link.
 *
 * In modules/ because four of the five product ranges use it (figma-architecture-
 * analysis.txt §7), as do several service pages.
 *
 * Layout — Figma: desktop (2882:586 + 2882:628) and tablet (2882:594 + 2882:684)
 * confirmed 2026-08-13. Everything sits on the page grid, no nested grids:
 *
 *   heading   the red rule full width under the title, from the shared
 *             section-heading component. Title on column 2 at desktop and the full
 *             width at tablet, which is what the component already does. 96px above
 *             the slider at desktop, 56px at tablet.
 *   desktop   three cards per view on columns 3–10 (Figma's 1206px is 8 columns to
 *             the pixel), 390 x 430, arrows on columns 2 and 11.
 *   tablet    one card per view on columns 2–5 (Figma's 460px is 4 columns to the
 *             pixel), 460 x 551, arrows on columns 1 and 6. Note the tablet card is
 *             taller than the desktop one, not smaller.
 *   arrows    vertically centred on the cards at both, never dropped below them.
 *   bullets   centred, 56px under the cards at both.
 *   cards     Figma spaces the desktop three 18px apart; this uses the grid's own
 *             20px gutter, which puts them at 388.67px instead of 390.
 *
 *   mobile — no Figma frame yet. One per view, card height interim.
 *
 * The arrows and the viewport are siblings in the one .theme-grid, each pinned to
 * row 1, rather than an overlay with a grid of its own. Their column placement lives
 * in the SASS as a single grid-column declaration: col-start-* and col-span-* are two
 * utilities and the span one compiles to the shorthand, which resets the start — this
 * project has been caught by that ordering more than once.
 *
 * ACF fields (flat, prefixed) — the `stories_references` group. The group name is
 * what produces this prefix, so renaming it renames all of these and orphans
 * whatever is stored:
 *   stories_references_section_title  (clone of "Section Title") the heading.
 *   stories_references_items          (repeater) one per story:
 *     → image  (image → ID)      the card image
 *     → title  (text)            the name shown on the bar
 *     → text   (textarea)        the copy the hover reveals
 *     → link   (link, optional)  the card's destination, and its Title is the label
 *                                shown on the card — "mehr" in the frames. Typed in
 *                                ACF rather than a theme string, because it is
 *                                content and each language needs its own. Optional
 *                                by design: see the "Button needs to be optional"
 *                                note on the frame. A link with no Title still makes
 *                                the card clickable, it just shows no label.
 *
 * Usage — on a range page the fields come from the current post:
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

// ACF read context: the current post normally, the options store on archives.
$sr_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several contexts.
$sr_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

if ( ! have_rows( $sr_prefix . 'stories_references_items', $sr_ctx ) ) {
	return;
}

/*
 * Slides are counted first, because whether the controls are worth showing depends on
 * how many there are — and the answer differs by breakpoint, since the number of cards
 * in view does:
 *
 *   one card       nothing to slide anywhere, so no arrows and no bullets at all.
 *   two or three   one card per view below xl, so the controls are needed there; at xl
 *                  all three fit one view and the controls would be dead, so they are
 *                  hidden from that breakpoint up.
 *   four or more   controls everywhere.
 *
 * The middle case is why this is a class and not just a conditional render: the same
 * markup has to be present below xl and gone at xl, which only CSS can do.
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
		/*
		 * The heading comes from the shared helper, which reads a cloned "Section Title" group
		 * in either of its Displays and rebuilds the links a Seamless clone hides behind a
		 * composite field reference. See weizenkorn_get_section_heading() in
		 * inc/theme-template-tags.php for why that read is not a plain get_field().
		 */
		$sr_heading = weizenkorn_get_section_heading( $sr_prefix . 'stories_references_', $sr_ctx );

		if ( $sr_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $sr_heading );
		}
		?>

		<?php
		/*
		 * The frames put 32 / 56 / 96px between the rule and the cards. This heading has
		 * no overline, so section-heading renders nothing after its title block and the
		 * gap is that block's own bottom margin — 16 / 24 / 32px — plus what is added
		 * here.
		 */
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

							// The visible label is the link's own title, typed in ACF — "mehr"
							// in the German frames, but it is content and translators need it,
							// so it is not a string in the theme. No title, no label: the card
							// still links, it just shows no text affordance.
							$sr_label = ( is_array( $sr_link ) && ! empty( $sr_link['title'] ) ) ? $sr_link['title'] : '';

							// A card with a link is the link, so "mehr" inside it is a span:
							// an <a> inside an <a> is invalid and browsers unnest it.
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
