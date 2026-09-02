<?php
/**
 * Organization page team member card — a photo (or the Figma placeholder
 * silhouette on a neutral background), then a bordered box with the name,
 * role and workplace. Receives its data via $args; never calls get_field()
 * itself — the caller (template-parts/pages/organization/team.php) reads
 * the organization_team_items repeater row and resolves the bereich/
 * standort select fields' raw values to their labels before passing them
 * in (a 'select' field only ever returns its raw choice key via
 * get_sub_field(), never the label).
 *
 * Not the same component as template-parts/pages/for-social-offices-and-partners/team.php's
 * .card-team — that one has no third (workplace) line and a mail icon
 * instead, a different enough shape (and a name already taken) to be its
 * own component rather than a shared one bent to fit both.
 *
 * @param array $args {
 *     @type int    $photo    Optional. Attachment ID. Falls back to the
 *                            avatar-placeholder icon when empty.
 *     @type string $name     Required.
 *     @type string $bereich  Optional. The person's role, e.g. "Bäcker/in".
 *     @type string $standort Optional. Their workplace, e.g. "DasBreiteHotel".
 * }
 *
 * @package weizenkorn
 * @subpackage Component
 * @since 1.12.0
 */

if ( empty( $args['name'] ) ) {
	return;
}
?>
<div class="card-org-team border border-brand-red bg-white flex flex-col h-full">
	<div class="card-org-team__media aspect-[7/4] bg-[#E9E8E6] flex items-center justify-center overflow-hidden">
		<?php if ( ! empty( $args['photo'] ) ) : ?>
			<?php
			echo wp_get_attachment_image(
				$args['photo'],
				'medium',
				false,
				array(
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				)
			);
			?>
		<?php else : ?>
			<span class="card-org-team__placeholder text-white w-16 h-16" aria-hidden="true">
				<?php weizenkorn_the_svg_icon( 'avatar-placeholder' ); ?>
			</span>
		<?php endif; ?>
	</div>

	<div class="card-org-team__body px-5 py-4 flex flex-col gap-2">
		<h3 class="card-org-team__name font-primary font-bold text-brand-dark m-0"><?php echo esc_html( $args['name'] ); ?></h3>

		<?php if ( ! empty( $args['bereich'] ) ) : ?>
			<p class="card-org-team__role body-text text-brand-dark m-0"><?php echo esc_html( $args['bereich'] ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $args['standort'] ) ) : ?>
			<p class="card-org-team__workplace label-overline text-brand-dark m-0"><?php echo esc_html( $args['standort'] ); ?></p>
		<?php endif; ?>
	</div>
</div>
