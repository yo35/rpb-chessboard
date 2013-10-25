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


require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstractactiontrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Process the request resulting from a click in the "Options" form in the backend.
 */
class RPBChessboardTraitActionDefineOptions extends RPBChessboardAbstractActionTrait
{
	public function processRequest()
	{
		// Set the square size parameter
		$value = $this->getPostSquareSize();
		if(!is_null($value)) {
			update_option('rpbchessboard_squareSize', $value);
		}

		// Set the show-coordinates parameter
		$value = $this->getPostShowCoordinates();
		if(!is_null($value)) {
			update_option('rpbchessboard_showCoordinates', $value ? 1 : 0);
		}
	}


	/**
	 * New default square size for the chessboard widgets.
	 *
	 * @return int May be null if the corresponding POST field is undefined or invalid.
	 */
	public function getPostSquareSize()
	{
		if(array_key_exists('squareSize', $_POST)) {
			return RPBChessboardHelperValidation::validateSquareSize($_POST['squareSize']);
		}
		else {
			return null;
		}
	}


	/**
	 * New default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean May be null if the corresponding POST field is undefined or invalid.
	 */
	public function getPostShowCoordinates()
	{
	if(array_key_exists('showCoordinates', $_POST)) {
			$value = RPBChessboardHelperValidation::prefilterBooleanFromInt($_POST['showCoordinates']);
			return RPBChessboardHelperValidation::validateShowCoordinates($value);
		}
		else {
			return null;
		}
	}
}
