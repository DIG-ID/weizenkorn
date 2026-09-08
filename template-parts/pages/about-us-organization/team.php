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
 * bereich/standort are ACF 'select' fields with a fixed choice list — the
 * client's real values turned out to already be exactly what each card's
 * second/third line shows (a role like "Bäcker/in", a workplace like
 * "DasBreiteHotel"), not a broader grouping layered on top, so one field
 * each does double duty as both display and filter criteria rather than
 * a separate free-text field plus a separate filter field. $tm_choices is
 * the single source for both this file's own card labels and
 * team-filters.php's checkbox labels — a 'select' field only ever returns
 * its raw choice key via get_sub_field(), never the label, so something
 * has to map key → label either way; keeping that map in exactly one place
 * (passed to team-filters.php as $args) is simpler than asking ACF for the
 * field object twice.
 *
 * assets/js/team-filters.js does the filtering and "Mehr Laden": every
 * card is rendered here upfront with its own data-bereich/data-standort
 * attributes (the raw slugs, not the labels — what the filter checkboxes'
 * own values are), cards past the twelfth start hidden, and the JS shows/
 * hides by matching those attributes against the checked filters, then
 * reveals more of the matching set on "Mehr Laden" — never a second
 * request. It also runs that same reveal logic once on its own init,
 * rather than trusting this file's own initial hidden/data-team-extra
 * markup to already match — so the two can never quietly disagree.
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

