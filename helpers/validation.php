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


/**
 * Validation functions.
 */
abstract class RPBChessboardHelperValidation
{
	/**
	 * Minimum square size of the chessboard widgets.
	 */
	const MINIMUM_SQUARE_SIZE = 12;


	/**
	 * Maximum square size of the chessboard widgets.
	 */
	const MAXIMUM_SQUARE_SIZE = 64;


	/**
	 * Validate a chessboard widget square size parameter.
	 *
	 * @param mixed $value
	 * @return int May be null is the value is not valid.
	 */
	public static function validateSquareSize($value)
	{
		$value = filter_var($value, FILTER_VALIDATE_INT);
		if($value===false) {
			return null;
		}
		else {
			return min(max($value, self::MINIMUM_SQUARE_SIZE), self::MAXIMUM_SQUARE_SIZE);
		}
	}


	/**
	 * Validate a piece symbol mode parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validatePieceSymbols($value)
	{
		if($value==='native' || $value==='localized' || $value==='figurines') {
			return $value;
		}
		else if(is_string($value) && preg_match('/^\([a-zA-Z]{6}\)$/', $value)) {
			return strtoupper($value);
		}
		else {
			return null;
		}
	}


	/**
	 * Validate a single piece symbol.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validatePieceSymbol($value)
	{
		return is_string($value) && preg_match('/^[a-zA-Z]$/', $value) ? strtoupper($value) : null;
	}


	/**
	 * Validate a navigation board position parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateNavigationBoard($value)
	{
		return ($value==='none' || $value==='frame' || $value==='floatLeft' || $value==='floatRight') ? $value : null;
	}


	/**
	 * Validate a boolean.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBoolean($value)
	{
		return ($value===null || $value==='') ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}


	/**
	 * Validate a boolean specified as an integer value.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBooleanFromInt($value)
	{
		$value = filter_var($value, FILTER_VALIDATE_INT);
		if     ($value===0) return false;
		else if($value===1) return true ;
		else                return null ;
	}
}
