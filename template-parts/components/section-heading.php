<?php
/**
 * Section Title — renders the reusable "Section Title" ACF group
 * (title heading tag, subtitle, title, left/right descriptions, 2 buttons).
 * Receives the group's data via $args; the component never calls get_field().
 *
 * $args keys match the "Section Title" ACF group field names verbatim:
 *   title_heading      string  h1..h6 (heading tag; default h2)
 *   subtitle           string  eyebrow / overline
 *   title              string  main title (may contain <br>; new_lines = br)
 *   description        string  'left' | 'right' | 'both' (which columns show)
 *   desciption_left    string  wysiwyg — left column   [sic: ACF field name]
 *   description_right  string  wysiwyg — right column
 *   buttons            array   { prmary: link, secondary: link }  [sic]
 *   image              int     optional heading image (attachment ID)
 *
 * Usage (section reads its cloned Section Title group and passes it through):
 *   $st = get_field( 'products_section_title' );
 *   if ( $st ) { get_template_part( 'template-parts/components/section-heading', null, $st ); }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.1.0
 */

$st_tag      = ! empty( $args['title_heading'] ) ? $args['title_heading'] : 'h2';
$st_subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$st_title    = isset( $args['title'] ) ? $args['title'] : '';
$st_layout   = ! empty( $args['description'] ) ? $args['description'] : 'right';
$st_left     = isset( $args['desciption_left'] ) ? $args['desciption_left'] : '';
$st_right    = isset( $args['description_right'] ) ? $args['description_right'] : '';
$st_buttons  = ( isset( $args['buttons'] ) && is_array( $args['buttons'] ) ) ? $args['buttons'] : array();
$st_image    = isset( $args['image'] ) ? (int) $args['image'] : 0;

$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$st_tag       = in_array( $st_tag, $allowed_tags, true ) ? $st_tag : 'h2';

$show_left  = in_array( $st_layout, array( 'left', 'both' ), true );
$show_right = in_array( $st_layout, array( 'right', 'both' ), true );

$btn_primary   = ! empty( $st_buttons['prmary'] ) ? $st_buttons['prmary'] : null;
$btn_secondary = ! empty( $st_buttons['secondary'] ) ? $st_buttons['secondary'] : null;

// Only render the intro/description row when there is something to show, so the
// component can be reused for a title-only heading (title + rule) — e.g. the
// About teaser, which builds its own text/image row below.
$st_has_row = ( $st_subtitle || $btn_primary || $btn_secondary || ( $show_left && $st_left ) || ( $show_right && $st_right ) );

if ( ! $st_title && ! $st_subtitle && ! $st_left && ! $st_right && ! $st_image ) {
	return;
}
?>
<header class="section-heading">
	<?php if ( $st_title ) : ?>
		<div class="section-heading__title-wrap border-b border-brand-red pb-6 xl:pb-10 mb-8 xl:mb-12">
			<div class="theme-grid">
				<<?php echo esc_html( $st_tag ); ?> class="title-main section-heading__title col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php
					echo wp_kses(
						$st_title,
						array(
							'br'     => array(),
							'strong' => array(),
							'em'     => array(),
						)
					);
					?>
				</<?php echo esc_html( $st_tag ); ?>>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $st_image ) : ?>
		<div class="section-heading__image mb-8 xl:mb-12">
			<?php
			echo wp_get_attachment_image(
				$st_image,
				'full',
				false,
				array(
					'class'   => 'w-full object-cover max-h-[182px] md:max-h-[230px] xl:max-h-[600px]',
					'loading' => 'lazy',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<?php if ( $st_has_row ) : ?>
		<div class="section-heading__row theme-grid">

			<div class="section-heading__intro col-span-2 md:col-span-2 xl:col-start-2 xl:col-span-4">
				<?php if ( $st_subtitle ) : ?>
					<p class="section-heading__subtitle mb-6 xl:mb-8 uppercase"><?php echo esc_html( $st_subtitle ); ?></p>
				<?php endif; ?>

				<?php if ( $btn_primary || $btn_secondary ) : ?>
					<div class="section-heading__buttons">

						<?php
						if ( $btn_primary ) {
							get_template_part(
								'template-parts/components/button',
								null,
								array_merge( $btn_primary, array( 'style' => 'primary' ) )
							);
						}

						if ( $btn_secondary ) {
							get_template_part(
								'template-parts/components/button',
								null,
								array_merge( $btn_secondary, array( 'style' => 'secondary' ) )
							);
						}
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ( $show_left && $st_left ) || ( $show_right && $st_right ) ) : ?>
				<div class="section-heading__desc col-span-2 md:col-start-4 md:col-span-3 xl:col-start-7 xl:col-span-5">
					<?php if ( $show_left && $st_left ) : ?>
						<div class="body-text section-heading__desc-text"><?php echo wp_kses_post( $st_left ); ?></div>
					<?php endif; ?>
					<?php if ( $show_right && $st_right ) : ?>
						<div class="body-text section-heading__desc-text"><?php echo wp_kses_post( $st_right ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	<?php endif; ?>
</header>
