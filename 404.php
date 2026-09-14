<?php
/**
 * The template for displaying 404 (not found) pages.
 *
 * A red panel with two rules across it, holding the overline, the 404 itself, a button back
 * to the home page and a sentence explaining what happened. Everything is centred.
 *
 * The copy lives here and not in ACF: a 404 has no post to read fields from. It stays
 * translatable, which is what WPML picks up.
 *
 * @package weizenkorn
 * @subpackage Template
 * @since 1.15.0
 */

get_header();
do_action( 'before_main_content' );
?>
<section class="error-404 xl:mb-20">
	<div class="theme-container">
		<div class="error-404__panel bg-brand-red text-white">
			<?php
			// The two rules are this box's own top and bottom borders, so they stay the panel's
			// full width and keep their distance from its edges without a element of their own.
			?>
			<div class="error-404__inner">
				<p class="error-404__overline label-overline">
					<?php esc_html_e( 'Ups, hier sind wir auf dem Holzweg...', 'weizenkorn' ); ?>
				</p>

				<?php
				// Not an <h1>: the heading of a 404 is the message, not the status code. Marking
				// the number as the page's heading would have a screen reader announce "404".
				?>
				<p class="error-404__code" aria-hidden="true">404</p>

				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-white error-404__button">
					<span><?php esc_html_e( 'zur Startseite', 'weizenkorn' ); ?></span>
					<span class="btn__icon" aria-hidden="true"><?php weizenkorn_the_svg_icon( 'arrow-right' ); ?></span>
				</a>

				<h1 class="error-404__text">
					<?php esc_html_e( 'Die gesuchte Seite konnten wir leider nicht finden. Über die Startseite gelangen Sie zurück zur Vielfalt von Weizenkorn: Arbeitsbereiche, Dienstleistungen und Produkte.', 'weizenkorn' ); ?>
				</h1>
			</div>
		</div>
	</div>
</section>
<?php
do_action( 'after_main_content' );
get_footer();
