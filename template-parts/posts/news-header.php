<?php
/**
 * News header — the date, the rule under it and the article's title.
 *
 * The rule runs the container's width while the date and the title keep to the inset,
 * which is why it sits on a wrapper of its own and not on the row that holds them.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

?>
<header class="news-header">
	<div class="theme-container">

		<div class="news-header__meta theme-grid">
			<time class="news-header__date label-overline text-brand-red col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-10" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
		</div>

		<div class="news-header__rule border-b border-brand-red"></div>

		<div class="theme-grid">
			<h1 class="news-header__title title-main text-brand-dark col-span-2 md:col-span-6 xl:col-start-2 xl:col-span-9">
				<?php the_title(); ?>
			</h1>
		</div>

	</div>
</header>
