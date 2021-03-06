<?php
/**
 * Timber Osom Theme
 * https://github.com/osomstudio/OsomTheme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

require_once 'inc/custom-blocks.php';

function osom_theme_enqueue() {
	wp_enqueue_style( 'app-css', get_template_directory_uri() . '/dist/_app.css', array(), time() );
	wp_enqueue_script( 'app-js', get_template_directory_uri() . '/dist/_app.js', array(), time(), true );
}

add_action( 'wp_enqueue_scripts', 'osom_theme_enqueue' );


/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

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
