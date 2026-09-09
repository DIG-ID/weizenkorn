<?php
/**
 * Home — Products section.
 *
 * A section heading and a grid of product-range cards. The cards are rendered by
 * components/range-grid, which the products archive uses too; this section only supplies
 * the rows.
 *
 * ACF structure (group "products"):
 *   section_title (clone → "Section Title") fed to the section-heading component
 *   hide_secondary_button (true/false) hides the heading's second button without emptying
 *                 its Link, so a seasonal CTA can be switched off and back on. Only this
 *                 section has it — the shared clone deliberately does not, which is what
 *                 keeps the checkbox out of the other seventeen sections' admin.
 *   ranges        (repeater) → image (image, ID), title (text), text (textarea),
 *                              page (link)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

?>
<section id="section-products" class="section-products mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php if ( have_rows( 'products' ) ) : ?>
			<?php
			while ( have_rows( 'products' ) ) :
				the_row();
				?>
				<?php if ( get_sub_field( 'section_title' ) ) : ?>
					<?php
					get_template_part(
						'template-parts/components/section-heading',
						null,
						array_merge(
							get_sub_field( 'section_title' ),
							array( 'hide_secondary' => (bool) get_sub_field( 'hide_secondary_button' ) )
						)
					);
					?>
				<?php endif; ?>

				<?php if ( get_sub_field( 'ranges' ) ) : ?>
					<?php
					// The page grid stays 12-col; the component only fills the inset span it is given.
					?>
					<div class="theme-grid">
						<div class="col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10 mt-8 md:mt-14 xl:mt-24">
							<?php
							get_template_part(
								'template-parts/components/range-grid',
								null,
								array( 'ranges' => get_sub_field( 'ranges' ) )
							);
							?>
						</div>
					</div>
				<?php endif; ?>
				<?php
			endwhile;
			?>
		<?php endif; ?>
	</div>
</section>
