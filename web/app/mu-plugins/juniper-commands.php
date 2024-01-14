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
			private $mu_plugins;

			public function __construct() {
				$this->mu_plugins = dirname( __FILE__ );
			}

			private function get_name( $assoc_args ) {
				if ( ! key_exists( 'name', $assoc_args ) ) {
					WP_CLI::error( 'I need to know the name' );
				}

				return $assoc_args['name'];
			}

			private function validate_name( $name ) {
				if ( strlen( $name ) > 20 ) {
					WP_CLI::error( 'The name is too long - Max 20 characters' );
				}

				if ( ! preg_match( '/^[A-Za-z0-9-_ ]+$/i', $name ) ) {
					WP_CLI::error( 'Name can only have: letters, spaces, dashes, floors' );
				}
			}

			private function get_needed_names( $name ) {
				$lowercase_name = strtolower( $name );

				return array(
					'lowercase_name' => $lowercase_name,
					'slug_name'      => str_replace( ' ', '-', $lowercase_name ),
					'rewrite_name'   => str_replace( ' ', '-', $lowercase_name ),
				);
			}

			/**
			 * Adds a new custom post type.
			 *
			 * ## OPTIONS
			 *
			 * [--name]
			 * : The name of custom post type you want
			 *
			 * ## EXAMPLES
			 *
			 *     wp add cpt --name="Products"
			 *
			 * @when before_wp_load
			 */
			public function cpt( $args, $assoc_args ) {
				$og_name = $this->get_name( $assoc_args );

				$this->validate_name( $og_name );

				extract( $this->get_needed_names( $og_name ) ); // phpcs:ignore

				$class_name = ucfirst( $slug_name );

				if ( file_exists( $this->mu_plugins . "/../themes/juniper-theme/inc/Cpt/$class_name.php" ) ) {
					WP_CLI::error( 'Custom post type already exists' );
				}

				$replace_array = array(
					array( 'replace_cpt_slug', $class_name ),
					array( 'replace_cpt_name', $og_name ),
					array( 'replace_rewrite_name', $rewrite_name ),
				);

				$file_contents = file_get_contents( $this->mu_plugins . '/../../../dev/cpt.txt' ); // phpcs:ignore
				foreach ( $replace_array as $search_replace ) {
					$file_contents = str_replace( $search_replace[0], $search_replace[1], $file_contents );
				}

				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/inc/Cpt/$class_name.php", $file_contents );

				$new_class = "\$juniper_$slug_name = new \Juniper\Cpt\\$class_name();" . PHP_EOL;
				file_put_contents( $this->mu_plugins . '/../themes/juniper-theme/inc/include.php', $new_class, FILE_APPEND );

				shell_exec( 'phpcbf --standard=WordPress-Extra ' . $this->mu_plugins . "/../themes/juniper-theme/inc/Cpt/$slug_name.php" );
				shell_exec( 'phpcbf --standard=WordPress-Extra ' . $this->mu_plugins . '/../themes/juniper-theme/inc/include.php' );
			}

			/**
			 * Adds a new taxonomy and attaches it to a post type.
			 *
			 * ## OPTIONS
			 *
			 * [--name]
			 * : The name of custom taxonomy you want to add
			 *
			 * [--post]
			 * : The name of the custom post type that this taxonomy should be attached to
			 *
			 * ## EXAMPLES
			 *
			 *     wp add taxonomy --name="Categories" --post="products"
			 *
			 * @when before_wp_load
			 */
			public function taxonomy( $args, $assoc_args ) {
				$og_name = $this->get_name( $assoc_args );

				if ( ! key_exists( 'post', $assoc_args ) ) {
					WP_CLI::error( 'I need to know the name of the custom post type' );
				}

				$post_cpt = $assoc_args['post'];

				$this->validate_name( $og_name );
				$this->validate_name( $post_cpt );

				extract( $this->get_needed_names( $og_name ) );

				if ( file_exists( $this->mu_plugins . "/../themes/juniper-theme/inc/Taxonomies/$slug_name.php" ) ) {
					WP_CLI::error( 'Taxonomy already exists' );
				}

				$replace_array = array(
					array( 'replace_taxonomy_slug', $slug_name ),
					array( 'replace_taxonomy_name', $og_name ),
					array( 'replace_rewrite_name', $rewrite_name ),
					array( 'selected_post_type', $post_cpt ),
				);

				$file_contents = file_get_contents( $this->mu_plugins . '/../../../dev/taxonomy.txt' );
				foreach ( $replace_array as $search_replace ) {
					$file_contents = str_replace( $search_replace[0], $search_replace[1], $file_contents );
				}

				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/inc/Taxonomies/$slug_name.php", $file_contents );

				$new_class = "\$juniper_$slug_name = new \Juniper\Taxonomies\\$slug_name();" . PHP_EOL;
				file_put_contents( $this->mu_plugins . '/../themes/juniper-theme/inc/include.php', $new_class, FILE_APPEND );

				shell_exec( 'phpcbf --standard=WordPress-Extra ' . $this->mu_plugins . '/../themes/juniper-theme/inc/include.php' );
			}


			/**
			 * Adds a Gutenberg block.
			 *
			 * ## OPTIONS
			 *
			 * [--name]
			 * : The name of the new Gutenberg block
			 *
			 * [--keywords]
			 * : Optional keywords of the Gutenberg block
			 *
			 * [--description]
			 * : Optional description of the Gutenberg block
			 *
			 * ## EXAMPLES
			 *     wp add block --name="Reviews"
			 *     wp add block --name="Reviews" --keywords="people,stars,quotes"
			 *     wp add block --name="Reviews" --keywords="people,stars,quotes" --description="This section shows the three newest reviews"
			 *
			 * @when before_wp_load
			 */
			public function block( $args, $assoc_args ) {
				$og_name = $this->get_name( $assoc_args );

				if ( ! preg_match( '/^[A-Za-z0-9-_ ]+$/i', $og_name ) ) {
					WP_CLI::error( 'Name can only have: letters, spaces, dashes, floors' );
				}

				$lowercase_name = strtolower( $og_name );
				$slug_name      = str_replace( ' ', '-', $lowercase_name );

				if ( file_exists( "../themes/juniper-theme/blocks/$slug_name/" ) ) {
					WP_CLI::error( 'Block already exists' );
				}

				mkdir( $this->mu_plugins . "/../themes/juniper-theme/blocks/$slug_name/", 0755 );

				$keywords = '';
				if ( key_exists( 'keywords', $assoc_args ) ) {
					$keywords = $assoc_args['keywords'];
				}

				$description = '';
				if ( key_exists( 'description', $assoc_args ) ) {
					$description = $assoc_args['description'];
				}

				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/blocks/$slug_name/$slug_name-script.js", '' );
				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/blocks/$slug_name/$slug_name-ajax.js", '' );

				$css = ".$slug_name {}\n\n" .
				"body.wp-admin {\n" .
				"\t.$slug_name {}\n" .
				'}';
				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/blocks/$slug_name/$slug_name-style.scss", $css );

				$php = "<?php\n\n" .
				"add_action('wp_enqueue_scripts', function() {\n" .
				"\tif (has_block('acf/$slug_name')) {\n" .
				"\t\$time = time();\n" .
				"\t\$theme_path = get_template_directory_uri();\n\n" .
				"\t\twp_enqueue_style('$slug_name-css', \$theme_path . '/dist/$slug_name-style.css', array(), \$time, 'all');\n" .
				"\t\twp_enqueue_script('$slug_name-js', \$theme_path . '/dist/$slug_name-script.js', array(), \$time, true);\n" .
				"\t}\n" .
				"});\n\n" .
				"add_filter(\n" .
				"\t'timber/acf-gutenberg-blocks-data/$slug_name',\n" .
				"\tfunction( \$context ) {\n" .
				"\treturn \$context;\n" .
				'});';
				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/blocks/$slug_name/functions.php", $php );

				$html = "{#\n" .
				"\tTitle: $og_name\n" .
				"\tDescription: $description\n" .
				"\tCategory: formatting\n" .
				"\tIcon: admin-comments\n" .
				"\tKeywords: $keywords\n" .
				"\tMode: edit\n" .
				"\tAlign: full\n" .
				"\tPostTypes: page post\n" .
				"\tSupportsAlign: left right full\n" .
				"\tSupportsMode: true\n" .
				"\tSupportsMultiple: true\n" .
				'#}';
				file_put_contents( $this->mu_plugins . "/../themes/juniper-theme/views/blocks/$slug_name.twig", $html );

				shell_exec( 'phpcbf --standard=WordPress-Extra ' . $this->mu_plugins . "/../themes/juniper-theme/Blocks/$slug_name/functions.php" );
			}
		}

		WP_CLI::add_command( 'add', 'Juniper_Commands' );
	}
}
