<?php
/**
 * Preview card (Figma "Design System" page, node 3429:4759 — variants Default/hover/
 * download/tablet/tablet-hover/Tablet-download) — an image with a title bar over its
 * bottom edge, expanding to reveal a short text and a "mehr" link. Receives its data via
 * $args; never calls get_field() itself — the caller (template-parts/modules/preview-cards.php)
 * reads it off whichever page was picked in its "Preview Cards" relationship field.
 *
 * Two shapes, chosen by whether $args['text'] is set:
 *
 * Static (no $text): just the image and the title bar, no reveal, no toggle button — the
 * same "Weizenkorn-Produkte"/"Gastronomie und Hotellerie" cards on Services' own "Entdecken
 * Sie mehr" draw, which carry no description at all. Still an <a> when $url is set, same
 * as card-overview.php's own convention.
 *
 * Interactive (with $text): the card itself is deliberately NOT a link — unlike
 * card-service.php/card-overview.php's single big <a>, this shape needs its own toggle
 * button below desktop (see next paragraph), and a <button> nested inside an <a> (or the
 * reverse) is both invalid HTML and unreachable for assistive tech. Instead only the
 * "mehr" line at the end of the reveal is the real link.
 *
 * The toggle button only matters below desktop: at xl the reveal opens on :hover/
 * :focus-within (CSS only, no icon shown — the Design System's own "hover" variant carries
 * none) — same mechanism as card-service.php. Below xl there is no reliable hover, so the
 * Design System gives tablet/mobile their own explicit +/− tap target instead (the
 * "tablet"/"tablet-hover" variants); assets/js/preview-cards.js toggles an `is-open` class
 * plus aria-expanded, and _components/_card.sass opens the same grid-rows reveal off
 * either :focus-within or .is-open, whichever applies at that breakpoint.
 *
 * Both shapes share one fixed-height box with the image absolutely filling it and the
 * panel pinned to its bottom edge — confirmed against Services' own frame at every
 * breakpoint, unlike card-service.php/card-overview.php's aspect-ratio boxes. There is no
 * aspect-ratio fallback here, so $media_height (or its default) always applies.
 *
 * @param array $args {
 *     @type int    $image         Attachment ID. Required — nothing is drawn without it.
 *     @type string $title
 *     @type string $text          Optional. Plain text — presence alone switches the card
 *                                 to the interactive shape.
 *     @type string $url           Optional. Card becomes an <a> (static shape) or "mehr"
 *                                 becomes one (interactive shape) only when set.
 *                                 Download-type URLs (same list as
 *                                 template-parts/components/button.php) get the download
 *                                 arrow instead of the default one.
 *     @type string $media_height  Optional. Tailwind height classes for the card/media box.
 *                                 Default: Services' own "Entdecken Sie mehr" measurements
 *                                 (256px mobile, 384px tablet, 320px desktop).
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.13.0
 */

if ( empty( $args['image'] ) || empty( $args['title'] ) ) {
	return;
}

$has_text     = ! empty( $args['text'] );
$url          = ! empty( $args['url'] ) ? $args['url'] : '';
$media_height = ! empty( $args['media_height'] ) ? $args['media_height'] : 'h-[256px] md:h-[384px] xl:h-[320px]';

$download_types = array( 'pdf', 'zip', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'rar', '7z' );
$url_path       = (string) wp_parse_url( $url, PHP_URL_PATH );
$url_extension  = strtolower( pathinfo( $url_path, PATHINFO_EXTENSION ) );
$is_download    = in_array( $url_extension, $download_types, true );
?>

<?php if ( ! $has_text ) : ?>

	<?php
	// Static shape: same "no url, no arrow, still draws" convention as card-overview.php.
	$card_tag = $url ? 'a' : 'div';
	?>
	<?php echo '<' . esc_html( $card_tag ) . ( $url ? ' href="' . esc_url( $url ) . '"' : '' ) . ' class="card-preview no-underline ' . esc_attr( $media_height ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- tag is always 'a' or 'div', href/class built above with esc_url()/esc_attr(). ?>
		<div class="card-preview__media">
			<?php
			echo wp_get_attachment_image(
				$args['image'],
				'large',
				false,
				array(
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				)
			);
			?>
		</div>
		<div class="card-preview__panel">
			<h3 class="card-preview__title"><?php echo esc_html( $args['title'] ); ?></h3>
		</div>
	<?php echo '</' . esc_html( $card_tag ) . '>'; ?>

<?php else : ?>

	<?php $reveal_id = wp_unique_id( 'card-preview-reveal-' ); ?>
	<article class="card-preview js-card-preview <?php echo esc_attr( $media_height ); ?>">
		<div class="card-preview__media">
			<?php
			echo wp_get_attachment_image(
				$args['image'],
				'large',
				false,
				array(
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				)
			);
			?>
		</div>

		<div class="card-preview__panel">
			<div class="card-preview__head">
				<h3 class="card-preview__title"><?php echo esc_html( $args['title'] ); ?></h3>

				<?php
				/*
				 * xl:hidden — desktop opens on hover/focus-within instead, matching the
				 * Design System's own "hover" variant, which carries no icon at all.
				 */
				?>
				<button
					type="button"
					class="card-preview__toggle js-card-preview-toggle xl:hidden"
					aria-expanded="false"
					aria-controls="<?php echo esc_attr( $reveal_id ); ?>"
				>
					<span class="sr-only"><?php esc_html_e( 'Toggle preview', 'weizenkorn' ); ?></span>
					<?php weizenkorn_the_svg_icon( 'toggle' ); ?>
				</button>
			</div>

			<div class="card-preview__reveal" id="<?php echo esc_attr( $reveal_id ); ?>">
				<div>
					<div class="card-preview__text"><?php echo esc_html( $args['text'] ); ?></div>

					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="card-preview__more">
							<?php esc_html_e( 'mehr', 'weizenkorn' ); ?>
							<span aria-hidden="true"><?php weizenkorn_the_svg_icon( $is_download ? 'arrow-download' : 'arrow-right' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</article>

<?php endif; ?>
