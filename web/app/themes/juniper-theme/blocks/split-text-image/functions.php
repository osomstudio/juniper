<?php

add_action(
    'wp_enqueue_scripts', function () {

        if (has_block('acf/split-text-image')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('split-text-image-css', $theme_path . '/dist/blocks/split-text-image/style.css', array(), $time, 'all');
            wp_enqueue_script('split-text-image-js', $theme_path . '/dist/blocks/split-text-image/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/split-text-image',
    function ( $context ) {
        return $context;
    }
);
