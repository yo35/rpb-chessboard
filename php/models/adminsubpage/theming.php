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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/adminsubpage/abstract.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/customcolorsets.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/custompiecesets.php';


/**
 * Delegate model for the sub-page 'theming'.
 */
class RPBChessboardModelAdminSubPageTheming extends RPBChessboardAbstractModelAdminSubPage {

    use RPBChessboardTraitCustomColorsets, RPBChessboardTraitCustomPiecesets;

    private $piecesetEditionButtonTitle;


    public function getTemplateName() {
        return 'theming';
    }

    public function getFormEditColorsetAction( $isNew ) {
        return 'Theming/Colorset:' . ( $isNew ? 'add' : 'edit' );
    }

    public function getFormDeleteColorsetAction() {
        return 'Theming/Colorset:delete';
    }

    public function getFormEditPiecesetAction( $isNew ) {
        return 'Theming/Pieceset:' . ( $isNew ? 'add' : 'edit' );
    }

    public function getFormDeletePiecesetAction() {
        return 'Theming/Pieceset:delete';
    }


    /**
     * Return a label suggestion when creating a new colorset.
     */
    public function getLabelProposalForNewColorset() {
        $counter = 1;
        $base    = __( 'My colorset', 'rpb-chessboard' );
        $result  = $base;
        while ( $this->isColorsetLabelAlreadyUsed( $result ) ) {
            ++$counter;
            $result = $base . ' ' . $counter;
        }
        return $result;
    }


    private function isColorsetLabelAlreadyUsed( $label ) {
        foreach ( $this->getAvailableColorsets() as $colorset ) {
            if ( $label === $this->getColorsetLabel( $colorset ) ) {
                return true;
            }
        }
        return false;
    }


    /**
     * Return a label suggestion when creating a new pieceset.
     */
    public function getLabelProposalForNewPieceset() {
        $counter = 1;
        $base    = __( 'My pieceset', 'rpb-chessboard' );
        $result  = $base;
        while ( $this->isPiecesetLabelAlreadyUsed( $result ) ) {
            ++$counter;
            $result = $base . ' ' . $counter;
        }
        return $result;
    }


    private function isPiecesetLabelAlreadyUsed( $label ) {
        foreach ( $this->getAvailablePiecesets() as $pieceset ) {
            if ( $label === $this->getPiecesetLabel( $pieceset ) ) {
                return true;
            }
        }
        return false;
    }


    /**
     * Return a random color to be used for dark squares.
     *
     * @return string
     */
    public function getRandomDarkSquareColor() {
        return self::getRandomColor( 0x88, 0xbf, 0x88, 0xbf, 0x88, 0xbf );
    }


    /**
     * Return a random color to be used for light squares.
     *
     * @return string
     */
    public function getRandomLightSquareColor() {
        return self::getRandomColor( 0xc0, 0xf7, 0xc0, 0xf7, 0xc0, 0xf7 );
    }


    /**
     * Return a random color to be used for blue markers.
     *
     * @return string
     */
    public function getRandomBlueMarkerColor() {
        return self::getRandomColor( 0x00, 0x40, 0x00, 0x40, 0xc0, 0xff );
    }


    /**
     * Return a random color to be used for green markers.
     *
     * @return string
     */
    public function getRandomGreenMarkerColor() {
        return self::getRandomColor( 0x00, 0x40, 0xb0, 0xff, 0x00, 0x40 );
    }


    /**
     * Return a random color to be used for red markers.
     *
     * @return string
     */
    public function getRandomRedMarkerColor() {
        return self::getRandomColor( 0xb0, 0xff, 0x00, 0x40, 0x00, 0x40 );
    }


    /**
     * Return a random color to be used for yellow markers.
     *
     * @return string
     */
    public function getRandomYellowMarkerColor() {
        return self::getRandomColor( 0xb0, 0xff, 0xb0, 0xff, 0x00, 0x40 );
    }


    private static function getRandomColor( $redRangeMin, $redRangeMax, $greenRangeMin, $greenRangeMax, $blueRangeMin, $blueRangeMax ) {
        $red   = wp_rand( $redRangeMin, $redRangeMax );
        $green = wp_rand( $greenRangeMin, $greenRangeMax );
        $blue  = wp_rand( $blueRangeMin, $blueRangeMax );
        return sprintf( '#%02x%02x%02x', $red, $green, $blue );
    }


    /**
     * Text to use for the tooltip of the pieceset edition buttons.
     */
    public function getPiecesetEditionButtonTitle( $coloredPiece ) {
        if ( ! isset( $this->piecesetEditionButtonTitle ) ) {
            $this->piecesetEditionButtonTitle = array(
                // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing.SpaceBeforeComma
                'bp' => __( 'Select the image to use for the black pawns'     , 'rpb-chessboard' ),
                'bn' => __( 'Select the image to use for the black knights'   , 'rpb-chessboard' ),
                'bb' => __( 'Select the image to use for the black bishops'   , 'rpb-chessboard' ),
                'br' => __( 'Select the image to use for the black rooks'     , 'rpb-chessboard' ),
                'bq' => __( 'Select the image to use for the black queens'    , 'rpb-chessboard' ),
                'bk' => __( 'Select the image to use for the black kings'     , 'rpb-chessboard' ),
                'bx' => __( 'Select the image to use for the black turn flags', 'rpb-chessboard' ),
                'wp' => __( 'Select the image to use for the white pawns'     , 'rpb-chessboard' ),
                'wn' => __( 'Select the image to use for the white knights'   , 'rpb-chessboard' ),
                'wb' => __( 'Select the image to use for the white bishops'   , 'rpb-chessboard' ),
                'wr' => __( 'Select the image to use for the white rooks'     , 'rpb-chessboard' ),
                'wq' => __( 'Select the image to use for the white queens'    , 'rpb-chessboard' ),
                'wk' => __( 'Select the image to use for the white kings'     , 'rpb-chessboard' ),
                'wx' => __( 'Select the image to use for the white turn flags', 'rpb-chessboard' ),
                // phpcs:enable Generic.Functions.FunctionCallArgumentSpacing.SpaceBeforeComma
            );
        }
        return $this->piecesetEditionButtonTitle[ $coloredPiece ];
    }


    /**
     * URL to the image to use for the pieceset edition buttons.
     */
    public function getPiecesetEditionButtonImage( $pieceset, $coloredPiece ) {
        return '' === $pieceset || $this->getCustomPiecesetImageId( $pieceset, $coloredPiece ) < 0 ? self::getEmptyPiecesetImageURL( $coloredPiece ) :
            $this->getCustomPiecesetImageURL( $pieceset, $coloredPiece );
    }


    private static function getEmptyPiecesetImageURL( $coloredPiece ) {
        return RPBCHESSBOARD_URL . 'images/undefined-' . $coloredPiece . '.png';
    }

}
