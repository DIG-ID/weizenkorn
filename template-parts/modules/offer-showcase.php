<?php
/**
 * Offer showcase — a heading and a grid of offer cards, three to a row, each an image with
 * the offer's name on a cream bar across its bottom that grows on hover to reveal a line
 * of copy. The Schreinerei service pages' "Eine Auswahl von unserem Angebot".
 *
 * Its own module and not one of the three it sits closest to. Not modules/offer-grid.php,
 * whose cards put the title and an arrow UNDER the image and run two to a row. Not
 * modules/stories-references.php, which is this card in a Swiper rather than a grid. Not
 * modules/product-overview.php, whose cards are laid out four to a row on a grid of their
 * own and belong to the `products` post type.
 *
 * What it does share is the card. Its look comes from .card-story in
 * _modules/_stories-references.sass, with only the height, the panel's padding and what
 * opens it overridden in this module's own partial; its behaviour below xl is
 * assets/js/reveal-cards.js, the same file the product range's cards use. One card and one
 * script, drawn in three arrangements, rather than three to keep in step. Only the markup
 * is repeated — the same call template-parts/pages/home/services.php already makes for
 * this card.
 *
 * COLUMNS. Three cards across the ten-column inset, and ten does not divide into three, so
 * the list takes the inset as a span and subdivides it into sixths — the one sanctioned
 * way to place something the page grid's own columns cannot hold. See _layout/_grid.sass.
 * How many cards share the last row is decided in CSS from the count and not here, so
 * adding an offer rearranges the grid on its own — see _modules/_offer-showcase.sass.
 *
 * ACF fields (flat, prefixed) — the `offer_showcase` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   offer_showcase_section_title  (clone of "Section Title") the title and its red rule.
 *                                 Clone the GROUP, never a repeater inside one.
 *   offer_showcase_items          (repeater) one row per offer:
 *     → image  (image → ID)      required — a row without one has nothing to draw and is
 *                                skipped, which also keeps it out of the CSS count above.
 *     → title  (text)            the name on the bar
 *     → text   (textarea)        the copy the hover reveals
 *     → link   (link, optional)  the card's destination, and the reveal then shows a
 *                                "mehr erfahren" under the copy. Typing a Title on the
 *                                link replaces that label, for a card that wants to say
 *                                something else; leaving it empty gives every card the
 *                                same wording, which is what the rest of the theme does.
 *
 * BELOW XL there is no hover, so the bar carries a +/- and a tap is what opens it — see
 * assets/js/reveal-cards.js for which gesture does what. The frames draw every bar
 * closed at those widths and no icon, but they also draw no open card there at all: read
 * literally they would put the copy out of reach on a phone entirely, which is the one
 * reading that cannot be right.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/offer-showcase' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.19.0
 */

$os_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$os_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$os_heading = weizenkorn_get_section_heading( $os_prefix . 'offer_showcase_', $os_ctx );

/*
 * Collected before rendering rather than drawn inside the loop: a row skipped for a
 * missing image must not reach the markup at all, or the CSS that widens the last row
 * would be counting cards the page never draws.
 */
$os_cards = array();

if ( have_rows( $os_prefix . 'offer_showcase_items', $os_ctx ) ) {
	while ( have_rows( $os_prefix . 'offer_showcase_items', $os_ctx ) ) {
		the_row();

		if ( ! get_sub_field( 'image' ) ) {
			continue;
		}

		$os_row_link = get_sub_field( 'link' );

		$os_cards[] = array(
			'image'  => get_sub_field( 'image' ),
			'title'  => get_sub_field( 'title' ),
			'text'   => get_sub_field( 'text' ),
			'url'    => ( is_array( $os_row_link ) && ! empty( $os_row_link['url'] ) ) ? $os_row_link['url'] : '',
			'target' => ( is_array( $os_row_link ) && ! empty( $os_row_link['target'] ) ) ? $os_row_link['target'] : '',

			/*
			 * The label the reveal shows under the copy. The link's own Title where the
			 * editor typed one, so a card can say something other than the default — and
			 * that default where they did not, rather than a linked card opening on a
			 * reveal with nothing in it to follow. Same shape as the product range's
			 * "zum Produkt".
			 */
			'label'  => ( is_array( $os_row_link ) && ! empty( $os_row_link['title'] ) )
				? $os_row_link['title']
				: _x( 'mehr erfahren', 'offer showcase card link', 'weizenkorn' ),
		);
	}
}

