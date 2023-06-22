<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/project-filter')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('project-filter-css', $theme_path . '/blocks/project-filter/style.css', array(), $time, 'all');
            wp_enqueue_script('project-filter-js', $theme_path . '/blocks/project-filter/script.js', array(), $time, true);

            $attributes = [];
            wp_enqueue_script( 'dashboardBlockFrontendScript', $theme_path . '/blocks/project-filter/build/frontend.js', array(
				'wp-blocks',
				'wp-element',
				'wp-editor',
				'wp-api',
				'wp-element',
				'wp-i18n',
				'wp-polyfill',
				'wp-api-fetch'
			), rand( 0, 9999 ), true );
			wp_localize_script( 'dashboardBlockFrontendScript', 'projectFilterData', $attributes );
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/project-filter',
    function ( $context ) {
        return $context;
    }
);
