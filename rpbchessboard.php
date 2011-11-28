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

// Prepare script enqueuing
add_action('wp_enqueue_scripts', 'rpbchessboard_enqueue_script');
function rpbchessboard_enqueue_script()
{
	wp_register_script('rpbchessboard-pgn4web-script', RPBCHESSBOARD_URL.'/pgn4web/pgn4web.js');
	wp_enqueue_script('rpbchessboard-pgn4web-script');
}

// Printer
add_shortcode('pgn', 'rpbchessboard_shortcode_printer');
function rpbchessboard_shortcode_printer($atts, $content='')
{
	ob_start();
	//echo 'Bonjour';
	//var_dump($atts);
	//echo 'Content :';
	//var_dump($content);
	echo '<div>';
	echo '<form style="display: none;"><textarea style="display: none;" id="pgnText">';
	echo $content;
	echo '</textarea></form>';
	echo '<div id="GameWhite"></div>';
	echo '<div id="GameWhite"></div>';
	echo '</div>';
	return ob_get_clean();
}
