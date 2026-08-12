<?php
/**
 * Home — Products section ("Schönes mit Sinn" / PRODUKTE).
 * Section heading (reusable "Section Title" clone) + grid of product-range cards.
 *
 * ACF structure (group "products"):
 *   section_title (clone → "Section Title" group; fed to the section-heading
 *                  component, which renders subtitle, title, descriptions and
 *                  the CTA buttons)
 *   ranges        (repeater) → image (image, ID), title (text),
 *                                   text (textarea), page (link)
 *
 * The cards themselves are rendered by components/range-grid, which the products
 * archive uses too — this section only supplies the rows.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.1.0
 */

?>
<section class="section-products mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php if ( have_rows( 'products' ) ) : ?>
			<?php
			while ( have_rows( 'products' ) ) :
				the_row();
				?>
				<?php if ( get_sub_field( 'section_title' ) ) : ?>
					<?php get_template_part( 'template-parts/components/section-heading', null, get_sub_field( 'section_title' ) ); ?>
				<?php endif; ?>

				<?php if ( get_sub_field( 'ranges' ) ) : ?>
					<?php
					// The card flow is shared with the products archive, so it lives in a
					// component. The page grid stays 12-col; the component only fills the
					// inset span it is given.
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
