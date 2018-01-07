<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'models/post/theming.php';


/**
 * Process a pieceset creation/edition/deletion request.
 */
class RPBChessboardModelPostThemingPieceset extends RPBChessboardModelPostTheming {

	private static $COLORED_PIECE_CODES = array( 'bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx' );


	protected function getCreationSuccessMessage() {
		return __( 'Pieceset created.', 'rpb-chessboard' );
	}

	protected function getEditionSuccessMessage() {
		return __( 'Pieceset updated.', 'rpb-chessboard' );
	}

	protected function getDeletionSuccessMessage() {
		return __( 'Pieceset deleted.', 'rpb-chessboard' );
	}

	protected function getManagedSetCode() {
		return 'pieceset';
	}

	protected function isBuiltinSetCode( $pieceset ) {
		return $this->isBuiltinPieceset( $pieceset );
	}

	protected function processAttributes( $pieceset ) {
		$values = array();
		foreach ( self::$COLORED_PIECE_CODES as $coloredPiece ) {
			$id = self::getImageId( $coloredPiece );
			if ( ! isset( $id ) ) {
				return false;
			}
			$values[] = $id;
		}
		update_option( 'rpbchessboard_custom_pieceset_attributes_' . $pieceset, implode( '|', $values ) );
		return true;
	}


	/**
	 * Return the attachment ID defined for the given colored piece.
	 */
	private static function getImageId( $coloredPiece ) {
		$key = 'imageId-' . $coloredPiece;
		return isset( $_POST[ $key ] ) ? RPBChessboardHelperValidation::validateInteger( $_POST[ $key ] ) : null;
	}
}
