<?php
/**
 * Products archive — "Sie möchten bestellen?" ordering section.
 *
 * Two halves under one heading: what resellers do, and where end customers can
 * buy each product range.
 *
 * Layout — Figma, desktop confirmed 2026-08-11 (canvas 1920 / container 1820):
 *
 * The whole body sits in ONE grid item on the container's inset columns 2–11, the
 * same span the section-heading puts the title on. Everything inside then divides
 * that span with plain grids, so no child needs its own column placement:
 *
 *   resellers  overline, then two 410px cards side by side from xl — halves of the
 *              10-column span, which is the 5 + 5 of the design.
 *   customers  overline, then the range cards: thirds of the span from xl. Figma has
 *              them 493px with a 17px gap; thirds with the grid's own 20px gutter
 *              give 491px, so they land on the grid.
 *   tablet / mobile — confirmed 2026-08-11. Every card row becomes a single column
 *              at the container's full width, 24px apart at tablet and 16px at
 *              mobile, with the type stepped down to 14/22.
 *
 * Card heights are min-heights: the content is editorial and the ranges do not all
 * have the same number of stockists.
 *
 * The sign-up card is Contact Form 7 — a second form, separate from the one in
 * modules/cta-form, because it collects a different enquiry. Its controls use the
 * shared .form-field classes; see _components/_form-fields.sass for the form
 * template to paste into the plugin.
 *
 * ACF fields (flat, prefixed):
 *   order_form_title              (text)     the section heading
 *   order_form_reseller_overline  (text)     "Als Wiederverkäufer"
 *   order_form_reseller_title     (text)     "Bestehende Wiederverkäufer"
 *   order_form_reseller_link      (link)     "Über die Händlerplattform pepperi"
 *   order_form_form_title         (text)     "Ich bin neu interessiert"
 *   order_form_shortcode          (text)     the CF7 shortcode for the sign-up form
 *   order_form_customer_overline  (text)     "Als Endkunde"
 *   order_form_cards              (repeater) one per product range:
 *     → title       (text)     "Kerzen"
 *     → intro       (textarea)  optional lead-in
 *     → list_title  (text)     "Endkunden kaufen unsere Kerzen in Basel:"
 *     → list_items  (repeater) → text (text), link (link, optional). Only the items
 *                                with a link become buttons and get an arrow — the
 *                                Werkladen points at the contact page, the market
 *                                dates are plain text.
 *     → links_title (text)     "Online erhalten Sie … über folgende Vertriebspartner"
 *     → links       (repeater) → link (link)
 *
 * Usage:
 *   get_template_part( 'template-parts/archives/product/order-form' );
 *
 * On the archive there is no post context, so pass the options store and prefix:
 *   get_template_part(
 *       'template-parts/archives/product/order-form',
 *       null,
 *       array(
 *           'post_id' => 'option',
 *           'prefix'  => 'products_archive_',
 *       )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.5.0
 */

