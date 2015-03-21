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
	 * Whether the small-screen compatibility mode is enabled or not.
	 *
	 * @return boolean
	 */
	public function getSmallScreenCompatibility() {
		if(!isset(self::$smallScreenCompatibility)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_smallScreenCompatibility'));
			self::$smallScreenCompatibility = isset($value) ? $value : true;
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
		$data = isset($data) ? $data : array(
			240 => (object) array('squareSize' => 18, 'hideCoordinates' => true ),
			320 => (object) array('squareSize' => 24, 'hideCoordinates' => true ),
			480 => (object) array('squareSize' => 32, 'hideCoordinates' => false),
			768 => (object) array('squareSize' => 56, 'hideCoordinates' => false)
		);

		// Format the mode entries
		self::$smallScreenModes = array();
		$previousScreenWidthBound = 0;
		foreach($data as $screenWidthBound => $mode) {
			$mode->minScreenWidth = $previousScreenWidthBound;
			$mode->maxScreenWidth = $screenWidthBound;
			array_push(self::$smallScreenModes, $mode);
			$previousScreenWidthBound = $screenWidthBound;
		}
	}


	/**
	 * Return the background-position x-offset to use for sprites havinge size `$squareSize`.
	 *
	 * @param int $squareSize
	 * @return int
	 */
	public function getBackgroundPositionXForSquareSize($squareSize) {
		if($squareSize <= 32) {
			$squareSize = 65 - $squareSize;
		}

		// delta_x = - sum (k = 33 to $squareSize - 1) { k }
		return 528 - $squareSize*($squareSize - 1)/2;
	}


	/**
	 * Return the background-position y-offset to use for sprites havinge size `$squareSize`.
	 *
	 * @param int $squareSize
	 * @return int
	 */
	public function getBackgroundPositionYForSquareSize($squareSize) {
		return $squareSize <= 32 ? $squareSize - 65 : 0;
	}


	/**
	 * Selector to use to introduce the specific CSS instructions for the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSmallScreenModeMainSelector($mode) {
		$res = '@media all';
		if($mode->minScreenWidth > 0) {
			$res .= ' and (min-width:' . ($mode->minScreenWidth + 1) . 'px)';
		}
		$res .= ' and (max-width:' . $mode->maxScreenWidth . 'px)';
		return $res;
	}


	/**
	 * Whether the square size must be customized in the given small-screen mode or not.
	 *
	 * @return boolean
	 */
	public function hasSmallScreenSizeSquareSizeSection($mode) {
		return $mode->squareSize < RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Selector to use to introduce the specific CSS instructions to customize the square size in the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSmallScreenModeSquareSizeSelector($mode) {
		$selectors = array();
		for($size = $mode->squareSize+1; $size <= RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE; ++$size) {
			array_push($selectors, '.uichess-chessboard-size' . $size);
		}
		return implode(',', $selectors);
	}
}
