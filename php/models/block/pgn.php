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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/block/abstract.php';


/**
 * Model associated to the PGN block.
 */
class RPBChessboardModelBlockPGN extends RPBChessboardAbstractModelBlock {

	/**
	 * Return the arguments to pass to the JS chessgame widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {

		$atts       = $this->getAttributes();
		$mainModel  = rpbchessboard_main_model();
		$widgetArgs = array();

		// Chessgame content
		if ( isset( $atts['pgn'] ) ) {
			$widgetArgs['pgn'] = $atts['pgn'];
		}
		if ( isset( $atts['flipped'] ) ) {
			$widgetArgs['flipped'] = $atts['flipped'];
		}
		if ( isset( $atts['initialSelection'] ) ) {
			$widgetArgs['initialSelection'] = $atts['initialSelection'];
		}

		// Chessgame aspect
		$widgetArgs['pieceSymbols']       = isset( $atts['pieceSymbols'] ) ? $atts['pieceSymbols'] : $mainModel->getDefaultPieceSymbols();
		$widgetArgs['navigationBoard']    = isset( $atts['navigationBoard'] ) ? $atts['navigationBoard'] : $mainModel->getDefaultNavigationBoard();
		$widgetArgs['withFlipButton']     = isset( $atts['withFlipButton'] ) ? 'true' === $atts['withFlipButton'] : $mainModel->getDefaultShowFlipButton();
		$widgetArgs['withDownloadButton'] = isset( $atts['withDownloadButton'] ) ? 'true' === $atts['withDownloadButton'] : $mainModel->getDefaultShowDownloadButton();

		// Specific options for the navigation board.
		$widgetArgs['nboSquareSize']        = isset( $atts['nboSquareSize'] ) ? $atts['nboSquareSize'] : $mainModel->getDefaultSquareSize();
		$widgetArgs['nboCoordinateVisible'] = isset( $atts['nboCoordinateVisible'] ) ? 'true' === $atts['nboCoordinateVisible'] : $mainModel->getDefaultShowCoordinates();
		$widgetArgs['nboColorset']          = isset( $atts['nboColorset'] ) ? $atts['nboColorset'] : $mainModel->getDefaultColorset();
		$widgetArgs['nboPieceset']          = isset( $atts['nboPieceset'] ) ? $atts['nboPieceset'] : $mainModel->getDefaultPieceset();
		$widgetArgs['nboAnimated']          = isset( $atts['nboAnimated'] ) ? 'true' === $atts['nboAnimated'] : $mainModel->getDefaultAnimated();
		$widgetArgs['nboMoveArrowVisible']  = isset( $atts['nboMoveArrowVisible'] ) ? 'true' === $atts['nboMoveArrowVisible'] : $mainModel->getDefaultShowMoveArrow();

		// Specific options for the diagrams.
		$widgetArgs['idoSquareSize']        = isset( $atts['idoSquareSize'] ) ? $atts['idoSquareSize'] : $mainModel->getDefaultSquareSize();
		$widgetArgs['idoCoordinateVisible'] = isset( $atts['idoCoordinateVisible'] ) ? 'true' === $atts['idoCoordinateVisible'] : $mainModel->getDefaultShowCoordinates();
		$widgetArgs['idoColorset']          = isset( $atts['idoColorset'] ) ? $atts['idoColorset'] : $mainModel->getDefaultColorset();
		$widgetArgs['idoPieceset']          = isset( $atts['idoPieceset'] ) ? $atts['idoPieceset'] : $mainModel->getDefaultPieceset();

		return $widgetArgs;
	}

}
