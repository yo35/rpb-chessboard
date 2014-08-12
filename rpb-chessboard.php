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
Version: 3.1
*/


// Directories
define('RPBCHESSBOARD_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCHESSBOARD_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR.'/');
define('RPBCHESSBOARD_URL'       , site_url().'/wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR);


// Enable localization
load_plugin_textdomain('rpbchessboard', false, RPBCHESSBOARD_PLUGIN_DIR . '/languages/');


// JavaScript
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbchessboard_init_scripts');
function rpbchessboard_init_scripts()
{
	require_once(RPBCHESSBOARD_ABSPATH . 'wp/scripts.php');
	RPBChessboardScripts::register();
}


// CSS
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbchessboard_init_style_sheets');
function rpbchessboard_init_style_sheets()
{
	require_once(RPBCHESSBOARD_ABSPATH . 'wp/stylesheets.php');
	RPBChessboardStyleSheets::register();
}


// Administration pages
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
