<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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
Author URI: https://github.com/yo35
License: GPLv3
Requires at least: 5.5
Requires PHP: 5.6
Version: 7.1.2
*/


// Directories
define( 'RPBCHESSBOARD_ABSPATH', plugin_dir_path( __FILE__ ) );
define( 'RPBCHESSBOARD_BASENAME', plugin_basename( __FILE__ ) );
define( 'RPBCHESSBOARD_URL', plugin_dir_url( __FILE__ ) );
define( 'RPBCHESSBOARD_VERSION', '7.1.2' );


// Enable localization
load_plugin_textdomain( 'rpb-chessboard', false, basename( dirname( __FILE__ ) ) . '/languages' );


// Load the helpers
require_once RPBCHESSBOARD_ABSPATH . 'php/helpers/loader.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/helpers/validation.php';


// POST actions, shortcodes, and miscellaneous...
add_action( 'init', 'rpbchessboard_init' );
function rpbchessboard_init() {

	$controller = RPBChessboardHelperLoader::loadController( is_admin() ? 'ControllerAdmin' : 'ControllerFrontend' );
	$controller->init();

	if ( is_admin() ) {

		require_once RPBCHESSBOARD_ABSPATH . 'wp/postactions.php';
		RPBChessboardPostActions::run();
	}
}


// Administration pages
if ( is_admin() ) {
	add_action( 'admin_menu', 'rpbchessboard_init_admin_pages' );
	function rpbchessboard_init_admin_pages() {
		require_once RPBCHESSBOARD_ABSPATH . 'wp/adminpages.php';
		RPBChessboardAdminPages::register();
	}
}
