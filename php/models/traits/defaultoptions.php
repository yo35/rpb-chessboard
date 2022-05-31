<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Default general options associated to chessboard and chessgame widgets.
 */
trait RPBChessboardTraitDefaultOptions {

	private $squareSize;
	private $showCoordinates;
	private $colorset;
	private $pieceset;
	private $diagramAlignment;
	private $pieceSymbols;
	private $navigationBoard;
	private $showFlipButton;
	private $showDownloadButton;
	private $animated;
	private $showMoveArrow;

	private static $DEFAULT_SQUARE_SIZE          = 32;
	private static $DEFAULT_SHOW_COORDINATES     = true;
	private static $DEFAULT_COLORSET             = 'original';
	private static $DEFAULT_PIECESET             = 'cburnett';
	private static $DEFAULT_DIAGRAM_ALIGNMENT    = 'center';
	private static $DEFAULT_PIECE_SYMBOLS        = 'localized';
	private static $DEFAULT_NAVIGATION_BOARD     = 'frame';
	private static $DEFAULT_SHOW_FLIP_BUTTON     = true;
	private static $DEFAULT_SHOW_DOWNLOAD_BUTTON = true;
	private static $DEFAULT_ANIMATED             = true;
	private static $DEFAULT_SHOW_MOVE_ARROW      = true;


	/**
	 * Default square size for the chessboard widgets.
	 *
	 * @return int
	 */
	public function getDefaultSquareSize() {
		if ( ! isset( $this->squareSize ) ) {
			$value            = RPBChessboardHelperValidation::validateInteger( get_option( 'rpbchessboard_squareSize' ) );
			$this->squareSize = isset( $value ) ? $value : self::$DEFAULT_SQUARE_SIZE;
		}
		return $this->squareSize;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean
	 */
	public function getDefaultShowCoordinates() {
		if ( ! isset( $this->showCoordinates ) ) {
			$value                 = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showCoordinates' ) );
			$this->showCoordinates = isset( $value ) ? $value : self::$DEFAULT_SHOW_COORDINATES;
		}
		return $this->showCoordinates;
	}


	/**
	 * Default colorset parameter for the chessboard widgets.
	 *
	 * @return string
	 */
	public function getDefaultColorset() {
		if ( ! isset( $this->colorset ) ) {
			$value          = RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_colorset' ) );
			$this->colorset = isset( $value ) ? $value : self::$DEFAULT_COLORSET;

			// FIXME Colorset 'original' was named as 'default' in version 4.3 and 4.3.1.
			if ( 'default' === $this->colorset ) {
				$this->colorset = 'original';
			}
		}
		return $this->colorset;
	}


	/**
	 * Default pieceset parameter for the chessboard widgets.
	 *
	 * @return string
	 */
	public function getDefaultPieceset() {
		if ( ! isset( $this->pieceset ) ) {
			$value          = RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_pieceset' ) );
			$this->pieceset = isset( $value ) ? $value : self::$DEFAULT_PIECESET;
		}
		return $this->pieceset;
	}


	/**
	 * Check whether the given colorset is the default one or not.
	 *
	 * @param string $colorset
	 * @return boolean
	 */
	public function isDefaultColorset( $colorset ) {
		return $this->getDefaultColorset() === $colorset;
	}


	/**
	 * Check whether the given pieceset is the default one or not.
	 *
	 * @param string $pieceset
	 * @return boolean
	 */
	public function isDefaultPieceset( $pieceset ) {
		return $this->getDefaultPieceset() === $pieceset;
	}


	/**
	 * Default diagram alignment parameter for chessboard widgets.
	 *
	 * @return string
	 */
	public function getDefaultDiagramAlignment() {
		if ( ! isset( $this->diagramAlignment ) ) {
			$value                  = RPBChessboardHelperValidation::validateDiagramAlignment( get_option( 'rpbchessboard_diagramAlignment' ) );
			$this->diagramAlignment = isset( $value ) ? $value : self::$DEFAULT_DIAGRAM_ALIGNMENT;
		}
		return $this->diagramAlignment;
	}


	/**
	 * Default move notation mode.
	 *
	 * @return string
	 */
	public function getDefaultPieceSymbols() {
		if ( ! isset( $this->pieceSymbols ) ) {
			$value              = RPBChessboardHelperValidation::validatePieceSymbols( get_option( 'rpbchessboard_pieceSymbols' ) );
			$this->pieceSymbols = isset( $value ) ? $value : self::$DEFAULT_PIECE_SYMBOLS;
		}
		return $this->pieceSymbols;
	}


	/**
	 * Default navigation board position.
	 *
	 * @return string
	 */
	public function getDefaultNavigationBoard() {
		if ( ! isset( $this->navigationBoard ) ) {
			$value                 = RPBChessboardHelperValidation::validateNavigationBoard( get_option( 'rpbchessboard_navigationBoard' ) );
			$this->navigationBoard = isset( $value ) ? $value : self::$DEFAULT_NAVIGATION_BOARD;
		}
		return $this->navigationBoard;
	}


	/**
	 * Whether the flip button in the navigation toolbar should be visible or not.
	 *
	 * @return boolean
	 */
	public function getDefaultShowFlipButton() {
		if ( ! isset( $this->showFlipButton ) ) {
			$value                = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showFlipButton' ) );
			$this->showFlipButton = isset( $value ) ? $value : self::$DEFAULT_SHOW_FLIP_BUTTON;
		}
		return $this->showFlipButton;
	}


	/**
	 * Whether the download button in the navigation toolbar should be visible or not.
	 *
	 * @return boolean
	 */
	public function getDefaultShowDownloadButton() {
		if ( ! isset( $this->showDownloadButton ) ) {
			$value                    = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showDownloadButton' ) );
			$this->showDownloadButton = isset( $value ) ? $value : self::$DEFAULT_SHOW_DOWNLOAD_BUTTON;
		}
		return $this->showDownloadButton;
	}


	/**
	 * Whether the moves are animated by default or not.
	 *
	 * @return boolean
	 */
	public function getDefaultAnimated() {
		if ( ! isset( $this->animated ) ) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_animated' ) );

			// FIXME Compatibility with the parameter `animationSpeed` (deprecated since 6.0).
			if ( ! isset( $value ) ) {
				$animationSpeed = RPBChessboardHelperValidation::validateInteger( get_option( 'rpbchessboard_animationSpeed' ) );
				if ( isset( $animationSpeed ) ) {
					$value = $animationSpeed > 0;
				}
			}

			$this->animated = isset( $value ) ? $value : self::$DEFAULT_ANIMATED;
		}
		return $this->animated;
	}


