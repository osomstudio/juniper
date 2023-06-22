<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/servicesgrid')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('servicesgrid-css', $theme_path . '/blocks/servicesgrid/style.css', array(), $time, 'all');
            wp_enqueue_script('servicesgrid-js', $theme_path . '/blocks/servicesgrid/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/servicesgrid',
    function ( $context ) {
        return $context;
    }
);
