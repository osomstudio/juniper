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
	$timber = new Timber\Timber();
}

require_once 'inc/include.php';



/**
 * @param string $scssFile the File we want to watch
 * @param bool $is_import the watched file is a file that gets importet in scss via @import
 * @param string $fileToCombile the file that needs to be recombiled when the $scssFile file changes
 */

function check_for_recompile( string $scssFile, bool $is_import = false, string $fileToCombile=''){
    $css_file = __DIR__ . '/src/css/theme.min.css';
    $map_file = 'style.map';

    if(!file_exists($scssFile)){

        // show message for Administrators
        if(function_exists('current_user_can')){
            if(current_user_can('Administrator')){
                $style="position:fixed; top: 0; left: 0; right: 0; background: red; color: white; text-align: center; padding: 0.5rem;";
                echo '<div style="'.$style.'">'.$scssFile . ' - file not found in scss compiler'.'</div>';
            }
        }
        return;
    };

    
    if( filemtime($scssFile) > filemtime($css_file) || filesize($css_file) == 0) {

        try {
            $wp_root_path = str_replace('/wp-content/themes', '', get_theme_root());
            $compiler = new ScssPhp\ScssPhp\Compiler();
            $compiler->setImportPaths(__DIR__ . '/scss');
            $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
            $compiler->setSourceMap(ScssPhp\ScssPhp\Compiler::SOURCE_MAP_FILE);
            $compiler->setSourceMapOptions([
                'sourceMapURL' =>  get_stylesheet_directory_uri() . '/style.map',
                'sourceMapBasepath' => get_stylesheet_directory_uri(),//$wp_root_path
            ]);

            if(true === $is_import){
                $scss_raw_string = file_get_contents($fileToCombile);
            }else{
                $scss_raw_string = file_get_contents($scssFile);
            }

            $result =  $compiler->compileString($scss_raw_string);

            if(!!$result){
                file_put_contents($map_file, $result->getSourceMap());
                file_put_contents($css_file, $result->getCss());
            }

        } catch(\Exception $e){
            // show message for Administrators
            if(function_exists('current_user_can')){
                if(current_user_can('administrator')){
                    $style="position:fixed; top: 0; left: 0; right: 0; background: red; color: white; text-align: center; padding: 0.5rem; z-index: 999999999;";
                    echo '<div style="'.$style.'">scssphp: Unable to compile content: '.$e->getMessage().'</div>';
                }
            }
        }
    }
}

function juniper_theme_enqueue() {
	$refresh_cache_time = time();
	wp_enqueue_style( 'tailwind-css', get_template_directory_uri() . '/src/css/_tailwindStyles.css', array(), $refresh_cache_time );
	wp_enqueue_script( 'app-js', get_template_directory_uri() . '/dist/src/js/_app.js', array(), $refresh_cache_time, true );

    check_for_recompile( __DIR__ . '/src/scss/_project.scss', true, __DIR__ . '/src/scss/_project.scss');
	wp_enqueue_style( 'theme-css', get_template_directory_uri() . '/src/css/theme.min.css', array(), $refresh_cache_time );

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
$site = new StarterSite();

add_theme_support( 'custom-logo' );

add_filter( 'timber/context', 'wps_add_to_context' );
function wps_add_to_context( $context ) {

    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_url( $custom_logo_id , 'full' );
    $context['logo'] = $logo;

    $upload_dir = wp_upload_dir();
    $context['uploads'] = $upload_dir;

    $context['theme_dir'] = get_stylesheet_directory_uri();

    //$context['menu'] = new \Timber\Menu( 'primary-menu' );

    return $context;
}
