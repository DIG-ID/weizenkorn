<?php
/**
 * Page hero (detail level) — the hero for second-level interior pages.
 *
 * Mobile and tablet are structurally identical to modules/hero-section.php: image on top,
 * bordered title and text panel below. Desktop diverges into a side-by-side panel and
 * image row, so both layouts are rendered and toggled with responsive visibility rather
 * than forced into one fluid structure. Its own module and fields, not a hero-section
 * variant, because the desktop layout genuinely differs and not just its content.
 *
 * ACF fields (flat, prefixed):
 *   page_hero_detail_image           (image → return ID)
 *   page_hero_detail_title           (text) a <br> may be typed in to break the line,
 *                                    with a class saying at which widths it should —
 *                                    see the echo below.
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
		// The stacked layout, hidden from xl up where the row layout below takes over.
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
				<?php
				// The tablet padding is hero-section.php's own, so the two heroes indent their
				// box alike: two steps rather than one, because at 768 the container is only
				// 634 wide and the title and text side by side already take 590 of it — 44
				// either side would not fit. The wider step comes in at lg, where it does.
				?>
				<div class="border-2 border-brand-dark flex flex-col md:flex-row gap-12 md:gap-0 md:justify-between md:items-start p-8 md:px-[0.8rem] md:py-6 lg:px-11 lg:py-12">
					<?php if ( $page_hero_detail_title ) : ?>
						<h1 class="title-hero md:w-[293px]">
							<?php
							// Not esc_html(): a title carries a <br> where the frame breaks the
							// line, and escaping printed the tag as text. Same allowed set as
							// hero-section.php — a <br> and nothing else, with its class kept so
							// the break can apply at some widths only (<br class="xl:hidden">).
							// Those utilities are safelisted in tailwind.config.js, which never
							// scans the database.
							echo wp_kses( $page_hero_detail_title, array( 'br' => array( 'class' => array() ) ) );
							?>
						</h1>
					<?php endif; ?>

					<?php if ( $page_hero_detail_text ) : ?>
						<div class="body-text md:w-[297px]"><?php echo wp_kses_post( $page_hero_detail_text ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php
		// Panel and image side by side, the same column split as the home hero-card.
		?>
		<div class="hidden xl:block">
			<div class="theme-grid items-stretch">
				<?php if ( $page_hero_detail_title || $page_hero_detail_text ) : ?>
					<div class="col-span-5 border-2 border-brand-dark flex flex-col gap-24 xl:gap-40 px-10 py-8 xl:px-14 xl:py-[46px]">
						<?php if ( $page_hero_detail_title ) : ?>
							<h1 class="title-hero">
								<?php echo wp_kses( $page_hero_detail_title, array( 'br' => array( 'class' => array() ) ) ); ?>
							</h1>
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
						'class'   => 'w-auto h-auto max-h-[29px] max-w-[86px] md:max-h-[64px] md:max-w-[189px] xl:max-h-[91px] xl:max-w-[270px]',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		</div>
	<?php endif; ?>
</section>
