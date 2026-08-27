<?php

add_action(
	'init',
	function () {
		$editor_handle = 'juniper-filteringposts-editor';

		wp_register_script(
			$editor_handle,
			get_template_directory_uri() . '/dist/blocks/filteringposts/edit.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		register_block_type( __DIR__, array( 'editor_script' => $editor_handle ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( has_block( 'juniper-theme/filteringposts' ) ) {
			$version    = wp_get_theme()->get( 'Version' );
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'filteringposts-css', $theme_path . '/dist/blocks/filteringposts/style.css', array(), $version, 'all' );
			wp_enqueue_script( 'filteringposts-js', $theme_path . '/dist/blocks/filteringposts/script.js', array(), $version, true );
		}
	}
);

add_action(
	'admin_init',
	function () {
		add_editor_style( '/dist/blocks/filteringposts/style.css' );
	}
);

$block_name       = 'filteringposts';
$js_dir           = get_template_directory_uri() . '/dist/blocks/' . $block_name . '/ajax.js';
$ajax_action_name = $block_name;
$juniper_ajax     = new \Juniper\Ajax\JuniperAjaxFilteringposts( $ajax_action_name, $js_dir, $block_name );
