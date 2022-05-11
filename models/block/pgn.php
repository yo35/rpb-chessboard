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
 * Model associated to the PGN block.
 */
class RPBChessboardModelBlockPGN extends RPBChessboardAbstractModelBlock {

	private $widgetArgs;


	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		$this->loadDelegateModel( 'Common/DefaultOptions' );
	}


	/**
	 * Return the arguments to pass to the JS chessgame widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {
		if ( ! isset( $this->widgetArgs ) ) {
			$atts             = $this->getAttributes();
			$this->widgetArgs = array();

			// Chessgame content
			if ( isset( $atts['pgn'] ) ) {
				$this->widgetArgs['pgn'] = $atts['pgn'];
			}

			// Chessgame aspect
			$this->widgetArgs['pieceSymbols']    = isset( $atts['pieceSymbols'] ) ? $atts['pieceSymbols'] : $this->getDefaultPieceSymbols();
			$this->widgetArgs['navigationBoard'] = isset( $atts['navigationBoard'] ) ? $atts['navigationBoard'] : $this->getDefaultNavigationBoard();

			$this->widgetArgs['diagramOptions']         = array();
			$this->widgetArgs['navigationBoardOptions'] = array();
			// TODO map the other attributes
		}
		return $this->widgetArgs;
	}

}
