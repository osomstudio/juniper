<?php
/**
 * Timber Juniper Theme
 * https://github.com/osomstudio/JuniperTheme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	Timber\Timber::init();
}

require_once 'inc/include.php';

function juniper_theme_enqueue() {
	$refresh_cache_time     = time();
	$template_directory_uri = get_template_directory_uri();

	wp_enqueue_style( 'app-css', $template_directory_uri . '/dist/src/css/_app.css', array(), $refresh_cache_time );
	wp_enqueue_style( 'style-editor-css', $template_directory_uri . '/dist/src/css/_style-editor.css', array(), $refresh_cache_time );
	wp_enqueue_script( 'app-js', $template_directory_uri . '/dist/src/js/_app.js', array(), $refresh_cache_time, true );
}

add_action( 'wp_enqueue_scripts', 'juniper_theme_enqueue' );

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function ( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


//StarterSite class
require_once 'class-startersite.php';
new StarterSite();


add_theme_support( 'editor-styles' );
add_action( 'admin_init', 'juniper_editor_styles', 1000 );
function juniper_editor_styles() {
	// Enqueue editor styles.
	add_editor_style( '/dist/src/css/_style-editor.css' );
}

add_action(
	'init',
	function() {
		register_block_style(
			'core/button',
			array(
				'name'  => 'arrowed',
				'label' => __( 'Arrowed', 'viking' ),
			)
		);

		register_block_style(
			'core/button',
			array(
				'name'  => 'arrowed-external',
				'label' => __( 'Arrowed external', 'viking' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'mobile',
				'label' => __( 'Mobile', 'viking' ),
			)
		);
	}
);
