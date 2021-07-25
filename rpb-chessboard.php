<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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
Plugin URI: https://wordpress.org/plugins/rpb-chessboard/
Description: This plugin allows you to typeset and display chess diagrams and PGN-encoded chess games.
Text Domain: rpb-chessboard
Domain Path: /languages
Author: Yoann Le Montagner
License: GPLv3
Version: 6.2.0
*/


// Directories
define( 'RPBCHESSBOARD_ABSPATH', plugin_dir_path( __FILE__ ) );
define( 'RPBCHESSBOARD_URL', plugin_dir_url( __FILE__ ) );
define( 'RPBCHESSBOARD_VERSION', '6.2.0' );


// Enable localization
load_plugin_textdomain( 'rpb-chessboard', false, basename( dirname( __FILE__ ) ) . '/languages' );


// Various tools
require_once RPBCHESSBOARD_ABSPATH . 'helpers/loader.php';


// POST actions, shortcodes, and miscellaneous...
add_action( 'init', 'rpbchessboard_init' );
function rpbchessboard_init() {

	if ( is_admin() ) {

		require_once RPBCHESSBOARD_ABSPATH . 'wp/postactions.php';
		RPBChessboardPostActions::run();

		require_once RPBCHESSBOARD_ABSPATH . 'wp/misc.php';
		RPBChessboardMisc::register();
	}

	require_once RPBCHESSBOARD_ABSPATH . 'wp/shortcodes.php';
	RPBChessboardShortcodes::register();
}


// JavaScript & CSS
add_action( is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts', 'rpbchessboard_init_js_css' );
function rpbchessboard_init_js_css() {
	require_once RPBCHESSBOARD_ABSPATH . 'wp/scripts.php';
	RPBChessboardScripts::register();

	require_once RPBCHESSBOARD_ABSPATH . 'wp/stylesheets.php';
	RPBChessboardStyleSheets::register();
}


// Administration pages
if ( is_admin() ) {
	add_action( 'admin_menu', 'rpbchessboard_init_admin_pages' );
	function rpbchessboard_init_admin_pages() {
		require_once RPBCHESSBOARD_ABSPATH . 'wp/adminpages.php';
		RPBChessboardAdminPages::register();
	}
}
