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


require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/defaultoptions.php');


/**
 * Default general options with some additional information.
 */
class RPBChessboardTraitDefaultOptionsEx extends RPBChessboardTraitDefaultOptions
{
	private static $pieceSymbolLocalizationAvailable;
	private static $simplifiedPieceSymbols;


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
}
