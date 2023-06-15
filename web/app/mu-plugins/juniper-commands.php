<?php

/**
 * Plugin Name: Juniper Commands
 * Description: Extends WP-CLI to include commands for easier juniper management
 * Text Domain: juniper-commands
 * License:     MIT License
 *
 * @package Juniper Commands
 */

if ( defined( 'WP_CLI' ) && WP_CLI && class_exists( 'WP_CLI' ) ) {
	if ( ! class_exists( 'Juniper_Commands' ) ) {
		class Juniper_Commands extends WP_CLI_Command {
			private function get_name( $assoc_args ) {
				if ( ! key_exists( 'name', $assoc_args ) ) {
					WP_CLI::error( 'I need to know the name' );
				}

				return $assoc_args['name'];
			}

			private function validate_name( $name ) {
				if ( strlen( $name ) > 20 ) {
					WP_CLI::error( 'This name is to long - Max 20 characters' );
				}

				if ( ! preg_match( '/^[A-Za-z0-9-_ ]+$/i', $name ) ) {
					WP_CLI::error( 'Name can only have: letters, spaces, dashes, floors' );
				}
			}

			private function get_needed_names( $name ) {
				$lowercase_name = strtolower( $name );

				return array(
					'lowercase_name' => $lowercase_name,
					'slug_name'      => str_replace( ' ', '_', $lowercase_name ),
					'rewrite_name'   => str_replace( ' ', '-', $lowercase_name ),
				);
			}

			//TODO: check if cpt and taxonomy already exists

			public function cpt( $args, $assoc_args ) {
				$og_name = $this->get_name( $assoc_args );

				$this->validate_name( $og_name );

				extract( $this->get_needed_names( $og_name ) );

				$replace_array = array(
					array( 'replace_cpt_slug', $slug_name ),
					array( 'replace_cpt_name', $og_name ),
					array( 'replace_rewrite_name', $rewrite_name ),
				);

				$file_contents = file_get_contents( './dev/cpt.txt' );
				foreach ( $replace_array as $search_replace ) {
					$file_contents = str_replace( $search_replace[0], $search_replace[1], $file_contents );
				}

				$new_file = fopen( "./web/app/themes/juniper-theme/inc/Cpt/$slug_name.php", 'w' );
				fwrite( $new_file, $file_contents );
				fclose( $new_file );

				$new_class   = "\$juniper_$slug_name = new \Juniper\cpt\\$slug_name();" . PHP_EOL;
				$include_php = fopen( './web/app/themes/juniper-theme/inc/include.php', 'a' ) or die( 'Unable to open file!' );
				fwrite( $include_php, $new_class );
				fclose( $include_php );

				shell_exec( "phpcbf --standard=WordPress-Extra ./web/app/themes/juniper-theme/inc/Cpt/$slug_name.php" );
				shell_exec( 'phpcbf --standard=WordPress-Extra ./web/app/themes/juniper-theme/inc/include.php' );
			}

			public function taxonomy( $args, $assoc_args ) {
				$og_name = $this->get_name( $assoc_args );

				if ( ! key_exists( 'post', $assoc_args ) ) {
					WP_CLI::error( 'I need to know the name of the custom post type' );
				}

				$post_cpt = $assoc_args['post'];

				$this->validate_name( $og_name );
				$this->validate_name( $post_cpt );

				extract( $this->get_needed_names( $og_name ) );

				$replace_array = array(
					array( 'replace_taxonomy_slug', $slug_name ),
					array( 'replace_taxonomy_name', $og_name ),
					array( 'replace_rewrite_name', $rewrite_name ),
					array( 'selected_post_type', $post_cpt ),
				);

				$file_contents = file_get_contents( './dev/taxonomy.txt' );
				foreach ( $replace_array as $search_replace ) {
					$file_contents = str_replace( $search_replace[0], $search_replace[1], $file_contents );
				}

				$new_file = fopen( "./web/app/themes/juniper-theme/inc/Taxonomies/$slug_name.php", 'w' );
				fwrite( $new_file, $file_contents );
				fclose( $new_file );

				$new_class   = "\$juniper_$slug_name = new \Juniper\Taxonomies\\$slug_name();" . PHP_EOL;
				$include_php = fopen( './web/app/themes/juniper-theme/inc/include.php', 'a' ) or die( 'Unable to open file!' );
				fwrite( $include_php, $new_class );
				fclose( $include_php );

				shell_exec( 'phpcbf --standard=WordPress-Extra ./web/app/themes/juniper-theme/inc/include.php' );
			}
		}

		WP_CLI::add_command( 'add', 'Juniper_Commands' );
	}
}
