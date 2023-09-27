<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/filter')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('filter-css', $theme_path . '/blocks/filter/style.css', array(), $time, 'all');
            wp_enqueue_script('filter-js', $theme_path . '/blocks/filter/script.js', array(), $time, true);

            $attributes = [];
            wp_enqueue_script( 'dashboardBlockFrontendScript', $theme_path . '/blocks/filter/build/frontend.js', array(
				'wp-blocks',
				'wp-element',
				'wp-editor',
				'wp-api',
				'wp-element',
				'wp-i18n',
				'wp-polyfill',
				'wp-api-fetch'
			), rand( 0, 9999 ), true );
			wp_localize_script( 'dashboardBlockFrontendScript', 'filterData', $attributes );
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/filter',
    function ( $context ) {
        $data_arr = array();
        $initial_posts = get_posts(
            array(
                'post_type' => $context['fields']['post_type']
            )
        );

        foreach ($initial_posts as $post) {
            $post->fields = get_fields($post);
            $post->excerpt = get_the_excerpt($post);
            $post->terms = get_the_terms($post, 'project-category') ? get_the_terms($post, 'project-category') : [];
        }

        $data_arr['posts'] = $initial_posts;

        $data_arr['style'] = $context['fields']['style'];

        $data_arr['terms'] = get_terms( 
            array(
                'taxonomy'   => 'project-category',
                'hide_empty' => false,
            )
        );

        $context['data'] = json_encode($data_arr);
        return $context;
    }
);

function acf_load_post_type_field_choices( $field ) {
    // Reset choices
    $field['choices'] = get_post_types();
    
    return $field;
    
}

add_filter('acf/load_field/name=post_type', 'acf_load_post_type_field_choices');
