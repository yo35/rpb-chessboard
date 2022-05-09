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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/block.php';


/**
 * Model associated to the FEN block.
 */
class RPBChessboardModelBlockFEN extends RPBChessboardAbstractModelBlock {

	private $widgetArgs;
	private $diagramAlignment;


	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		$this->loadDelegateModel( 'Common/DefaultOptions' );
	}


	/**
	 * Return the arguments to pass to the JS chessboard widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {
		if ( ! isset( $this->widgetArgs ) ) {
			$atts             = $this->getAttributes();
			$this->widgetArgs = array();

			// Chessboard content
			if ( isset( $atts['position'] ) ) {
				$this->widgetArgs['position'] = $atts['position'];
			}
			if ( isset( $atts['squareMarkers'] ) ) {
				$this->widgetArgs['csl'] = $atts['squareMarkers'];
			}
			if ( isset( $atts['arrowMarkers'] ) ) {
				$this->widgetArgs['cal'] = $atts['arrowMarkers'];
			}
			if ( isset( $atts['textMarkers'] ) ) {
				$this->widgetArgs['ctl'] = $atts['textMarkers'];
			}
			if ( isset( $atts['flipped'] ) ) {
				$this->widgetArgs['flip'] = $atts['flipped'];
			}

			// Chessboard aspect
			$this->widgetArgs['squareSize']      = isset( $atts['squareSize'] ) ? $atts['squareSize'] : $this->getDefaultSquareSize();
			$this->widgetArgs['showCoordinates'] = isset( $atts['coordinateVisible'] ) ? $atts['coordinateVisible'] : $this->getDefaultShowCoordinates();
			$this->widgetArgs['colorset']        = isset( $atts['colorset'] ) ? $atts['colorset'] : $this->getDefaultColorset();
			$this->widgetArgs['pieceset']        = isset( $atts['pieceset'] ) ? $atts['pieceset'] : $this->getDefaultPieceset();
		}
		return $this->widgetArgs;
	}


	public function getDiagramAlignment() {
		if ( ! isset( $this->diagramAlignment ) ) {
			$atts                   = $this->getAttributes();
			$this->diagramAlignment = isset( $atts['align'] ) ? $atts['align'] : $this->getDefaultDiagramAlignment();
		}
		return $this->diagramAlignment;
	}

}
