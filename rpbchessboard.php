<?php
/*
Plugin Name: RPB Chessboard
Description: This plugin allows you to print out chess diagrams and PGN-encoded chess games.
Author: Yoann Le Montagner
Version: 1.4
*/


// Debug option (comment to release)
//define('RPBCHESSBOARD_DEBUG', 1);


// Directories
define('RPBCHESSBOARD_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCHESSBOARD_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR.'/');
define('RPBCHESSBOARD_URL'       , site_url().'/wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR);


// Enable internationalization
load_plugin_textdomain('rpbchessboard', false, RPBCHESSBOARD_PLUGIN_DIR.'/languages/');


// Enqueue scripts
add_action('wp_enqueue_scripts', 'rpbchessboard_enqueue_script');
function rpbchessboard_enqueue_script()
{
	wp_register_script('rpbchessboard-jschess-script'        , RPBCHESSBOARD_URL.'/jschesslib/jschess.js'        );
	wp_register_script('rpbchessboard-jschesspgn-script'     , RPBCHESSBOARD_URL.'/jschesslib/jschesspgn.js'     );
	wp_register_script('rpbchessboard-jschessrenderer-script', RPBCHESSBOARD_URL.'/jschesslib/jschessrenderer.js');
	wp_enqueue_script('jquery-color'       );
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-dialog'   );
	wp_enqueue_script('jquery-ui-button'   );
	wp_enqueue_script('rpbchessboard-jschess-script'        );
	wp_enqueue_script('rpbchessboard-jschesspgn-script'     );
	wp_enqueue_script('rpbchessboard-jschessrenderer-script');
}


// Enqueue CSS
add_action('wp_print_styles'   , 'rpbchessboard_enqueue_css');
add_action('admin_print_styles', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	// Enqueue a jQuery CSS file for the jQuery dialog
	global $wp_scripts;
	$ui = $wp_scripts->query('jquery-ui-core');
	$protocol = is_ssl() ? 'https' : 'http';
	wp_register_style('jquery-ui', $protocol.'://code.jquery.com/ui/'.$ui->ver.'/themes/smoothness/jquery-ui.css');
	wp_enqueue_style ('jquery-ui');

	// Local CSS files
	wp_register_style('rpbchessboard-jschesslib', RPBCHESSBOARD_URL.'/jschesslib/jschesslib.css');
	wp_register_style('rpbchessboard-main'      , RPBCHESSBOARD_URL.'/css/rpbchessboard.css');
	wp_enqueue_style ('rpbchessboard-jschesslib');
	wp_enqueue_style ('rpbchessboard-main'      );


	// Additional CSS for the back-end
	if(is_admin()) {
		wp_register_style('rpbchessboard-admin', RPBCHESSBOARD_URL.'/css/admin.css');
		wp_enqueue_style ('rpbchessboard-admin');
	}
}


// Load either the back-end or the front-end depending on the context
if(is_admin()) {
	require_once(RPBCHESSBOARD_ABSPATH.'backend.php');
}
else {
	require_once(RPBCHESSBOARD_ABSPATH.'frontend.php');
}
