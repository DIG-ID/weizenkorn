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

if ( ! function_exists( 'weizenkorn_news_listing_meta_query' ) ) :
	/**
	 * The meta_query that keeps articles marked "hide from listings" out of a listing.
	 *
	 * An article hidden this way is still published and still has its own URL — it is
	 * reachable from wherever it is linked, and by anyone with the address. It is only kept
	 * out of the places that assemble a list of their own: the archive's featured article,
	 * the archive grid (and the REST route that pages it), and the slider under a single.
	 *
	 * The OR is not optional. A true/false field writes no row until the post is saved once,
	 * so an article that predates the field has no meta at all — testing only for '!= 1'
	 * would silently drop every one of them.
	 *
	 * @since 1.11.0
	 *
	 * @return array A meta_query array.
	 */
	function weizenkorn_news_listing_meta_query() {
		return array(
			'relation' => 'OR',
			array(
				'key'     => 'news_hide_from_lists',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'news_hide_from_lists',
				'value'   => '1',
				'compare' => '!=',
			),
		);
	}
endif;

if ( ! function_exists( 'weizenkorn_news_adjacent_post_where' ) ) :
	/**
	 * Keeps articles marked "hide from listings" out of the previous/next links too.
	 *
	 * Every other listing takes a meta_query — get_adjacent_post() does not, it builds one
	 * SQL string and hands it to a filter, so the same rule has to be written as SQL here.
	 * Without it a reader walking the articles with those two buttons lands on an article
	 * the archive deliberately leaves out.
	 *
	 * The subquery is the whole condition rather than a JOIN: the posts with the flag are a
	 * short list, and a JOIN on postmeta would have to be LEFT to keep the articles that
	 * have no row at all — the same trap the meta_query's NOT EXISTS avoids.
	 *
	 * @since 1.11.0
	 *
	 * @param string  $where          The WHERE clause, whose posts table is aliased `p`.
	 * @param bool    $in_same_term   Unused.
	 * @param array   $excluded_terms Unused.
	 * @param string  $taxonomy       Unused.
	 * @param WP_Post $post           The post the adjacent one is being found for.
	 * @return string The WHERE clause.
	 */
	function weizenkorn_news_adjacent_post_where( $where, $in_same_term, $excluded_terms, $taxonomy, $post ) {
		if ( ! $post instanceof WP_Post || 'news' !== $post->post_type ) {
			return $where;
		}

		global $wpdb;

		$where .= $wpdb->prepare(
			" AND p.ID NOT IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s )",
			'news_hide_from_lists',
			'1'
		);

		return $where;
	}
	add_filter( 'get_previous_post_where', 'weizenkorn_news_adjacent_post_where', 10, 5 );
	add_filter( 'get_next_post_where', 'weizenkorn_news_adjacent_post_where', 10, 5 );
endif;

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
				'meta_query'          => weizenkorn_news_listing_meta_query(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- the listing has to exclude hidden articles, and the alternative is over-fetching and filtering in PHP, which breaks paging.
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
