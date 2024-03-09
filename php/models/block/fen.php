<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2024  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Model associated to the FEN block.
 */
class RPBChessboardModelBlockFEN extends RPBChessboardAbstractModelBlock {

    /**
     * Return the arguments to pass to the JS chessboard widget.
     *
     * @return array
     */
    public function getWidgetArgs() {

        $atts       = $this->getAttributes();
        $widgetArgs = array();

        // Chessboard content
        $widgetArgs['position'] = isset( $atts['position'] ) ? $atts['position'] : 'start';
        if ( isset( $atts['squareMarkers'] ) ) {
            $widgetArgs['squareMarkers'] = $atts['squareMarkers'];
        }
        if ( isset( $atts['arrowMarkers'] ) ) {
            $widgetArgs['arrowMarkers'] = $atts['arrowMarkers'];
        }
        if ( isset( $atts['textMarkers'] ) ) {
            $widgetArgs['textMarkers'] = $atts['textMarkers'];
        }

        // Content customization
        if ( isset( $atts['flipped'] ) ) {
            $widgetArgs['flipped'] = $atts['flipped'];
        }

        // Chessboard aspect
        $widgetArgs['squareSize']        = isset( $atts['squareSize'] ) ? $atts['squareSize'] : $this->mainModel->getDefaultSquareSize( 'sdo' );
        $widgetArgs['coordinateVisible'] = isset( $atts['coordinateVisible'] ) ? 'true' === $atts['coordinateVisible'] : $this->mainModel->getDefaultShowCoordinates( 'sdo' );
        $widgetArgs['turnVisible']       = isset( $atts['turnVisible'] ) ? 'true' === $atts['turnVisible'] : $this->mainModel->getDefaultShowTurn( 'sdo' );
        $widgetArgs['colorset']          = isset( $atts['colorset'] ) ? $atts['colorset'] : $this->mainModel->getDefaultColorset( 'sdo' );
        $widgetArgs['pieceset']          = isset( $atts['pieceset'] ) ? $atts['pieceset'] : $this->mainModel->getDefaultPieceset( 'sdo' );

        return $widgetArgs;
    }


    /**
     * Diagram alignment code.
     *
     * @return string
     */
    public function getDiagramAlignment() {
        $atts = $this->getAttributes();
        return isset( $atts['align'] ) ? $atts['align'] : $this->mainModel->getDefaultDiagramAlignment();
    }

}
