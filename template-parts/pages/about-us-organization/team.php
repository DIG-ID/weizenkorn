<?php
/**
 * Organization page — "Das Weizenkorn Team" section (Figma desktop node
 * 4065:6258, filter panel node 4144:6561). A title, a results count with
 * the filter trigger beside it, a grid of the first 12 team members
 * (alphabetical by name) — four across at desktop, three from lg to xl, two
 * from md to lg, one at mobile — and a "Mehr Laden" button.
 *
 * The lg step exists because this grid stays a flat 6 columns the whole
 * md-to-xl range (.theme-grid's own breakpoints, not this section's) — 2
 * per row (md:col-span-3) reads too narrow on a wider tablet/small laptop,
 * so lg:col-span-2 packs 3 per row instead, still evenly, before xl's own
 * 12-column grid takes over at 4 per row.
 *
 * Not a post type: organization_team_items is a plain ACF repeater on this
 * page (see acf-exports/acf-organization-fields.json), so there's no
 * WP_Query/tax_query to filter or page against the way the Open Positions
 * archive does (inc/rest-job-filters.php). A client's own team is a small,
 * bounded, admin-managed list rather than an open-ended, publicly growing
 * one — exactly the case where filtering/paginating in the browser against
 * everything already rendered is the right trade-off, not the wrong one
 * (see inc/rest-job-filters.php's own docblock for why that call went the
 * other way for 100+ job postings).
 *
 * bereich/standort are ACF 'select' fields shown on each card (a role like
 * "Bäcker/in", a workplace like "DasBreiteHotel"). bereich_filter/
 * standort_filter are two more 'select' fields, admin-only, that the filter
 * panel reads instead — the client wanted the filter's own grouping to be
 * able to differ from what a card shows, so display and filter come from
 * separate fields rather than one field doing both jobs.
 *
 * None of the four are hardcoded anywhere: a 'select' field only ever
 * returns its raw choice key via get_sub_field(), never the label, so
 * every key → label map below (for this file's own card labels and, via
 * $tm_filter_choices, team-filters.php's checkbox labels) reads
 * get_sub_field_object()'s own 'choices' array instead, fetched once off
 * the first row — the field's choice list is the same on every row, so
 * there is nothing to gain re-reading it per row, only per page load.
 * Changing, reordering or renaming a choice is then a wp-admin-only edit
 * (Custom Fields → the field's own Choices setting); nothing here keeps a
 * second copy that could fall out of sync with it.
 *
 * assets/js/team-filters.js does the filtering and "Mehr Laden": every
 * card is rendered here upfront with its own data-bereich/data-standort
 * attributes — bereich_filter/standort_filter's raw values, not bereich/
 * standort's, and not the labels either — cards past the twelfth start
 * hidden, and the JS shows/hides by matching those attributes against the
 * checked filters, then reveals more of the matching set on "Mehr Laden"
 * — never a second request. It also runs that same reveal logic once on
 * its own init, rather than trusting this file's own initial hidden/
 * data-team-extra markup to already match — so the two can never quietly
 * disagree.
 *
 * Unlike job-listing.php's own .job-listing__grid (fixed pixel card widths,
 * because 3 equal 12-column tracks don't divide evenly to its own card
 * width), this grid uses real theme-grid columns: 12/4 = 3 columns per
 * card at desktop divides exactly, so a plain xl:col-span-3 always packs
 * 4 across regardless of the actual viewport width — a fixed pixel width
 * only fits 4 across on a wide enough one, dropping to 3 on a narrower
 * desktop instead of the 4 the grid itself would still have room for. Also
 * unlike most sections, both this grid and its results/filter bar span the
 * full 12 columns rather than the usual col-start-2/col-span-10 inset —
 * confirmed against Figma, where this section alone runs edge to edge.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.12.0
 */

$tm_visible_count = 12;

$tm_title = get_field( 'organization_team_title' );

if ( ! $tm_title || ! have_rows( 'organization_team_items' ) ) {
	return;
}

// Read every row once, up front: this file needs the full list to render the grid,
// team-filters.php needs it again to build the filter groups' own counts — reading it
// twice would leave the second read racing have_rows()'s own internal row pointer.
//
// $tm_choices/$tm_filter_choices are read off the first row's own field objects (see this
// file's own docblock for why) rather than kept as a literal array here.
$tm_choices        = array();
$tm_filter_choices = array();
$tm_items          = array();