	/**
	 * Default show-move-arrow parameter.
	 *
	 * @return boolean
	 */
	public function getDefaultShowMoveArrow() {
		if ( ! isset( $this->showMoveArrow ) ) {
			$value               = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showMoveArrow' ) );
			$this->showMoveArrow = isset( $value ) ? $value : self::$DEFAULT_SHOW_MOVE_ARROW;
		}
		return $this->showMoveArrow;
	}


	/**
	 * Default chessboard settings.
	 */
	public function getDefaultChessboardSettings() {
		return array(
			'squareSize'      => $this->getDefaultSquareSize(),
			'showCoordinates' => $this->getDefaultShowCoordinates(),
			'colorset'        => $this->getDefaultColorset(),
			'pieceset'        => $this->getDefaultPieceset(),
			'animated'        => $this->getDefaultAnimated(),
			'showMoveArrow'   => $this->getDefaultShowMoveArrow(),
		);
	}


	/**
	 * Default chessgame settings.
	 */
	public function getDefaultChessgameSettings() {
		$defaultChessboardSettings = $this->getDefaultChessboardSettings();
		return array(
			'pieceSymbols'           => $this->getDefaultPieceSymbols(),
			'navigationBoard'        => $this->getDefaultNavigationBoard(),
			'showFlipButton'         => $this->getDefaultShowFlipButton(),
			'showDownloadButton'     => $this->getDefaultShowDownloadButton(),
			'navigationBoardOptions' => $defaultChessboardSettings,
			'diagramOptions'         => $defaultChessboardSettings,
		);
	}

}
