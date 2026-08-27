<?php

add_action(
	'init',
	function () {
		$editor_handle = 'juniper-cta-editor';
		$version       = wp_get_theme()->get( 'Version' );

		wp_register_script(
			$editor_handle,
			get_template_directory_uri() . '/dist/blocks/cta/edit.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			$version,
			true
		);

		register_block_type( __DIR__, array( 'editor_script' => $editor_handle ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( has_block( 'juniper-theme/cta' ) ) {
			$version    = wp_get_theme()->get( 'Version' );
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'cta-css', $theme_path . '/dist/blocks/cta/style.css', array(), $version, 'all' );
			wp_enqueue_script( 'cta-js', $theme_path . '/dist/blocks/cta/script.js', array(), $version, true );
		}
	}
);

add_action(
	'admin_init',
	function () {
		add_editor_style( '/dist/blocks/cta/style.css' );
	}
);
