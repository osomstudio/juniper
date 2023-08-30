<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/job-facts')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('job-facts-css', $theme_path . '/blocks/job-facts/style.css', array(), $time, 'all');
            wp_enqueue_script('job-facts-js', $theme_path . '/blocks/job-facts/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/job-facts',
    function ( $context ) {
        return $context;
    }
);
