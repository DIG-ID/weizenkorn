<?php
/**
 * Hero Section in the Home Page Template.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

?>
<section class="section-hero">
	<div class="theme-container">
		<?php
		// Image display example.
		$img_id = get_field( 'hero_image' );
		if ( $img_id ) :
			echo wp_get_attachment_image(
				$img_id,
				'full',
				false,
				array(
					'class'         => 'w-full h-auto object-cover',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			);
	endif;
		?>
	</div>
</section>
