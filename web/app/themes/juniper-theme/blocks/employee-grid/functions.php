<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/employee-grid')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('employee-grid-css', $theme_path . '/blocks/employee-grid/style.css', array(), $time, 'all');
            wp_enqueue_script('employee-grid-js', $theme_path . '/blocks/employee-grid/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/employee-grid',
    function ( $context ) {
        $context['title'] = "Employees";
        return $context;
    }
);
