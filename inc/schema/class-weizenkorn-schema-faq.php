<?php
/**
 * FAQPage schema piece — see inc/schema.php for how this is registered.
 *
 * @package weizenkorn
 * @subpackage Functionality
 * @since 1.13.0
 */

/**
 * FAQPage schema for any page that renders template-parts/modules/faq.php.
 *
 * The module is a plain ACF repeater called with a different field-name prefix per page
 * (or none at all) — mirrors the exact set of callers in that file's own docblock. Add a
 * new prefix here whenever a new page starts calling the module with one that isn't
 * already listed, or its FAQ items won't get picked up.
 */
class Weizenkorn_Schema_FAQ extends \Yoast\WP\SEO\Generators\Schema\Abstract_Schema_Piece {

	/**
	 * Known template-parts/modules/faq.php prefixes (own field group, or a page's own).
	 *
	 * @var string[]
	 */
	const PREFIXES = array( '', 'donate_', 'apprenticeships_', 'supported_jobs_' );

	/**
	 * Questions found for the current post, set by is_needed().
	 *
	 * @var array
	 */
	private $questions = array();

	/**
	 * Only needed where one of the known prefixes has actual FAQ rows.
	 *
	 * @return bool
	 */
	public function is_needed() {
		if ( ! ( $this->context->post instanceof WP_Post ) ) {
			return false;
		}

		$post_id = $this->context->post->ID;

		foreach ( self::PREFIXES as $prefix ) {
			$questions = $this->collect_questions( $prefix, $post_id );

			if ( $questions ) {
				$this->questions = $questions;
				break;
			}
		}

		if ( ! $this->questions ) {
			return false;
		}

		if ( ! is_array( $this->context->schema_page_type ) ) {
			$this->context->schema_page_type = array( $this->context->schema_page_type );
		}
		$this->context->schema_page_type[] = 'FAQPage';

		// Same convention as Yoast's own FAQ piece: reference the Questions from the
		// WebPage node so they read as part of the page, not orphaned graph pieces.
		$this->context->main_entity_of_page = array_map(
			function ( $index ) {
				return array( '@id' => $this->question_id( $index ) );
			},
			array_keys( $this->questions )
		);

		return true;
	}

	/**
	 * Reads {$prefix}faq_items for one post, same field names/shape as faq.php itself.
	 *
	 * @param string $prefix  Field-name prefix.
	 * @param int    $post_id Post ID.
	 *
	 * @return array List of ['question' => string, 'answer' => string].
	 */
	private function collect_questions( $prefix, $post_id ) {
		$questions = array();

		if ( ! have_rows( $prefix . 'faq_items', $post_id ) ) {
			return $questions;
		}

		while ( have_rows( $prefix . 'faq_items', $post_id ) ) {
			the_row();

			$question = get_sub_field( 'question' );
			$answer   = get_sub_field( 'answer' );

			if ( ! $question || ! $answer ) {
				continue;
			}

			$questions[] = array(
				'question' => $question,
				'answer'   => $answer,
			);
		}

		return $questions;
	}

	/**
	 * Builds a stable @id for one question, keyed by its position.
	 *
	 * @param int $index Zero-based position in $this->questions.
	 *
	 * @return string
	 */
	private function question_id( $index ) {
		return $this->context->canonical . '#faq-question-' . ( $index + 1 );
	}

	/**
	 * Renders the collected questions as Question/Answer graph pieces.
	 *
	 * @return array
	 */
	public function generate() {
		$graph = array();

		foreach ( $this->questions as $index => $item ) {
			$id = $this->question_id( $index );

			$data = array(
				'@type'          => 'Question',
				'@id'            => $id,
				'position'       => $index + 1,
				'url'            => $id,
				'name'           => $this->helpers->schema->html->smart_strip_tags( $item['question'] ),
				'answerCount'    => 1,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_kses_post( $item['answer'] ),
				),
			);

			$graph[] = $this->helpers->schema->language->add_piece_language( $data );
		}

		return $graph;
	}
}
