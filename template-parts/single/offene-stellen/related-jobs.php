<?php
/**
 * Open Positions single post — "Weitere Stellenausschreibungen" related-jobs
 * slider (Figma desktop node 4450:6539). Title, an "Alle offenen Stellen"
 * button to the archive, then a slider of other offene-stellen posts —
 * three per view at desktop (where the controls hide, there being nothing
 * left to scroll to), one per view below it. Same arrows-in-the-outer-grid-
 * columns pattern as template-parts/modules/stories-references.php.
 *
 * The cards are template-parts/components/card-job.php, not an ACF
 * repeater: the posts ARE the record, one per job. Excluding this post
 * keeps a job from listing itself as "more like this".
 *
 * The `offene-stellen` post type must support:
 *   excerpt  the card text — read raw, so an empty one renders no text.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

$rj_query = new WP_Query(
	array(
		'post_type'      => 'offene-stellen',
		'post_status'    => 'publish',
		'post__not_in'   => array( get_the_ID() ),
		'posts_per_page' => 9,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	)
);

if ( ! $rj_query->have_posts() ) {
	return;
}

$rj_count        = count( $rj_query->posts );
$rj_has_controls = ( $rj_count > 1 );
$rj_fits_xl      = ( $rj_count <= 3 );
?>
<section class="related-jobs<?php echo $rj_fits_xl ? ' related-jobs--fits-xl' : ''; ?> mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => __( 'Weitere Stellenausschreibungen', 'weizenkorn' ) ) ); ?>

		<div class="theme-grid justify-items-start mt-8 xl:mt-12">
			<?php // md:col-start-2, matching where __viewport itself starts at this breakpoint (see _singles/_offene-stellen.sass) — col-span-5 rather than 6 so it still fits the grid without overflowing a 7th column. ?>
			<div class="col-span-2 md:col-start-2 md:col-span-5 xl:col-start-2 xl:col-span-10">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'title' => __( 'Alle offenen Stellen', 'weizenkorn' ),
						'url'   => get_post_type_archive_link( 'offene-stellen' ),
						'style' => 'primary',
					)
				);
				?>
			</div>
		</div>

		<div class="related-jobs__row theme-grid mt-8 xl:mt-12">

			<?php if ( $rj_has_controls ) : ?>
				<button type="button" class="related-jobs__nav related-jobs__nav--prev js-related-jobs-prev" aria-label="<?php echo esc_attr_x( 'Previous', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>
			<?php endif; ?>

			<div class="related-jobs__viewport">
				<div class="swiper js-related-jobs-slider">
					<div class="swiper-wrapper">
						<?php
						while ( $rj_query->have_posts() ) :
							$rj_query->the_post();
							?>
							<div class="swiper-slide">
								<?php
								get_template_part(
									'template-parts/components/card-job',
									null,
									array(
										'category' => weizenkorn_get_post_term_names( get_the_ID(), 'offene_stellen_anstellungsart' ),
										'location' => weizenkorn_get_post_term_names( get_the_ID(), 'offene_stellen_standort' ),
										'title'    => get_the_title(),
										'text'     => get_post()->post_excerpt,
										'url'      => get_permalink(),
									)
								);
								?>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>

			<?php if ( $rj_has_controls ) : ?>
				<button type="button" class="related-jobs__nav related-jobs__nav--next js-related-jobs-next" aria-label="<?php echo esc_attr_x( 'Next', 'slider control', 'weizenkorn' ); ?>">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</button>

				<div class="related-jobs__pagination js-related-jobs-pagination"></div>
			<?php endif; ?>

		</div>
	</div>
</section>
