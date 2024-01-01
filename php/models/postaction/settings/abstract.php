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


/**
 * Base class for the models in charge of processing the settings forms.
 */
abstract class RPBChessboardAbstractModelPostActionSettings {

	/**
	 * Entry-point for the update action.
	 */
	abstract public function update();


	/**
	 * Entry-point for the reset action.
	 */
	abstract public function reset();


	protected static function getUpdateSuccessMessage() {
		return (object) array(
			'message'     => __( 'Settings saved.', 'rpb-chessboard' ),
			'messageType' => 'success',
		);
	}


	protected static function getResetSuccessMessage() {
		return (object) array(
			'message'     => __( 'Settings reseted.', 'rpb-chessboard' ),
			'messageType' => 'success',
		);
	}


	protected static function deleteParameter( $key ) {
		delete_option( 'rpbchessboard_' . $key );
	}


	protected static function updateIntegerParameter( $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = RPBChessboardHelperValidation::validateInteger( $_POST[ $key ] );
			if ( isset( $value ) ) {
				update_option( 'rpbchessboard_' . $key, $value );
			}
		}
	}


	protected static function updateBooleanParameter( $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt( $_POST[ $key ] );
			if ( isset( $value ) ) {
				update_option( 'rpbchessboard_' . $key, $value ? 1 : 0 );
			}
			return $value;
		} else {
			return null;
		}
	}


	protected static function deleteBoardAspectParameters( $key ) {

		// FIXME Deprecated parameters (since 7.2)
		self::deleteParameter( 'squareSize' );
		self::deleteParameter( 'showCoordinates' );
		self::deleteParameter( 'colorset' );
		self::deleteParameter( 'pieceset' );

		self::deleteParameter( $key . 'SquareSize' );
		self::deleteParameter( $key . 'ShowCoordinates' );
		self::deleteParameter( $key . 'Colorset' );
		self::deleteParameter( $key . 'Pieceset' );
	}


	protected static function updateBoardAspectParameters( $key ) {
		self::updateIntegerParameter( $key . 'SquareSize' );
		self::updateBooleanParameter( $key . 'ShowCoordinates' );
		self::updateSetCodeParameter( $key . 'Colorset' );
		self::updateSetCodeParameter( $key . 'Pieceset' );
	}


	private static function updateSetCodeParameter( $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = RPBChessboardHelperValidation::validateSetCode( $_POST[ $key ] );
			if ( isset( $value ) ) {
				update_option( 'rpbchessboard_' . $key, $value );
			}
		}
	}

}
