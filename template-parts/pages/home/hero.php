<?php
/**
 * Home — Hero section ("Breit aufgestellt. Tief verwurzelt.").
 * Split hero-card: red title + intro (tagline + body) + "Mehr erfahren" + image.
 *
 * ACF fields (flat, prefixed):
 *   hero_title        (text)
 *   hero_tagline      (text)
 *   hero_body         (textarea / wysiwyg)
 *   hero_button       (link)
 *   hero_image        (image → return ID)
 *   hero_enable_video (true/false) — when true, hero_video_mp4/webm replace hero_image.
 *   hero_video_mp4    (file → return array)
 *   hero_video_webm   (file → return array)
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

$weizenkorn_hero_enable_video = get_field( 'hero_enable_video' );
$weizenkorn_hero_video_mp4    = $weizenkorn_hero_enable_video ? get_field( 'hero_video_mp4' ) : null;
$weizenkorn_hero_video_webm   = $weizenkorn_hero_enable_video ? get_field( 'hero_video_webm' ) : null;
?>
<section class="section-hero pb-[60px] xl:pb-[120px]">
	<?php
	// No explicit height/min-height here on purpose — the section is always
	// naturally sized to its content, exactly like before this whole
	// viewport-fit pass, so it never gets stretched taller than the content
	// actually needs (that stretching was the bug: it pushed the card and
	// image further down than the reference design whenever content was
	// shorter than a full viewport). The margins/padding below use
	// clamp(MIN, 100vh - X, MAX) — a linear ramp anchored so the value
	// equals MAX (today's fixed spacing) at a 900px-tall viewport and
	// shrinks smoothly below that (same technique as the mega menu's
	// height-responsive spacing, _menu-overlay.sass) — which in turn makes
	// the naturally-sized content shorter on shorter viewports, without
	// ever forcing it to BE taller than it needs. assets/js/hero-fit.js
	// tops this up further if the real rendered content (actual
	// line-wrapping, translated text) still doesn't fit within one
	// viewport — a best-effort minimiser, never a forced minimum height.
	?>
	<div class="theme-container real-hero-section">
		<div class="theme-grid items-stretch">

			<?php
			// No max-height here (unlike .section-hero__media below) — a
			// max-height without overflow:hidden doesn't stop content from
			// needing more room, it just caps the BOX while the text spills
			// past its border, which looked broken. This box must never be
			// smaller than what its own content needs, full stop — so it
			// stays purely naturally sized, matching the media column
			// whenever content fits within the target (the normal case,
			// via items-stretch) and simply taller than it in the rare case
			// content alone doesn't fit — media still caps at the target
			// either way, since cropping it further loses nothing.
			?>
			<div class="col-span-2 md:col-span-3 xl:col-span-5 section-hero__content border-2 border-brand-dark order-2 md:order-none pt-4 xl:pt-[clamp(24px,100vh_-_884px,64px)] px-4 xl:pl-8 2xl:pl-16">
				<?php if ( get_field( 'hero_title' ) ) : ?>
					<div class="section-hero__title mb-12 md:mb-14 xl:mb-[clamp(40px,100vh_-_820px,128px)]">
						<h1 class="title-hero xl:max-w-2xl"><?php the_field( 'hero_title' ); ?></h1>
					</div>
				<?php endif; ?>

				<?php if ( get_field( 'hero_tagline' ) ) : ?>
					<div class="section-hero__tagline mb-5 xl:mb-[clamp(8px,100vh_-_932px,16px)]">
						<p class="title-tagline"><?php the_field( 'hero_tagline' ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( get_field( 'hero_body' ) ) : ?>
					<div class="section-hero__body mb-12 md:mb-24 xl:mb-[clamp(24px,100vh_-_884px,64px)]">
						<div class="body-text"><?php the_field( 'hero_body' ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( get_field( 'hero_button' ) ) : ?>
					<div class="section-hero__cta ">
								<?php
									$cta_button = get_field( 'hero_button' );

								if ( $cta_button ) {
									get_template_part(
										'template-parts/components/button',
										null,
										array_merge( $cta_button, array( 'style' => 'arrow-down' ) )
									);
								}
								?>
					</div>
				<?php endif; ?>
			</div>

			<?php
			// xl:max-h-[...] caps just the image/video column at the target
			// height (viewport minus header minus the 48px bottom gap) — this
			// is what actually keeps the 48px gap at wide viewports: without
			// it, the column has no explicit height, so the browser sizes it
			// to the image's own aspect ratio at whatever width col-span-7
			// gives it, which grows taller at wider viewports and was
			// stretching the whole card (via items-stretch) past the target.
			// max-height (not min/height) because object-cover just crops a
			// wide-aspect image/video to fit shorter — no content is lost,
			// unlike the text column, which stays free to grow if it ever
			// needs more room than this.
			?>
			<div class="col-span-2 md:col-span-3 xl:col-span-7 section-hero__media overflow-hidden order-1 md:order-none xl:max-h-[calc(100vh-var(--header-height)-48px)]">
				<?php if ( $weizenkorn_hero_video_mp4 || $weizenkorn_hero_video_webm ) : ?>
					<video
						class="w-full h-full object-cover"
						<?php echo get_field( 'hero_image' ) ? 'poster="' . esc_url( wp_get_attachment_image_url( get_field( 'hero_image' ), 'full' ) ) . '"' : ''; ?>
						preload="auto"
						autoplay
						muted
						loop
						playsinline
					>
						<?php if ( $weizenkorn_hero_video_webm ) : ?>
							<source src="<?php echo esc_url( $weizenkorn_hero_video_webm['url'] ); ?>" type="video/webm" />
						<?php endif; ?>
						<?php if ( $weizenkorn_hero_video_mp4 ) : ?>
							<source src="<?php echo esc_url( $weizenkorn_hero_video_mp4['url'] ); ?>" type="video/mp4" />
						<?php endif; ?>
					</video>
				<?php elseif ( get_field( 'hero_image' ) ) : ?>
					<?php
					echo wp_get_attachment_image(
						get_field( 'hero_image' ),
						'full',
						false,
						array(
							'class'         => 'w-full h-full object-cover',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
						)
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>
	<?php if ( get_field( 'hero_seperator_logo' ) ) : ?>
		<div class="theme-container">
			<div class="section-hero__separator mt-24 md:mt-40 xl:mt-48 flex justify-center">
				<?php
				echo wp_get_attachment_image(
					get_field( 'hero_seperator_logo' ),
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
