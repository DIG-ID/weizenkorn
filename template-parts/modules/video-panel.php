<?php
/**
 * Video panel — a heading, then a video filling the left half with an overline and a
 * paragraph in the right-hand columns, the two ending on the same line.
 *
 * The video is set up the way the home page's hero and craft-showcase already are: a
 * switch and a file per format, with the section's own image as the poster and as the
 * fallback a browser without video support draws. Leave the switch off and the image is
 * simply the picture.
 *
 * ACF fields (flat, prefixed):
 *   {prefix}video_panel_section_title  (clone of "Section Title") the title and its rule.
 *   {prefix}video_panel_image          (image → ID) the poster, the fallback, and the
 *                                      picture when there is no video.
 *   {prefix}video_panel_enable_video   (true/false)
 *   {prefix}video_panel_video_webm     (file) the smaller of the two.
 *   {prefix}video_panel_video_mp4      (file) the one everything plays.
 *   {prefix}video_panel_overline       (text)
 *   {prefix}video_panel_text           (textarea / wpautop)
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/video-panel' );
 *
 * @param array $args {
 *     @type int|string $post_id Optional. ACF post id / options store. Default: current post.
 *     @type string     $prefix  Optional. Prepended to every field name.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.11.0
 */

$vp_ctx    = ( ! empty( $args['post_id'] ) ) ? $args['post_id'] : get_the_ID();
$vp_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';
$vp_field  = $vp_prefix . 'video_panel_';

// Not a plain get_field() — see weizenkorn_get_section_heading() for why.
$vp_heading = weizenkorn_get_section_heading( $vp_field, $vp_ctx );
/*
 * The clone stores its sub-fields flat, so the heading's `image` lands on {prefix}image —
 * the same meta row as this section's own picture field. One cell, two fields: whatever is
 * set draws twice, once wide under the rule and once in its column. The section's own
 * field owns that key here, so the heading gives the image up.
 *
 * Only the modules that have an image field of their own need this. trust and catering do
 * the mirror of it, reading the key as the heading's because they have none.
 */
unset( $vp_heading['image'] );

$vp_image    = get_field( $vp_field . 'image', $vp_ctx );
$vp_overline = get_field( $vp_field . 'overline', $vp_ctx );
$vp_text     = get_field( $vp_field . 'text', $vp_ctx );

/*
 * A Vimeo link wins over the self-hosted pair. Anything long enough to be worth a player
 * belongs on a streaming host: an mp4 in the media library is one file at one bitrate, so
 * a phone on mobile data downloads exactly what a desktop on fibre does.
 */
$vp_vimeo_field = get_field( $vp_field . 'vimeo', $vp_ctx );
$vp_vimeo       = weizenkorn_get_vimeo_embed_url( $vp_vimeo_field );

/*
 * A video the client has since deleted, made private or re-hashed would still draw its
 * facade, and the visitor would only find out on the click. Asked of Vimeo once and cached,
 * so a gone video falls back to the image instead — see the helper for why the check cannot
 * live in the browser, and why it fails open.
 */
$vp_vimeo_data  = $vp_vimeo ? weizenkorn_get_vimeo_data( $vp_vimeo_field ) : array();
$vp_vimeo_thumb = '';

if ( $vp_vimeo && empty( $vp_vimeo_data['available'] ) ) {
	$vp_vimeo = '';
} elseif ( $vp_vimeo && ! $vp_image ) {
	/*
	 * Vimeo's own still, so an unfilled Image field is a picture rather than a red button on
	 * a dark rectangle. The uploaded one always wins where there is one — it is the only way
	 * to art-direct the frame the reader sees — and this is a remote file, so it is one
	 * request to a Vimeo CDN on load. No cookies, and nothing that needs consent, but it is
	 * still Vimeo seeing an IP: filling the field keeps even that off the page.
	 */
	$vp_vimeo_thumb = ! empty( $vp_vimeo_data['thumbnail'] ) ? $vp_vimeo_data['thumbnail'] : '';
}

$vp_enabled = get_field( $vp_field . 'enable_video', $vp_ctx );
$vp_webm    = $vp_enabled ? weizenkorn_get_file_url( get_field( $vp_field . 'video_webm', $vp_ctx ) ) : '';
$vp_mp4     = $vp_enabled ? weizenkorn_get_file_url( get_field( $vp_field . 'video_mp4', $vp_ctx ) ) : '';
$vp_video   = ( $vp_webm || $vp_mp4 );

