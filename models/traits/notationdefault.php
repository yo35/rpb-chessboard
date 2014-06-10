<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Default options for the move notation.
 */
class RPBChessboardTraitNotationDefault extends RPBChessboardAbstractTrait
{
	private static $pieceSymbols;


	/**
	 * Initial move notation mode.
	 */
	const DEFAULT_PIECE_SYMBOLS = 'localized';


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
}
