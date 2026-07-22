<?php
/**
 * Design system: live preview of the theme's reusable components.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

$weizenkorn_buttons = array(
	array(
		'label' => 'Primary',
		'args'  => array(
			'title' => 'Button Design',
			'url'   => '#',
			'style' => 'primary',
		),
	),
	array(
		'label' => 'Secondary',
		'args'  => array(
			'title' => 'Button Design',
			'url'   => '#',
			'style' => 'secondary',
		),
	),
	array(
		'label' => 'Black',
		'args'  => array(
			'title' => 'Button Design',
			'url'   => '#',
			'style' => 'black',
		),
	),
	array(
		'label' => 'Arrow Down',
		'args'  => array(
			'title' => 'Button Design',
			'url'   => '#',
			'style' => 'arrow-down',
		),
	),
	array(
		'label' => 'Arrow Only',
		'args'  => array(
			'title' => 'Mehr erfahren',
			'url'   => '#',
			'style' => 'arrow',
		),
	),
);
?>
<div class="design-system">
	<div class="theme-container">

		<h2>Buttons</h2>

		<div class="flex flex-wrap items-start gap-10">
			<?php foreach ( $weizenkorn_buttons as $weizenkorn_button ) : ?>
				<div class="flex flex-col items-start gap-3">
					<span class="text-xs uppercase tracking-wide text-brand-dark/60"><?php echo esc_html( $weizenkorn_button['label'] ); ?></span>
					<?php get_template_part( 'template-parts/components/button', null, $weizenkorn_button['args'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<h2>Button — Primary only</h2>

		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'title' => 'Button Design',
				'url'   => '#',
				'style' => 'primary',
			)
		);
		?>

Exemplo a partir do ACF "link":

<?php
$cta_button = get_field( 'cta_button' ); // campo ACF tipo "Link"

if ( $cta_button ) {
	get_template_part(
		'template-parts/components/button',
		null,
		array_merge( $cta_button, array( 'style' => 'primary' ) )
	);
}
?>
	</div>
</div>
