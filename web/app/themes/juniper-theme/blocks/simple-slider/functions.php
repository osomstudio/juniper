<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/simple-slider')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('simple-slider-css', $theme_path . '/blocks/simple-slider/style.css', array(), $time, 'all');
            wp_enqueue_script('simple-slider-js', $theme_path . '/blocks/simple-slider/script.js', array(), $time, true);
            wp_enqueue_script('simple-slider-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), $time, false);

            
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/simple-slider',
    function ( $context ) {
        
        return $context;
    }
);
