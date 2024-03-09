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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/adminsubpage/abstractform.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/customcolorsets.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/custompiecesets.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/defaultoptions.php';


/**
 * Delegate model for the sub-page 'chess-game-settings'.
 */
class RPBChessboardModelAdminSubPageChessGameSettings extends RPBChessboardAbstractModelAdminSubPageForm {

    use RPBChessboardTraitCustomColorsets, RPBChessboardTraitCustomPiecesets, RPBChessboardTraitDefaultOptions;

    private $pieceSymbolCustomValues;


    public function getFormSubmitAction() {
        return 'Settings/ChessGame:update';
    }

    public function getFormResetAction() {
        return 'Settings/ChessGame:reset';
    }

    public function getFormTemplateName() {
        return 'chess-game-settings';
    }


    /**
     * Whether the localization is available for piece symbols or not.
     *
     * @return boolean
     */
    public function isPieceSymbolLocalizationAvailable() {
        // phpcs:disable
        return
            /*i18n King symbol   */ __( 'K', 'rpb-chessboard' ) !== 'K' ||
            /*i18n Queen symbol  */ __( 'Q', 'rpb-chessboard' ) !== 'Q' ||
            /*i18n Rook symbol   */ __( 'R', 'rpb-chessboard' ) !== 'R' ||
            /*i18n Bishop symbol */ __( 'B', 'rpb-chessboard' ) !== 'B' ||
            /*i18n Knight symbol */ __( 'N', 'rpb-chessboard' ) !== 'N' ||
            /*i18n Pawn symbol   */ __( 'P', 'rpb-chessboard' ) !== 'P';
        // phpcs:enable
    }


    /**
     * Type of piece symbols currently selected.
     *
     * @return string
     */
    public function getPieceSymbolMode() {
        switch ( $this->getDefaultPieceSymbols() ) {
            case 'native':
                return 'english';
            case 'figurines':
                return 'figurines';
            case 'localized':
                return $this->isPieceSymbolLocalizationAvailable() ? 'localized' : 'english';
            default:
                return 'custom';
        }
    }


    /**
     * Default value for the piece symbol custom fields.
     *
     * @param string $piece `'K'`, `'Q'`, `'R'`, `'B'`, `'N'`, or `'P'`.
     * @return string
     */
    public function getPieceSymbolCustomValue( $piece ) {
        if ( ! isset( $this->pieceSymbolCustomValues ) ) {
            if ( preg_match( '/^([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*),([a-zA-Z]*)$/', $this->getDefaultPieceSymbols(), $m ) ) {
                $this->pieceSymbolCustomValues = array(
                    'K' => $m[1],
                    'Q' => $m[2],
                    'R' => $m[3],
                    'B' => $m[4],
                    'N' => $m[5],
                    'P' => $m[6],
                );
            } else {
                $this->pieceSymbolCustomValues = array(
                    'K' => '',
                    'Q' => '',
                    'R' => '',
                    'B' => '',
                    'N' => '',
                    'P' => '',
                );
            }
        }
        return $this->pieceSymbolCustomValues[ $piece ];
    }

}
