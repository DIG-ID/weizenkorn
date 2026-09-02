<?php
/**
 * Organization page — "Das Weizenkorn Team" filter trigger + slide-in panel
 * (Figma node 4144:6561 — the same panel as the Open Positions archive's
 * own, reused for a second, unrelated grid). Two checkbox groups, Bereiche
 * and Standorte, counted straight off the team members passed in rather
 * than a taxonomy (organization_team_items is a plain repeater, not a post
 * type — see team.php's own docblock for why).
 *
 * Markup/styling (.filter-panel__*, _components/_filter-panel.sass) and the
 * open/close mechanics (assets/js/filter-panel.js) are shared with the Open
 * Positions archive's filter (template-parts/archives/offene-stellen/job-filters.php).
 * Only the js-team-filters-* hooks below and what "Apply"/"Clear" actually
 * do (assets/js/team-filters.js, entirely client-side) are this section's
 * own.
 *
 * $args['choices'] is team.php's own bereich/standort key → label map,
 * passed in rather than duplicated here — see that file's own docblock for
 * why a 'select' field's labels have to live somewhere in code at all.
 *
 * @param array $args {
 *     @type array $items   Every team member row, as team.php itself reads
 *                          them (photo/name/bereich/standort — the last two
 *                          as raw choice keys, not labels).
 *     @type array $choices { @type array $bereich, @type array $standort }
 *                          key → label maps.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.12.0
 */

if ( empty( $args['items'] ) || empty( $args['choices'] ) ) {
	return;
}

$tf_groups_labels = array(
	'bereich'  => __( 'Bereiche', 'weizenkorn' ),
	'standort' => __( 'Standorte', 'weizenkorn' ),
);

// Counts straight off the items passed in — only a choice actually in use shows up, same
// "hide_empty" behaviour as the Open Positions archive's own get_terms() call.
$tf_groups = array();

foreach ( $tf_groups_labels as $tf_key => $tf_label ) {
	$tf_choices = $args['choices'][ $tf_key ] ?? array();
	$tf_counts  = array();

	foreach ( $args['items'] as $tf_item ) {
		$tf_value = $tf_item[ $tf_key ];

		if ( ! $tf_value ) {
			continue;
		}

		$tf_counts[ $tf_value ] = ( $tf_counts[ $tf_value ] ?? 0 ) + 1;
	}

	if ( ! $tf_counts ) {
		continue;
	}

	$tf_terms = array();

	foreach ( $tf_counts as $tf_slug => $tf_count ) {
		$tf_terms[] = array(
			'slug'  => $tf_slug,
			'name'  => $tf_choices[ $tf_slug ] ?? $tf_slug,
			'count' => $tf_count,
		);
	}

	$tf_groups[ $tf_key ] = array(
		'label' => $tf_label,
		'terms' => $tf_terms,
	);
}
?>
<button type="button" class="filter-panel__trigger js-filter-panel-trigger js-team-filters-trigger inline-flex items-center gap-3 text-brand-dark" aria-haspopup="dialog" aria-expanded="false" aria-controls="team-filters-panel">
	<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'filter' ); ?></span>
	<?php esc_html_e( 'Filter', 'weizenkorn' ); ?>
	<span class="filter-panel__badge js-filter-panel-badge" hidden></span>
</button>

<div class="filter-panel__backdrop js-filter-panel-backdrop" hidden></div>

<aside id="team-filters-panel" class="filter-panel__panel js-filter-panel" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'Filter', 'weizenkorn' ); ?>">
	<div class="filter-panel__head">
		<button type="button" class="filter-panel__close js-filter-panel-close inline-flex items-center gap-2 text-brand-red">
			<span class="-scale-x-100 shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
			<?php esc_html_e( 'Close Filters', 'weizenkorn' ); ?>
		</button>
	</div>

	<div class="filter-panel__body">
		<?php foreach ( $tf_groups as $tf_key => $tf_group ) : ?>
			<fieldset class="filter-panel__group">
				<legend class="filter-panel__group-title label-overline text-brand-red"><?php echo esc_html( $tf_group['label'] ); ?></legend>
				<ul class="filter-panel__list">
					<?php foreach ( $tf_group['terms'] as $tf_term ) : ?>
						<li class="filter-panel__item">
							<label class="filter-panel__checkbox">
								<input type="checkbox" class="filter-panel__input" data-filter="<?php echo esc_attr( $tf_key ); ?>" value="<?php echo esc_attr( $tf_term['slug'] ); ?>">
								<span class="filter-panel__box" aria-hidden="true"></span>
								<span class="filter-panel__label"><?php echo esc_html( $tf_term['name'] ); ?></span>
								<span class="filter-panel__count"><?php echo esc_html( $tf_term['count'] ); ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endforeach; ?>
	</div>

	<div class="filter-panel__foot">
		<button type="button" class="js-filter-panel-clear js-team-filters-clear btn btn-secondary">
			<?php esc_html_e( 'Filter zurücksetzen', 'weizenkorn' ); ?>
		</button>
		<button type="button" class="js-filter-panel-apply js-team-filters-apply btn btn-primary">
			<?php esc_html_e( 'Apply Filters', 'weizenkorn' ); ?>
		</button>
	</div>
</aside>
