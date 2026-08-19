<?php
/**
 * Page hero (detail level) — shared hero module for "2nd level" interior
 * pages, e.g. the Schreinerei service detail pages (Figma "Innenausbau_desktop",
 * "hero-2sd-level-desktop" / "page-hero-tablet" / "page-hero-tablet" mobile).
 * Mobile/tablet are structurally identical to template-parts/modules/
 * hero-section.php (image on top, bordered title+text panel below); desktop
 * diverges into a side-by-side panel+image row instead, so both layouts are
 * rendered and toggled with responsive visibility rather than forced into one
 * fluid structure. Kept as its own module/fields (not a hero-section variant)
 * since the desktop layout genuinely differs, not just its content.
 *
 * ACF fields (flat, prefixed):
 *   page_hero_detail_image           (image → return ID)
 *   page_hero_detail_title           (text)
 *   page_hero_detail_text            (textarea / wpautop)
 *   page_hero_detail_separator_logo  (image → return ID)
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.4.0
 */

$page_hero_detail_image = get_field( 'page_hero_detail_image' );

if ( ! $page_hero_detail_image ) {
	return;
}

$page_hero_detail_title = get_field( 'page_hero_detail_title' );
$page_hero_detail_text  = get_field( 'page_hero_detail_text' );
?>
<section class="section-page-hero-detail">
	<div class="theme-container">

		<?php
		// Mobile/tablet (<1280px) — same structure as section-hero.php's
		// stacked layout, hidden from xl up where the row layout below takes
		// over instead.
		?>
		<div class="xl:hidden">
			<div class="section-page-hero-detail__media overflow-hidden mb-4 h-[176px] md:h-[256px]">
				<?php
				echo wp_get_attachment_image(
					$page_hero_detail_image,
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

			<?php if ( $page_hero_detail_title || $page_hero_detail_text ) : ?>
				<div class="border-2 border-brand-dark flex flex-col md:flex-row gap-12 md:gap-0 md:justify-between md:items-start p-8 md:px-11 md:py-12">
					<?php if ( $page_hero_detail_title ) : ?>
						<h1 class="title-hero md:w-[293px]"><?php echo esc_html( $page_hero_detail_title ); ?></h1>
					<?php endif; ?>

					<?php if ( $page_hero_detail_text ) : ?>
						<div class="body-text md:w-[297px]"><?php echo wp_kses_post( $page_hero_detail_text ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php
		// Desktop (xl+) — panel (5/12) + image (7/12) side by side, same
		// col-span split as the home hero-card (template-parts/pages/home/hero.php).
		?>
		<div class="hidden xl:block">
			<div class="theme-grid items-stretch">
				<?php if ( $page_hero_detail_title || $page_hero_detail_text ) : ?>
					<div class="col-span-5 border-2 border-brand-dark flex flex-col gap-24 xl:gap-40 px-10 py-8 xl:px-14 xl:py-[46px]">
						<?php if ( $page_hero_detail_title ) : ?>
							<h1 class="title-hero"><?php echo esc_html( $page_hero_detail_title ); ?></h1>
						<?php endif; ?>

						<?php if ( $page_hero_detail_text ) : ?>
							<div class="body-text"><?php echo wp_kses_post( $page_hero_detail_text ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="col-span-7 overflow-hidden h-[460px] 2xl:h-[512px]">
					<?php
					echo wp_get_attachment_image(
						$page_hero_detail_image,
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
			</div>
		</div>

	</div>

	<?php if ( get_field( 'page_hero_detail_separator_logo' ) ) : ?>
		<div class="theme-container">
			<div class="section-page-hero-detail__separator mt-24 md:mt-32 xl:mt-40 2xl:mt-48 flex justify-center">
				<?php
				echo wp_get_attachment_image(
					get_field( 'page_hero_detail_separator_logo' ),
					'full',
					false,
					array(
						'class'   => 'max-w-[96px] md:max-w-[212px] xl:max-w-[244px] h-auto',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		</div>
	<?php endif; ?>
</section>
