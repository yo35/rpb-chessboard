<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/postaction/abstractupdate.php';


class RPBChessboardModelPostActionUpdateSmallScreens extends RPBChessboardAbstractModelPostActionUpdate {

	public function run() {
		$smallScreenCompatibility = self::processBooleanParameter( 'smallScreenCompatibility' );
		if ( isset( $smallScreenCompatibility ) && $smallScreenCompatibility ) {
			self::processSmallScreenModes();
		}
		return self::getSuccessMessage();
	}


	private static function processSmallScreenModes() {
		$value = self::loadSmallScreenModes();
		if ( isset( $value ) ) {
			update_option( 'rpbchessboard_smallScreenModes', $value );
		}
	}


	/**
	 * Load and validate the small-screen mode parameters.
	 *
	 * @return string
	 */
	private static function loadSmallScreenModes() {
		if ( ! isset( $_POST['smallScreenModes'] ) ) {
			return null;
		}

		$smallScreenModeCount = RPBChessboardHelperValidation::validateInteger( $_POST['smallScreenModes'] );
		$smallScreenModes     = array();
		for ( $index = 0; $index < $smallScreenModeCount; ++$index ) {
			$smallScreenMode = self::loadSmallScreenMode( $index );
			if ( isset( $smallScreenMode ) ) {
				array_push( $smallScreenModes, $smallScreenMode );
			}
		}
		return implode( ',', $smallScreenModes );
	}


	/**
	 * Load and validate the small-screen mode corresponding to the given index.
	 *
	 * @param int $index
	 * @return string
	 */
	private static function loadSmallScreenMode( $index ) {
		$screenWidthKey     = 'smallScreenMode' . $index . '-screenWidth';
		$squareSizeKey      = 'smallScreenMode' . $index . '-squareSize';
		$hideCoordinatesKey = 'smallScreenMode' . $index . '-hideCoordinates';
		if ( isset( $_POST[ $screenWidthKey ] ) && isset( $squareSizeKey ) && isset( $hideCoordinatesKey ) ) {
			$screenWidth     = RPBChessboardHelperValidation::validateInteger( $_POST[ $screenWidthKey ] );
			$squareSize      = RPBChessboardHelperValidation::validateInteger( $_POST[ $squareSizeKey ] );
			$hideCoordinates = RPBChessboardHelperValidation::validateBooleanFromInt( $_POST[ $hideCoordinatesKey ] );
			if ( isset( $screenWidth ) && isset( $squareSize ) && isset( $hideCoordinates ) ) {
				return $screenWidth . ':' . $squareSize . ':' . ( $hideCoordinates ? 'true' : 'false' );
			}
		}
		return null;
	}

}
