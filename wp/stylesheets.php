<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Register the plugin CSS.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardStyleSheets
{
	public static function register()
	{
		// jQuery
		wp_enqueue_style('wp-jquery-ui-dialog');

		// Chess fonts
		wp_enqueue_style('rpbchessboard-chessfonts', RPBCHESSBOARD_URL . 'fonts/chess-fonts.css', false, RPBCHESSBOARD_VERSION);

		// Custom widgets
		wp_enqueue_style('rpbchessboard-chessboard', RPBCHESSBOARD_URL . 'css/uichess-chessboard.css', false, RPBCHESSBOARD_VERSION);
		wp_enqueue_style('rpbchessboard-chessgame' , RPBCHESSBOARD_URL . 'css/uichess-chessgame.css' , false, RPBCHESSBOARD_VERSION);

		// Small-screens
		RPBChessboardHelperCache::ensureExists('small-screens.css', 'Misc/SmallScreens', 'Misc/SmallScreens');
		wp_enqueue_style('rpbchessboard-smallscreens' , RPBChessboardHelperCache::getURL('small-screens.css'), false, RPBCHESSBOARD_VERSION);

		// Additional CSS for the backend.
		if(is_admin()) {
			wp_enqueue_style('rpbchessboard-jquery-ui-smoothness', RPBCHESSBOARD_URL . 'third-party-libs/jquery/jquery-ui.smoothness.min.css', false, '1.11.4');
			wp_enqueue_style('rpbchessboard-backend'             , RPBCHESSBOARD_URL . 'css/backend.css', false, RPBCHESSBOARD_VERSION);
		}

		// Additional CSS for the frontend.
		else {
			wp_enqueue_style('rpbchessboard-frontend', RPBCHESSBOARD_URL . 'css/frontend.css', false, RPBCHESSBOARD_VERSION);
		}
	}
}
