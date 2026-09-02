<?php
/**
 * News listing — the query variable the grid pages with, and the REST route that serves
 * the next page of cards.
 *
 * The route returns rendered HTML rather than the posts as JSON, so the card markup lives
 * in template-parts/components/card-news.php and nowhere else. A second copy of it in
 * JavaScript would drift from the first the day either one is touched.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.10.0
 */

if ( ! function_exists( 'weizenkorn_news_query_vars' ) ) :
	/**
	 * Registers the grid's page number as a query variable.
	 *
	 * A query variable and not $_GET: it survives WordPress's own rewriting, needs no
	 * unslashing, and reads the same on the archive and on a single article, where the
	 * main query's own paging is not available to the grid.
	 *
	 * @since 1.10.0
	 *
	 * @param array $vars Public query variables.
	 * @return array
	 */
	function weizenkorn_news_query_vars( $vars ) {
		$vars[] = 'news_page';

		return $vars;
	}
endif;

add_filter( 'query_vars', 'weizenkorn_news_query_vars' );

if ( ! function_exists( 'weizenkorn_register_news_routes' ) ) :
	/**
	 * Registers the route the news grid pages with.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	function weizenkorn_register_news_routes() {
		register_rest_route(
			'weizenkorn/v1',
			'/news',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'weizenkorn_rest_news_page',
				'permission_callback' => '__return_true',
				'args'                => array(
					'page'     => array(
						'type'              => 'integer',
						'default'           => 1,
						'sanitize_callback' => 'absint',
					),
					'exclude'  => array(
						'type'              => 'integer',
						'default'           => 0,
						'sanitize_callback' => 'absint',
					),
					'per_page' => array(
						'type'              => 'integer',
						'default'           => 6,
						'sanitize_callback' => 'absint',
					),
				),
			)
		);
	}
endif;

add_action( 'rest_api_init', 'weizenkorn_register_news_routes' );

if ( ! function_exists( 'weizenkorn_rest_news_page' ) ) :
	/**
	 * Returns one page of news cards, as the markup the grid already holds.
	 *
	 * @since 1.10.0
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	function weizenkorn_rest_news_page( $request ) {
		$page     = max( 1, (int) $request->get_param( 'page' ) );
		$exclude  = (int) $request->get_param( 'exclude' );
		$per_page = min( 24, max( 1, (int) $request->get_param( 'per_page' ) ) );

		$query = new WP_Query(
			array(
				'post_type'           => 'news',
				'post_status'         => 'publish',
				'posts_per_page'      => $per_page,
				'paged'               => $page,
				'post__not_in'        => $exclude ? array( $exclude ) : array(),
				'ignore_sticky_posts' => true,
			)
		);

		ob_start();

		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<div class="news-cards__item col-span-2 md:col-span-3 xl:col-span-4">
				<?php get_template_part( 'template-parts/components/card-news' ); ?>
			</div>
			<?php
		}

		wp_reset_postdata();

		return rest_ensure_response(
			array(
				'html' => ob_get_clean(),
				'page' => $page,
				'max'  => (int) $query->max_num_pages,
			)
		);
	}
endif;
