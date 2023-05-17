<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/slider')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('slider-css', $theme_path . '/dist/blocks/slider/style.css', array(), $time, 'all');
            wp_enqueue_script('slider-js', $theme_path . '/dist/blocks/slider/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/slider',
    function ( $context ) {
        return $context;
    }
);
