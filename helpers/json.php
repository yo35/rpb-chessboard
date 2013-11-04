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


/**
 * JSON-related functions.
 */
abstract class RPBChessboardHelperJSON
{
	/**
	 * Return a string representing a set of aspect options applicable to
	 * chessboard widgets, in a JSON format.
	 *
	 * @param boolean $flip
	 * @param int $squareSize
	 * @param boolean $showCoordinates
	 * @return string This string can be inlined in a javascript script.
	 */
	public static function formatChessWidgetAttributes($flip, $squareSize, $showCoordinates)
	{
		$retVal = array();
		if(!is_null($flip           )) $retVal[] = '"flip": '            . json_encode($flip           );
		if(!is_null($squareSize     )) $retVal[] = '"squareSize": '      . json_encode($squareSize     );
		if(!is_null($showCoordinates)) $retVal[] = '"showCoordinates": ' . json_encode($showCoordinates);
		return '{' . implode(', ', $retVal) . '}';
	}


	/**
	 * Trim the braces of a string.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function trimBraces($str)
	{
		return preg_replace('/^{|}$/', '', $str);
	}
}
