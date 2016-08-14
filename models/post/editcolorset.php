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

	private static $customColorsets;


	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel('Common/DefaultOptionsEx');
	}


	public function add() {
		$colorset = $this->getNewColorset();

		// Update attributes and list of custom colorsets.
		if(!(self::processLabel($colorset) && self::processAttributes($colorset))) {
			return null;
		}
		self::updateCustomColorsets(array_merge(self::getCustomColorsets(), array($colorset)));

		// Force cache refresh.
		self::invalidateCache();

		return __('Colorset created.', 'rpbchessboard');
	}


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
		self::updateCustomColorsets(array_diff(self::getCustomColorsets(), array($colorset)));

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
			return true;
		}
		return false;
	}


	private static function processAttributes($colorset) {
		$darkSquareColor = self::getSquareColor('dark');
		$lightSquareColor = self::getSquareColor('light');
		if(isset($darkSquareColor) && isset($lightSquareColor)) {
			update_option('rpbchessboard_custom_colorset_attributes_' . $colorset, $darkSquareColor . '|' . $lightSquareColor);
			return true;
		}
		return false;
	}


	private static function updateCustomColorsets($colorsets) {
		update_option('rpbchessboard_custom_colorsets', implode('|', $colorsets));
	}


	private static function invalidateCache() {
		RPBChessboardHelperCache::remove('custom-colorsets.css');
	}


	/**
	 * Check whether the given colorset code represents an existing custom colorset or not.
	 */
	private static function isCustomColorset($colorset) {
		return in_array($colorset, self::getCustomColorsets());
	}


	/**
	 * Retrieve the list of existing custom colorsets.
	 */
	private static function getCustomColorsets() {
		if(!isset(self::$customColorsets)) {
			$result = RPBChessboardHelperValidation::validateSetCodeList(get_option('rpbchessboard_custom_colorsets'));
			self::$customColorsets = isset($result) ? $result : array();
		}
		return self::$customColorsets;
	}


	/**
	 * Retrieve the colorset used by default, if any.
	 */
	private static function getDefaultColorset() {
		return RPBChessboardHelperValidation::validateSetCode(get_option('rpbchessboard_colorset'));
	}


	/**
	 * Retrieve the colorset concerned by this operation and make sure that it is a custom colorset.
	 */
	private static function getColorset() {
		$colorset = isset($_POST['colorset']) ? RPBChessboardHelperValidation::validateSetCode($_POST['colorset']) : null;
		if(isset($colorset) && !self::isCustomColorset($colorset)) {
			return null;
		}
		return $colorset;
	}


	/**
	 * Retrieve (and sanitize) the colorset code to use to create the new colorset.
	 */
	private function getNewColorset() {
		$colorset = isset($_POST['colorset']) ? $_POST['colorset'] : '';
		if(trim($colorset) === '' && isset($_POST['label'])) {
			$colorset =  $_POST['label'];
		}

		// Convert all upper case to lower case, spaces to '-', and remove the rest.
		$colorset = strtolower($colorset);
		$colorset = preg_replace('/\s/', '-', $colorset);
		$colorset = preg_replace('/[^a-z0-9\-]/', '', $colorset);

		// Concat consecutive '-', and trim the result.
		$colorset = preg_replace('/-+/', '-', $colorset);
		$colorset = trim($colorset, '-');

		// Ensure that the result is valid and not already used for another colorset.
		$counter = 1;
		$base = $colorset === '' ? 'colorset' : $colorset;
		$colorset = $colorset === '' ? 'colorset-1' : $colorset;
		while(self::isCustomColorset($colorset) || $this->isBuiltinColorset($colorset)) {
			$colorset = $base . '-' . ($counter++);
		}
		return $colorset;
	}


	/**
	 * Retrieve either the dark or the light square color.
	 */
	private static function getSquareColor($darkOrLight) {
		$key = $darkOrLight . 'SquareColor';
		return isset($_POST[$key]) ? RPBChessboardHelperValidation::validateColor($_POST[$key]) : null;
	}
}
