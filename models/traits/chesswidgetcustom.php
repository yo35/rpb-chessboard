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
class RPBChessboardTraitChessWidgetCustom extends RPBChessboardAbstractTrait
{
	private $atts;
	private $flipDefined            = false;
	private $squareSizeDefined      = false;
	private $showCoordinatesDefined = false;
	private $flip           ;
	private $squareSize     ;
	private $showCoordinates;


	/**
	 * Constructor.
	 *
	 * @param array $atts
	 */
	public function __construct($atts)
	{
		$this->atts = $atts;
	}


	/**
	 * Custom flip-board parameter for the chessboard widgets.
	 *
	 * @return boolean May be null if this parameter is let undefined.
	 */
	public function getCustomFlip()
	{
		if(!$this->flipDefined) {
			$this->flip = RPBChessboardHelperValidation::validateFlip($this->atts['flip']);
			$this->flipDefined = true;
		}
		return $this->flip;
	}


	/**
	 * Custom square size for the chessboard widgets.
	 *
	 * @return int May be null if this parameter is let undefined.
	 */
	public function getCustomSquareSize()
	{
		if(!$this->squareSizeDefined) {
			$this->squareSize = RPBChessboardHelperValidation::validateSquareSize($this->atts['square_size']);
			$this->squareSizeDefined = true;
		}
		return $this->squareSize;
	}


	/**
	 * Custom show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean May be null if this parameter is let undefined.
	 */
	public function getCustomShowCoordinates()
	{
		if(!$this->showCoordinatesDefined) {
			$this->showCoordinates = RPBChessboardHelperValidation::validateBoolean($this->atts['show_coordinates']);
			$this->showCoordinatesDefined = true;
		}
		return $this->showCoordinates;
	}
}
