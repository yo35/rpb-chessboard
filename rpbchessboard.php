<?php
/*
Plugin Name: RpbChessboard
Description: This plugin allows you to deal with PGN data.
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

// Global vars
$rpbchessboard_id_counter = 0;
$rpbchessboard_add_debug_tag = true;

// ID generation
function rpbchessboard_make_pgn_id()
{
	global $rpbchessboard_id_counter;
	++$rpbchessboard_id_counter;
	return 'rpbchessboard-pgn-'.get_the_ID().'-'.$rpbchessboard_id_counter;
}

// Simple diagram
add_shortcode('fen', 'rpbchessboard_shortcode_fen');
function rpbchessboard_shortcode_fen($atts, $content)
{
	ob_start();
	include(RPBCHESSBOARD_ABSPATH.'template-fen.php');
	return ob_get_clean();
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
	if(!is_array($atts)) {
		$atts = array();
	}
	if(array_key_exists('hide_annotator', $atts)) {
		$hide_annotator = $atts['hide_annotator']=='true';
	}
	else {
		$hide_annotator = false;
	}
	if(array_key_exists('hide_result', $atts)) {
		$hide_result = $atts['hide_result']=='true';
	}
	else {
		$hide_result = false;
	}
	include(RPBCHESSBOARD_ABSPATH.'template-pgn.php');
	return ob_get_clean();
}

?>
