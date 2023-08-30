<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/breadcrumbs')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('breadcrumbs-css', $theme_path . '/blocks/breadcrumbs/style.css', array(), $time, 'all');
            wp_enqueue_script('breadcrumbs-js', $theme_path . '/blocks/breadcrumbs/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/breadcrumbs',
    function ( $context ) {
        return $context;
    }
);
