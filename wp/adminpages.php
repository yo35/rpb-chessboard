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


/**
 * Register the plugin administration pages in the WordPress backend.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardAdminPages {

	public static function register() {
		// Create the menu
		add_menu_page(
			__( 'Chess games and diagrams', 'rpb-chessboard' ),
			__( 'Chess', 'rpb-chessboard' ),
			'edit_posts',
			'rpbchessboard',
			array( __CLASS__, 'callbackPageMemo' ),
			RPBCHESSBOARD_URL . 'images/menu.png'
		);

		// Page "memo" (same slug code that for the menu, to make it the default page).
		add_submenu_page(
			'rpbchessboard',
			__( 'Chess games and diagrams', 'rpb-chessboard' ) . ' - ' . __( 'Memo', 'rpb-chessboard' ),
			__( 'Memo', 'rpb-chessboard' ),
			'edit_posts',
			'rpbchessboard',
			array( __CLASS__, 'callbackPageMemo' )
		);

		// Page "options"
		add_submenu_page(
			'rpbchessboard',
			sprintf( __( 'Settings of the %1$s plugin', 'rpb-chessboard' ), 'RPB Chessboard' ),
			__( 'Settings', 'rpb-chessboard' ),
			'manage_options',
			'rpbchessboard-options',
			array( __CLASS__, 'callbackPageOptions' )
		);

		// Page "theming"
		add_submenu_page(
			'rpbchessboard',
			__( 'Manage colorsets & piecesets', 'rpb-chessboard' ),
			__( 'Theming', 'rpb-chessboard' ),
			'manage_options',
			'rpbchessboard-theming',
			array( __CLASS__, 'callbackPageTheming' )
		);

		// Page "help"
		add_submenu_page(
			'rpbchessboard',
			__( 'Chess games and diagrams', 'rpb-chessboard' ) . ' - ' . __( 'Help', 'rpb-chessboard' ),
			__( 'Help', 'rpb-chessboard' ),
			'edit_posts',
			'rpbchessboard-help',
			array( __CLASS__, 'callbackPageHelp' )
		);

		// Page "about"
		add_submenu_page(
			'rpbchessboard',
			sprintf( __( 'About %1$s', 'rpb-chessboard' ), 'RPB Chessboard' ),
			__( 'About', 'rpb-chessboard' ),
			'manage_options',
			'rpbchessboard-about',
			array( __CLASS__, 'callbackPageAbout' )
		);
	}

	// phpcs:disable
	public static function callbackPageMemo   () { self::printAdminPage( 'Memo'    ); }
	public static function callbackPageOptions() { self::printAdminPage( 'Options' ); }
	public static function callbackPageTheming() { self::printAdminPage( 'Theming' ); }
	public static function callbackPageHelp   () { self::printAdminPage( 'Help'    ); }
	public static function callbackPageAbout  () { self::printAdminPage( 'About'   ); }
	// phpcs:enable


	/**
	 * Load and print the plugin administration page named `$adminPageName`.
	 *
	 * @param string $adminPageName
	 */
	private static function printAdminPage( $adminPageName ) {
		$model = RPBChessboardHelperLoader::loadModel( 'AdminPage/' . $adminPageName );
		RPBChessboardHelperLoader::printTemplate( 'AdminPage', $model );
	}
}