$vp_poster = $vp_image ? wp_get_attachment_image_url( $vp_image, 'full' ) : '';

if ( ! $vp_heading && ! $vp_image && ! $vp_video && ! $vp_vimeo && ! $vp_text ) {
	return;
}
?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the previous
// section's bottom margin — the gap is these values, not their sum.
?>
<section class="video-panel mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">

		<?php
		if ( $vp_heading ) {
			get_template_part( 'template-parts/components/section-heading', null, $vp_heading );
		}
		?>

		<?php
		// items-end because at desktop the frame ends the video and the paragraph on the
		// same line, rather than centring the text beside the picture. Below xl the two
		// stack full width, where it has nothing to do.
		?>
		<div class="video-panel__row theme-grid items-end mt-8 md:mt-8 xl:mt-[52px]">

			<?php if ( $vp_video || $vp_vimeo || $vp_image ) : ?>
				<div class="video-panel__media <?php echo $vp_vimeo ? 'video-panel__media--embed ' : ''; ?>col-span-2 md:col-span-6 xl:col-span-6 overflow-hidden">
					<?php if ( $vp_vimeo ) : ?>
						<?php
						// A facade, not the iframe itself. Vimeo's player loads a few hundred KB
						// and sets its cookies the moment an iframe exists, whether or not anyone
						// watches — so the page draws the poster and a play button, and the
						// iframe is built by the click. Nothing third-party is fetched until a
						// visitor asks for it, which is also what keeps this off a consent banner.
						?>
						<button
							type="button"
							class="video-facade"
							data-video-facade="<?php echo esc_url( $vp_vimeo ); ?>"
							aria-label="<?php esc_attr_e( 'Play the video', 'weizenkorn' ); ?>"
						>
							<?php
							if ( $vp_image ) {
								echo wp_get_attachment_image(
									$vp_image,
									'large',
									false,
									array(
										'class'   => 'w-full h-full object-cover',
										'loading' => 'lazy',
									)
								);
							} elseif ( $vp_vimeo_thumb ) {
								// Decorative: the button it sits in carries the accessible name.
								printf(
									'<img src="%s" alt="" class="w-full h-full object-cover" loading="lazy" decoding="async" />',
									esc_url( $vp_vimeo_thumb )
								);
							}
							?>
							<span class="video-facade__play" aria-hidden="true">
								<?php weizenkorn_the_svg_icon( 'play' ); ?>
							</span>
						</button>
					<?php elseif ( $vp_video ) : ?>
						<?php
						// muted is what makes autoplay allowed at all — a browser blocks a video
						// with sound that starts on its own. playsinline keeps iOS from taking it
						// full screen. The image is the poster, so the column is never empty
						// while the file loads, and it is repeated inside the element as the
						// fallback — a poster is an attribute and carries no alt text.
						?>
						<video
							class="w-full h-full object-cover"
							<?php echo $vp_poster ? 'poster="' . esc_url( $vp_poster ) . '"' : ''; ?>
							autoplay
							muted
							loop
							playsinline
							preload="metadata"
						>
							<?php if ( $vp_webm ) : ?>
								<source src="<?php echo esc_url( $vp_webm ); ?>" type="video/webm" />
							<?php endif; ?>
							<?php if ( $vp_mp4 ) : ?>
								<source src="<?php echo esc_url( $vp_mp4 ); ?>" type="video/mp4" />
							<?php endif; ?>
							<?php
							if ( $vp_image ) {
								echo wp_get_attachment_image(
									$vp_image,
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
							$vp_image,
							'large',
							false,
							array(
								'class'   => 'w-full h-full object-cover',
								'loading' => 'lazy',
							)
						);
						?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $vp_overline || $vp_text ) : ?>
				<div class="video-panel__text col-span-2 md:col-span-6 xl:col-start-8 xl:col-span-5 text-brand-dark">
					<?php if ( $vp_overline ) : ?>
						<p class="video-panel__overline label-overline"><?php echo esc_html( $vp_overline ); ?></p>
					<?php endif; ?>

					<?php if ( $vp_text ) : ?>
						<?php // Textarea with wpautop: already wrapped in <p>, so the wrapper is a div. ?>
						<div class="body-text"><?php echo wp_kses_post( $vp_text ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
