<?php
/**
 * The Section for the Header Template.
 *
 * @package weizenkorn
 * @subpackage Section
 * @since 1.0.0
 */

$weizenkorn_logo_id = weizenkorn_get_logo_id();
?>
<header id="header-main" class="header-main" itemscope itemtype="http://schema.org/WebSite">
	<div class="theme-container">
		<div class="header-main__row">
			<?php get_template_part( 'template-parts/components/header-nav', null, array( 'logo_id' => $weizenkorn_logo_id ) ); ?>
		</div>
	</div>

	<div class="header-main__sticky" data-sticky-header>
		<div class="theme-container">
			<div class="header-main__row header-main__row--sticky">
				<?php get_template_part( 'template-parts/components/header-nav', null, array( 'logo_id' => $weizenkorn_logo_id ) ); ?>
			</div>
		</div>
	</div>
</header>
