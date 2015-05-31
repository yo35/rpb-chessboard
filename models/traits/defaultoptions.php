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
 * Default general options associated to chessboard and chessgame widgets.
 */
class RPBChessboardTraitDefaultOptions extends RPBChessboardAbstractTrait
{
	private static $squareSize     ;
	private static $showCoordinates;
	private static $pieceSymbols   ;
	private static $navigationBoard;

	private static $pieceSymbolLocalizationAvailable;
	private static $simplifiedPieceSymbols;


	/**
	 * Initial square size of the chessboard widgets.
	 */
	const DEFAULT_SQUARE_SIZE = 32;


	/**
	 * Initial value for the show-coordinates parameter of the chessboard widgets.
	 */
	const DEFAULT_SHOW_COORDINATES = true;


	/**
	 * Initial move notation mode.
	 */
	const DEFAULT_PIECE_SYMBOLS = 'localized';


	/**
	 * Initial navigation board position.
	 */
	const DEFAULT_NAVIGATION_BOARD = 'frame';


	/**
	 * Default square size for the chessboard widgets.
	 *
	 * @return int
	 */
	public function getDefaultSquareSize()
	{
		if(!isset(self::$squareSize)) {
			$value = RPBChessboardHelperValidation::validateSquareSize(get_option('rpbchessboard_squareSize'));
			self::$squareSize = isset($value) ? $value : self::DEFAULT_SQUARE_SIZE;
		}
		return self::$squareSize;
	}


	/**
	 * Minimum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMinimumSquareSize()
	{
		return RPBChessboardHelperValidation::MINIMUM_SQUARE_SIZE;
	}


	/**
	 * Maximum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMaximumSquareSize()
	{
		return RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean
	 */
	public function getDefaultShowCoordinates()
	{
		if(!isset(self::$showCoordinates)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_showCoordinates'));
			self::$showCoordinates = isset($value) ? $value : self::DEFAULT_SHOW_COORDINATES;
		}
		return self::$showCoordinates;
	}


	/**
	 * Default move notation mode.
	 *
	 * @return string
	 */
	public function getDefaultPieceSymbols()
	{
		if(!isset(self::$pieceSymbols)) {
			$value = RPBChessboardHelperValidation::validatePieceSymbols(get_option('rpbchessboard_pieceSymbols'));
			self::$pieceSymbols = isset($value) ? $value : self::DEFAULT_PIECE_SYMBOLS;
		}
		return self::$pieceSymbols;
	}


	/**
	 * Whether the localization is available for piece symbols or not.
	 *
	 * @return boolean
	 */
	public function isPieceSymbolLocalizationAvailable()
	{
		if(!isset(self::$pieceSymbolLocalizationAvailable)) {
			$englishPieceSymbols = 'KQRBNP';
			$localizedPieceSymbols =
				/*i18n King symbol   */ __('K', 'rpbchessboard') .
				/*i18n Queen symbol  */ __('Q', 'rpbchessboard') .
				/*i18n Rook symbol   */ __('R', 'rpbchessboard') .
				/*i18n Bishop symbol */ __('B', 'rpbchessboard') .
				/*i18n Knight symbol */ __('N', 'rpbchessboard') .
				/*i18n Pawn symbol   */ __('P', 'rpbchessboard');
			self::$pieceSymbolLocalizationAvailable = ($englishPieceSymbols !== $localizedPieceSymbols);
		}
		return self::$pieceSymbolLocalizationAvailable;
	}


	/**
	 * Simplified version of the default piece symbol mode.
	 *
	 * @return boolean
	 */
	public function getDefaultSimplifiedPieceSymbols()
	{
		if(!isset(self::$simplifiedPieceSymbols)) {
			switch($this->getDefaultPieceSymbols()) {
				case 'native'   : self::$simplifiedPieceSymbols = 'english'  ; break;
				case 'figurines': self::$simplifiedPieceSymbols = 'figurines'; break;
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
	public function getDefaultPieceSymbolCustomValues()
	{
		if($this->getDefaultSimplifiedPieceSymbols() === 'custom') {
			$pieceSymbols = $this->getDefaultPieceSymbols();
			return array(
				'K' => substr($pieceSymbols, 1, 1),
				'Q' => substr($pieceSymbols, 2, 1),
				'R' => substr($pieceSymbols, 3, 1),
				'B' => substr($pieceSymbols, 4, 1),
				'N' => substr($pieceSymbols, 5, 1),
				'P' => substr($pieceSymbols, 6, 1)
			);
		}
		else {
			return array();
		}
	}


	/**
	 * Default navigation board position.
	 *
	 * @return string
	 */
	public function getDefaultNavigationBoard()
	{
		if(!isset(self::$navigationBoard)) {
			$value = RPBChessboardHelperValidation::validateNavigationBoard(get_option('rpbchessboard_navigationBoard'));
			self::$navigationBoard = isset($value) ? $value : self::DEFAULT_NAVIGATION_BOARD;
		}
		return self::$navigationBoard;
	}
}
