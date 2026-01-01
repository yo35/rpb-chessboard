<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2026  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Model associated to the [fen][/fen] shortcode.
 */
class RPBChessboardModelBlockShortcodeFEN extends RPBChessboardAbstractModelBlock {

    /**
     * Return the arguments to pass to the JS chessboard widget.
     *
     * @return array
     */
    public function getWidgetArgs() {

        $atts       = $this->getAttributes();
        $widgetArgs = array();

        // Chessboard content
        $widgetArgs['position'] = $this->getContent();
        if ( isset( $atts['csl'] ) && is_string( $atts['csl'] ) ) {
            $widgetArgs['squareMarkers'] = $atts['csl'];
        }
        if ( isset( $atts['cal'] ) && is_string( $atts['cal'] ) ) {
            $widgetArgs['arrowMarkers'] = $atts['cal'];
        }
        if ( isset( $atts['ctl'] ) && is_string( $atts['ctl'] ) ) {
            $widgetArgs['textMarkers'] = $atts['ctl'];
        }

        // Content customization
        $value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['flip'] ) : null;
        if ( isset( $value ) ) {
            $widgetArgs['flipped'] = $value;
        }

        // Chessboard aspect
        $value                           = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['square_size'] ) : null;
        $widgetArgs['squareSize']        = isset( $value ) ? $value : $this->mainModel->getDefaultSquareSize( 'sdo' );
        $value                           = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_coordinates'] ) : null;
        $widgetArgs['coordinateVisible'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowCoordinates( 'sdo' );
        $value                           = isset( $atts['show_turn'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_turn'] ) : null;
        $widgetArgs['turnVisible']       = isset( $value ) ? $value : $this->mainModel->getDefaultShowTurn( 'sdo' );
        $value                           = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['colorset'] ) : null;
        $widgetArgs['colorset']          = isset( $value ) ? $value : $this->mainModel->getDefaultColorset( 'sdo' );
        $value                           = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['pieceset'] ) : null;
        $widgetArgs['pieceset']          = isset( $value ) ? $value : $this->mainModel->getDefaultPieceset( 'sdo' );

        return $widgetArgs;
    }


    /**
     * Diagram alignment code.
     *
     * @return string
     */
    public function getDiagramAlignment() {
        $atts  = $this->getAttributes();
        $value = isset( $atts['align'] ) ? RPBChessboardHelperValidation::validateDiagramAlignment( $atts['align'] ) : null;
        return isset( $value ) ? $value : $this->mainModel->getDefaultDiagramAlignment();
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
