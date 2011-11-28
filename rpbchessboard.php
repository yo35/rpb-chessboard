<?php
/*
Plugin Name: RpbChessboard
Description: This plugin allows you to deal with PGN data.
Author: Yoann Le Montagner
Version: 0.1
*/

// Directories
define('RPBCHESSBOARD_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCHESSBOARD_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR.'/');
define('RPBCHESSBOARD_URL'       , site_url().'/wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR);

// Enqueue scripts
add_action('wp_enqueue_scripts', 'rpbchessboard_enqueue_script');
function rpbchessboard_enqueue_script()
{
	wp_register_script('rpbchessboard-jschess-script'  , RPBCHESSBOARD_URL.'/chess4web/jschess.js');
	wp_register_script('rpbchessboard-jspgn-script'    , RPBCHESSBOARD_URL.'/chess4web/jspgn.js');
	wp_register_script('rpbchessboard-chess4web-script', RPBCHESSBOARD_URL.'/chess4web/chess4web.js');
	wp_enqueue_script('rpbchessboard-jschess-script'  );
	wp_enqueue_script('rpbchessboard-jspgn-script'    );
	wp_enqueue_script('rpbchessboard-chess4web-script');
	echo '<script type="text/javascript">';
	echo 'function chess4webInit() {';
	echo '  chess4webBaseURL = "'.RPBCHESSBOARD_URL.'/chess4web/sprite/";';
	echo '}';
	echo '</script>';
}

// Enqueue general styles
add_action('wp_print_styles', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	wp_register_style('rpbchessboard-chess4web', RPBCHESSBOARD_URL.'/chess4web/chess4web.css');
	wp_enqueue_style ('rpbchessboard-chess4web');
}

// Printer
add_shortcode('pgn', 'rpbchessboard_shortcode_printer');
function rpbchessboard_shortcode_printer($atts, $content='')
{
	static $id_counter = 0;
	static $add_debug_tag = true;
	++$id_counter;
	ob_start();
	if($add_debug_tag) {
		echo '<pre id="chess4web-debug"></pre>';
		$add_debug_tag = false;
	}
	$content = preg_replace('/<[^>]*>/', '', $content);
	echo '<div>';
	echo '<pre class="chess4web-pgn" id="rpchessboard_pgn_'.$id_counter.'">';
	echo $content;
	echo '</pre>';
	echo '<div class="chess4web-javascript-warning">No javascript</div>';
	echo '<div class="chess4web-out chess4web-hide-this" id="rpchessboard_pgn_'.$id_counter.'">';
	echo '<p>White name: <span class="chess4web-template-WhiteFullName"></span></p>';
	echo '<p>Black name: <span class="chess4web-template-BlackFullName"></span></p>';
	echo '</div>';
	echo '</div>';
	return ob_get_clean();
}
