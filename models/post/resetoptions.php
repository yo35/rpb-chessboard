<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php');


/**
 * Process a "reset settings" request.
 */
class RPBChessboardModelPostResetOptions extends RPBChessboardAbstractModel {

	/**
	 * Reset the general settings.
	 *
	 * @return string
	 */
	public function resetGeneral() {
		delete_option('rpbchessboard_squareSize'     );
		delete_option('rpbchessboard_showCoordinates');
		delete_option('rpbchessboard_colorset'       );
		delete_option('rpbchessboard_pieceset'       );
		delete_option('rpbchessboard_pieceSymbols'   );
		delete_option('rpbchessboard_navigationBoard');
		delete_option('rpbchessboard_animationSpeed' );
		delete_option('rpbchessboard_showMoveArrow'  );
		return self::resetMessage();
	}


	/**
	 * Reset the compatibility settings.
	 *
	 * @return string
	 */
	public function resetCompatibility() {
		delete_option('rpbchessboard_fenCompatibilityMode');
		delete_option('rpbchessboard_pgnCompatibilityMode');
		return self::resetMessage();
	}


	/**
	 * Reset the compatibility settings.
	 *
	 * @return string
	 */
	public function resetSmallScreens() {
		delete_option('rpbchessboard_smallScreenCompatibility');
		delete_option('rpbchessboard_smallScreenModes'        );
		RPBChessboardHelperCache::remove('small-screens.css');
		return self::resetMessage();
	}


	/**
	 * Message returned by the reset methods.
	 *
	 * @return string
	 */
	private static function resetMessage() {
		return __('Settings reseted.', 'rpbchessboard');
	}
}
