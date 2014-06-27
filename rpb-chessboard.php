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
Version: 2.99.1
*/


// Directories
define('RPBCHESSBOARD_PLUGIN_DIR', basename(dirname(__FILE__)));
define('RPBCHESSBOARD_ABSPATH'   , ABSPATH.'wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR.'/');
define('RPBCHESSBOARD_URL'       , site_url().'/wp-content/plugins/'.RPBCHESSBOARD_PLUGIN_DIR);


// Enable localization
load_plugin_textdomain('rpbchessboard', false, RPBCHESSBOARD_PLUGIN_DIR . '/languages/');


// Enqueue scripts
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbchessboard_enqueue_scripts');
function rpbchessboard_enqueue_scripts()
{
	$ext = WP_DEBUG ? '.js' : '.min.js';

	// Chess-js library
	wp_register_script('rpbchessboard-chessjs', RPBCHESSBOARD_URL . '/third-party-libs/chess-js/chess' . $ext);

	// PGN-parsing tools
	wp_register_script('rpbchessboard-pgn', RPBCHESSBOARD_URL . '/js/pgn' . $ext, array(
		'rpbchessboard-chessjs'
	));

	// Chessboard widget
	wp_register_script('rpbchessboard-chessboard', RPBCHESSBOARD_URL . '/js/uichess-chessboard' . $ext, array(
		'rpbchessboard-chessjs',
		'jquery-ui-widget',
		'jquery-ui-selectable',
		'jquery-ui-draggable', // TODO: remove this dependency (only used by the editors)
		'jquery-ui-droppable'  // TODO: remove this dependency (only used by the editors)
	));

	// Chessgame widget
	wp_register_script('rpbchessboard-chessgame', RPBCHESSBOARD_URL . '/js/uichess-chessgame' . $ext, array(
		'rpbchessboard-pgn',
		'rpbchessboard-chessboard',
		'jquery-ui-widget',
		'jquery-color',
		'jquery-ui-dialog',
		'jquery-ui-resizable'
	));

	// Enqueue the scripts.
	wp_enqueue_script('rpbchessboard-chessboard');
	wp_enqueue_script('rpbchessboard-chessgame' );

	// Additional scripts for the backend.
	if(is_admin()) {
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-tabs'  );
	}
}


// Localization script
add_action(is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts', 'rpbchessboard_localization_script');
function rpbchessboard_localization_script()
{
	include(RPBCHESSBOARD_ABSPATH . 'templates/localization.php');
}


// Enqueue CSS
add_action(is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbchessboard_enqueue_css');
function rpbchessboard_enqueue_css()
{
	// jQuery
	wp_enqueue_style('wp-jquery-ui-dialog');

	// Chess fonts
	wp_register_style('rpbchessboard-chessfonts', RPBCHESSBOARD_URL . '/fonts/chess-fonts.css');
	wp_enqueue_style('rpbchessboard-chessfonts');

	// Custom widgets
	wp_register_style('rpbchessboard-chessboard', RPBCHESSBOARD_URL . '/css/uichess-chessboard.css');
	wp_register_style('rpbchessboard-chessgame' , RPBCHESSBOARD_URL . '/css/uichess-chessgame.css' );
	wp_enqueue_style('rpbchessboard-chessboard');
	wp_enqueue_style('rpbchessboard-chessgame' );

	// Additional CSS for the backend.
	if(is_admin())
	{
		// Theme for the jQuery widgets used in the administration pages.
		wp_register_style('rpbchessboard-jquery-ui', RPBCHESSBOARD_URL . '/third-party-libs/jquery/jquery-ui-1.10.4.custom.min.css');
		wp_enqueue_style('rpbchessboard-jquery-ui');

		// Backend
		wp_register_style('rpbchessboard-backend', RPBCHESSBOARD_URL . '/css/backend.css');
		wp_enqueue_style('rpbchessboard-backend');
	}

	// Additional CSS for the frontend.
	else {
		wp_register_style('rpbchessboard-frontend', RPBCHESSBOARD_URL . '/css/frontend.css');
		wp_enqueue_style('rpbchessboard-frontend');
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
