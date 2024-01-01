<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2024  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/postaction/settings/abstract.php';


class RPBChessboardModelPostActionSettingsSmallScreens extends RPBChessboardAbstractModelPostActionSettings {

	public function update() {
		$smallScreenCompatibility = self::updateBooleanParameter( 'smallScreenCompatibility' );
		if ( isset( $smallScreenCompatibility ) && $smallScreenCompatibility ) {
			self::updateSmallScreenModes();
		}
		return self::getUpdateSuccessMessage();
	}


	public function reset() {
		self::deleteParameter( 'smallScreenCompatibility' );
		self::deleteParameter( 'smallScreenModes' );
		return self::getResetSuccessMessage();
	}


	private static function updateSmallScreenModes() {
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
		$hideTurnKey        = 'smallScreenMode' . $index . '-hideTurn';
		if ( isset( $_POST[ $screenWidthKey ] ) && isset( $squareSizeKey ) && isset( $hideCoordinatesKey ) ) {
			$screenWidth     = RPBChessboardHelperValidation::validateInteger( $_POST[ $screenWidthKey ] );
			$squareSize      = RPBChessboardHelperValidation::validateInteger( $_POST[ $squareSizeKey ] );
			$hideCoordinates = RPBChessboardHelperValidation::validateBooleanFromInt( $_POST[ $hideCoordinatesKey ] );
			$hideTurn        = RPBChessboardHelperValidation::validateBooleanFromInt( $_POST[ $hideTurnKey ] );
			if ( isset( $screenWidth ) && isset( $squareSize ) && isset( $hideCoordinates ) && isset( $hideTurn ) ) {
				return $screenWidth . ':' . $squareSize . ':' . ( $hideCoordinates ? 'true' : 'false' ) . ':' . ( $hideTurn ? 'true' : 'false' );
			}
		}
		return null;
	}

}
