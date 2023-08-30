<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/job-intro')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('job-intro-css', $theme_path . '/blocks/job-intro/style.css', array(), $time, 'all');
            wp_enqueue_script('job-intro-js', $theme_path . '/blocks/job-intro/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/job-intro',
    function ( $context ) {
        return $context;
    }
);
