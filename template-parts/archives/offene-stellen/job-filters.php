<?php
/**
 * Open Positions archive — filter trigger + slide-in panel (Figma node
 * 4129:5548). Two checkbox groups built straight from the archive's own
 * taxonomies (Anstellungsart, Standort — both registered in
 * inc/theme-setup.php), each term's post count read off get_terms(), and
 * an Apply/Clear footer.
 *
 * Markup/styling (.filter-panel__*, _components/_filter-panel.sass) and the
 * open/close mechanics (assets/js/filter-panel.js) are shared with Das
 * Weizenkorn Team's own filter (template-parts/pages/about-us-organization/team-filters.php)
 * — the same Figma panel reused for a second, unrelated grid. Only the
 * js-job-filters-* hooks below and what "Apply"/"Clear" actually do are this
 * section's own.
 *
 * Called from job-listing.php's own results-count row; this file renders
 * the trigger button plus the panel it opens, nothing else. All the actual
 * filtering and "Mehr Laden" pagination happen client-side in
 * assets/js/job-filters.js, which GETs weizenkorn/v1/jobs
 * (inc/rest-job-filters.php) and swaps job-listing.php's grid — this file
 * only renders the checkboxes' initial (unchecked) state and their counts.
 * Those counts are the taxonomy's own unfiltered totals (e.g. Figma's own
 * "Schreinerei 31"), not recalculated live as other filters are picked —
 * that finer behaviour isn't in this Figma frame.
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.11.0
 */

$jf_groups = array(
	'anstellungsart' => array(
		'label' => __( 'Anstellungsart', 'weizenkorn' ),
		'terms' => get_terms(
			array(
				'taxonomy'   => 'offene_stellen_anstellungsart',
				'hide_empty' => true,
			)
		),
	),
	'standort'       => array(
		'label' => __( 'Standort', 'weizenkorn' ),
		'terms' => get_terms(
			array(
				'taxonomy'   => 'offene_stellen_standort',
				'hide_empty' => true,
			)
		),
	),
);
?>
<button type="button" class="filter-panel__trigger js-filter-panel-trigger js-job-filters-trigger inline-flex items-center gap-3 text-brand-dark" aria-haspopup="dialog" aria-expanded="false" aria-controls="job-filters-panel">
	<?php esc_html_e( 'Filter', 'weizenkorn' ); ?>
	<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'filter' ); ?></span>
	<span class="filter-panel__badge js-filter-panel-badge" hidden></span>
</button>

<div class="filter-panel__backdrop js-filter-panel-backdrop" hidden></div>

<aside id="job-filters-panel" class="filter-panel__panel js-filter-panel" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'Filter', 'weizenkorn' ); ?>">
	<div class="filter-panel__head">
		<button type="button" class="filter-panel__close js-filter-panel-close inline-flex items-center gap-2 text-brand-red">
			<span class="-scale-x-100 shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
			<?php esc_html_e( 'Filter schliessen', 'weizenkorn' ); ?>
		</button>
	</div>

	<div class="filter-panel__body">
		<?php foreach ( $jf_groups as $jf_key => $jf_group ) : ?>
			<?php if ( empty( $jf_group['terms'] ) || is_wp_error( $jf_group['terms'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<fieldset class="filter-panel__group">
				<legend class="filter-panel__group-title label-overline text-brand-red"><?php echo esc_html( $jf_group['label'] ); ?></legend>
				<ul class="filter-panel__list">
					<?php foreach ( $jf_group['terms'] as $jf_term ) : ?>
						<li class="filter-panel__item">
							<label class="filter-panel__checkbox">
								<input type="checkbox" class="filter-panel__input" data-filter="<?php echo esc_attr( $jf_key ); ?>" value="<?php echo esc_attr( $jf_term->slug ); ?>">
								<span class="filter-panel__box" aria-hidden="true"></span>
								<span class="filter-panel__label"><?php echo esc_html( $jf_term->name ); ?></span>
								<span class="filter-panel__count"><?php echo esc_html( $jf_term->count ); ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endforeach; ?>
	</div>

	<div class="filter-panel__foot">
		<button type="button" class="js-filter-panel-clear js-job-filters-clear btn btn-secondary">
			<?php esc_html_e( 'Filter zurücksetzen', 'weizenkorn' ); ?>
		</button>
		<button type="button" class="js-filter-panel-apply js-job-filters-apply btn btn-primary">
			<?php esc_html_e( 'Apply Filters', 'weizenkorn' ); ?>
		</button>
	</div>
</aside>
