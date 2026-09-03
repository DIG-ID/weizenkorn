<?php
/**
 * Open Positions archive — "Aktuell offene Stellen" section (Figma desktop
 * node 2629:3105, filter panel node 4129:5548). Title, a results count with
 * the filter trigger beside it, a grid of the first 9 published
 * offene-stellen posts — three across at desktop, two at tablet, one at
 * mobile — and a "Mehr Laden" button.
 *
 * This is deliberately just the SSR first page: filtering and "Mehr Laden"
 * are the same query (see inc/rest-job-filters.php's own docblock), both
 * handled client-side by assets/js/job-filters.js against the
 * weizenkorn/v1/jobs REST route, which swaps this section's grid rather
 * than this template rendering more than page 1 itself. The two both read
 * $jl_per_page so the SSR page and every REST page after it stay the same
 * size.
 *
 * The cards are template-parts/components/card-job.php, rendered here via
 * weizenkorn_render_job_cards() (inc/theme-template-tags.php) — the same
 * helper the REST endpoint calls, so the SSR page and every page loaded
 * after it are identical markup. It's also the single post template's
 * "Weitere Stellenausschreibungen" slider's component, confirmed as the
 * same Figma component reused in both places.
 *
 * The `offene-stellen` post type must support 'excerpt' — the card text,
 * read raw so an empty one renders no text (same convention as
 * template-parts/archives/product/ranges.php).
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

$jl_per_page = 9;

$jl_query = new WP_Query(
	array(
		'post_type'      => 'offene-stellen',
		'post_status'    => 'publish',
		'posts_per_page' => $jl_per_page,
		'paged'          => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

if ( ! $jl_query->have_posts() ) {
	return;
}
?>
<section class="job-listing mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => __( 'Aktuell offene Stellen', 'weizenkorn' ) ) ); ?>

		<div class="theme-grid mt-8 xl:mt-12">
			<div class="job-listing__bar col-span-2 xl:col-start-2 xl:col-span-10 flex items-center justify-between">
				<p class="job-listing__count js-job-listing-count body-text text-brand-dark">
					<?php
					printf(
						/* translators: %d: number of open positions. */
						esc_html( _n( '%d Resultat', '%d Resultate', $jl_query->found_posts, 'weizenkorn' ) ),
						(int) $jl_query->found_posts
					);
					?>
				</p>
				<?php get_template_part( 'template-parts/archives/offene-stellen/job-filters' ); ?>
			</div>
		</div>

		<?php
		/*
		 * Deliberately against Figma (client request): the frame's own cards stay a
		 * fixed 440px, flush left with empty space to the right of the third one at
		 * desktop — here they instead stretch to fill the full 10-column inset, three
		 * equal columns with no leftover gap. xl:grid-cols-3 replaces the flex-wrap
		 * layout that used to size each card to its own fixed width; the fixed width
		 * itself (weizenkorn_render_job_cards()'s xl:w-[440px] wrapper, shared with the
		 * single post's own related-jobs slider, which keeps it) is neutralised for
		 * this grid only in _archives/_offene-stellen.sass.
		 *
		 * The nested .job-listing__grid below stays one level inside a plain theme-grid
		 * (used only to position it in the 10-column inset) rather than sharing a single
		 * element with theme-grid itself — theme-grid and a grid utility on the very
		 * same element fight at equal specificity and the utility loses.
		 */
		?>
		<div class="theme-grid mt-8 xl:mt-12">
			<div
				class="job-listing__grid js-job-listing-grid col-span-2 xl:col-start-2 xl:col-span-10 flex flex-col gap-y-8 md:grid md:grid-cols-2 md:gap-x-5 md:gap-y-8 xl:grid xl:grid-cols-3 xl:gap-x-5 xl:gap-y-8"
				data-page="1"
				data-max-pages="<?php echo esc_attr( $jl_query->max_num_pages ); ?>"
			>
				<?php
				echo weizenkorn_render_job_cards( $jl_query ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- card-job.php escapes its own output; this just concatenates one rendered card per post.
				?>
			</div>
		</div>

		<?php if ( $jl_query->max_num_pages > 1 ) : ?>
			<div class="job-listing__more js-job-listing-more theme-grid mt-12 xl:mt-16">
				<?php /* col-span the full row, then flex+justify-center to actually center the button in it — justify-items-center on the grid itself would only center this div within its own track, and spanning the full row makes that a no-op. */ ?>
				<div class="col-span-2 md:col-span-6 xl:col-span-12 flex justify-center">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'title' => __( 'Mehr Laden', 'weizenkorn' ),
							'style' => 'primary',
							'icon'  => 'arrow-down',
							'type'  => 'submit',
						)
					);
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
