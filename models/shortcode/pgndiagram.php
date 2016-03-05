<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/shortcode.php');


/**
 * Model associated to the [pgndiagram] shortcode.
 */
class RPBChessboardModelShortcodePGNDiagram extends RPBChessboardAbstractModelShortcode {

	private $diagramOptions;
	private $diagramOptionsAsString;


	/**
	 * Options specific to the current diagram, that may override the settings defined either
	 * at the [pgn][/pgn] shortcode level or at the global level.
	 *
	 * @return array
	 */
	public function getDiagramOptions() {
		if(!isset($this->diagramOptions)) {
			$this->diagramOptions = array();
			$atts = $this->getAttributes();

			// Orientation
			$value = isset($atts['flip']) ? RPBChessboardHelperValidation::validateBoolean($atts['flip']) : null;
			if(isset($value)) {
				$this->diagramOptions['flip'] = $value;
			}

			// Square size
			$value = isset($atts['square_size']) ? RPBChessboardHelperValidation::validateSquareSize($atts['square_size']) : null;
			if(isset($value)) {
				$this->diagramOptions['squareSize'] = $value;
			}

			// Show coordinates
			$value = isset($atts['show_coordinates']) ? RPBChessboardHelperValidation::validateBoolean($atts['show_coordinates']) : null;
			if(isset($value)) {
				$this->diagramOptions['showCoordinates'] = $value;
			}

			// Colorset
			$value = isset($atts['colorset']) ? RPBChessboardHelperValidation::validateSetCode($atts['colorset']) : null;
			if(isset($value)) {
				$this->diagramOptions['colorset'] = $value;
			}

			// Pieceset
			$value = isset($atts['pieceset']) ? RPBChessboardHelperValidation::validateSetCode($atts['pieceset']) : null;
			if(isset($value)) {
				$this->diagramOptions['pieceset'] = $value;
			}
		}
		return $this->diagramOptions;
	}


	/**
	 * Diagram specific settings, as a string ready to be inlined in its PGN text comment.
	 *
	 * @return string
	 */
	public function getDiagramOptionsAsString() {
		if(!isset($this->diagramOptionsAsString)) {
			$this->diagramOptionsAsString = json_encode($this->getDiagramOptions());
			$this->diagramOptionsAsString = preg_replace('/{|}|\\\\/', '\\\\$0', $this->diagramOptionsAsString);
		}
		return $this->diagramOptionsAsString;
	}
}
