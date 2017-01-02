<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2017  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'models/post/theming.php');


/**
 * Process a colorset creation/edition/deletion request.
 */
class RPBChessboardModelPostThemingColorset extends RPBChessboardModelPostTheming {

	protected function getCreationSuccessMessage() {
		return __('Colorset created.', 'rpbchessboard');
	}

	protected function getEditionSuccessMessage() {
		return __('Colorset updated.', 'rpbchessboard');
	}

	protected function getDeletionSuccessMessage() {
		return __('Colorset deleted.', 'rpbchessboard');
	}

	protected function getManagedSetCode() {
		return 'colorset';
	}

	protected function isBuiltinSetCode($colorset) {
		return $this->isBuiltinColorset($colorset);
	}

	protected function processAttributes($colorset) {
		$darkSquareColor = self::getSquareColor('dark');
		$lightSquareColor = self::getSquareColor('light');
		if(isset($darkSquareColor) && isset($lightSquareColor)) {
			update_option('rpbchessboard_custom_colorset_attributes_' . $colorset, $darkSquareColor . '|' . $lightSquareColor);
			return true;
		}
		return false;
	}


	/**
	 * Retrieve either the dark or the light square color.
	 */
	private static function getSquareColor($darkOrLight) {
		$key = $darkOrLight . 'SquareColor';
		return isset($_POST[$key]) ? RPBChessboardHelperValidation::validateColor($_POST[$key]) : null;
	}
}
