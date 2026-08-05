<?php
/**
 * Juniper Theme
 * https://github.com/osomstudio/JuniperTheme
 *
 * @package  WordPress
 * @subpackage  Juniper
 */

$composer_autoload = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
}

require_once 'inc/include.php';

function juniper_theme_enqueue() {
	$version                = wp_get_theme()->get( 'Version' );
	$template_directory_uri = get_template_directory_uri();

	wp_enqueue_style( 'app-css', $template_directory_uri . '/dist/src/css/_app.css', array(), $version );
	wp_enqueue_script( 'app-js', $template_directory_uri . '/dist/src/js/_app.js', array(), $version, true );
}

add_action( 'wp_enqueue_scripts', 'juniper_theme_enqueue' );

add_theme_support( 'editor-styles' );
add_action( 'admin_init', 'juniper_editor_styles', 1000 );
function juniper_editor_styles() {
	add_editor_style( '/dist/src/css/_app.css' );
}

add_action( 'after_setup_theme', 'juniper_theme_supports' );
function juniper_theme_supports() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Enable support for Post Formats.
	add_theme_support(
		'post-formats',
		array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		)
	);

	add_theme_support( 'menus' );
}
