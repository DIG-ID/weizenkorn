<?php
/**
 * Work & Training — "Zuweisende Stellen" section (Figma "Group 1000006258" on
 * Work & Training_desktop). Title + a primary button and a description on
 * the right — entirely the shared section-heading component; this file only
 * reads the fields and fixes the layout choice (button left, text right),
 * since that isn't meant to be editable here.
 *
 * ACF fields (flat, prefixed):
 *   work_training_referrals_title           (text)
 *   work_training_referrals_buttons_prmary  (link)   [sic, matches
 *                                            weizenkorn_get_section_heading()'s
 *                                            expected field name]
 *   work_training_referrals_text            (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.7.0
 */

$wtr_heading = weizenkorn_get_section_heading( 'work_training_referrals_', get_the_ID() );

if ( ! $wtr_heading ) {
	return;
}

$wtr_heading['description']       = 'right';
$wtr_heading['description_right'] = get_field( 'work_training_referrals_text' );
?>
<section class="section-work-referrals mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, $wtr_heading ); ?>
	</div>
</section>
