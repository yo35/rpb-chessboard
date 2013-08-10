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
	wp_register_script('rpbchessboard-htmlparser'            , RPBCHESSBOARD_URL.'/jschesslib/htmlparser.js'     );
	wp_register_script('rpbchessboard-jschess-script'        , RPBCHESSBOARD_URL.'/jschesslib/jschess.js'        );
	wp_register_script('rpbchessboard-jschesspgn-script'     , RPBCHESSBOARD_URL.'/jschesslib/jschesspgn.js'     );
	wp_register_script('rpbchessboard-jschessrenderer-script', RPBCHESSBOARD_URL.'/jschesslib/jschessrenderer.js');
	wp_enqueue_script('jquery-color'       );
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-dialog'   );
	wp_enqueue_script('jquery-ui-button'   );
	wp_enqueue_script('rpbchessboard-htmlparser'            );
	wp_enqueue_script('rpbchessboard-jschess-script'        );
	wp_enqueue_script('rpbchessboard-jschesspgn-script'     );
	wp_enqueue_script('rpbchessboard-jschessrenderer-script');
}

// Enqueue general styles
add_action('wp_print_styles', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	global $wp_scripts;
	$ui = $wp_scripts->query('jquery-ui-core');
	$protocol = is_ssl() ? 'https' : 'http';
	wp_register_style('jquery-ui', $protocol.'://code.jquery.com/ui/'.$ui->ver.'/themes/smoothness/jquery-ui.css');
	wp_enqueue_style ('jquery-ui');

	wp_register_style('rpbchessboard-jschesslib', RPBCHESSBOARD_URL.'/jschesslib/jschesslib.css');
	wp_register_style('rpbchessboard-main'      , RPBCHESSBOARD_URL.'/rpbchessboard.css');
	wp_enqueue_style ('rpbchessboard-jschesslib');
	wp_enqueue_style ('rpbchessboard-main'      );
}

// Administration page
add_action('admin_menu', 'rpbchessboard_build_admin_menu');
function rpbchessboard_build_admin_menu()
{
	require_once(RPBCHESSBOARD_ABSPATH.'admin.php');
	add_options_page(
		__('Chess games and diagrams', 'rpbchessboard'),
		__('Chess games and diagrams', 'rpbchessboard'),
		'manage_options', 'rpbchessboard-admin', 'rpbchessboard_admin_menu'
	);
}

// Short-code [fen][/fen]
add_shortcode('fen', 'rpbchessboard_shortcode_fen');
function rpbchessboard_shortcode_fen($atts, $content)
{
	ob_start();
	include(RPBCHESSBOARD_ABSPATH.'template-init.php');
	include(RPBCHESSBOARD_ABSPATH.'template-fen.php');
	return ob_get_clean();
}

// Short-code [pgndiagram]
add_shortcode('pgndiagram', 'rpbchessboard_shortcode_diagram');
function rpbchessboard_shortcode_diagram($atts)
{
	return '<span class="jsChessLib-anchor-diagram"></span>';
}

// Short-code [pgn][/pgn]
add_shortcode('pgn', 'rpbchessboard_shortcode_pgn');
function rpbchessboard_shortcode_pgn($atts, $content='')
{
	ob_start();
	include(RPBCHESSBOARD_ABSPATH.'template-init.php');
	include(RPBCHESSBOARD_ABSPATH.'template-pgn.php');
	return ob_get_clean();
}
