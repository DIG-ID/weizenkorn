<?php
/**
 * Supported Jobs — "Arbeiten mit IV-Rente" section (Figma desktop node
 * 180:3610). Title + a primary button and a description on the right —
 * entirely the shared section-heading component; this file only reads the
 * fields and fixes the layout choice (button left, text right), matching
 * template-parts/pages/work-training/referrals.php's shape exactly.
 *
 * The button ("Unsere offenen Stellen") is a plain ACF link field for now —
 * linking it to a pre-filtered "Arbeitsstellen only" view of the jobs
 * listing (per the Figma annotation) needs that filtering to exist first;
 * until then, the editor points it at the jobs page directly.
 *
 * ACF fields (flat, prefixed):
 *   supported_jobs_intro_title           (text)
 *   supported_jobs_intro_buttons_prmary  (link)   [sic, matches
 *                                         weizenkorn_get_section_heading()'s
 *                                         expected field name]
 *   supported_jobs_intro_text            (textarea / wpautop)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.8.0
 */

$sji_heading = weizenkorn_get_section_heading( 'supported_jobs_intro_', get_the_ID() );

if ( ! $sji_heading ) {
	return;
}

$sji_heading['description']       = 'right';
$sji_heading['description_right'] = get_field( 'supported_jobs_intro_text' );
?>
<section class="section-supported-jobs-intro mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, $sji_heading ); ?>
	</div>
</section>
