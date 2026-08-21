<?php
/**
 * CTA form — a section heading followed by a full-width red band holding a Contact Form 7
 * form. Used on 8-9 pages with a different heading each time.
 *
 * The form's markup comes from the template the client writes in the plugin, not from
 * here. This module renders the band and drops the shortcode in; _modules/_cta-form.sass
 * styles CF7's controls and holds the form template to paste into CF7.
 *
 * SETUP NOTE: messages must go to verkauf@weizenkorn.ch with the subject "Kontaktformular
 * Weizenkorn - Produktseite". That is CF7 Mail configuration, not theme code.
 *
 * ACF fields. Names come from the `cta` group — ACF joins a group to its subfields with an
 * underscore, so renaming it orphans whatever is stored:
 *   cta_section_title  (clone of "Section Title") the heading, when the clone is in Group
 *                      display and returns an array.
 *   cta_title          (text) the heading when the clone is Seamless. Read only if the
 *                      above is empty.
 *   cta_shortcode      (text) optional. Overrides the form for this page or archive only.
 *
 * ACF fields (theme options, unprefixed):
 *   general_cta_form_shortcode (text) the site-wide default form. Used whenever the field
 *                              above is empty, which is most of the time.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/cta-form' );
 *
 * A CPT archive has no post context, so pass the options store plus the archive's prefix:
 *   get_template_part(
 *       'template-parts/modules/cta-form',
 *       null,
 *       array( 'post_id' => 'option', 'prefix' => 'products_archive_' )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read the fields
 *                               from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

$cta_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$cta_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Never hard-coded: the CF7 form id differs between local and production, so it lives in
// a field and each environment keeps its own value. Per-context field first, then the
// site-wide default — set the form once, override it on one page if needed.
$cta_shortcode = get_field( $cta_prefix . 'cta_shortcode', $cta_ctx );

if ( ! $cta_shortcode ) {
	$cta_shortcode = get_field( 'general_cta_form_shortcode', 'option' );
}

// The band on its own is an empty red block.
if ( ! $cta_shortcode ) {
	return;
}
?>
<section class="cta-form mt-20 mb-28 md:mb-32 md:mt-32 xl:mb-48 xl:mt-48">
	<div class="theme-container">

		<?php
		// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
		$cta_heading = weizenkorn_get_section_heading( $cta_prefix . 'cta_', $cta_ctx );

		if ( $cta_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $cta_heading );
		}
		?>

		<?php
		/*
		 * The band carries no grid of its own: the form's own row is the .theme-grid (see
		 * the CF7 template in _modules/_cta-form.sass), so the fields sit on the page's
		 * real columns. That is why there is no side padding at xl — the inset comes from
		 * the grid, so the form lines up with the heading and with every other section.
		 */
		?>
		<div class="cta-form__band bg-brand-red px-6 py-12 md:px-16 md:py-14 xl:px-0 xl:py-[76px]">
			<?php echo do_shortcode( $cta_shortcode ); ?>
		</div>
	</div>
</section>
