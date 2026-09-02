<?php
/**
 * News cards — a heading and a page of news, six to a page, with an arrow either side.
 *
 * Two arrangements. The archive pages a grid of six with arrows under it. A single
 * article carries three as a slider — three abreast at desktop, one at a time with bullets
 * below it, which is the stories-references slider's behaviour and the same Swiper set-up.
 *
 * Both draw the same card and run the same query, so the difference is an argument rather
 * than a second module.
 *
 * PAGING
 *
 * The arrows are real links carrying `news_page`, and the query reads it, so the grid
 * pages with JavaScript switched off — a page load rather than a swap, same content. The
 * enhancement lives in assets/js/news-pagination.js: it catches the clicks, asks the REST
 * route for the next six cards, replaces the grid and writes the new page into the URL.
 * The data attributes below are what it needs to do that.
 *
 * The post above is excluded with post__not_in and never with `offset`: offset on a paged
 * query leaves max_num_pages counting the excluded post, and the last page comes back
 * short or repeats one. With a single id there is no cost to the exclusion.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/news-cards', null, array( 'exclude' => $id ) );
 *
 * @param array $args {
 *     @type int    $exclude  Optional. Post to leave out — the featured one, or the one
 *                            being read. Default: none.
 *     @type int    $per_page Optional. Default 6, which is the frame's three by two.
 *     @type string $title    Optional. Heading above the grid.
 *     @type bool   $back     Optional. Show the link back to the archive, which the single
 *                            article carries and the archive itself has no use for.
 *     @type string $variant  Optional. 'slider' for the single article's three abreast.
 *                            Default: the archive's paged grid.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.10.0
 */

$nc_exclude  = ! empty( $args['exclude'] ) ? (int) $args['exclude'] : 0;
$nc_per_page = ! empty( $args['per_page'] ) ? (int) $args['per_page'] : 6;
$nc_title    = isset( $args['title'] ) ? $args['title'] : __( 'Weitere Beiträge', 'weizenkorn' );
$nc_back     = ! empty( $args['back'] ) ? get_post_type_archive_link( 'news' ) : '';
$nc_page     = max( 1, (int) get_query_var( 'news_page' ) );
$nc_slider   = ( ! empty( $args['variant'] ) && 'slider' === $args['variant'] );

// Six in the slider: three abreast at desktop is one view, and the frame draws arrows and
// bullets, which are only worth anything when there is a second view to reach.
if ( $nc_slider ) {
	$nc_per_page = ! empty( $args['per_page'] ) ? (int) $args['per_page'] : 6;
	$nc_page     = 1;
}

$nc_query = new WP_Query(
	array(
		'post_type'           => 'news',
		'post_status'         => 'publish',
		'posts_per_page'      => $nc_per_page,
		'paged'               => $nc_page,
		'post__not_in'        => $nc_exclude ? array( $nc_exclude ) : array(),
		'ignore_sticky_posts' => true,
	)
);

if ( ! $nc_query->have_posts() ) {
	wp_reset_postdata();
	return;
}

$nc_max = (int) $nc_query->max_num_pages;
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="news-cards mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $nc_title ) {
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'title_heading' => 'h2',
					'title'         => $nc_title,
				)
			);
		}
		?>

		<?php if ( $nc_back ) : ?>
			<div class="news-cards__back theme-grid">
				<div class="col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'title' => __( 'Zurück zur Übersicht', 'weizenkorn' ),
							'url'   => $nc_back,
							'style' => 'primary',
						)
					);
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $nc_slider ) : ?>
			<div class="theme-grid mt-8 md:mt-14 xl:mt-24">
				<div class="news-cards__slider-col col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">
					<?php
					// A frame around the slider alone, because the arrows are placed against it:
					// measured from the column they would centre on the cards plus the bullets
					// under them, which puts them lower than the frame's own position.
					?>
					<div class="news-cards__slider-frame">
					<div class="swiper js-news-slider">
						<div class="swiper-wrapper">
							<?php
							while ( $nc_query->have_posts() ) :
								$nc_query->the_post();
								?>
								<div class="swiper-slide">
									<?php get_template_part( 'template-parts/components/card-news' ); ?>
								</div>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>

					<?php
					// Buttons and not links: these move the slider, they do not go anywhere. The
					// arrows sit outside the cards in the container's margins, which is why they
					// are siblings of the slider and placed against this column.
					?>
					<button type="button" class="news-cards__nav-arrow news-cards__nav-arrow--prev js-news-prev" aria-label="<?php echo esc_attr_x( 'Previous articles', 'slider control', 'weizenkorn' ); ?>">
						<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
					</button>

					<button type="button" class="news-cards__nav-arrow news-cards__nav-arrow--next js-news-next" aria-label="<?php echo esc_attr_x( 'More articles', 'slider control', 'weizenkorn' ); ?>">
						<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
					</button>

					</div>

					<div class="news-cards__pagination js-news-pagination"></div>
				</div>
			</div>
		<?php else : ?>
			<div class="theme-grid mt-8 md:mt-14 xl:mt-24">
			<?php
			// A nested grid over the inset, not the page grid: the cards keep to columns 2-11,
			// where the heading starts, and the inset re-divided into twelve makes a card of
			// four come out at the frame's 490. Four of the page's own twelve would be 593.
			?>
			<div
				class="news-cards__grid theme-grid col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10"
				data-news-grid
				data-exclude="<?php echo esc_attr( (string) $nc_exclude ); ?>"
				data-per-page="<?php echo esc_attr( (string) $nc_per_page ); ?>"
				data-page="<?php echo esc_attr( (string) $nc_page ); ?>"
				data-max="<?php echo esc_attr( (string) $nc_max ); ?>"
			>
				<?php
				while ( $nc_query->have_posts() ) :
					$nc_query->the_post();
					?>
					<div class="news-cards__item col-span-2 md:col-span-3 xl:col-span-4">
						<?php get_template_part( 'template-parts/components/card-news' ); ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			</div>
		<?php endif; ?>

		<?php if ( ! $nc_slider && $nc_max > 1 ) : ?>
			<?php
			// Links and not buttons: with no JavaScript these are what pages the grid, and
			// an arrow that leads to another page of content is a link either way. The JS
			// upgrades them in place rather than replacing them.
			?>
			<nav class="news-cards__nav flex items-center justify-center gap-20 xl:gap-12" aria-label="<?php esc_attr_e( 'News pages', 'weizenkorn' ); ?>">
				<?php
				// One arrow in the icon set, turned around for the one that goes back. A second
				// SVG that is the mirror of the first would be a second thing to keep in step.
				?>
				<a
					class="news-cards__arrow news-cards__arrow--prev"
					href="<?php echo esc_url( add_query_arg( 'news_page', max( 1, $nc_page - 1 ) ) ); ?>"
					rel="prev"
					data-news-prev
					<?php echo 1 === $nc_page ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
				>
					<span class="sr-only"><?php esc_html_e( 'Previous news', 'weizenkorn' ); ?></span>
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</a>

				<a
					class="news-cards__arrow news-cards__arrow--next"
					href="<?php echo esc_url( add_query_arg( 'news_page', min( $nc_max, $nc_page + 1 ) ) ); ?>"
					rel="next"
					data-news-next
					<?php echo $nc_page >= $nc_max ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
				>
					<span class="sr-only"><?php esc_html_e( 'More news', 'weizenkorn' ); ?></span>
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</a>
			</nav>
		<?php endif; ?>

	</div>
</section>
