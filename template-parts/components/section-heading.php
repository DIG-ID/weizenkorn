<?php
/**
 * Section heading — overline + title (+ optional divider line).
 * The most reused block in the design (see figma-architecture-analysis.txt §4).
 *
 * Pass data via get_template_part():
 *   get_template_part( 'template-parts/components/section-heading', null, array(
 *       'overline' => 'PRODUKTE',
 *       'title'    => 'Schönes mit Sinn',
 *       'tag'      => 'h2',             // h1 | h2 | h3 — default h2
 *       'title_class' => 'title-main',  // title-hero | title-main | title-section
 *       'align'    => 'left',          // left | center
 *       'rule'     => true,            // show the horizontal divider line
 *   ) );
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.1.0
 */

$heading_overline    = isset( $args['overline'] ) ? $args['overline'] : '';
$heading_title       = isset( $args['title'] ) ? $args['title'] : '';
$heading_tag         = isset( $args['tag'] ) ? $args['tag'] : 'h2';
$heading_title_class = isset( $args['title_class'] ) ? $args['title_class'] : 'title-main';
$heading_align       = ( isset( $args['align'] ) && 'center' === $args['align'] ) ? 'text-center' : '';
$heading_rule        = ! empty( $args['rule'] );

$heading_allowed = array( 'h1', 'h2', 'h3' );
$heading_tag     = in_array( $heading_tag, $heading_allowed, true ) ? $heading_tag : 'h2';

if ( ! $heading_title && ! $heading_overline ) {
	return;
}
?>
<header class="section-heading <?php echo esc_attr( $heading_align ); ?>">
	<?php if ( $heading_overline ) : ?>
		<p class="overline section-heading__overline text-brand-red"><?php echo esc_html( $heading_overline ); ?></p>
	<?php endif; ?>

	<?php if ( $heading_title ) : ?>
		<<?php echo esc_html( $heading_tag ); ?> class="<?php echo esc_attr( $heading_title_class ); ?> section-heading__title">
			<?php echo esc_html( $heading_title ); ?>
		</<?php echo esc_html( $heading_tag ); ?>>
	<?php endif; ?>

	<?php if ( $heading_rule ) : ?>
		<hr class="section-heading__rule border-0 border-t border-brand-red mt-6" />
	<?php endif; ?>
</header>
