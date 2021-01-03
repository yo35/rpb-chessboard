<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php';


/**
 * Model to generate the small-screen-specific CSS configuration.
 */
class RPBChessboardModelCSSSmallScreens extends RPBChessboardAbstractModel {

	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel( 'Common/SmallScreens' );
	}


	/**
	 * Whether the square size must be customized in the given small-screen mode or not.
	 *
	 * @return boolean
	 */
	public function hasSquareSizeSection( $mode ) {
		return $mode->squareSize < RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Selector to use to introduce the specific CSS instructions for the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSanitizedMainSelector( $mode ) {
		$res = '@media all';
		if ( $mode->minScreenWidth > 0 ) {
			$res .= ' and (min-width:' . ( $mode->minScreenWidth + 1 ) . 'px)';
		}
		$res .= ' and (max-width:' . $mode->maxScreenWidth . 'px)';
		return $res;
	}


	/**
	 * Selector to use to introduce the specific CSS instructions to customize the square size in the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSanitizedSquareSizeSelector( $mode ) {
		$selectors = array();
		for ( $size = $mode->squareSize + 1; $size <= RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE; ++$size ) {
			array_push( $selectors, '.rpbui-chessboard-size' . $size . ' .rpbui-chessboard-sized' );
		}
		return implode( ', ', $selectors );
	}


	/**
	 * Selector to use to introduce the specific CSS instructions to customize the annotation layer in the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSanitizedAnnotationLayerSelector( $mode ) {
		$selectors = array();
		for ( $size = $mode->squareSize + 1; $size <= RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE; ++$size ) {
			array_push( $selectors, '.rpbui-chessboard-size' . $size . ' .rpbui-chessboard-annotations' );
		}
		return implode( ', ', $selectors );
	}


	/**
	 * Return the height and width to use for the annotation layer when using the given square size.
	 *
	 * @param int $squareSize
	 * @return int
	 */
	public function getHeightWidthForAnnotationLayer( $squareSize ) {
		return $squareSize * 8;
	}


	/**
	 * Return the x-offset (from right) to use for the annotation layer when using the given square size.
	 *
	 * @param int $squareSize
	 * @return int
	 */
	public function getRightForAnnotationLayer( $squareSize ) {
		return $squareSize + 8;
	}
}
