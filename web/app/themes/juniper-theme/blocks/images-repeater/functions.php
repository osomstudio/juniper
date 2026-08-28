<?php

add_action(
	'init',
	function () {
		$editor_handle = 'juniper-images-repeater-editor';

		wp_register_script(
			$editor_handle,
			get_template_directory_uri() . '/dist/blocks/images-repeater/edit.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		register_block_type( __DIR__, array( 'editor_script' => $editor_handle ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( has_block( 'juniper-theme/images-repeater' ) ) {
			$version    = wp_get_theme()->get( 'Version' );
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'images-repeater-css', $theme_path . '/dist/blocks/images-repeater/style.css', array(), $version, 'all' );
			wp_enqueue_script( 'images-repeater-js', $theme_path . '/dist/blocks/images-repeater/script.js', array(), $version, true );
		}
	}
);

add_action(
	'admin_init',
	function () {
		add_editor_style( '/dist/blocks/images-repeater/style.css' );
	}
);
