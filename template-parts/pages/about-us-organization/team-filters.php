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
 * $args['choices'] is team.php's own bereich_filter/standort_filter key →
 * label map (read live off the fields' own 'choices', not bereich/standort's
 * — see that file's own docblock for why display and filter are separate
 * fields), passed in rather than re-read here.
 *
 * @param array $args {
 *     @type array $items   Every team member row, as team.php itself reads
 *                          them — bereich_filter/standort_filter (below) are
 *                          the ones this file counts and filters by; the
 *                          bereich/standort also present are that same row's
 *                          display fields and are of no interest here.
 *     @type array $choices { @type array $bereich, @type array $standort }
 *                          key → label maps — despite the array keys, these
 *                          are bereich_filter's/standort_filter's own
 *                          choices, not bereich's/standort's.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.12.0
 */

if ( empty( $args['items'] ) || empty( $args['choices'] ) ) {
	return;
}

// item_key: the row key each group actually counts/filters by — bereich_filter/
// standort_filter, not the display bereich/standort also present on the same item.
$tf_groups_config = array(
	'bereich'  => array(
		'label'    => __( 'Bereiche', 'weizenkorn' ),
		'item_key' => 'bereich_filter',
	),
	'standort' => array(
		'label'    => __( 'Standorte', 'weizenkorn' ),
		'item_key' => 'standort_filter',
	),
);

// Counts straight off the items passed in — only a choice actually in use shows up, same
// "hide_empty" behaviour as the Open Positions archive's own get_terms() call.
$tf_groups = array();

foreach ( $tf_groups_config as $tf_key => $tf_config ) {
	$tf_choices = $args['choices'][ $tf_key ] ?? array();
	$tf_counts  = array();

	foreach ( $args['items'] as $tf_item ) {
		$tf_value = $tf_item[ $tf_config['item_key'] ];

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
		'label' => $tf_config['label'],
		'terms' => $tf_terms,
	);
}
?>
<button type="button" class="filter-panel__trigger js-filter-panel-trigger js-team-filters-trigger inline-flex items-center gap-3 text-brand-dark" aria-haspopup="dialog" aria-expanded="false" aria-controls="team-filters-panel">
	<?php esc_html_e( 'Filter', 'weizenkorn' ); ?>
	<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'filter' ); ?></span>
	<?php // Active-filter count dot — not in Figma, client's own call. Kept commented rather than removed: assets/js/filter-panel.js's updateBadge() already no-ops safely with the markup gone, so re-enabling this is just uncommenting the span. ?>
	<?php /* <span class="filter-panel__badge js-filter-panel-badge" hidden></span> */ ?>
</button>

<div class="filter-panel__backdrop js-filter-panel-backdrop" hidden></div>

<aside id="team-filters-panel" class="filter-panel__panel js-filter-panel" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'Filter', 'weizenkorn' ); ?>">
	<div class="filter-panel__head">
		<button type="button" class="filter-panel__close js-filter-panel-close inline-flex items-center gap-4 text-brand-red">
			<span class="-scale-x-100 shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
			<?php esc_html_e( 'Filter schliessen', 'weizenkorn' ); ?>
		</button>
	</div>

	<div class="filter-panel__body">
		<?php
		/*
		 * The scrollable box itself (_components/_filter-panel.sass's own __groups) —
		 * both groups scroll together in here, inset from this section's own close
		 * button and the Apply/Clear footer by __body's padding, which stays put
		 * regardless of scroll position since it lives outside this element.
		 *
		 * data-lenis-prevent: assets/js/gsap.js runs Lenis globally, which hijacks the
		 * page's own wheel scroll for its smooth-scroll effect — without this attribute
		 * (Lenis' own documented escape hatch, its CSS already in _components/_lenis.sass)
		 * it swallows wheel events over this box too, and the mouse wheel stops doing
		 * anything here at all rather than scrolling it natively.
		 */
		?>
		<div class="filter-panel__groups" data-lenis-prevent>
			<?php foreach ( $tf_groups as $tf_key => $tf_group ) : ?>
				<fieldset class="filter-panel__group">
					<legend class="filter-panel__group-title label-overline text-brand-red"><?php echo esc_html( $tf_group['label'] ); ?></legend>
					<hr class="filter-panel__group-line" aria-hidden="true">
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
	</div>

	<div class="filter-panel__foot">
		<button type="button" class="js-filter-panel-clear js-team-filters-clear btn btn-secondary">
			<?php esc_html_e( 'Filter zurücksetzen', 'weizenkorn' ); ?>
		</button>
		<button type="button" class="js-filter-panel-apply js-team-filters-apply btn btn-primary">
			<?php esc_html_e( 'Filter anwenden', 'weizenkorn' ); ?>
		</button>
	</div>
</aside>
