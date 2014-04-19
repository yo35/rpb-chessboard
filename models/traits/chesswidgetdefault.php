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
 * Trait for loading the default options associated to chessboard widgets
 * from the WP database.
 */
class RPBChessboardTraitChessWidgetDefault extends RPBChessboardAbstractTrait
{
	private $squareSize     ;
	private $showCoordinates;
	private $allParameters  ;


	/**
	 * Initial default square size of the chessboard widgets.
	 */
	const DEFAULT_SQUARE_SIZE = 32;


	/**
	 * Initial default show-coordinates parameter of the chessboard widgets.
	 */
	const DEFAULT_SHOW_COORDINATES = true;


	/**
	 * Return all the default parameters in a "key => value" array.
	 *
	 * @return array
	 */
	public function getDefaultAll()
	{
		if(!isset($this->allParameters)) {
			$this->allParameters = array();
			$this->allParameters['squareSize'     ] = $this->getDefaultSquareSize     ();
			$this->allParameters['showCoordinates'] = $this->getDefaultShowCoordinates();
		}
		return $this->allParameters;
	}


	/**
	 * Default square size for the chessboard widgets.
	 *
	 * @return int
	 */
	public function getDefaultSquareSize()
	{
		if(!isset($this->squareSize)) {
			$value = RPBChessboardHelperValidation::validateSquareSize(get_option('rpbchessboard_squareSize'));
			$this->squareSize = isset($value) ? $value : self::DEFAULT_SQUARE_SIZE;
		}
		return $this->squareSize;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean
	 */
	public function getDefaultShowCoordinates()
	{
		if(!isset($this->showCoordinates)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_showCoordinates'));
			$this->showCoordinates = isset($value) ? $value : self::DEFAULT_SHOW_COORDINATES;
		}
		return $this->showCoordinates;
	}
}
