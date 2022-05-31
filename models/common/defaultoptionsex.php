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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php';


/**
 * Default general options with some additional information.
 */
class RPBChessboardModelCommonDefaultOptionsEx extends RPBChessboardAbstractModel {

	private static $pieceSymbolLocalizationAvailable;
	private static $simplifiedPieceSymbols;
	private static $pieceSymbolCustomValues;

	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods(
			'getMinimumSquareSize',
			'getMaximumSquareSize',
			'isPieceSymbolLocalizationAvailable',
			'getDefaultSimplifiedPieceSymbols',
			'getDefaultPieceSymbolCustomValues'
		);

		$this->loadDelegateModel( 'Common/DefaultOptions' );
	}


	/**
	 * Minimum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMinimumSquareSize() {
		return RPBChessboardHelperValidation::MINIMUM_SQUARE_SIZE;
	}


	/**
	 * Maximum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMaximumSquareSize() {
		return RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Whether the localization is available for piece symbols or not.
	 *
	 * @return boolean
	 */
	public function isPieceSymbolLocalizationAvailable() {
		if ( ! isset( self::$pieceSymbolLocalizationAvailable ) ) {
			$englishPieceSymbols                    = 'KQRBNP';
			$localizedPieceSymbols                  =
				/*i18n King symbol   */ __( 'K', 'rpb-chessboard' ) .
				/*i18n Queen symbol  */ __( 'Q', 'rpb-chessboard' ) .
				/*i18n Rook symbol   */ __( 'R', 'rpb-chessboard' ) .
				/*i18n Bishop symbol */ __( 'B', 'rpb-chessboard' ) .
				/*i18n Knight symbol */ __( 'N', 'rpb-chessboard' ) .
				/*i18n Pawn symbol   */ __( 'P', 'rpb-chessboard' );
			self::$pieceSymbolLocalizationAvailable = ( $englishPieceSymbols !== $localizedPieceSymbols );
		}
		return self::$pieceSymbolLocalizationAvailable;
	}


	/**
	 * Simplified version of the default piece symbol mode.
	 *
	 * @return boolean
	 */
	public function getDefaultSimplifiedPieceSymbols() {
		if ( ! isset( self::$simplifiedPieceSymbols ) ) {
			switch ( $this->getDefaultPieceSymbols() ) {
				case 'native':
					self::$simplifiedPieceSymbols = 'english';
					break;
				case 'figurines':
					self::$simplifiedPieceSymbols = 'figurines';
					break;
				case 'localized':
					self::$simplifiedPieceSymbols = $this->isPieceSymbolLocalizationAvailable() ? 'localized' : 'english';
					break;
				default:
					self::$simplifiedPieceSymbols = 'custom';
					break;
			}
		}
		return self::$simplifiedPieceSymbols;
	}


	/**
	 * Default values for the piece symbol custom fields.
	 *
	 * @return array
	 */
	public function getDefaultPieceSymbolCustomValues() {
		if ( ! isset( self::$pieceSymbolCustomValues ) ) {
			if ( preg_match( '/^([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*)$/', $this->getDefaultPieceSymbols(), $m ) ) {
				self::$pieceSymbolCustomValues = array(
					'K' => $m[1],
					'Q' => $m[2],
					'R' => $m[3],
					'B' => $m[4],
					'N' => $m[5],
					'P' => $m[6],
				);
			} else {
				self::$pieceSymbolCustomValues = array();
			}
		}
		return self::$pieceSymbolCustomValues;
	}
}
