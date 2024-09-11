<?php

add_action(
	'wp_enqueue_scripts',
	function() {
		if ( has_block( 'acf/header' ) ) {
			$time       = time();
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'header-css', $theme_path . '/dist/blocks/header/style.css', array(), $time, 'all' );
			wp_enqueue_script( 'header-js', $theme_path . '/dist/blocks/header/script.js', array(), $time, true );
		}
	}
);

add_filter(
	'timber/acf-gutenberg-blocks-data/header',
	function( $context ) {
		return $context;
	}
);
