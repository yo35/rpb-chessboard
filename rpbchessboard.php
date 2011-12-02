<?php
/*
Plugin Name: RpbChessboard
Description: This plugin allows you to deal with PGN data.
Author: Yoann Le Montagner
Version: 0.1
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
	wp_register_script('rpbchessboard-jschess-script'  , RPBCHESSBOARD_URL.'/chess4web/jschess.js');
	wp_register_script('rpbchessboard-jspgn-script'    , RPBCHESSBOARD_URL.'/chess4web/jspgn.js');
	wp_register_script('rpbchessboard-htmlparser'      , RPBCHESSBOARD_URL.'/chess4web/htmlparser.js');
	wp_register_script('rpbchessboard-chess4web-script', RPBCHESSBOARD_URL.'/chess4web/chess4web.js');
	wp_enqueue_script('rpbchessboard-jschess-script'  );
	wp_enqueue_script('rpbchessboard-jspgn-script'    );
	wp_enqueue_script('rpbchessboard-htmlparser'      );
	wp_enqueue_script('rpbchessboard-chess4web-script');
	include(RPBCHESSBOARD_ABSPATH.'template-script.php');
}

// Enqueue general styles
add_action('wp_print_styles', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	wp_register_style('rpbchessboard-chess4web', RPBCHESSBOARD_URL.'/chess4web/chess4web.css');
	wp_register_style('rpbchessboard-main'     , RPBCHESSBOARD_URL.'/rpbchessboard.css');
	wp_enqueue_style ('rpbchessboard-chess4web');
	wp_enqueue_style ('rpbchessboard-main'     );
}

// Shortcode for diagrams
add_shortcode('pgndiagram', 'rpbchessboard_shortcode_diagram');
function rpbchessboard_shortcode_diagram($atts)
{
	return '<span class="chess4web-template-InlinedPosition"></span>';
}

// Printer
add_shortcode('pgn', 'rpbchessboard_shortcode_printer');
function rpbchessboard_shortcode_printer($atts, $content='')
{
	ob_start();
	include(RPBCHESSBOARD_ABSPATH.'template-pgn.php');
	return ob_get_clean();
}

?>
