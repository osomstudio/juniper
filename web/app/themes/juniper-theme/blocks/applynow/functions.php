<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/applynow')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('applynow-css', $theme_path . '/blocks/applynow/style.css', array(), $time, 'all');
            wp_enqueue_script('applynow-js', $theme_path . '/blocks/applynow/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/applynow',
    function ( $context ) {
        return $context;
    }
);
