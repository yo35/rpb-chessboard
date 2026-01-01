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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/postaction/theming/abstract.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/customcolorsets.php';


class RPBChessboardModelPostActionThemingColorset extends RPBChessboardAbstractModelPostActionTheming {

    use RPBChessboardTraitCustomColorsets;


    protected function getCreationSuccessMessage() {
        return __( 'Colorset created.', 'rpb-chessboard' );
    }

    protected function getCreationFailureMessage() {
        return __( 'Error while creating the colorset.', 'rpb-chessboard' );
    }

    protected function getEditionSuccessMessage() {
        return __( 'Colorset updated.', 'rpb-chessboard' );
    }

    protected function getEditionFailureMessage() {
        return __( 'Error while updating the colorset.', 'rpb-chessboard' );
    }

    protected function getDeletionSuccessMessage() {
        return __( 'Colorset deleted.', 'rpb-chessboard' );
    }

    protected function getDeletionFailureMessage() {
        return __( 'Error while deleting the colorset.', 'rpb-chessboard' );
    }


    protected function getDataType() {
        return 'colorset';
    }


    protected function isAlreadyUsedSlug( $slug ) {
        return in_array( $slug, $this->getAvailableColorsets(), true );
    }


    protected function getCustomSlugs() {
        return $this->getCustomColorsets();
    }


    protected function loadAttributes() {
        $darkSquareColor   = self::getColorValue( 'darkSquareColor' );
        $lightSquareColor  = self::getColorValue( 'lightSquareColor' );
        $greenMarkerColor  = self::getColorValue( 'greenMarkerColor' );
        $redMarkerColor    = self::getColorValue( 'redMarkerColor' );
        $yellowMarkerColor = self::getColorValue( 'yellowMarkerColor' );
        $blueMarkerColor   = self::getColorValue( 'blueMarkerColor' );
        if ( isset( $darkSquareColor ) && isset( $lightSquareColor ) && isset( $greenMarkerColor ) && isset( $redMarkerColor ) && isset( $yellowMarkerColor ) && isset( $blueMarkerColor ) ) {
            return implode( '|', array( $darkSquareColor, $lightSquareColor, $greenMarkerColor, $redMarkerColor, $yellowMarkerColor, $blueMarkerColor ) );
        }
        return null;
    }

    private static function getColorValue( $key ) {
        return isset( $_POST[ $key ] ) ? RPBChessboardHelperValidation::validateColor( $_POST[ $key ] ) : null;
    }
}
