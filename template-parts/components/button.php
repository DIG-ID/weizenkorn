<?php
/**
 * Button component: the 5 button types from the Figma "Design System" page
 * (Buttons section) — primary, secondary, black, arrow-down and arrow
 * (icon-only). Colors/states per type are documented in
 * assets/sass/_components/_buttons.sass.
 *
 * $args mirrors the shape of an ACF Link field ('title'/'url'/'target'), so a
 * caller can pass get_field( 'some_button' ) straight through and just add
 * 'style'/'type' on top — no glue code, and the component never calls
 * get_field() itself.
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
 *     @type string $title  Button text. Required, except for style 'arrow'
 *                          where it is used as the accessible name only
 *                          (icon-only button has no visible label).
 *     @type string $url    Destination URL. Ignored for type 'submit'.
 *     @type string $target Link target ('_blank'/'_self'). Ignored for type 'submit'.
 *     @type string $style  'primary' (default), 'secondary', 'black',
 *                          'arrow-down' (text + down arrow) or 'arrow'
 *                          (icon-only, no visible label).
 *     @type string $type   'link' (default, renders <a>) or 'submit'
 *                          (renders <button type="submit">).
 * }
 */

$defaults = array(
	'title'  => '',
	'url'    => '',
	'target' => '',
	'style'  => 'primary',
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
$icon_name = ( 'arrow-down' === $args['style'] ) ? 'arrow-down' : 'arrow-right';
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
