<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>            *
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
 * Trait for loading the options controlling the aspect of chessboard widgets
 * and passed by a short-code attribute.
 */
class RPBChessboardTraitCompatibility extends RPBChessboardAbstractTrait
{
	private $fenCompatibilityMode = null;
	private $pgnCompatibilityMode = null;


	/**
	 * Whether the compatibility mode is activated or not for the [fen][/fen] shortcode
	 * (which means that [fen_compat][/fen_compat] would be used instead).
	 *
	 * @return boolean
	 */
	public function getFENCompatibilityMode()
	{
		if(is_null($this->fenCompatibilityMode)) {
			$value = RPBChessboardHelperValidation::prefilterBooleanFromInt(get_option('rpbchessboard_fenCompatibilityMode'));
			$this->fenCompatibilityMode = is_null($value) ? false : $value;
		}
		return $this->fenCompatibilityMode;
	}


	/**
	 * Whether the compatibility mode is activated or not for the [pgn][/pgn] shortcode
	 * (which means that [pgn_compat][/pgn_compat] would be used instead).
	 *
	 * @return boolean
	 */
	public function getPGNCompatibilityMode()
	{
		if(is_null($this->pgnCompatibilityMode)) {
			$value = RPBChessboardHelperValidation::prefilterBooleanFromInt(get_option('rpbchessboard_pgnCompatibilityMode'));
			$this->pgnCompatibilityMode = is_null($value) ? false : $value;
		}
		return $this->pgnCompatibilityMode;
	}


	/**
	 * Return the shortcode to use for FEN diagrams.
	 *
	 * @return string
	 */
	public function getFENShortcode()
	{
		return $this->getFENCompatibilityMode() ? 'fen_compat' : 'fen';
	}


	/**
	 * Return the shortcode to use for PGN games.
	 *
	 * @return string
	 */
	public function getPGNShortcode()
	{
		return $this->getPGNCompatibilityMode() ? 'pgn_compat' : 'pgn';
	}
}
