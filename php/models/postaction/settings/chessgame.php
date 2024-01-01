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


class RPBChessboardModelPostActionSettingsChessGame extends RPBChessboardAbstractModelPostActionSettings {

	public function update() {
		self::updatePieceSymbols();
		self::updateNavigationBoard();

		self::updateBoardAspectParameters( 'nbo' );
		self::updateBoardAspectParameters( 'ido' );

		self::updateBooleanParameter( 'animated' );
		self::updateBooleanParameter( 'showMoveArrow' );
		self::updateMoveArrowColor();
		self::updateBooleanParameter( 'showFlipButton' );
		self::updateBooleanParameter( 'showDownloadButton' );

		return self::getUpdateSuccessMessage();
	}


	public function reset() {
		self::deleteParameter( 'pieceSymbols' );
		self::deleteParameter( 'navigationBoard' );

		self::deleteBoardAspectParameters( 'nbo' );
		self::deleteBoardAspectParameters( 'ido' );

		self::deleteParameter( 'animationSpeed' ); // FIXME Deprecated parameter (since 6.0)
		self::deleteParameter( 'animated' );
		self::deleteParameter( 'showMoveArrow' );
		self::deleteParameter( 'moveArrowColor' );
		self::deleteParameter( 'showFlipButton' );
		self::deleteParameter( 'showDownloadButton' );

		return self::getResetSuccessMessage();
	}


	private static function updateNavigationBoard() {
		if ( isset( $_POST['navigationBoard'] ) ) {
			$value = RPBChessboardHelperValidation::validateNavigationBoard( $_POST['navigationBoard'] );
			if ( isset( $value ) ) {
				update_option( 'rpbchessboard_navigationBoard', $value );
			}
		}
	}


	private static function updateMoveArrowColor() {
		if ( isset( $_POST['moveArrowColor'] ) ) {
			$value = RPBChessboardHelperValidation::validateSymbolicColor( $_POST['moveArrowColor'] );
			if ( isset( $value ) ) {
				update_option( 'rpbchessboard_moveArrowColor', $value );
			}
		}
	}


	private static function updatePieceSymbols() {
		$value = self::loadPieceSymbols();
		if ( isset( $value ) ) {
			update_option( 'rpbchessboard_pieceSymbols', $value );
		}
	}


	/**
	 * Load and validate the piece symbol parameter.
	 *
	 * @return string
	 */
	private static function loadPieceSymbols() {
		if ( ! isset( $_POST['pieceSymbolMode'] ) ) {
			return null;
		}

		switch ( $_POST['pieceSymbolMode'] ) {
			case 'english':
				return 'native';
			case 'localized':
				return 'localized';
			case 'figurines':
				return 'figurines';

			case 'custom':
				$kingSymbol   = self::loadPieceSymbol( 'kingSymbol' );
				$queenSymbol  = self::loadPieceSymbol( 'queenSymbol' );
				$rookSymbol   = self::loadPieceSymbol( 'rookSymbol' );
				$bishopSymbol = self::loadPieceSymbol( 'bishopSymbol' );
				$knightSymbol = self::loadPieceSymbol( 'knightSymbol' );
				$pawnSymbol   = self::loadPieceSymbol( 'pawnSymbol' );
				return isset( $kingSymbol ) && isset( $queenSymbol ) && isset( $rookSymbol ) &&
					isset( $bishopSymbol ) && isset( $knightSymbol ) && isset( $pawnSymbol ) ?
					$kingSymbol . ',' . $queenSymbol . ',' . $rookSymbol . ',' . $bishopSymbol . ',' . $knightSymbol . ',' . $pawnSymbol : null;

			default:
				return null;
		}
	}


	/**
	 * Load a single piece symbol.
	 *
	 * @param string $fieldName
	 * @return string
	 */
	private static function loadPieceSymbol( $fieldName ) {
		return isset( $_POST[ $fieldName ] ) ? RPBChessboardHelperValidation::validatePieceSymbol( $_POST[ $fieldName ] ) : null;
	}

}
