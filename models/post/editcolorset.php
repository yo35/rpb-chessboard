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
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * Process a colorset edition/deletion request.
 */
class RPBChessboardModelPostEditColorset extends RPBChessboardAbstractModel {

	public function edit() {
		$colorset = self::getColorset();
		if(!isset($colorset)) {
			return null;
		}

		self::processLabel($colorset);
		self::processAttributes($colorset);
		self::invalidateCache();

		return __('Colorset updated.', 'rpbchessboard');
	}


	public function delete() {
		$colorset = self::getColorset();
		if(!isset($colorset)) {
			return null;
		}

		// Remove the colorset from the database.
		$remainingCustomColorsets = array_diff(self::getCustomColorsets(), array($colorset));
		update_option('rpbchessboard_custom_colorsets', implode('|', $remainingCustomColorsets));

		// Reset default colorset if it corresponds to the colorset being deleted.
		if($colorset === self::getDefaultColorset()) {
			delete_option('rpbchessboard_colorset');
		}

		// Cleanup the database and the cache.
		delete_option('rpbchessboard_custom_colorset_label_' . $colorset);
		delete_option('rpbchessboard_custom_colorset_attributes_' . $colorset);
		self::invalidateCache();

		return __('Colorset deleted.', 'rpbchessboard');
	}


	private static function processLabel($colorset) {
		if(isset($_POST['label'])) {
			update_option('rpbchessboard_custom_colorset_label_' . $colorset, $_POST['label']);
		}
	}


	private static function processAttributes($colorset) {
		$darkSquareColor = self::getSquareColor('dark');
		$lightSquareColor = self::getSquareColor('light');
		if(isset($darkSquareColor) && isset($lightSquareColor)) {
			update_option('rpbchessboard_custom_colorset_attributes_' . $colorset, $darkSquareColor . '|' . $lightSquareColor);
		}
	}


	private static function invalidateCache() {
		RPBChessboardHelperCache::remove('custom-colorsets.css');
	}


	/**
	 * Retrieve the list of existing custom colorsets.
	 */
	private static function getCustomColorsets() {
		$result = RPBChessboardHelperValidation::validateSetCodeList(get_option('rpbchessboard_custom_colorsets'));
		return isset($result) ? $result : array();
	}


	/**
	 * Retrieve the colorset used by default, if any.
	 */
	private static function getDefaultColorset() {
		return RPBChessboardHelperValidation::validateSetCode(get_option('rpbchessboard_colorset'));
	}


	/**
	 * Retrieve the colorset concerned by this operation.
	 */
	private static function getColorset() {
		return isset($_POST['colorset']) ? RPBChessboardHelperValidation::validateSetCode($_POST['colorset']) : null;
	}


	/**
	 * Retrieve either the dark or the light square color.
	 */
	private static function getSquareColor($darkOrLight) {
		$key = $darkOrLight . 'SquareColor';
		return isset($_POST[$key]) ? RPBChessboardHelperValidation::validateColor($_POST[$key]) : null;
	}
}
