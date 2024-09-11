<?php

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( has_block( 'acf/section-tracker' ) ) {
			$time       = time();
			$theme_path = get_template_directory_uri();

			wp_enqueue_style( 'section-tracker-css', $theme_path . '/dist/blocks/section-tracker/style.css', array(), $time, 'all' );
			wp_enqueue_script( 'section-tracker-js', $theme_path . '/dist/blocks/section-tracker/scripts.js', array(), $time, true );
		}
	}
);

add_filter(
	'timber/acf-gutenberg-blocks-data/section-tracker',
	function ( $context ) {
		return $context;
	}
);
