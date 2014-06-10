<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/

/*
Plugin Name: RPB Chessboard
Plugin URI: http://yo35.org/rpb-chessboard/
Description: This plugin allows you to typeset and display chess diagrams and PGN-encoded chess games.
Text Domain: rpbchessboard
Author: Yoann Le Montagner
Author URI: http://yo35.org/
License: GPLv3
Version: 2.4.3
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
	$chessWidgetDeps = array('jquery-ui-widget', 'jquery-ui-selectable', 'jquery-ui-draggable', 'jquery-ui-droppable');
	$pgnWidgetDeps   = array('jquery-color', 'jquery-ui-resizable', 'jquery-ui-dialog');
	wp_register_script('rpbchessboard-chessjs'    , RPBCHESSBOARD_URL.'/third-party-libs/chess-js/chess.min.js');
	wp_register_script('rpbchessboard-pgn'        , RPBCHESSBOARD_URL.'/js/pgn.js');
	wp_register_script('rpbchessboard-chesswidget', RPBCHESSBOARD_URL.'/js/uichess-chessboard.js', $chessWidgetDeps);
	wp_register_script('rpbchessboard-pgnwidget'  , RPBCHESSBOARD_URL.'/js/uichess-chessgame.js', $pgnWidgetDeps);
	wp_register_script('rpbchessboard-main'       , RPBCHESSBOARD_URL.'/js/main.js');
	wp_enqueue_script('rpbchessboard-chessjs'    );
	wp_enqueue_script('rpbchessboard-pgn'        );
	wp_enqueue_script('rpbchessboard-chesswidget');
	wp_enqueue_script('rpbchessboard-pgnwidget'  );
	wp_enqueue_script('rpbchessboard-main'       );

	// Additional scripts for the backend.
	if(is_admin()) {
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-tabs'  );
	}
}


// Enqueue CSS
add_action(is_admin() ? 'admin_print_styles' : 'wp_print_styles', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	// jQuery CSS
	wp_enqueue_style('wp-jquery-ui-dialog');

	// Local CSS files
	wp_register_style('rpbchessboard-chesswidget', RPBCHESSBOARD_URL.'/css/uichess-chessboard.css');
	wp_register_style('rpbchessboard-pgnwidget'  , RPBCHESSBOARD_URL.'/css/uichess-chessgame.css');
	wp_register_style('rpbchessboard-main'       , RPBCHESSBOARD_URL.'/css/main.css'       );
	wp_enqueue_style ('rpbchessboard-chesswidget');
	wp_enqueue_style ('rpbchessboard-pgnwidget'  );
	wp_enqueue_style ('rpbchessboard-main'       );

	// Additional CSS for the back-end
	if(is_admin()) {
		wp_register_style('rpbchessboard-jquery-ui', RPBCHESSBOARD_URL.'/third-party-libs/jquery/jquery-ui-1.10.4.custom.min.css');
		wp_register_style('rpbchessboard-admin'    , RPBCHESSBOARD_URL.'/css/admin.css');
		wp_enqueue_style ('rpbchessboard-jquery-ui');
		wp_enqueue_style ('rpbchessboard-admin'    );
	}
}


// Plugin administration pages
if(is_admin()) {
	add_action('admin_menu', 'rpbchessboard_init_admin_pages');
	function rpbchessboard_init_admin_pages()
	{
		require_once(RPBCHESSBOARD_ABSPATH . 'wp/adminpages.php');
		RPBChessboardAdminPages::register();
	}
}


// Shortcodes
if(!is_admin()) {
	add_action('init', 'rpbchessboard_init_shortcodes');
	function rpbchessboard_init_shortcodes()
	{
		require_once(RPBCHESSBOARD_ABSPATH . 'wp/shortcodes.php');
		RPBChessboardShortcodes::register();
	}
}


// Custom buttons in the text editors.
if(is_admin()) {
	add_action('admin_print_footer_scripts', 'rpbchessboard_admin_customize_editors');
	function rpbchessboard_admin_customize_editors()
	{
		require_once(RPBCHESSBOARD_ABSPATH . 'controllers/editors.php');
		$controller = new RPBChessboardControllerEditors();
		$controller->run();
	}
}
