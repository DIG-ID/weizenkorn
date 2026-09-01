<?php
/**
 * Open Positions single post — header (Figma desktop node 4450:6539).
 * Hero image full width, a "Zurück zur Übersicht" link back to the
 * archive, the publish date and a rule, the title, three meta badges
 * (employment, start date, location), and the body copy.
 *
 * The title runs the full inset (10 of 12 columns) while everything below
 * it — the badges, the body copy — keeps to a narrower reading column (7),
 * confirmed against the desktop frame's own measurements. No tablet/mobile
 * frames exist for this template — both stack to the full width there,
 * the common-sense read of a desktop-only design.
 *
 * The image and body are their own ACF fields rather than the native
 * featured image / post content: the client wants the featured image free
 * for something else, and the body in a WYSIWYG they control directly
 * rather than the block editor. The publish date is still the post's own
 * (get_the_date()), and the title get_the_title() — WordPress already
 * carries both. The date's format comes from weizenkorn_get_date_format()
 * (inc/theme-template-tags.php), which reads the day/month/year order for
 * the current WPML language — German by default ("27. August 2026"),
 * ready for English/French once those languages are added, with no
 * further work needed here.
 *
 * ACF fields (flat, unprefixed — this post type's own fields, not a
 * shared module's):
 *   offene_stellen_hero_image  (image → ID) omit to hide it
 *   offene_stellen_employment  (text) e.g. "30 - 100 % Anstellung"
 *   offene_stellen_start_date  (text) e.g. "Ab 01. Februar 2026"
 *   offene_stellen_standort    (taxonomy → term(s)) e.g. "Schreinerei" — the
 *                              client's own workshop, also the archive's own
 *                              "Standort" filter group (inc/theme-setup.php).
 *   offene_stellen_body        (wysiwyg)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

$jh_image      = get_field( 'offene_stellen_hero_image' );
$jh_employment = get_field( 'offene_stellen_employment' );
$jh_start_date = get_field( 'offene_stellen_start_date' );
$jh_location   = weizenkorn_get_post_term_names( get_the_ID(), 'offene_stellen_standort' );
$jh_body       = get_field( 'offene_stellen_body' );
?>
<article class="job-header">
	<?php if ( $jh_image ) : ?>
		<div class="job-header__media h-[240px] md:h-[360px] xl:h-[519px] overflow-hidden">
			<?php
			echo wp_get_attachment_image(
				$jh_image,
				'full',
				false,
				array(
					'class'         => 'w-full h-full object-cover',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="theme-container">
		<div class="theme-grid mt-8 xl:mt-12">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'offene-stellen' ) ); ?>" class="job-header__back btn btn-secondary justify-self-start col-span-2 xl:col-start-2 xl:col-span-5">
				<span class="btn__icon -scale-x-100" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
				<?php esc_html_e( 'Zurück zur Übersicht', 'weizenkorn' ); ?>
			</a>
		</div>

		<div class="job-header__meta theme-grid mt-8 xl:mt-16 pb-4 border-b border-brand-red">
			<time class="job-header__date text-brand-red col-span-2 xl:col-start-2 xl:col-span-7" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date( weizenkorn_get_date_format() ) ); ?>
			</time>
		</div>

		<div class="theme-grid mt-8 xl:mt-12">
			<h1 class="job-header__title title-main text-brand-dark col-span-2 xl:col-start-2 xl:col-span-10">
				<?php the_title(); ?>
			</h1>
		</div>

		<?php if ( $jh_employment || $jh_start_date || $jh_location ) : ?>
			<div class="theme-grid mt-8">
				<div class="job-header__badges col-span-2 xl:col-start-2 xl:col-span-7 flex flex-wrap gap-4">
					<?php foreach ( array( $jh_employment, $jh_start_date, $jh_location ) as $jh_badge ) : ?>
						<?php if ( $jh_badge ) : ?>
							<span class="job-header__badge border border-brand-red text-brand-red font-primary font-bold text-[12px] xl:text-[14px] tracking-[0.5px] px-4 py-3">
								<?php echo esc_html( $jh_badge ); ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $jh_body ) : ?>
			<div class="theme-grid mt-8">
				<div class="job-header__body body-text text-brand-dark col-span-2 xl:col-start-2 xl:col-span-7">
					<?php echo wp_kses_post( $jh_body ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</article>
