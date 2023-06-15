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
			public function add( $args, $assoc_args ) {
				if ( ! key_exists( 'name', $assoc_args ) ) {
					WP_CLI::error( 'I need to know the name of the custom post type' );
				}

				$og_name = $assoc_args['name'];

				if ( strlen( $og_name ) > 20 ) {
					WP_CLI::error( 'This name is to long - Max 20 characters' );
				}

				if ( ! preg_match( '/^[A-Za-z0-9-_ ]+$/i', $og_name ) ) {
					WP_CLI::error( 'CPT name can only have: letters, spaces, dashes, floors' );
				}

				$lowercase_name = strtolower( $og_name );
				$slug_name      = str_replace( ' ', '_', $lowercase_name );
				$rewrite_name   = str_replace( ' ', '-', $lowercase_name );

				$cpt_file_contents = file_get_contents( './dev/cpt.txt' );

				$replace_array = array(
					array( 'replace_cpt_slug', $slug_name ),
					array( 'replace_cpt_name', $og_name ),
					array( 'replace_rewrite_name', $rewrite_name ),
				);

				foreach ( $replace_array as $search_replace ) {
					$cpt_file_contents = str_replace( $search_replace[0], $search_replace[1], $cpt_file_contents );
				}

				$new_cpt_file = fopen( "./web/app/themes/juniper-theme/inc/Cpt/$slug_name.php", 'w' );
				fwrite( $new_cpt_file, $cpt_file_contents );
				fclose( $new_cpt_file );

				$new_class_initialization = "\$juniper_$slug_name = new \Juniper\cpt\\$slug_name();" . PHP_EOL;
				$include_php              = fopen( './web/app/themes/juniper-theme/inc/include.php', 'a' ) or die( 'Unable to open file!' );
				fwrite( $include_php, $new_class_initialization );
				fclose( $include_php );

				shell_exec( "phpcbf --standard=WordPress-Extra ./web/app/themes/juniper-theme/inc/Cpt/$slug_name.php" );
				shell_exec( 'phpcbf --standard=WordPress-Extra ./web/app/themes/juniper-theme/inc/include.php' );
			}
		}

		WP_CLI::add_command( 'cpt', 'Juniper_Commands' );
	}
}
