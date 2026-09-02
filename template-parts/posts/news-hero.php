<?php
/**
 * News hero — the article's photograph across the container, and under it the two links
 * that walk the archive: the previous article on the left, the next one on the right.
 *
 * "letzter" and "nächster" are the reader's order, not WordPress's: get_previous_post()
 * is the older article, which is the one the frame puts on the left.
 *
 * Either link is left out when there is nothing on that side — the first article has no
 * older one — and the row still holds its ends, so the remaining button stays where the
 * frame puts it.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

$nh_previous = get_previous_post();
$nh_next     = get_next_post();
?>
<?php
// No top margin: the photograph opens the page, right under the site header.
//
// 84 under it at mobile, which the frame annotates rather than the theme's own 96 — the
// only place in this page where the two differ. Tablet and desktop keep the rhythm.
?>
<section class="news-hero mb-[84px] md:mb-32 xl:mb-48">

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="theme-container">
			<div class="news-hero__media overflow-hidden">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class'         => 'w-full h-full object-cover',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					)
				);
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $nh_previous || $nh_next ) : ?>
		<div class="theme-container">
			<?php
			// justify-between and not two column spans: with only one of the two links there
			// is nothing to hold the other end, and a span would leave it stranded in the
			// middle of the row. The older article leads in the markup, which is the reading
			// order; at mobile the frame stacks the newer one on top, which the SASS does by
			// reversing the column rather than by moving these two around.
			?>
			<nav class="news-hero__nav theme-grid" aria-label="<?php esc_attr_e( 'Article navigation', 'weizenkorn' ); ?>">
				<div class="news-hero__nav-inner col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10">

					<div class="news-hero__prev">
						<?php
						if ( $nh_previous ) {
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'title' => __( 'Letzter Beitrag', 'weizenkorn' ),
									'url'   => get_permalink( $nh_previous ),
									'style' => 'secondary',
								)
							);
						}
						?>
					</div>

					<div class="news-hero__next">
						<?php
						if ( $nh_next ) {
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'title' => __( 'Nächster Beitrag', 'weizenkorn' ),
									'url'   => get_permalink( $nh_next ),
									'style' => 'primary',
								)
							);
						}
						?>
					</div>

				</div>
			</nav>
		</div>
	<?php endif; ?>

</section>
