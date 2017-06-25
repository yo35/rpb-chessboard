<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2017  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php');
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * Default general options associated to chessboard and chessgame widgets.
 */
class RPBChessboardModelCommonDefaultOptions extends RPBChessboardAbstractModel
{
	private static $squareSize        ;
	private static $showCoordinates   ;
	private static $colorset          ;
	private static $pieceset          ;
	private static $diagramAlignment  ;
	private static $pieceSymbols      ;
	private static $navigationBoard   ;
	private static $showFlipButton    ;
	private static $showDownloadButton;
	private static $animationSpeed    ;
	private static $showMoveArrow     ;


	const DEFAULT_SQUARE_SIZE          = 32;
	const DEFAULT_SHOW_COORDINATES     = true;
	const DEFAULT_COLORSET             = 'original';
	const DEFAULT_PIECESET             = 'cburnett';
	const DEFAULT_DIAGRAM_ALIGNMENT    = 'center';
	const DEFAULT_PIECE_SYMBOLS        = 'localized';
	const DEFAULT_NAVIGATION_BOARD     = 'frame';
	const DEFAULT_SHOW_FLIP_BUTTON     = true;
	const DEFAULT_SHOW_DOWNLOAD_BUTTON = true;
	const DEFAULT_ANIMATION_SPEED      = 200;
	const DEFAULT_SHOW_MOVE_ARROW      = true;


	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods('getDefaultSquareSize', 'getDefaultShowCoordinates',
			'getDefaultColorset', 'getDefaultPieceset', 'getDefaultDiagramAlignment', 'getDefaultPieceSymbols',
			'getDefaultNavigationBoard', 'getDefaultShowFlipButton', 'getDefaultShowDownloadButton',
			'getDefaultAnimationSpeed', 'getDefaultShowMoveArrow',
			'getDefaultChessboardSettings');
	}


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
	 * Default colorset parameter for the chessboard widgets.
	 *
	 * @return string
	 */
	public function getDefaultColorset() {
		if(!isset(self::$colorset)) {
			$value = RPBChessboardHelperValidation::validateSetCode(get_option('rpbchessboard_colorset'));
			self::$colorset = isset($value) ? $value : self::DEFAULT_COLORSET;

			// FIXME Colorset 'original' was named as 'default' in version 4.3 and 4.3.1.
			if(self::$colorset === 'default') {
				self::$colorset = 'original';
			}
		}
		return self::$colorset;
	}


	/**
	 * Default pieceset parameter for the chessboard widgets.
	 *
	 * @return string
	 */
	public function getDefaultPieceset() {
		if(!isset(self::$pieceset)) {
			$value = RPBChessboardHelperValidation::validateSetCode(get_option('rpbchessboard_pieceset'));
			self::$pieceset = isset($value) ? $value : self::DEFAULT_PIECESET;
		}
		return self::$pieceset;
	}


	/**
	 * Default diagram alignment parameter for chessboard widgets.
	 *
	 * @return string
	 */
	public function getDefaultDiagramAlignment() {
		if(!isset(self::$diagramAlignment)) {
			$value = RPBChessboardHelperValidation::validateDiagramAlignment(get_option('rpbchessboard_diagramAlignment'));
			self::$diagramAlignment = isset($value) ? $value : self::DEFAULT_DIAGRAM_ALIGNMENT;
		}
		return self::$diagramAlignment;
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
	 * Whether the flip button in the navigation toolbar should be visible or not.
	 *
	 * @return boolean
	 */
	public function getDefaultShowFlipButton() {
		if(!isset(self::$showFlipButton)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_showFlipButton'));
			self::$showFlipButton = isset($value) ? $value : self::DEFAULT_SHOW_FLIP_BUTTON;
		}
		return self::$showFlipButton;
	}


	/**
	 * Whether the download button in the navigation toolbar should be visible or not.
	 *
	 * @return boolean
	 */
	public function getDefaultShowDownloadButton() {
		if(!isset(self::$showDownloadButton)) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt(get_option('rpbchessboard_showDownloadButton'));
			self::$showDownloadButton = isset($value) ? $value : self::DEFAULT_SHOW_DOWNLOAD_BUTTON;
		}
		return self::$showDownloadButton;
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


	/**
	 * Default chessboard settings.
	 */
	public function getDefaultChessboardSettings() {
		return array(
			'squareSize'      => $this->getDefaultSquareSize(),
			'showCoordinates' => $this->getDefaultShowCoordinates(),
			'colorset'        => $this->getDefaultColorset(),
			'pieceset'        => $this->getDefaultPieceset()
		);
	}
}
