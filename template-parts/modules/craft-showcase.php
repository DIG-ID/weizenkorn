<?php
/**
 * Craft showcase — a heading and two image columns: the left one captioned with a
 * paragraph under its image, the right one a single taller image.
 *
 * Named for what it says rather than for any one page's heading, more than one page using
 * it. Not `feature`: the Figma analysis applies that word to at least four different
 * blocks and defines none of them.
 *
 * The three parts are direct items of the page grid, each with its own columns and row —
 * no wrapper columns, so the paragraph takes a span of its own rather than a max-width
 * inside a cell. At desktop the second image comes out level with the left pair by
 * spanning their two rows rather than carrying a height.
 *
 * ACF fields (flat, prefixed) — the `craft_showcase` group. The group name produces the
 * prefix, so renaming it orphans whatever is stored:
 *   craft_showcase_section_title    (clone of "Section Title") the heading.
 *   craft_showcase_image            (image → ID) the left, captioned image.
 *   craft_showcase_text             (textarea)   the paragraph under it.
 *   craft_showcase_image_secondary  (image → ID) the right, taller image. Also the video's
 *                                   poster, so it is what a reader sees before the video
 *                                   starts and what stays if it cannot play at all.
 *   craft_showcase_enable_video     (true/false) optional. When on, the two video fields
 *                                   take the right column's place. Named as on the home
 *                                   page's hero, which set the convention.
 *   craft_showcase_video_webm       (file) optional. The smaller of the two.
 *   craft_showcase_video_mp4        (file) optional. The one everything plays, and
 *                                   what a browser falls back to when it cannot read webm.
 *                                   Upload both to the media library: a Google Drive or
 *                                   YouTube link is a page, not a file, and a <video>
 *                                   cannot play it.
 *
 * The video plays by itself, muted and looping, the way an animated image would — the
 * frames give it no controls and no play button. A clip with narration or one that runs
 * long wants `controls` instead, which is one attribute below.
 *
 * The image does two jobs behind a video: it is the poster, which is what shows before the
 * video starts and what stays when autoplay is refused or the file will not play; and it is
 * repeated inside the element, where a browser with no video support draws it and where the
 * picture keeps the alt text a poster attribute cannot carry.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/craft-showcase' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store to read from.
 *                               Default: the current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.5.0
 */

$cs_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$cs_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$cs_heading = weizenkorn_get_section_heading( $cs_prefix . 'craft_showcase_', $cs_ctx );

if ( ! $cs_heading
	&& ! get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx )
	&& ! get_field( $cs_prefix . 'craft_showcase_image_secondary', $cs_ctx )
	&& ! get_field( $cs_prefix . 'craft_showcase_video_mp4', $cs_ctx )
	&& ! get_field( $cs_prefix . 'craft_showcase_video_webm', $cs_ctx ) ) {
	return;
}

/*
 * Same three fields as the home page's hero, so a video is set up the same way wherever a
 * section takes one: a switch, and a file per format. The switch is what lets a page keep
 * the files and still show the photograph.
 */
$cs_enable_video = get_field( $cs_prefix . 'craft_showcase_enable_video', $cs_ctx );
$cs_video_mp4    = $cs_enable_video ? weizenkorn_get_file_url( get_field( $cs_prefix . 'craft_showcase_video_mp4', $cs_ctx ) ) : '';
$cs_video_webm   = $cs_enable_video ? weizenkorn_get_file_url( get_field( $cs_prefix . 'craft_showcase_video_webm', $cs_ctx ) ) : '';
$cs_has_video    = ( $cs_video_webm || $cs_video_mp4 );

$cs_side_image = get_field( $cs_prefix . 'craft_showcase_image_secondary', $cs_ctx );
$cs_poster     = $cs_side_image ? wp_get_attachment_image_url( $cs_side_image, 'large' ) : '';
?>
<?php
// Adjacent siblings' vertical margins collapse, so these do not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="craft-showcase mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $cs_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $cs_heading );
		}
		?>

		<?php
		// The section-heading already carries part of this gap, so only the difference is
		// added — nothing at desktop, where its own margin is the whole gap.
		?>
		<div class="craft-showcase__row theme-grid mt-4 md:mt-2 xl:mt-0">

			<?php if ( get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx ) ) : ?>
				<figure class="craft-showcase__media">
					<?php
					echo wp_get_attachment_image(
						get_field( $cs_prefix . 'craft_showcase_image', $cs_ctx ),
						'large',
						false,
						array(
							'class'   => 'w-full h-full object-cover',
							'loading' => 'lazy',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( get_field( $cs_prefix . 'craft_showcase_text', $cs_ctx ) ) : ?>
				<div class="craft-showcase__text text-brand-dark">
					<?php echo wp_kses_post( get_field( $cs_prefix . 'craft_showcase_text', $cs_ctx ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $cs_has_video || $cs_side_image ) : ?>
				<figure class="craft-showcase__media craft-showcase__media--side">
					<?php if ( $cs_has_video ) : ?>
						<?php
						// muted is what makes autoplay allowed at all — a browser blocks a video
						// with sound that starts on its own. playsinline keeps iOS from taking it
						// full screen. The poster is the section's own image, so the column is
						// never empty while the file loads.
						?>
						<video
							class="w-full h-full object-cover"
							<?php echo $cs_poster ? 'poster="' . esc_url( $cs_poster ) . '"' : ''; ?>
							autoplay
							muted
							loop
							playsinline
							preload="metadata"
						>
								<?php if ( $cs_video_webm ) : ?>
								<source src="<?php echo esc_url( $cs_video_webm ); ?>" type="video/webm" />
							<?php endif; ?>
							<?php if ( $cs_video_mp4 ) : ?>
								<source src="<?php echo esc_url( $cs_video_mp4 ); ?>" type="video/mp4" />
							<?php endif; ?>
							<?php
							// The real fallback, and the only place the picture can carry its alt
							// text: a poster is an attribute, so it has no accessible name of its
							// own. A browser that plays the video never fetches this; one that
							// cannot draws it instead of the video.
							if ( $cs_side_image ) {
								echo wp_get_attachment_image(
									$cs_side_image,
									'large',
									false,
									array( 'class' => 'w-full h-full object-cover' )
								);
							}
							?>
						</video>
					<?php else : ?>
						<?php
						echo wp_get_attachment_image(
							$cs_side_image,
							'large',
							false,
							array(
								'class'   => 'w-full h-full object-cover',
								'loading' => 'lazy',
							)
						);
						?>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

		</div>
	</div>
</section>
