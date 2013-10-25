<?php
/*
Plugin Name: RPB Chessboard
Description: This plugin allows you to print out chess diagrams and PGN-encoded chess games.
Text Domain: rpbchessboard
Author: Yoann Le Montagner
Version: 1.99
*/


// Directories
define('RPBCHESSBOARD_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCHESSBOARD_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR.'/');
define('RPBCHESSBOARD_URL'       , site_url().'/wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR);


// Enable internationalization
load_plugin_textdomain('rpbchessboard', false, RPBCHESSBOARD_PLUGIN_DIR.'/languages/');


// Enqueue scripts
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbchessboard_enqueue_script');
function rpbchessboard_enqueue_script()
{
	wp_enqueue_script('jquery-color'       );
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-dialog'   );
	wp_enqueue_script('jquery-ui-button'   );
	wp_register_script('rpbchessboard-chessjs'    , RPBCHESSBOARD_URL.'/js/chess.js/chess.min.js');
	wp_register_script('rpbchessboard-pgn'        , RPBCHESSBOARD_URL.'/js/pgn.js');
	wp_register_script('rpbchessboard-chesswidget', RPBCHESSBOARD_URL.'/js/chesswidget.js');
	wp_register_script('rpbchessboard-pgnwidget'  , RPBCHESSBOARD_URL.'/js/pgnwidget.js'  );
	wp_register_script('rpbchessboard-main'       , RPBCHESSBOARD_URL.'/js/main.js');
	wp_enqueue_script('rpbchessboard-chessjs'    );
	wp_enqueue_script('rpbchessboard-pgn'        );
	wp_enqueue_script('rpbchessboard-chesswidget');
	wp_enqueue_script('rpbchessboard-pgnwidget'  );
	wp_enqueue_script('rpbchessboard-main'       );

	// Additional scripts for the backend.
	if(is_admin()) {
		wp_enqueue_script('jquery-ui-slider');
	}
}


// Enqueue CSS
add_action(is_admin() ? 'admin_print_styles' : 'wp_print_styles', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	// Enqueue a jQuery CSS file for the jQuery dialog
	global $wp_scripts;
	$ui = $wp_scripts->query('jquery-ui-core');
	$protocol = is_ssl() ? 'https' : 'http';
	wp_register_style('jquery-ui', $protocol.'://code.jquery.com/ui/'.$ui->ver.'/themes/smoothness/jquery-ui.css');
	wp_enqueue_style ('jquery-ui');

	// Local CSS files
	wp_register_style('rpbchessboard-chesswidget', RPBCHESSBOARD_URL.'/css/chesswidget.css');
	wp_register_style('rpbchessboard-pgnwidget'  , RPBCHESSBOARD_URL.'/css/pgnwidget.css'  );
	wp_register_style('rpbchessboard-main'       , RPBCHESSBOARD_URL.'/css/main.css'       );
	wp_enqueue_style ('rpbchessboard-chesswidget');
	wp_enqueue_style ('rpbchessboard-pgnwidget'  );
	wp_enqueue_style ('rpbchessboard-main'       );

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
