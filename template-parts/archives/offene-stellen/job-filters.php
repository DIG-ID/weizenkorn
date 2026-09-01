<?php
/**
 * Open Positions archive — filter trigger + slide-in panel (Figma node
 * 4129:5548). Two checkbox groups built straight from the archive's own
 * taxonomies (Anstellungsart, Standort — both registered in
 * inc/theme-setup.php), each term's post count read off get_terms(), and
 * an Apply/Clear footer.
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
 * Panel width: full-screen below `md` (mobile), a fixed width from `md` up
 * (tablet and desktop share it) — the client's own spec for this panel,
 * not a per-breakpoint Figma measurement (only a desktop frame exists).
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
<button type="button" class="job-filters__trigger js-job-filters-trigger inline-flex items-center gap-3 text-brand-dark" aria-haspopup="dialog" aria-expanded="false" aria-controls="job-filters-panel">
	<span class="shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'filter' ); ?></span>
	<?php esc_html_e( 'Filter', 'weizenkorn' ); ?>
	<span class="job-filters__badge js-job-filters-badge" hidden></span>
</button>

<div class="job-filters__backdrop js-job-filters-backdrop" hidden></div>

<aside id="job-filters-panel" class="job-filters__panel js-job-filters-panel" role="dialog" aria-modal="true" aria-hidden="true" aria-label="<?php esc_attr_e( 'Filter', 'weizenkorn' ); ?>">
	<div class="job-filters__head">
		<button type="button" class="job-filters__close js-job-filters-close inline-flex items-center gap-2 text-brand-red">
			<span class="job-filters__close-icon -scale-x-100 shrink-0" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
			<?php esc_html_e( 'Close Filters', 'weizenkorn' ); ?>
		</button>
	</div>

	<div class="job-filters__body">
		<?php foreach ( $jf_groups as $jf_key => $jf_group ) : ?>
			<?php if ( empty( $jf_group['terms'] ) || is_wp_error( $jf_group['terms'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<fieldset class="job-filters__group">
				<legend class="job-filters__group-title label-overline text-brand-red"><?php echo esc_html( $jf_group['label'] ); ?></legend>
				<ul class="job-filters__list">
					<?php foreach ( $jf_group['terms'] as $jf_term ) : ?>
						<li class="job-filters__item">
							<label class="job-filters__checkbox">
								<input type="checkbox" class="job-filters__input" data-filter="<?php echo esc_attr( $jf_key ); ?>" value="<?php echo esc_attr( $jf_term->slug ); ?>">
								<span class="job-filters__box" aria-hidden="true"></span>
								<span class="job-filters__label"><?php echo esc_html( $jf_term->name ); ?></span>
								<span class="job-filters__count"><?php echo esc_html( $jf_term->count ); ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endforeach; ?>
	</div>

	<div class="job-filters__foot">
		<button type="button" class="job-filters__clear js-job-filters-clear btn btn-secondary">
			<?php esc_html_e( 'Filter zurücksetzen', 'weizenkorn' ); ?>
		</button>
		<button type="button" class="job-filters__apply js-job-filters-apply btn btn-primary">
			<?php esc_html_e( 'Apply Filters', 'weizenkorn' ); ?>
		</button>
	</div>
</aside>
