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


require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/abstracttrait.php');


/**
 * Process a "reset settings" request.
 */
class RPBChessboardTraitResetOptions extends RPBChessboardAbstractTrait
{
	/**
	 * Reset the general settings.
	 *
	 * @return string
	 */
	public function resetGeneral()
	{
		delete_option('rpbchessboard_squareSize'     );
		delete_option('rpbchessboard_showCoordinates');
		delete_option('rpbchessboard_pieceSymbols'   );
		delete_option('rpbchessboard_navigationBoard');
		return self::resetMessage();
	}


	/**
	 * Reset the compatibility settings.
	 *
	 * @return string
	 */
	public function resetCompatibility()
	{
		delete_option('rpbchessboard_fenCompatibilityMode');
		delete_option('rpbchessboard_pgnCompatibilityMode');
		return self::resetMessage();
	}


	/**
	 * Message returned by the reset methods.
	 *
	 * @return string
	 */
	private static function resetMessage()
	{
		return __('Settings reseted.', 'rpbchessboard');
	}
}
