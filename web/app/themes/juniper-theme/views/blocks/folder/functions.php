<?php

$block_name = 'folder';

add_action(
	'wp_enqueue_scripts',
	function() use ( $block_name ) {
		if ( has_block( 'acf/' . $block_name ) ) {
			$time       = time();
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'folder-css', $theme_path . '/dist/blocks/folder/style.css', array(), $time, 'all' );
			wp_enqueue_script( 'folder-js', $theme_path . '/dist/blocks/folder/script.js', array(), $time, true );
		}
	}
);

add_filter(
	'timber/acf-gutenberg-blocks-data/' . $block_name,
	function( $context ) {
		return $context;
	}
);

$js_dir           = get_template_directory_uri() . '/dist/blocks/' . $block_name . '/ajax.js';
$ajax_action_name = $block_name;
$juniper_ajax     = new \TMPJuniper\Ajax\FolderAjax( $ajax_action_name, $js_dir, $block_name );
