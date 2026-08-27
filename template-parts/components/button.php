<?php
/**
 * Button component — the 5 button types from the Figma Design System page. Colours and
 * states per type live in assets/sass/_components/_buttons.sass.
 *
 * $args mirrors the shape of an ACF Link field, so a caller can pass
 * get_field( 'some_button' ) straight through and add 'style' / 'type' on top.
 *
 * Usage:
 *   $button = get_field( 'cta_button' );
 *   if ( $button ) {
 *       get_template_part( 'template-parts/components/button', null, array_merge( $button, array( 'style' => 'secondary' ) ) );
 *   }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.0.0
 *
 * @var array $args {
 *     @type string $title  Button text. Required — for style 'arrow' it is the accessible
 *                          name only, that button having no visible label.
 *     @type string $url    Destination URL. Ignored for type 'submit'.
 *     @type string $target Link target. Ignored for type 'submit'.
 *     @type string $style  'primary' (default), 'secondary', 'black', 'arrow-down' or
 *                          'arrow' (icon-only).
 *     @type string $icon   Optional. Icon name, overriding the one the style and the URL
 *                          would choose — 'arrow-download' for a section whose buttons all
 *                          fetch a file, whatever their URLs happen to be while the page is
 *                          still being filled in.
 *     @type string $type   'link' (default, renders <a>) or 'submit'.
 * }
 */

$defaults = array(
	'title'  => '',
	'url'    => '',
	'target' => '',
	'style'  => 'primary',
	'icon'   => '',
	'type'   => 'link',
);

$args = wp_parse_args( $args, $defaults );

if ( '' === $args['title'] ) {
	return;
}

$target    = $args['target'] ? $args['target'] : '_self';
$class     = 'btn btn-' . sanitize_html_class( $args['style'] );
$icon_only = ( 'arrow' === $args['style'] );
$has_icon  = in_array( $args['style'], array( 'primary', 'secondary', 'black', 'arrow-down', 'arrow' ), true );

// Arrow. The 'arrow-down' style says so outright; for every other style it is read off
// the URL, a button that fetches a file getting the download arrow and one that navigates
// getting the one pointing right.
//
// Inferring it means an ACF Link needs no companion field and an editor cannot pair the
// wrong arrow with a PDF. The cost is the reverse: a download served by a URL with no
// extension points right. Both cases are fixed by choosing 'arrow-down' explicitly.
$download_types = array( 'pdf', 'zip', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'rar', '7z' );
$url_path       = (string) wp_parse_url( $args['url'], PHP_URL_PATH );
$url_extension  = strtolower( pathinfo( $url_path, PATHINFO_EXTENSION ) );

$is_download = in_array( $url_extension, $download_types, true );

/*
 * Three icons, not two. `arrow-download` is the one the design gives a file — the arrow
 * with the line under it — and it is what product-overview already draws for its
 * documents. `arrow-down` has no line and means scrolling, which is why the style of that
 * name keeps it: the home hero's button points down the page, not at a file.
 */
if ( $args['icon'] ) {
	$icon_name = $args['icon'];
} elseif ( 'arrow-down' === $args['style'] ) {
	$icon_name = 'arrow-down';
} elseif ( $is_download ) {
	$icon_name = 'arrow-download';
} else {
	$icon_name = 'arrow-right';
}
?>
<?php
$icon = '';
if ( $has_icon ) :
	ob_start();
	?>
	<span class="btn__icon" aria-hidden="true">
		<?php weizenkorn_the_svg_icon( $icon_name ); ?>
	</span>
	<?php
	$icon = ob_get_clean();
endif;
?>
<?php if ( 'submit' === $args['type'] ) : ?>

	<button type="submit" class="<?php echo esc_attr( $class ); ?>"<?php echo $icon_only ? ' aria-label="' . esc_attr( $args['title'] ) . '"' : ''; ?>>
		<?php
		if ( ! $icon_only ) :
			?>
			<?php echo esc_html( $args['title'] ); ?><?php endif; ?>
		<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup built above, nothing user-supplied. ?>
	</button>

<?php else : ?>

	<a
		class="<?php echo esc_attr( $class ); ?>"
		href="<?php echo esc_url( $args['url'] ); ?>"
		target="<?php echo esc_attr( $target ); ?>"
		<?php echo '_blank' === $target ? 'rel="noopener noreferrer"' : ''; ?>
		<?php echo $icon_only ? 'aria-label="' . esc_attr( $args['title'] ) . '"' : ''; ?>
	>
	<?php
	if ( ! $icon_only ) :
		?>
		<?php echo esc_html( $args['title'] ); ?><?php endif; ?>
		<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup built above, nothing user-supplied. ?>
	</a>

<?php endif; ?>
