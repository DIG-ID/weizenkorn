<?php
/**
 * The template for the Open Positions archive (post type `offene-stellen`,
 * Figma "Open positions_desktop"). Shared hero-section module, the
 * archive's own "Aktuell offene Stellen" job listing, the shared
 * offer-links module ("Weizenkorn mitgestalten. Wirkung entfalten."), and
 * the shared contact-person module ("Dürfen wir weiterhelfen?"). The two
 * category-grid calls that used to sit between offer-links and
 * contact-person ("Schaffen Perspektiven. Unsere Ausbildungen" and
 * "Sinnstiftend vielfältig Unsere Arbeitsstellen") are commented out below,
 * each moved to a page of its own as of 1.18.6: the training one to
 * Supported Apprenticeships' own "Schaffen Perspektiven", the job one to
 * Supported Jobs' own "Sinnstiftend vielfältig Unsere Arbeitsstellen".
 *
 * "Aktuell offene Stellen" has no filter or "Mehr laden" pagination yet —
 * both need the same AJAX work, so they're their own task. It shows every
 * published posting in one page for now; see
 * template-parts/archives/offene-stellen/job-listing.php.
 *
 * The trailing "Das könnte Sie auch interessieren" preview section, closing the archive, is
 * the shared preview-cards module — same 'option' + prefix pattern as every other section
 * here (prefix 'offene_stellen_archive_'); see the module's own docblock for the two ACF
 * Clone fields it expects.
 *
 * An archive has no post context, so its page-level fields live in the
 * theme options under the `offene_stellen_archive_` prefix, and every
 * section is told to read from there via $args — same approach as
 * archive-products.php.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.10.0
 */

get_header();

do_action( 'before_main_content' );

get_template_part(
	'template-parts/modules/hero-section',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_',
	)
);
get_template_part( 'template-parts/archives/offene-stellen/job-listing' );
get_template_part(
	'template-parts/modules/offer-links',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_',
	)
);
// Both moved to a page of their own — the training grid to Supported Apprenticeships'
// own "Schaffen Perspektiven" section, the job grid to Supported Jobs' own
// "Sinnstiftend vielfältig Unsere Arbeitsstellen" section.
/* get_template_part( 'template-parts/modules/category-grid', null, array( 'post_id' => 'option', 'prefix' => 'offene_stellen_archive_training_', 'columns' => 3 ) ); get_template_part( 'template-parts/modules/category-grid', null, array( 'post_id' => 'option', 'prefix' => 'offene_stellen_archive_jobs_', 'columns' => 4 ) ); */
get_template_part(
	'template-parts/modules/contact-person',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_',
	)
);
get_template_part(
	'template-parts/modules/preview-cards',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_',
	)
);

do_action( 'after_main_content' );

get_footer();