// Mirrors organization_team_items' own bereich/standort choices exactly
// (acf-exports/acf-organization-fields.json) — see this file's own docblock
// for why the map lives here in code rather than being read off the field.
$tm_choices = array(
	'bereich'  => array(
		'abteilungsleiter-in-hauswirtschaft'       => __( 'Abteilungsleiter/in Hauswirtschaft', 'weizenkorn' ),
		'abteilungsleiter-in-holzwerkstatt'        => __( 'Abteilungsleiter/in Holzwerkstatt', 'weizenkorn' ),
		'abteilungsleiter-in-endfertigung'         => __( 'Abteilungsleiter/in Endfertigung', 'weizenkorn' ),
		'abteilungsleiter-in-kerzen'               => __( 'Abteilungsleiter/in Kerzen', 'weizenkorn' ),
		'abteilungsleiter-in-sozialdienst'         => __( 'Abteilungsleiter/in Sozialdienst', 'weizenkorn' ),
		'back-office-manager-dbh'                  => __( 'Back Office Manager DBH', 'weizenkorn' ),
		'bereichsleiter-in'                        => __( 'Bereichsleiter/in', 'weizenkorn' ),
		'baecker-in'                               => __( 'Bäcker/in', 'weizenkorn' ),
		'fachmitarbeiter-in-finanzen'              => __( 'Fachmitarbeiter/in Finanzen', 'weizenkorn' ),
		'fachmitarbeiter-in-hotelkommunikation'    => __( 'Fachmitarbeiter/in Hotelkommunikation', 'weizenkorn' ),
		'fachmitarbeiter-in-kerzen'                => __( 'Fachmitarbeiter/in Kerzen', 'weizenkorn' ),
		'fachmitarbeiter-in-logistik-und-messebau' => __( 'Fachmitarbeiter/in Logistik und Messebau', 'weizenkorn' ),
		'fachmitarbeiter-in-sozialdienst'          => __( 'Fachmitarbeiter/in Sozialdienst', 'weizenkorn' ),
		'fachmitarbeiter-in-verkauf-vertrieb'      => __( 'Fachmitarbeiter/in Verkauf/Vertrieb', 'weizenkorn' ),
		'geschaeftsfuehrer-in'                     => __( 'Geschäftsführer/in', 'weizenkorn' ),
		'gruppenleiter-in-empfang'                 => __( 'Gruppenleiter/in Empfang', 'weizenkorn' ),
		'gruppenleiter-in-endfertigung'            => __( 'Gruppenleiter/in Endfertigung', 'weizenkorn' ),
		'gruppenleiter-in-hauswart'                => __( 'Gruppenleiter/in Hauswart', 'weizenkorn' ),
		'gruppenleiter-in-hauswirtschaft'          => __( 'Gruppenleiter/in Hauswirtschaft', 'weizenkorn' ),
		'gruppenleiter-in-holzwerkstatt'           => __( 'Gruppenleiter/in Holzwerkstatt', 'weizenkorn' ),
		'gruppenleiter-in-host'                    => __( 'Gruppenleiter/in Host', 'weizenkorn' ),
		'gruppenleiter-in-kerzen'                  => __( 'Gruppenleiter/in Kerzen', 'weizenkorn' ),
		'gruppenleiter-in-kreativatelier'          => __( 'Gruppenleiter/in Kreativatelier', 'weizenkorn' ),
		'gruppenleiter-in-kueche'                  => __( 'Gruppenleiter/in Küche', 'weizenkorn' ),
		'gruppenleiter-in-service'                 => __( 'Gruppenleiter/in Service', 'weizenkorn' ),
		'gruppenleiter-in-verkauf'                 => __( 'Gruppenleiter/in Verkauf', 'weizenkorn' ),
		'hilfsmitarbeiter-in-baeckerei'            => __( 'Hilfsmitarbeiter/in Bäckerei', 'weizenkorn' ),
		'kundenberater-in-aussendienst'            => __( 'Kundenberater/in Aussendienst', 'weizenkorn' ),
		'lehrer-in-fachmitarbeiter-in'             => __( 'Lehrer/in (Fachmitarbeiter/in)', 'weizenkorn' ),
		'leiter-in-baeckerei-und-verkauf'          => __( 'Leiter/in Bäckerei und Verkauf', 'weizenkorn' ),
		'leiter-in-finanzen'                       => __( 'Leiter/in Finanzen', 'weizenkorn' ),
		'leiter-in-kreativatelier'                 => __( 'Leiter/in Kreativatelier', 'weizenkorn' ),
		'leiter-in-personal'                       => __( 'Leiter/in Personal', 'weizenkorn' ),
		'leiter-in-restaurant-cantina'             => __( 'Leiter/in Restaurant Cantina', 'weizenkorn' ),
		'leiterin-kommunikation-pr-marketing'      => __( 'Leiterin Kommunikation/PR/Marketing', 'weizenkorn' ),
		'lernende-r'                               => __( 'Lernende/r', 'weizenkorn' ),
		'praktikant-in'                            => __( 'Praktikant/in', 'weizenkorn' ),
		'schreiner-in'                             => __( 'Schreiner/in', 'weizenkorn' ),
		'teamleiter-in-kerzen'                     => __( 'Teamleiter/in Kerzen', 'weizenkorn' ),
		'teamleiter-in-kueche'                     => __( 'Teamleiter/in Küche', 'weizenkorn' ),
		'teamleiter-in-logistik'                   => __( 'Teamleiter/in Logistik', 'weizenkorn' ),
		'verkaeufer-in-baeckerei'                  => __( 'Verkäufer/in Bäckerei', 'weizenkorn' ),
	),
	'standort' => array(
		'baeckerei-augst'                    => __( 'Bäckerei Augst', 'weizenkorn' ),
		'baeckerei-dreispitz-bachspitz'      => __( 'Bäckerei Dreispitz (Bachspitz)', 'weizenkorn' ),
		'baeckerei-erasmusplatz-bachegge'    => __( 'Bäckerei Erasmusplatz (Bachegge)', 'weizenkorn' ),
		'baeckerei-produktion-bachstube'     => __( 'Bäckerei Produktion (Bachstube)', 'weizenkorn' ),
		'cantina-e9'                         => __( 'Cantina E9', 'weizenkorn' ),
		'dasbreitehotel'                     => __( 'DasBreiteHotel', 'weizenkorn' ),
		'empfang'                            => __( 'Empfang', 'weizenkorn' ),
		'finanzen'                           => __( 'Finanzen', 'weizenkorn' ),
		'geschaeftsfuehrer'                  => __( 'Geschäftsführer', 'weizenkorn' ),
		'hr'                                 => __( 'HR', 'weizenkorn' ),
		'hausdienst'                         => __( 'Hausdienst', 'weizenkorn' ),
		'hausdienst-ve37'                    => __( 'Hausdienst Ve37', 'weizenkorn' ),
		'holzmanufaktur'                     => __( 'Holzmanufaktur', 'weizenkorn' ),
		'kerzenwerkstatt'                    => __( 'Kerzenwerkstatt', 'weizenkorn' ),
		'kommunikation-pr-marketing'         => __( 'Kommunikation/PR/Marketing', 'weizenkorn' ),
		'kreativatelier'                     => __( 'Kreativatelier', 'weizenkorn' ),
		'restaurant-dbh'                     => __( 'Restaurant DBH', 'weizenkorn' ),
		'schreinerei-innenausbau-und-moebel' => __( 'Schreinerei Innenausbau und Möbel', 'weizenkorn' ),
		'sozialdienst'                       => __( 'Sozialdienst', 'weizenkorn' ),
		'verkauf'                            => __( 'Verkauf', 'weizenkorn' ),
	),
);

$tm_title = get_field( 'organization_team_title' );

if ( ! $tm_title || ! have_rows( 'organization_team_items' ) ) {
	return;
}

// Read every row once, up front: this file needs the full list to render the grid,
// team-filters.php needs it again to build the filter groups' own counts — reading it
// twice would leave the second read racing have_rows()'s own internal row pointer.
$tm_items = array();

while ( have_rows( 'organization_team_items' ) ) {
	the_row();

	if ( ! get_sub_field( 'name' ) ) {
		continue;
	}

	$tm_items[] = array(
		'photo'    => get_sub_field( 'photo' ),
		'name'     => get_sub_field( 'name' ),
		'bereich'  => get_sub_field( 'bereich' ),
		'standort' => get_sub_field( 'standort' ),
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
						'choices' => $tm_choices,
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
						data-bereich="<?php echo esc_attr( $tm_item['bereich'] ); ?>"
						data-standort="<?php echo esc_attr( $tm_item['standort'] ); ?>"
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
