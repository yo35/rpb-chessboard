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


/**
 * Default general options associated to chessboard and chessgame widgets.
 */
trait RPBChessboardTraitDefaultOptions {

    private $sdoSquareSize;
    private $nboSquareSize;
    private $idoSquareSize;
    private $sdoShowCoordinates;
    private $nboShowCoordinates;
    private $idoShowCoordinates;
    private $sdoShowTurn;
    private $nboShowTurn;
    private $idoShowTurn;
    private $sdoColorset;
    private $nboColorset;
    private $idoColorset;
    private $sdoPieceset;
    private $nboPieceset;
    private $idoPieceset;
    private $diagramAlignment;
    private $pieceSymbols;
    private $navigationBoard;
    private $showPlayButton;
    private $showFlipButton;
    private $showDownloadButton;
    private $animated;
    private $showMoveArrow;
    private $moveArrowColor;

    private static $DEFAULT_SQUARE_SIZE          = 32;
    private static $DEFAULT_SHOW_COORDINATES     = true;
    private static $DEFAULT_SHOW_TURN            = true;
    private static $DEFAULT_COLORSET             = 'original';
    private static $DEFAULT_PIECESET             = 'cburnett';
    private static $DEFAULT_DIAGRAM_ALIGNMENT    = 'center';
    private static $DEFAULT_PIECE_SYMBOLS        = 'localized';
    private static $DEFAULT_NAVIGATION_BOARD     = 'frame';
    private static $DEFAULT_SHOW_PLAY_BUTTON     = true;
    private static $DEFAULT_SHOW_FLIP_BUTTON     = true;
    private static $DEFAULT_SHOW_DOWNLOAD_BUTTON = true;
    private static $DEFAULT_ANIMATED             = true;
    private static $DEFAULT_SHOW_MOVE_ARROW      = true;
    private static $DEFAULT_MOVE_ARROW_COLOR     = 'b';


    /**
     * Default square size for the diagrams.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @return int
     */
    public function getDefaultSquareSize( $key ) {
        $field = self::validateKey( $key, 'SquareSize' );
        if ( ! isset( $this->$field ) ) {
            $value = RPBChessboardHelperValidation::validateInteger( get_option( 'rpbchessboard_' . $field ) );

            // FIXME Until 7.2, there was a single parameter for standalone diagrams, navigation board and inner chess game diagrams.
            if ( ! isset( $value ) ) {
                $value = RPBChessboardHelperValidation::validateInteger( get_option( 'rpbchessboard_squareSize' ) );
            }

            $this->$field = isset( $value ) ? $value : self::$DEFAULT_SQUARE_SIZE;
        }
        return $this->$field;
    }


    /**
     * Default coordinate visible parameter for the diagrams.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @return boolean
     */
    public function getDefaultShowCoordinates( $key ) {
        $field = self::validateKey( $key, 'ShowCoordinates' );
        if ( ! isset( $this->$field ) ) {
            $value = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_' . $field ) );

            // FIXME Until 7.2, there was a single parameter for standalone diagrams, navigation board and inner chess game diagrams.
            if ( ! isset( $value ) ) {
                $value = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showCoordinates' ) );
            }

            $this->$field = isset( $value ) ? $value : self::$DEFAULT_SHOW_COORDINATES;
        }
        return $this->$field;
    }


    /**
     * Default turn flag visible parameter for the diagrams.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @return boolean
     */
    public function getDefaultShowTurn( $key ) {
        $field = self::validateKey( $key, 'ShowTurn' );
        if ( ! isset( $this->$field ) ) {
            $value        = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_' . $field ) );
            $this->$field = isset( $value ) ? $value : self::$DEFAULT_SHOW_TURN;
        }
        return $this->$field;
    }


    /**
     * Default colorset parameter for the diagrams.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @return string
     */
    public function getDefaultColorset( $key ) {
        $field = self::validateKey( $key, 'Colorset' );
        if ( ! isset( $this->$field ) ) {
            $value = RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_' . $field ) );

            // FIXME Until 7.2, there was a single parameter for standalone diagrams, navigation board and inner chess game diagrams.
            if ( ! isset( $value ) ) {
                $value = RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_colorset' ) );
            }

            $this->$field = isset( $value ) ? $value : self::$DEFAULT_COLORSET;
        }
        return $this->$field;
    }


    /**
     * Default pieceset parameter for the diagrams.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @return string
     */
    public function getDefaultPieceset( $key ) {
        $field = self::validateKey( $key, 'Pieceset' );
        if ( ! isset( $this->$field ) ) {
            $value = RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_' . $field ) );

            // FIXME Until 7.2, there was a single parameter for standalone diagrams, navigation board and inner chess game diagrams.
            if ( ! isset( $value ) ) {
                $value = RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_pieceset' ) );
            }

            $this->$field = isset( $value ) ? $value : self::$DEFAULT_PIECESET;
        }
        return $this->$field;
    }


    /**
     * Check whether the given colorset is the default one or not.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @param string $colorset
     * @return boolean
     */
    public function isDefaultColorset( $key, $colorset ) {
        return $this->getDefaultColorset( $key ) === $colorset;
    }


