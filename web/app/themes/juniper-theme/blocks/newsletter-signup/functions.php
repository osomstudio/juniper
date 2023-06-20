<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/newsletter-signup')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('newsletter-signup-css', $theme_path . '/dist/blocks/newsletter-signup/style.css', array(), $time, 'all');
            wp_enqueue_script('newsletter-signup-js', $theme_path . '/dist/blocks/newsletter-signup/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/newsletter-signup',
    function ( $context ) {
        return $context;
    }
);
