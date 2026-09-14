<?php
/**
 * Title band — the page title centred inside a bordered band, standing in for a hero.
 *
 * What the legal pages open on instead of hero-section: AGB, Datenschutzerklärung and
 * Impressum, the last of which is laid out differently below the band but opens the same
 * way. A module for that reason.
 *
 * No ACF and no arguments: the band shows the page's own title, so a new legal page needs
 * nothing filled in for its heading to appear. It reads the loop, so it belongs on a
 * template with a post context and not on an archive.
 *
 * The title is the page's <h1>. The legal content's own headings are <h2>, which keeps the
 * outline in order.
 *
 * @package weizenkorn
 * @subpackage Module
 * @since 1.14.1
 */

?>
<?php
// Padding and not a margin above: this is the first thing in <main>, and a top margin on a
// first child collapses out through its parent instead of pushing the band down. Below it:
// 96px at mobile, 128 at tablet, 192 at desktop.
?>
<header class="title-band pt-8 md:pt-16 xl:pt-20 mb-24 md:mb-32 xl:mb-48">
	<div class="theme-container">
		<div class="title-band__box border-2 border-brand-red flex items-center justify-center px-6 py-4 md:py-[34px] xl:px-8 xl:py-[50px]">
			<h1 class="title-band__title"><?php the_title(); ?></h1>
		</div>
	</div>
</header>