    /**
     * Check whether the given pieceset is the default one or not.
     *
     * @param string $key `sdo`, `nbo` or `ido` for respectively the standalone diagrams, the navigation boards, and the inner diagrams.
     * @param string $pieceset
     * @return boolean
     */
    public function isDefaultPieceset( $key, $pieceset ) {
        return $this->getDefaultPieceset( $key ) === $pieceset;
    }


    private static function validateKey( $key, $fieldName ) {
        if ( 'sdo' === $key || 'nbo' === $key || 'ido' === $key ) {
            return $key . $fieldName;
        } else {
            throw new Exception( 'Invalid board setting key: ' . $key );
        }
    }


    /**
     * Default diagram alignment parameter for chessboard widgets.
     *
     * @return string
     */
    public function getDefaultDiagramAlignment() {
        if ( ! isset( $this->diagramAlignment ) ) {
            $value                  = RPBChessboardHelperValidation::validateDiagramAlignment( get_option( 'rpbchessboard_diagramAlignment' ) );
            $this->diagramAlignment = isset( $value ) ? $value : self::$DEFAULT_DIAGRAM_ALIGNMENT;
        }
        return $this->diagramAlignment;
    }


    /**
     * Default move notation mode.
     *
     * @return string
     */
    public function getDefaultPieceSymbols() {
        if ( ! isset( $this->pieceSymbols ) ) {
            $value              = RPBChessboardHelperValidation::validatePieceSymbols( get_option( 'rpbchessboard_pieceSymbols' ) );
            $this->pieceSymbols = isset( $value ) ? $value : self::$DEFAULT_PIECE_SYMBOLS;
        }
        return $this->pieceSymbols;
    }


    /**
     * Default navigation board position.
     *
     * @return string
     */
    public function getDefaultNavigationBoard() {
        if ( ! isset( $this->navigationBoard ) ) {
            $value                 = RPBChessboardHelperValidation::validateNavigationBoard( get_option( 'rpbchessboard_navigationBoard' ) );
            $this->navigationBoard = isset( $value ) ? $value : self::$DEFAULT_NAVIGATION_BOARD;
        }
        return $this->navigationBoard;
    }


    /**
     * Whether the play/stop button in the navigation toolbar should be visible or not.
     *
     * @return boolean
     */
    public function getDefaultShowPlayButton() {
        if ( ! isset( $this->showPlayButton ) ) {
            $value                = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showPlayButton' ) );
            $this->showPlayButton = isset( $value ) ? $value : self::$DEFAULT_SHOW_PLAY_BUTTON;
        }
        return $this->showPlayButton;
    }


    /**
     * Whether the flip button in the navigation toolbar should be visible or not.
     *
     * @return boolean
     */
    public function getDefaultShowFlipButton() {
        if ( ! isset( $this->showFlipButton ) ) {
            $value                = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showFlipButton' ) );
            $this->showFlipButton = isset( $value ) ? $value : self::$DEFAULT_SHOW_FLIP_BUTTON;
        }
        return $this->showFlipButton;
    }


    /**
     * Whether the download button in the navigation toolbar should be visible or not.
     *
     * @return boolean
     */
    public function getDefaultShowDownloadButton() {
        if ( ! isset( $this->showDownloadButton ) ) {
            $value                    = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showDownloadButton' ) );
            $this->showDownloadButton = isset( $value ) ? $value : self::$DEFAULT_SHOW_DOWNLOAD_BUTTON;
        }
        return $this->showDownloadButton;
    }


    /**
     * Whether the moves are animated by default or not.
     *
     * @return boolean
     */
    public function getDefaultAnimated() {
        if ( ! isset( $this->animated ) ) {
            $value = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_animated' ) );

            // FIXME Compatibility with the parameter `animationSpeed` (deprecated since 6.0).
            if ( ! isset( $value ) ) {
                $animationSpeed = RPBChessboardHelperValidation::validateInteger( get_option( 'rpbchessboard_animationSpeed' ) );
                if ( isset( $animationSpeed ) ) {
                    $value = $animationSpeed > 0;
                }
            }

            $this->animated = isset( $value ) ? $value : self::$DEFAULT_ANIMATED;
        }
        return $this->animated;
    }


    /**
     * Default show-move-arrow parameter.
     *
     * @return boolean
     */
    public function getDefaultShowMoveArrow() {
        if ( ! isset( $this->showMoveArrow ) ) {
            $value               = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_showMoveArrow' ) );
            $this->showMoveArrow = isset( $value ) ? $value : self::$DEFAULT_SHOW_MOVE_ARROW;
        }
        return $this->showMoveArrow;
    }


    /**
     * Default move-arrow-color parameter.
     *
     * @return string
     */
    public function getDefaultMoveArrowColor() {
        if ( ! isset( $this->moveArrowColor ) ) {
            $value                = RPBChessboardHelperValidation::validateSymbolicColor( get_option( 'rpbchessboard_moveArrowColor' ) );
            $this->moveArrowColor = isset( $value ) ? $value : self::$DEFAULT_MOVE_ARROW_COLOR;
        }
        return $this->moveArrowColor;
    }

}
