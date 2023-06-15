<?php

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    if ( ! class_exists( 'CPT_Commands' ) ) {
        class CPT_Commands extends WP_CLI_Command {
            public function cpt( $args, $assoc_args ) {
                if ( key_exists(0, $args)) {
                    WP_CLI::error( 'I need to know the name of the custom post type' );
                }

                $og_name = $args[0];

                //Check if max 20 characters
                //Check if only letters, spaces, dashes, floors

                $lowercase_name = strtolower($og_name);
                $slug_name = preg_replace(' ', '_', $lowercase_name);
                $rewrite_name = preg_replace(' ', '-', $lowercase_name);

                if ( key_exists('add', $assoc_args) && ! $assoc_args['add'] ) {
                    //check if already exists
                    $this->add_cpt($og_name, $slug_name, $rewrite_name);
                } else {
                    WP_CLI::error( 'I need to know what to do with this post type' );
                }
            }

            private function add_cpt($og_name, $slug_name, $rewrite_name) {
                $cpt_file_contents = file_get_contents('./dev/cpt.txt');

                $search_slug = "replace_cpt_slug";
                $cpt_file_contents = preg_replace($search_slug, $slug_name, $cpt_file_contents);

                $search_name = "replace_cpt_name";
                $cpt_file_contents = preg_replace($search_name, $og_name, $cpt_file_contents);

                $search_rewrite = 'replace_rewrite_name';
                $cpt_file_contents = preg_replace($search_rewrite, $rewrite_name, $cpt_file_contents);

                $new_cpt_file = fopen("/web/app/themes/juniper-theme/inc/cpt/$slug_name.php", "w");
                fwrite($new_cpt_file, $cpt_file_contents);
                fclose($new_cpt_file);

                $new_class_initialization = "\$juniper_$slug_name = new \Juniper\cpt\\$slug_name();";
                $include_php = fopen("/web/app/themes/juniper-theme/inc/cpt/include.php", "a") or die("Unable to open file!");
                fwrite($include_php, $new_class_initialization);
                fclose($include_php);
            }
        }

        WP_CLI::add_commands('cpt', 'CPT_Commands');
    }
}