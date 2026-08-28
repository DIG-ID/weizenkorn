<?php
/**
 * The template for the Open Positions archive (post type `offene-stellen`,
 * Figma "Open positions_desktop"). Shared hero-section module, the shared
 * offer-links module ("Weizenkorn mitgestalten. Wirkung entfalten."), the
 * archive's own category-grid partial called twice ("Schaffen Perspektiven.
 * Unsere Ausbildungen" and "Sinnstiftend vielfältig Unsere Arbeitsstellen"),
 * and the shared contact-person module ("Dürfen wir weiterhelfen?").
 *
 * Two sections from the Figma frame are intentionally not built yet:
 *   - "Aktuell offene Stellen" — the actual job listing cards. These come
 *     from the offene-stellen posts themselves (results count, filter,
 *     card grid, load-more), so the single post template needs to exist
 *     first; that's the next piece of work, not this one.
 *   - The trailing "Das könnte Sie auch interessieren" preview section —
 *     not present in this archive's Figma frame at all (unlike the page
 *     templates, which do have one).
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
get_template_part(
	'template-parts/modules/offer-links',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_',
	)
);
get_template_part(
	'template-parts/archives/offene-stellen/category-grid',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_training_',
		'columns' => 3,
	)
);
get_template_part(
	'template-parts/archives/offene-stellen/category-grid',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_jobs_',
		'columns' => 4,
	)
);
get_template_part(
	'template-parts/modules/contact-person',
	null,
	array(
		'post_id' => 'option',
		'prefix'  => 'offene_stellen_archive_',
	)
);

do_action( 'after_main_content' );

get_footer();
