<?php
/**
 * One-time migration: moves the workshop terms out of the Standort taxonomy
 * into Betrieb.
 *
 * Until 1.20.0 offene_stellen_standort held two kinds of term in one list —
 * the client's own workshops (Holzmanufaktur, DASBREITEHOTEL, Bäckerei
 * Produktion, …) and the towns they're in (Münchenstein, Basel-Stadt, …) —
 * and editors tagged each job with one of each. This re-files every workshop
 * term under offene_stellen_betrieb in place, by changing the term's own
 * taxonomy row rather than creating a new term, so every job keeps the
 * terms it already had and nobody has to tag anything again.
 *
 * A term counts as a workshop when its name contains one of the keywords in
 * weizenkorn_is_job_betrieb_name(). Anything else stays in Standort. Each
 * moved term takes its WPML translations along with it, whatever they are
 * called, and is removed from the ACF taxonomy field's own saved value too,
 * so that field can't write a workshop back into Standort on the next save.
 * It also has WPML reread wpml-config.xml, where Betrieb is declared
 * translatable.
 *
 * Runs on the first request after deploy, then never again (the
 * weizenkorn_job_betrieb_migrated option). The next admin page load shows a
 * notice with what moved and what stayed, to check against the Standort list.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.20.0
 */

/**
 * Tells whether a Standort term name is one of the client's workshops.
 *
 * @since 1.20.0
 *
 * @param string $name Term name.
 * @return bool
 */
