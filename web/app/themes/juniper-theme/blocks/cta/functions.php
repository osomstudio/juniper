<?php

add_action(
	'wp_enqueue_scripts',
	function() {
		if ( has_block( 'acf/cta' ) ) {
			$time       = time();
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'cta-css', $theme_path . '/dist/blocks/cta/style.css', array(), $time, 'all' );
			wp_enqueue_script( 'cta-js', $theme_path . '/dist/blocks/cta/script.js', array(), $time, true );
		}
	}
);

add_action(
	'admin_init',
	function() {
		add_editor_style( '/dist/blocks/cta/style.css' );
	}
);

add_filter(
	'timber/acf-gutenberg-blocks-data/cta',
	function( $context ) {
		return $context;
	}
);
