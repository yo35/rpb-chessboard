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
	private static $animationSpeed ;
	private static $showMoveArrow  ;


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
	 * Initial animation speed.
	 */
	const DEFAULT_ANIMATION_SPEED = 200;


	/**
	 * Initial value for the show-move-arrow parameter.
	 */
	const DEFAULT_SHOW_MOVE_ARROW = true;


	/**
	 * Default square size for the chessboard widgets.
	 *
	 * @return int
	 */
	public function getDefaultSquareSize() {
		if(!isset(self::$squareSize)) {
			$value = RPBChessboardHelperValidation::validateSquareSize(get_option('rpbchessboard_squareSize'));
			self::$squareSize = isset($value) ? $value : self::DEFAULT_SQUARE_SIZE;
		}
		return self::$squareSize;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean
	 */
	public function getDefaultShowCoordinates() {
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
	public function getDefaultPieceSymbols() {
		if(!isset(self::$pieceSymbols)) {
			$value = RPBChessboardHelperValidation::validatePieceSymbols(get_option('rpbchessboard_pieceSymbols'));
			self::$pieceSymbols = isset($value) ? $value : self::DEFAULT_PIECE_SYMBOLS;
		}
		return self::$pieceSymbols;
	}


	/**
	 * Default navigation board position.
	 *
	 * @return string
	 */
	public function getDefaultNavigationBoard() {
		if(!isset(self::$navigationBoard)) {
			$value = RPBChessboardHelperValidation::validateNavigationBoard(get_option('rpbchessboard_navigationBoard'));
			self::$navigationBoard = isset($value) ? $value : self::DEFAULT_NAVIGATION_BOARD;
		}
		return self::$navigationBoard;
	}


	/**
	 * Default animation speed.
	 *
	 * @return int
	 */
	public function getDefaultAnimationSpeed() {
		if(!isset(self::$animationSpeed)) {
			$value = RPBChessboardHelperValidation::validateAnimationSpeed(get_option('rpbchessboard_animationSpeed'));
			self::$animationSpeed = isset($value) ? $value : self::DEFAULT_ANIMATION_SPEED;
		}
		return self::$animationSpeed;
	}


	/**
	 * Default show-move-arrow parameter.
	 *
	 * @return boolean
	 */
	public function getDefaultShowMoveArrow() {
		if(!isset(self::$showMoveArrow)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_showMoveArrow'));
			self::$showMoveArrow = isset($value) ? $value : self::DEFAULT_SHOW_MOVE_ARROW;
		}
		return self::$showMoveArrow;
	}
}