function weizenkorn_is_job_betrieb_name( $name ) {
	$keywords = array(
		'manufaktur',
		'schreinerei',
		'hotel',
		'backerei',
		'baeckerei',
		'atelier',
		'rhyvage',
		'cantina',
		'xyloba',
		'treuhand',
		'holzspielwaren',
	);

	$normalised = strtolower( remove_accents( $name ) );

	foreach ( $keywords as $keyword ) {
		if ( false !== strpos( $normalised, $keyword ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Moves the workshop terms from offene_stellen_standort to offene_stellen_betrieb.
 *
 * Reads and writes the term tables directly: get_terms() would only return
 * the current WPML language's terms, and there is no core API for moving a
 * term to another taxonomy.
 *
 * @since 1.20.0
 */
function weizenkorn_migrate_job_betrieb() {
	if ( get_option( 'weizenkorn_job_betrieb_migrated' ) ) {
		return;
	}

	update_option( 'weizenkorn_job_betrieb_migrated', '1', true );

	global $wpdb;

	// phpcs:disable WordPress.DB.DirectDatabaseQuery -- one-time migration, see the file docblock.
	$rows = $wpdb->get_results(
		"SELECT tt.term_taxonomy_id, tt.term_id, tt.parent, t.name
		FROM {$wpdb->term_taxonomy} tt
		INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
		WHERE tt.taxonomy = 'offene_stellen_standort'"
	);

	$by_tt_id = array();
	$matched  = array();

	foreach ( $rows as $row ) {
		$by_tt_id[ (int) $row->term_taxonomy_id ] = $row;

		if ( weizenkorn_is_job_betrieb_name( $row->name ) ) {
			$matched[] = (int) $row->term_taxonomy_id;
		}
	}

	$moved     = $matched;
	$wpml_tbl  = $wpdb->prefix . 'icl_translations';
	$has_wpml  = ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpml_tbl ) ) === $wpml_tbl );
	$wpml_type = 'tax_offene_stellen_standort';

	// Bring every translation of a matched term along, whatever it's called.
	if ( $has_wpml && $matched ) {
		$ids  = implode( ',', array_map( 'intval', $matched ) );
		$trid = $wpdb->get_col( "SELECT DISTINCT trid FROM {$wpml_tbl} WHERE element_type = '{$wpml_type}' AND element_id IN ({$ids})" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- integers only.

		if ( $trid ) {
			$trids = implode( ',', array_map( 'intval', $trid ) );
			$moved = array_map( 'intval', $wpdb->get_col( "SELECT element_id FROM {$wpml_tbl} WHERE element_type = '{$wpml_type}' AND trid IN ({$trids})" ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- integers only.
			$moved = array_values( array_unique( array_merge( $matched, $moved ) ) );
		}
	}

	$moved_term_ids = array();
	foreach ( $moved as $tt_id ) {
		if ( isset( $by_tt_id[ $tt_id ] ) ) {
			$moved_term_ids[] = (int) $by_tt_id[ $tt_id ]->term_id;
		}
	}

	foreach ( $moved as $tt_id ) {
		if ( ! isset( $by_tt_id[ $tt_id ] ) ) {
			continue;
		}

		// A parent that stays in Standort can't be a parent across taxonomies.
		$parent = (int) $by_tt_id[ $tt_id ]->parent;
		$parent = in_array( $parent, $moved_term_ids, true ) ? $parent : 0;

		$wpdb->update(
			$wpdb->term_taxonomy,
			array(
				'taxonomy' => 'offene_stellen_betrieb',
				'parent'   => $parent,
			),
			array( 'term_taxonomy_id' => $tt_id )
		);

		if ( $has_wpml ) {
			$wpdb->update(
				$wpml_tbl,
				array( 'element_type' => 'tax_offene_stellen_betrieb' ),
				array(
					'element_type' => $wpml_type,
					'element_id'   => $tt_id,
				)
			);
		}
	}

	// The ACF taxonomy field keeps its own copy of the term IDs in post meta.
	if ( $moved_term_ids ) {
		$metas = $wpdb->get_results(
			"SELECT pm.meta_id, pm.post_id, pm.meta_value
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = 'offene_stellen_standort' AND p.post_type = 'offene-stellen'"
		);

		foreach ( $metas as $meta ) {
			$value = maybe_unserialize( $meta->meta_value );
			$ids   = array_map( 'intval', (array) $value );
			$kept  = array_values( array_diff( $ids, $moved_term_ids ) );

			if ( count( $kept ) === count( $ids ) ) {
				continue;
			}

			$new_value = is_array( $value ) ? array_map( 'strval', $kept ) : ( $kept ? (string) $kept[0] : '' );
			update_post_meta( (int) $meta->post_id, 'offene_stellen_standort', $new_value );
		}
	}
	// phpcs:enable WordPress.DB.DirectDatabaseQuery

	/*
	 * WPML only rereads wpml-config.xml when an admin opens Plugins, Themes or its
	 * own settings. Until it does, it doesn't know Betrieb is translatable and
	 * get_terms() hands back every language at once — the filter would list each
	 * workshop once per translation. Reload it now rather than wait for that visit.
	 */
	if ( class_exists( 'WPML_Config' ) ) {
		WPML_Config::load_config_run();
	}

	clean_term_cache( $moved_term_ids, '', true );
	clean_taxonomy_cache( 'offene_stellen_standort' );
	clean_taxonomy_cache( 'offene_stellen_betrieb' );
	wp_cache_flush();

	$names = static function ( $tt_ids ) use ( $by_tt_id ) {
		$list = array();
		foreach ( $tt_ids as $tt_id ) {
			$list[] = $by_tt_id[ $tt_id ]->name;
		}
		return $list;
	};

	update_option(
		'weizenkorn_job_betrieb_report',
		array(
			'moved'  => $names( array_intersect( $moved, array_keys( $by_tt_id ) ) ),
			'stayed' => $names( array_diff( array_keys( $by_tt_id ), $moved ) ),
		),
		false
	);
}

add_action( 'init', 'weizenkorn_migrate_job_betrieb', 20 );

/**
 * Shows the migration's result once, on the next admin page load.
 *
 * @since 1.20.0
 */
function weizenkorn_job_betrieb_migration_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$report = get_option( 'weizenkorn_job_betrieb_report' );

	if ( ! is_array( $report ) ) {
		return;
	}

	delete_option( 'weizenkorn_job_betrieb_report' );

	$none = __( '(none)', 'weizenkorn' );
	?>
	<div class="notice notice-info is-dismissible">
		<p><strong><?php esc_html_e( 'Offene Stellen: workshops moved from Standort to the new Betrieb taxonomy.', 'weizenkorn' ); ?></strong></p>
		<p><?php esc_html_e( 'Moved to Betrieb:', 'weizenkorn' ); ?> <?php echo esc_html( $report['moved'] ? implode( ', ', $report['moved'] ) : $none ); ?></p>
		<p><?php esc_html_e( 'Stayed in Standort:', 'weizenkorn' ); ?> <?php echo esc_html( $report['stayed'] ? implode( ', ', $report['stayed'] ) : $none ); ?></p>
		<p><?php esc_html_e( 'If a workshop is still listed under Standort, create it under Betrieb, tag its jobs there and delete it from Standort.', 'weizenkorn' ); ?></p>
	</div>
	<?php
}

add_action( 'admin_notices', 'weizenkorn_job_betrieb_migration_notice' );
