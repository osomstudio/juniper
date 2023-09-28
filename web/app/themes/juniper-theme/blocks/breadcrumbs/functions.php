<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/breadcrumbs')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('breadcrumbs-css', $theme_path . '/blocks/breadcrumbs/style.css', array(), $time, 'all');
            wp_enqueue_script('breadcrumbs-js', $theme_path . '/blocks/breadcrumbs/script.js', array(), $time, true);
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/breadcrumbs',
    function ( $context ) {
        
         // title and URL of current page
         $page_title = get_the_title();
         $page_url = get_permalink();

        // title and URL of parent page
        $parent_page_title = '';
        $parent_page_url = '';
        if (is_page() && $post = get_post()) {
            $parent_page_id = $post->post_parent;
            if ($parent_page_id) {
                $parent_page = get_post($parent_page_id);
                $parent_page_title = get_the_title($parent_page);
                $parent_page_url = get_permalink($parent_page);
            }
        }

         // URL of home page
         $home_page_url = home_url();

        // Add pages to context
     $context['home_page_url'] = $home_page_url;
     $context['page_title'] =   $page_title;
     $context['page_url'] =   $page_url;
     $context['parent_page_title'] = $parent_page_title;
     $context['parent_page_url'] = $parent_page_url;

        return $context;
    }
);

