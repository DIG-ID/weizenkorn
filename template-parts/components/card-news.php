<?php
/**
 * News card — the photograph, then a bordered box holding the date, the title and the
 * opening of the article.
 *
 * Reads the post it is called for rather than taking data in $args, so the same card can
 * be rendered inside a loop, from a query, or from the REST route that pages the grid —
 * one markup for all three. Call it inside the loop, or pass a post id.
 *
 * The whole card is the link. The arrow beside the date is decorative: it repeats what
 * the card already does, so it is hidden from assistive technology rather than announced
 * a second time.
 *
 * Usage:
 *   get_template_part( 'template-parts/components/card-news' );                    // in the loop
 *   get_template_part( 'template-parts/components/card-news', null, array( 'post_id' => 12 ) );
 *
 * @param array $args {
 *     @type int $post_id Optional. Post to render. Default: the current post.
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.10.0
 */

$card_post_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();

if ( ! $card_post_id ) {
	return;
}

$card_excerpt = get_the_excerpt( $card_post_id );
?>
<article class="card-news">
	<a class="card-news__link group flex flex-col" href="<?php echo esc_url( get_permalink( $card_post_id ) ); ?>">

		<?php if ( has_post_thumbnail( $card_post_id ) ) : ?>
			<div class="card-news__media overflow-hidden">
				<?php
				echo get_the_post_thumbnail(
					$card_post_id,
					'large',
					array(
						'class'   => 'w-full h-full object-cover',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<div class="card-news__body">

			<div class="card-news__meta flex items-center justify-between gap-4">
				<time class="card-news__date label-overline text-brand-red" datetime="<?php echo esc_attr( get_the_date( 'c', $card_post_id ) ); ?>">
					<?php echo esc_html( get_the_date( '', $card_post_id ) ); ?>
				</time>

				<span class="card-news__arrow text-brand-red shrink-0" aria-hidden="true">
					<?php weizenkorn_the_svg_icon( 'arrow-right' ); ?>
				</span>
			</div>

			<div class="card-news__text">
				<h3 class="card-news__title title-card text-brand-dark group-hover:underline group-focus-visible:underline underline-offset-4">
					<?php echo esc_html( get_the_title( $card_post_id ) ); ?>
				</h3>

				<?php if ( $card_excerpt ) : ?>
					<?php
					// Clamped to what the frames draw: four lines at mobile, where the card runs
					// the container's width, and three from tablet up. The limit is there for a
					// summary someone writes long — the grid already levels the cards beside it,
					// so this is about the row not growing, not about the boxes matching.
					?>
					<p class="card-news__excerpt body-text text-brand-dark line-clamp-4 md:line-clamp-3"><?php echo esc_html( $card_excerpt ); ?></p>
				<?php endif; ?>
			</div>

		</div>
	</a>
</article>
