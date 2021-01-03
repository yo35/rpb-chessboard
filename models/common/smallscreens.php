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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php';
require_once RPBCHESSBOARD_ABSPATH . 'helpers/validation.php';


/**
 * Specific settings to deal with small-screen devices (such as smartphones).
 */
class RPBChessboardModelCommonSmallScreens extends RPBChessboardAbstractModel {

	private static $smallScreenCompatibility;
	private static $smallScreenModes;


	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods( 'getSmallScreenCompatibility', 'getSmallScreenModes' );
	}


	/**
	 * Whether the small-screen compatibility mode is enabled or not.
	 *
	 * @return boolean
	 */
	public function getSmallScreenCompatibility() {
		if ( ! isset( self::$smallScreenCompatibility ) ) {
			$value                          = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_smallScreenCompatibility' ) );
			self::$smallScreenCompatibility = isset( $value ) ? $value : true;
		}
		return self::$smallScreenCompatibility;
	}


	/**
	 * Return the small-screen modes.
	 *
	 * @return array
	 */
	public function getSmallScreenModes() {
		if ( ! isset( self::$smallScreenModes ) ) {
			self::loadSmallScreenModes();
		}
		return self::$smallScreenModes;
	}


	/**
	 * Load the small-screen mode specifications.
	 */
	private static function loadSmallScreenModes() {

		// Load the raw data
		$data = RPBChessboardHelperValidation::validateSmallScreenModes( get_option( 'rpbchessboard_smallScreenModes' ) );
		$data = isset( $data ) ? $data : array(
			240 => (object) array(
				'squareSize'      => 18,
				'hideCoordinates' => true,
			),
			320 => (object) array(
				'squareSize'      => 24,
				'hideCoordinates' => true,
			),
			480 => (object) array(
				'squareSize'      => 32,
				'hideCoordinates' => false,
			),
			768 => (object) array(
				'squareSize'      => 56,
				'hideCoordinates' => false,
			),
		);

		// Format the mode entries
		self::$smallScreenModes   = array();
		$previousScreenWidthBound = 0;
		foreach ( $data as $screenWidthBound => $mode ) {
			$mode->minScreenWidth = $previousScreenWidthBound;
			$mode->maxScreenWidth = $screenWidthBound;
			array_push( self::$smallScreenModes, $mode );
			$previousScreenWidthBound = $screenWidthBound;
		}
	}
}
