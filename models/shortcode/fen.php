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
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/defaultoptions.php';


/**
 * Model associated to the [fen][/fen] shortcode.
 *
 * @deprecated Only for legacy content (old pages/posts that may contain this shortcode).
 */
class RPBChessboardModelShortcodeFEN extends RPBChessboardAbstractModelBlock {

	use RPBChessboardTraitDefaultOptions;

	private $widgetArgs;
	private $diagramAlignment;


	/**
	 * Return the arguments to pass to the JS chessboard widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {
		if ( ! isset( $this->widgetArgs ) ) {
			$this->widgetArgs = array( 'position' => $this->getContent() );
			$atts             = $this->getAttributes();

			// Square markers
			if ( isset( $atts['csl'] ) && is_string( $atts['csl'] ) ) {
				$this->widgetArgs['squareMarkers'] = $atts['csl'];
			}

			// Arrow markers
			if ( isset( $atts['cal'] ) && is_string( $atts['cal'] ) ) {
				$this->widgetArgs['arrowMarkers'] = $atts['cal'];
			}

			// Text markers
			if ( isset( $atts['ctl'] ) && is_string( $atts['ctl'] ) ) {
				$this->widgetArgs['textMarkers'] = $atts['ctl'];
			}

			// Orientation
			$value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['flip'] ) : null;
			if ( isset( $value ) ) {
				$this->widgetArgs['flipped'] = $value;
			}

			// Square size
			$value                          = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['square_size'] ) : null;
			$this->widgetArgs['squareSize'] = isset( $value ) ? $value : $this->getDefaultSquareSize();

			// Show coordinates
			$value                                 = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_coordinates'] ) : null;
			$this->widgetArgs['coordinateVisible'] = isset( $value ) ? $value : $this->getDefaultShowCoordinates();

			// Colorset
			$value                        = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['colorset'] ) : null;
			$this->widgetArgs['colorset'] = isset( $value ) ? $value : $this->getDefaultColorset();

			// Pieceset
			$value                        = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['pieceset'] ) : null;
			$this->widgetArgs['pieceset'] = isset( $value ) ? $value : $this->getDefaultPieceset();
		}
		return $this->widgetArgs;
	}


	public function getDiagramAlignment() {
		if ( ! isset( $this->diagramAlignment ) ) {
			$atts                   = $this->getAttributes();
			$value                  = isset( $atts['align'] ) ? RPBChessboardHelperValidation::validateDiagramAlignment( $atts['align'] ) : null;
			$this->diagramAlignment = isset( $value ) ? $value : $this->getDefaultDiagramAlignment();
		}
		return $this->diagramAlignment;
	}


	/**
	 * Ensure that the FEN string is trimmed.
	 */
	protected function filterShortcodeContent( $content ) {
		$regex = '\s|<br *\/>';
		$regex = "(?:$regex)*";
		return preg_replace( "/^$regex|$regex\$/i", '', $content );
	}
}
