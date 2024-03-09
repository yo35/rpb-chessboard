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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/postaction/theming/abstract.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/custompiecesets.php';


class RPBChessboardModelPostActionThemingPieceset extends RPBChessboardAbstractModelPostActionTheming {

    use RPBChessboardTraitCustomPiecesets;


    protected function getCreationSuccessMessage() {
        return __( 'Pieceset created.', 'rpb-chessboard' );
    }

    protected function getCreationFailureMessage() {
        return __( 'Error while creating the pieceset.', 'rpb-chessboard' );
    }

    protected function getEditionSuccessMessage() {
        return __( 'Pieceset updated.', 'rpb-chessboard' );
    }

    protected function getEditionFailureMessage() {
        return __( 'Error while updating the pieceset.', 'rpb-chessboard' );
    }

    protected function getDeletionSuccessMessage() {
        return __( 'Pieceset deleted.', 'rpb-chessboard' );
    }

    protected function getDeletionFailureMessage() {
        return __( 'Error while deleting the pieceset.', 'rpb-chessboard' );
    }


    protected function getDataType() {
        return 'pieceset';
    }


    protected function isAlreadyUsedSlug( $slug ) {
        return in_array( $slug, $this->getAvailablePiecesets(), true );
    }


    protected function getCustomSlugs() {
        return $this->getCustomPiecesets();
    }


    protected function loadAttributes() {
        $values = array();
        foreach ( self::$COLORED_PIECE_CODES as $coloredPiece ) {
            $id = self::getImageId( $coloredPiece );
            if ( ! isset( $id ) ) {
                return null;
            }
            $values[] = $id;
        }
        return implode( '|', $values );
    }


    /**
     * Return the attachment ID defined for the given colored piece.
     */
    private static function getImageId( $coloredPiece ) {
        $key = 'imageId-' . $coloredPiece;
        return isset( $_POST[ $key ] ) ? RPBChessboardHelperValidation::validateInteger( $_POST[ $key ] ) : null;
    }
}
