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
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * Specific settings to deal with small-screen devices (such as smartphones).
 */
class RPBChessboardTraitSmallScreens extends RPBChessboardAbstractTrait
{
	private static $smallScreenCompatibility;
	private static $smallScreenModes;


	/**
	 * Whether the small-screen compatibility mode enabled by default or not.
	 */
	const DEFAULT_SMALL_SCREEN_COMPATIBILITY = true;


	/**
	 * Initial small-screen mode specifications.
	 */
	const DEFAULT_SMALL_SCREEN_MODES = array(240 => 18, 320 => 24, 480 => 32, 768 => 64);


	/**
	 * Whether the small-screen compatibility mode is enabled or not.
	 *
	 * @return boolean
	 */
	public function getSmallScreenCompatibility() {
		if(!isset(self::$smallScreenCompatibility)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_smallScreenCompatibility'));
			self::$smallScreenCompatibility = isset($value) ? $value : DEFAULT_SMALL_SCREEN_COMPATIBILITY;
		}
		return self::$smallScreenCompatibility;
	}


	/**
	 * Return the small-screen modes.
	 *
	 * @return array
	 */
	public function getSmallScreenModes() {
		if(!isset(self::$smallScreenModes)) {
			self::loadSmallScreenModes();
		}
		return self::$smallScreenModes;
	}


	/**
	 * Load the small-screen mode specifications.
	 */
	private static function loadSmallScreenModes() {

		// Load the raw data
		$data = RPBChessboardHelperValidation::validateSmallScreenModes(get_option('rpbchessboard_smallScreenModes'));
		$data = isset($value) ? $value : DEFAULT_SMALL_SCREEN_MODES;

		// Format the mode entries
		self::$smallScreenModes = array();
		$previousScreenWidthBound = 0;
		foreach($data as $screenWidthBound => $squareSize) {
			array_push(self::$smallScreenModes, (object) array(
				'screenWidthMin' => $previousScreenWidthBound,
				'screenWidthMax' => $screenWidthBound,
				'squareSize'     => $squareSize
			));
			$previousScreenWidthBound = $screenWidthBound;
		}
	}
}
