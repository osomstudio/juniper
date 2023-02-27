<?php

add_action(
	'wp_enqueue_scripts',
	function() {
		if ( has_block( 'acf/filteringposts' ) ) {
			wp_enqueue_style( 'filteringposts-css', get_template_directory_uri() . '/dist/blocks/filteringposts/style.css', array(), time(), 'all' );
			wp_enqueue_script( 'filteringposts-js', get_template_directory_uri() . '/dist/blocks/filteringposts/script.js', array(), time(), true );
		}
	}
);

add_filter(
	'timber/acf-gutenberg-blocks-data/filteringposts',
	function( $context ) {}
);

$block_name       = 'filteringposts';
$js_dir           = get_template_directory_uri() . '/dist/blocks/' . $block_name . '/ajax.js';
$ajax_action_name = $block_name;
$osom_ajax        = new \Osom\Ajax\OsomAjaxFilteringposts( $ajax_action_name, $js_dir, $block_name );