while ( have_rows( 'organization_team_items' ) ) {
	the_row();

	if ( ! get_sub_field( 'name' ) ) {
		continue;
	}

	if ( ! $tm_choices ) {
		$tm_choices = array(
			'bereich'  => get_sub_field_object( 'bereich' )['choices'] ?? array(),
			'standort' => get_sub_field_object( 'standort' )['choices'] ?? array(),
		);

		$tm_filter_choices = array(
			'bereich'  => get_sub_field_object( 'bereich_filter' )['choices'] ?? array(),
			'standort' => get_sub_field_object( 'standort_filter' )['choices'] ?? array(),
		);
	}

	$tm_items[] = array(
		'photo'           => get_sub_field( 'photo' ),
		'name'            => get_sub_field( 'name' ),
		'bereich'         => get_sub_field( 'bereich' ),
		'standort'        => get_sub_field( 'standort' ),
		'bereich_filter'  => get_sub_field( 'bereich_filter' ),
		'standort_filter' => get_sub_field( 'standort_filter' ),
	);
}

if ( ! $tm_items ) {
	return;
}

/*
 * Alphabetical by name, not repeater row order — Collator does proper locale
 * collation (ä sorts next to a, not after z, as a byte-order strcmp() would),
 * falling back to strcasecmp() on a build without the intl extension. "Mehr
 * Laden" and the filter (assets/js/team-filters.js) both just show/hide
 * these already-rendered cards, so sorting here is also what decides which
 * 12 show first and the order "Mehr Laden" reveals the rest in.
 */
$tm_collator = class_exists( 'Collator' ) ? new Collator( get_locale() ) : null;

usort(
	$tm_items,
	static function ( $a, $b ) use ( $tm_collator ) {
		return $tm_collator
			? $tm_collator->compare( $a['name'], $b['name'] )
			: strcasecmp( $a['name'], $b['name'] );
	}
);
?>
<section class="team mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php get_template_part( 'template-parts/components/section-heading', null, array( 'title' => $tm_title ) ); ?>

		<div class="theme-grid mt-8 xl:mt-12">
			<div class="team__bar col-span-2 md:col-span-6 xl:col-span-12 flex items-center justify-between">
				<p class="team__count js-team-count body-text text-brand-dark">
					<?php
					printf(
						/* translators: %d: number of team members. */
						esc_html( _n( '%d Resultat', '%d Resultate', count( $tm_items ), 'weizenkorn' ) ),
						count( $tm_items )
					);
					?>
				</p>
				<?php
				get_template_part(
					'template-parts/pages/about-us-organization/team-filters',
					null,
					array(
						'items'   => $tm_items,
						'choices' => $tm_filter_choices,
					)
				);
				?>
			</div>
		</div>

		<div class="theme-grid mt-8 xl:mt-12">
			<div class="team__grid js-team-grid theme-grid col-span-2 md:col-span-6 xl:col-span-12 gap-y-8">
				<?php foreach ( $tm_items as $tm_index => $tm_item ) : ?>
					<?php $tm_is_extra = ( $tm_index >= $tm_visible_count ); ?>
					<?php
					/*
					 * hidden as a plain attribute here, never baked into class="" as the Tailwind
					 * `hidden` utility — the JS toggles visibility via the `hidden` PROPERTY
					 * (card.hidden = …), which only ever touches the ATTRIBUTE. A class in the
					 * markup would never get removed by that and the card could never be revealed
					 * again once "Mehr Laden"/a filter tried to.
					 */
					?>
					<div
						class="col-span-2 md:col-span-3 lg:col-span-2 xl:col-span-3"
						data-team-card
						data-bereich="<?php echo esc_attr( $tm_item['bereich_filter'] ); ?>"
						data-standort="<?php echo esc_attr( $tm_item['standort_filter'] ); ?>"
						<?php echo $tm_is_extra ? ' hidden data-team-extra' : ''; ?>
					>
						<?php
						get_template_part(
							'template-parts/components/card-org-team',
							null,
							array(
								'photo'    => $tm_item['photo'],
								'name'     => $tm_item['name'],
								'bereich'  => $tm_choices['bereich'][ $tm_item['bereich'] ] ?? '',
								'standort' => $tm_choices['standort'][ $tm_item['standort'] ] ?? '',
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( count( $tm_items ) > $tm_visible_count ) : ?>
			<div class="team__more js-team-more theme-grid mt-12 xl:mt-16">
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
