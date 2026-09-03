<?php
/**
 * News content — the article as the editor wrote it.
 *
 * Everything inside comes from the block editor: paragraphs, buttons and galleries. The
 * theme's part in it is the width. The body is the page grid itself rather than a column
 * of it, so a block can be told which columns it takes: text keeps to seven, a gallery
 * runs all twelve. Without that the gallery would be stuck inside the text's width, and
 * widening it would take negative margins that break at every other viewport.
 *
 * The opening paragraph is not read from the excerpt any more — it is a paragraph like the
 * others, set in the larger of the two sizes the editor offers.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.10.0
 */

?>
<?php
// Adjacent siblings' vertical margins collapse, so this does not add to the news grid's
// own top margin — the gap between them is the larger of the two, not the sum.
?>
<div class="news-content mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<div class="news-content__body theme-grid">
			<?php the_content(); ?>
		</div>
	</div>
</div>
