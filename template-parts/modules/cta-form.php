<?php
/**
 * CTA form — shared module. A section heading followed by a full-width red band
 * holding a Contact Form 7 form. Used on 8–9 pages with a different heading each
 * time ("Noch Fragen?", "Möchten Sie mehr wissen?", "Dürfen wir Sie beraten?",
 * "Bereit für den Wechsel?"), see figma-architecture-analysis.txt §3.
 *
 * Layout — Figma "Noch Fragen?": desktop (2819:1043) confirmed 2026-07-31, tablet
 * (2530:1717) and mobile (2619:54) confirmed 2026-08-12.
 *
 *   desktop (1820px / 12 col) — heading on columns 2–11 with the red rule under
 *     it (both from the shared section-heading component), then the band at the
 *     full container width with 76px of vertical padding. The form is placed on
 *     the page grid: the stacked single-line fields on columns 2–6 (32px apart),
 *     the message box on columns 7–11, and the submit right-aligned across 2–11,
 *     45px below. Fields are white with 24px padding and 20/30 text.
 *   tablet (700px) — the two columns collapse into one flat stack of four controls
 *     32px apart, and the band takes over the inset with 64px of side padding and
 *     56px above and below. Type stays at the desktop 20/30.
 *   mobile (321px) — the same single stack 24px apart, band padding 24/48, and the
 *     type steps down to 14/22.
 *
 * The form itself is Contact Form 7, so its markup comes from the form template
 * the client writes in the plugin, not from here. This module renders the band
 * and drops the shortcode in; _modules/_cta-form.sass styles CF7's controls.
 * See that file for the form template to paste into CF7.
 *
 * SETUP NOTE (from the Figma annotation on this section): messages must go to
 * verkauf@weizenkorn.ch with the subject "Kontaktformular Weizenkorn -
 * Produktseite". That is CF7 Mail configuration, not theme code.
 *
 * ACF fields. Names come from the `cta` group in the field group — ACF joins a
 * group to its subfields with an underscore, so renaming that group renames all of
 * these and orphans whatever is already stored.
 *   cta_section_title      (clone of the "Section Title" group) — the heading, when
 *                          the clone is in Group display and returns an array.
 *   cta_title              (text) — the heading when the clone is Seamless, or when
 *                          a plain title field is used. Read only if the above is
 *                          empty. Every Figma frame of this section needs just this.
 *   cta_shortcode          (text) — optional. Overrides the form for this page or
 *                          archive only.
 *
 * ACF fields (theme options, unprefixed):
 *   general_cta_form_shortcode (text) — the site-wide default form, e.g.
 *                          [contact-form-7 id="12"]. Used whenever the field
 *                          above is empty, which is most of the time.
 *
 * Usage — on a page the fields come from the current post:
 *   get_template_part( 'template-parts/modules/cta-form' );
 *
 * A CPT archive has no post context, so pass the options store plus the
 * archive's field prefix, the same way as modules/hero-section:
 *   get_template_part(
 *       'template-parts/modules/cta-form',
 *       null,
 *       array(
 *           'post_id' => 'option',
 *           'prefix'  => 'products_archive_',
 *       )
 *   );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read
 *                               the fields from. Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 *                               Default: '' (names used as-is).
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

// ACF read context: the current post normally, the options store on archives.
$cta_ctx = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();

// Field-name prefix, so one shared module can serve several archives.
$cta_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

/*
 * The Contact Form 7 shortcode is never hard-coded: the form id differs between
 * local and production, so it lives in a field and each environment keeps its own
 * value.
 *
 * Read the per-context field first, then fall back to the site-wide default in the
 * theme options. The default is what makes this practical across the 8-9 pages
 * that use the section — set the form once — while a single page or archive can
 * still point at a different one by filling its own field.
 */
$cta_shortcode = get_field( $cta_prefix . 'cta_shortcode', $cta_ctx );

if ( ! $cta_shortcode ) {
	$cta_shortcode = get_field( 'general_cta_form_shortcode', 'option' );
}

// Nothing to show without a form: the band on its own is an empty red block.
if ( ! $cta_shortcode ) {
	return;
}
?>
<section class="cta-form mt-20 mb-28 md:mb-32 md:mt-32 xl:mb-48 xl:mt-48">
	<div class="theme-container">

		<?php
		/*
		 * The heading comes from the shared helper, which reads a cloned "Section Title" group
		 * in either of its Displays and rebuilds the links a Seamless clone hides behind a
		 * composite field reference. See weizenkorn_get_section_heading() in
		 * inc/theme-template-tags.php for why that read is not a plain get_field().
		 */
		$cta_heading = weizenkorn_get_section_heading( $cta_prefix . 'cta_', $cta_ctx );

		if ( $cta_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $cta_heading );
		}
		?>

		<?php
		/*
		 * The band is the full container width and carries no grid of its own: the
		 * form's own row is the .theme-grid (see the CF7 template in
		 * _modules/_cta-form.sass), so the fields are placed on the page's real
		 * columns — 2-6 for the stacked fields, 7-11 for the message box.
		 *
		 * That is why there is no side padding at xl. The inset comes from the grid,
		 * so the form lines up with the heading above it and with every other
		 * section. Below xl the columns stack full width, so plain padding is enough.
		 */
		?>
		<div class="cta-form__band bg-brand-red px-6 py-12 md:px-16 md:py-14 xl:px-0 xl:py-[76px]">
			<?php echo do_shortcode( $cta_shortcode ); ?>
		</div>
	</div>
</section>
