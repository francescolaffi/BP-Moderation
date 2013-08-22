<?php


class bpModDocsContentType
{
	const SLUG = 'buddypress-docs';

	function __construct()
	{
		$label = __('BP Docs', 'bp-moderation');

		$callbacks = array(
			'info' => array($this, 'info'),
			'init' => array($this, 'init'),
			'edit' => array($this, 'edit'),
			'delete' => array($this, 'delete'),
		);

		$activity_types = array('bp_doc_created', 'bp_doc_edited');

		foreach ($activity_types as $type) {
			add_filter("bp_moderation_activity_loop_link_args_$type", array($this, 'convert_activity_id'));
		}

		return bpModeration::register_content_type(self::SLUG, $label, $callbacks, $activity_types);
	}

	function convert_activity_id($args)
	{
		$args['id'] = bp_get_activity_secondary_item_id();
		$args['id2'] = null;
	}

	function init()
	{
		add_action('bp_docs_loop_additional_th', array($this, 'loop_th'));
		add_action('bp_docs_loop_additional_td', array($this, 'loop_td'));
		add_action('bp_docs_single_doc_meta', array($this, 'single_meta'));
	}

	function loop_th()
	{
		echo '<th scope="column" class="bpmod-flag">';
		_e( 'Report', 'bp-moderation' );
		echo '</th>';
	}

	function loop_td()
	{
		$link = bpModFrontend::get_link(array(
			'type' => self::SLUG,
			'id' => get_the_ID(),
			'author_id' => get_the_author_meta('id')
		));
		echo '<td class="bpmod-flag">';
		echo $link;
		echo '</td>';
	}

	function single_meta()
	{
		$link = bpModFrontend::get_link(array(
			'type' => self::SLUG,
			'id' => get_the_ID(),
			'author_id' => get_the_author_meta('id'),
			'unflagged_text' => __('Report this document as inappropriate', 'bp-moderation')
		));
		echo $link;
	}

	function info($id)
	{
		$post = get_post($id);

		return array(
			'author' => $post->post_author,
			'url' => bp_docs_get_doc_link($id), //this link url don't depend on permalink structure and post slug
			'date' => $post->post_date_gmt,
		);
	}

	function edit($id)
	{
		return bp_docs_get_doc_edit_link($id);
	}

	function delete($id)
	{
		return !get_post($id) || wp_trash_post($id);
	}
}

?>
