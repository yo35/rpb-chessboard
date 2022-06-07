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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/postaction/abstract.php';


/**
 * Base class for the models in charge of processing update forms.
 */
abstract class RPBChessboardAbstractModelPostActionUpdate extends RPBChessboardAbstractModelPostAction {

	protected static function getSuccessMessage() {
		return __( 'Settings saved.', 'rpb-chessboard' );
	}

	protected static function processBooleanParameter( $key ) {
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

}
