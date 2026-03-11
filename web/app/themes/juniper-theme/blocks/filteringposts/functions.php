<?php

add_action(
	'wp_enqueue_scripts',
	function() {
		if ( has_block( 'acf/filteringposts' ) ) {
			$version       = wp_get_theme()->get( 'Version' );
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'filteringposts-css', $theme_path . '/dist/blocks/filteringposts/style.css', array(), $version, 'all' );
			wp_enqueue_script( 'filteringposts-js', $theme_path . '/dist/blocks/filteringposts/script.js', array(), $version, true );
		}
	}
);

add_action(
	'admin_init',
	function() {
		add_editor_style( '/dist/blocks/filteringposts/style.css' );
	}
);

add_filter(
	'timber/acf-gutenberg-blocks-data/filteringposts',
	function( $context ) {
		return $context;
	}
);

$block_name       = 'filteringposts';
$js_dir           = get_template_directory_uri() . '/dist/blocks/' . $block_name . '/ajax.js';
$ajax_action_name = $block_name;
$juniper_ajax     = new \Juniper\Ajax\JuniperAjaxFilteringposts( $ajax_action_name, $js_dir, $block_name );

