<?php
/**
 * News featured — the most recent article, with its photograph beside the opening of it
 * and a link through to the whole thing.
 *
 * Takes the post to feature rather than querying for it, so the template that draws it
 * also knows which id to keep out of the grid below — the two have to agree, and one
 * query is what makes them.
 *
 * Usage:
 *   get_template_part( 'template-parts/modules/news-featured', null, array( 'post_id' => $id ) );
 *
 * @param array $args {
 *     @type int $post_id Required. The article to feature.
 * }
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.10.0
 */

$nf_post_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;

if ( ! $nf_post_id ) {
	return;
}

$nf_excerpt = get_the_excerpt( $nf_post_id );
?>
<section class="news-featured mt-24 md:mt-32 xl:mt-48 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<?php
		// The frame ends the photograph and the button on the same line, so the text column
		// sits at the bottom of the picture rather than beside its middle. At tablet the two
		// come out the same height anyway — 30 + 24 + 220 + 24 + 48 is the image's 346.
		?>
		<div class="news-featured__row theme-grid items-end">

			<?php if ( has_post_thumbnail( $nf_post_id ) ) : ?>
				<div class="news-featured__media col-span-2 md:col-span-3 xl:col-span-6 overflow-hidden">
					<?php
					echo get_the_post_thumbnail(
						$nf_post_id,
						'full',
						array(
							'class'   => 'w-full h-full object-cover',
							'loading' => 'lazy',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<div class="news-featured__text col-span-2 md:col-span-3 xl:col-start-8 xl:col-span-5 text-brand-dark">

				<?php
				// The frame draws three blocks: one short line, the paragraph, the button. The
				// short line is the article's title, set as the overline — a featured article
				// with no title tells the reader nothing, and the date is already on every card
				// in the grid below.
				?>
				<h2 class="news-featured__title label-overline">
					<?php echo esc_html( get_the_title( $nf_post_id ) ); ?>
				</h2>

				<?php if ( $nf_excerpt ) : ?>
					<p class="news-featured__excerpt body-text"><?php echo esc_html( $nf_excerpt ); ?></p>
				<?php endif; ?>

				<div class="news-featured__action">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'title' => __( 'Weiterlesen', 'weizenkorn' ),
							'url'   => get_permalink( $nf_post_id ),
							'style' => 'primary',
						)
					);
					?>
				</div>

			</div>
		</div>
	</div>
</section>
