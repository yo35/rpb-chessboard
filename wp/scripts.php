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


require_once(RPBCHESSBOARD_ABSPATH . 'helpers/loader.php');


/**
 * Register the plugin JavaScript scripts.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardScripts
{
	public static function register()
	{
		$ext = WP_DEBUG ? '.js' : '.min.js';

		// chess.js (https://github.com/jhlywa/chess.js)
		wp_register_script('rpbchessboard-chessjs', RPBCHESSBOARD_URL . '/third-party-libs/chess-js/chess' . $ext);

		// Moment.js (http://momentjs.com/)
		wp_register_script('rpbchessboard-momentjs', RPBCHESSBOARD_URL . '/third-party-libs/moment-js/moment' . $ext);

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
			'rpbchessboard-momentjs',
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

		// Inlined scripts
		add_action(is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts', array(__CLASS__, 'callbackInlinedScripts'));
	}


	public static function callbackInlinedScripts()
	{
		include(RPBCHESSBOARD_ABSPATH . 'templates/localization.php');
	}
}