if ( ! $os_heading && ! $os_cards ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum. The bottom is 96px at
// desktop and not the usual 192: the frame puts the text section's rule that far under the
// last card, and margins collapsing means the smaller of the two is what has to give.
?>
<section class="offer-showcase mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-24">
	<div class="theme-container">

		<?php
		if ( $os_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $os_heading );
		}
		?>

		<?php if ( $os_cards ) : ?>
			<?php
			// 56px under the rule at tablet and 96px at desktop, both the frames' own. The
			// heading has no second row here, so its bottom margin collapses with this one
			// rather than adding to it — these values are the whole gap.
			?>
			<div class="theme-grid mt-8 md:mt-14 xl:mt-24">
				<ul class="offer-showcase__list col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php foreach ( $os_cards as $os_card ) : ?>
						<?php
						// A card with a link IS the link, so the label inside it is a span: an <a>
						// inside an <a> is invalid and browsers unnest it.
						$os_card_tag = $os_card['url'] ? 'a' : 'article';

						/*
						 * Whether this card draws a reveal at all — the same two conditions the
						 * markup below uses, read once here because two things depend on the
						 * answer: the +/- only means something when there is something to open,
						 * and the JS only intercepts a tap when there is.
						 */
						$os_has_reveal = $os_card['url'] || $os_card['text'];
						$os_reveal_id  = wp_unique_id( 'offer-showcase-reveal-' );
						?>
						<li class="offer-showcase__item">
							<<?php echo esc_html( $os_card_tag ); ?> class="card-story"<?php echo $os_card['url'] ? ' href="' . esc_url( $os_card['url'] ) . '"' : ''; ?><?php echo ( '_blank' === $os_card['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>

								<?php
								/*
								 * 'large' and the default `sizes`, the same as the two modules this
								 * card is shared with. The one card that runs the full width when it
								 * is alone on the last row is the exception, and takes a softer image
								 * than the rest — worth revisiting only if it shows.
								 */
								echo wp_get_attachment_image(
									$os_card['image'],
									'large',
									false,
									array(
										'class'   => 'card__media',
										'loading' => 'lazy',
									)
								);
								?>

								<div class="card__panel">
									<div class="card__head">
										<div class="offer-showcase__head-row">
											<?php if ( $os_card['title'] ) : ?>
												<h3 class="card__title"><?php echo esc_html( $os_card['title'] ); ?></h3>
											<?php endif; ?>

											<?php if ( $os_has_reveal ) : ?>
												<?php
												/*
												 * The +/- of the preview cards and the product range, saying the
												 * same thing: there is more under this bar. Below xl only —
												 * desktop opens on hover and the frames draw no icon there, where
												 * below xl a tap is the only way into the copy.
												 *
												 * A <span> on a linked card and not the <button> below: the card
												 * IS the <a>, and interactive content cannot nest inside a link.
												 * The JS gives it its behaviour; aria-hidden keeps it from being
												 * announced as a control a keyboard cannot reach, and the card's
												 * own aria-expanded is what assistive tech reads instead.
												 */
												?>
												<?php if ( $os_card['url'] ) : ?>
													<span class="offer-showcase__toggle xl:hidden" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'toggle' ); ?></span>
												<?php else : ?>
													<?php
													/*
													 * No link, so the card is an <article> and a real <button> is
													 * allowed — keyboard and all. It is also the only way into the
													 * copy here: the card has nowhere to go, so nothing about it
													 * is tappable but this.
													 */
													?>
													<button
														type="button"
														class="offer-showcase__toggle js-offer-showcase-toggle xl:hidden"
														aria-expanded="false"
														aria-controls="<?php echo esc_attr( $os_reveal_id ); ?>"
													>
														<span class="sr-only"><?php esc_html_e( 'Toggle description', 'weizenkorn' ); ?></span>
														<?php weizenkorn_the_svg_icon( 'toggle' ); ?>
													</button>
												<?php endif; ?>
											<?php endif; ?>
										</div>

										<?php if ( $os_card['text'] ) : ?>
											<div class="card__reveal" id="<?php echo esc_attr( $os_reveal_id ); ?>">
												<?php
												/*
												 * The bare <div> is load-bearing — it is the element the collapse
												 * clips, so it carries no spacing of its own; that sits on the
												 * content inside it. See the note on __reveal in
												 * _modules/_offer-showcase.sass.
												 */
												?>
												<div>
													<div class="card__text"><?php echo wp_kses_post( $os_card['text'] ); ?></div>
												</div>
											</div>
										<?php endif; ?>
									</div>

									<?php if ( $os_card['url'] ) : ?>
										<div class="card__reveal">
											<?php // The same bare <div> as above, and for the same reason. ?>
											<div>
												<span class="card__more"><?php echo esc_html( $os_card['label'] ); ?></span>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</<?php echo esc_html( $os_card_tag ); ?>>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

	</div>
</section>
