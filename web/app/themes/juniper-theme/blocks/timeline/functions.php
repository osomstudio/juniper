<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/timeline')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('timeline-css', $theme_path . '/dist/blocks/timeline/style.css', array(), $time, 'all');
            wp_enqueue_script('timeline-js', $theme_path . '/dist/blocks/timeline/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/timeline',
    function ( $context ) {
        return $context;
    }
);