// ACF read context: the current post normally, the options store on archives.
$of_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so the section can serve a page or an archive.
$of_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Both halves are optional, but with neither there is no section.
if ( ! get_field( $of_prefix . 'order_form_reseller_title', $of_ctx )
	&& ! get_field( $of_prefix . 'order_form_shortcode', $of_ctx )
	&& ! have_rows( $of_prefix . 'order_form_cards', $of_ctx ) ) {
	return;
}
?>
<section class="order-form mt-20 mb-28 md:mb-32 md:mt-32 xl:mb-48 xl:mt-48">
	<div class="theme-container">

		<?php
		// Heading + red rule. Same component and the same synthesised args as
		// modules/cta-form: this section needs a title and nothing else from it.
		if ( get_field( $of_prefix . 'order_form_title', $of_ctx ) ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'title'         => get_field( $of_prefix . 'order_form_title', $of_ctx ),
					'title_heading' => 'h2',
				)
			);
		}
		?>

		<?php
		/*
		 * One grid item for the entire body, on the inset columns 2–11. Everything
		 * below divides that span with its own plain grid, so the cards never carry
		 * column placement of their own and always line up with the heading.
		 */
		?>
		<div class="theme-grid">
			<div class="order-form__inner col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">

				<?php if ( get_field( $of_prefix . 'order_form_reseller_title', $of_ctx ) || get_field( $of_prefix . 'order_form_shortcode', $of_ctx ) ) : ?>
					<div class="order-form__block">

						<?php if ( get_field( $of_prefix . 'order_form_reseller_overline', $of_ctx ) ) : ?>
							<p class="order-form__overline text-brand-dark">
								<?php echo esc_html( get_field( $of_prefix . 'order_form_reseller_overline', $of_ctx ) ); ?>
							</p>
						<?php endif; ?>

						<div class="order-form__row">

							<?php if ( get_field( $of_prefix . 'order_form_reseller_title', $of_ctx ) ) : ?>
								<div class="order-form__card order-form__card--reseller">
									<h3 class="order-form__card-title"><?php echo esc_html( get_field( $of_prefix . 'order_form_reseller_title', $of_ctx ) ); ?></h3>

									<?php
									$of_link = get_field( $of_prefix . 'order_form_reseller_link', $of_ctx );

									if ( is_array( $of_link ) && ! empty( $of_link['url'] ) ) :
										?>
										<a class="order-form__link" href="<?php echo esc_url( $of_link['url'] ); ?>"<?php echo ( '_blank' === ( $of_link['target'] ?? '' ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
											<span><?php echo esc_html( ! empty( $of_link['title'] ) ? $of_link['title'] : $of_link['url'] ); ?></span>
											<span class="order-form__link-icon" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( get_field( $of_prefix . 'order_form_shortcode', $of_ctx ) ) : ?>
								<div class="order-form__card order-form__card--form">
									<?php if ( get_field( $of_prefix . 'order_form_form_title', $of_ctx ) ) : ?>
										<h3 class="order-form__card-title"><?php echo esc_html( get_field( $of_prefix . 'order_form_form_title', $of_ctx ) ); ?></h3>
									<?php endif; ?>

									<?php echo do_shortcode( get_field( $of_prefix . 'order_form_shortcode', $of_ctx ) ); ?>
								</div>
							<?php endif; ?>

						</div>
					</div>
				<?php endif; ?>

				<?php if ( have_rows( $of_prefix . 'order_form_cards', $of_ctx ) ) : ?>
					<div class="order-form__block">

						<?php if ( get_field( $of_prefix . 'order_form_customer_overline', $of_ctx ) ) : ?>
							<p class="order-form__overline text-brand-dark">
								<?php echo esc_html( get_field( $of_prefix . 'order_form_customer_overline', $of_ctx ) ); ?>
							</p>
						<?php endif; ?>

						<div class="order-form__cards">
							<?php
							while ( have_rows( $of_prefix . 'order_form_cards', $of_ctx ) ) :
								the_row();
								?>
								<article class="order-form__card order-form__card--range">

									<?php // Row 1 of the subgrid: title and intro travel together, so a card without an intro still lines up with one that has it. ?>
									<div class="order-form__card-header">
										<?php if ( get_sub_field( 'title' ) ) : ?>
											<h3 class="order-form__card-title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
										<?php endif; ?>

										<?php if ( get_sub_field( 'intro' ) ) : ?>
											<div class="order-form__card-intro"><?php echo wp_kses_post( get_sub_field( 'intro' ) ); ?></div>
										<?php endif; ?>
									</div>

								<?php
								/*
								 * Only the items that carry a link become buttons — in the design that
								 * is the Werkladen, which points at the contact page, while the market
								 * dates are plain text. So the arrow is per item, not per block, and an
								 * item without a link renders as text.
								 */
								?>
								<?php if ( get_sub_field( 'list_title' ) || have_rows( 'list_items' ) ) : ?>
									<div class="order-form__group order-form__group--stockists">

										<?php if ( get_sub_field( 'list_title' ) ) : ?>
											<p class="order-form__group-title"><?php echo esc_html( get_sub_field( 'list_title' ) ); ?></p>
										<?php endif; ?>

										<?php if ( have_rows( 'list_items' ) ) : ?>
											<ul class="order-form__list">
												<?php
												while ( have_rows( 'list_items' ) ) :
													the_row();
													$of_item      = get_sub_field( 'link' );
													$of_item_url  = ( is_array( $of_item ) && ! empty( $of_item['url'] ) ) ? $of_item['url'] : '';
													$of_item_text = get_sub_field( 'text' );

													if ( ! $of_item_text && $of_item_url && ! empty( $of_item['title'] ) ) {
														$of_item_text = $of_item['title'];
													}
													?>
													<li class="order-form__list-item">
														<?php if ( $of_item_url ) : ?>
															<a class="order-form__link" href="<?php echo esc_url( $of_item_url ); ?>"<?php echo ( '_blank' === ( $of_item['target'] ?? '' ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
																<span><?php echo esc_html( $of_item_text ); ?></span>
																<span class="order-form__link-icon" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
															</a>
														<?php else : ?>
															<?php echo esc_html( $of_item_text ); ?>
														<?php endif; ?>
													</li>
													<?php
												endwhile;
												?>
											</ul>
										<?php endif; ?>
									</div>
								<?php endif; ?>

									<?php if ( get_sub_field( 'links_title' ) || have_rows( 'links' ) ) : ?>
										<div class="order-form__group order-form__group--partners">
											<?php if ( get_sub_field( 'links_title' ) ) : ?>
												<p class="order-form__group-title"><?php echo esc_html( get_sub_field( 'links_title' ) ); ?></p>
											<?php endif; ?>

											<?php
											while ( have_rows( 'links' ) ) :
												the_row();
												$of_partner = get_sub_field( 'link' );

												if ( ! is_array( $of_partner ) || empty( $of_partner['url'] ) ) {
													continue;
												}
												?>
												<a class="order-form__link" href="<?php echo esc_url( $of_partner['url'] ); ?>"<?php echo ( '_blank' === ( $of_partner['target'] ?? '' ) ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
													<span><?php echo esc_html( ! empty( $of_partner['title'] ) ? $of_partner['title'] : $of_partner['url'] ); ?></span>
													<span class="order-form__link-icon" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
												</a>
												<?php
											endwhile;
											?>
										</div>
									<?php endif; ?>

								</article>
								<?php
							endwhile;
							?>
						</div>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</section>
