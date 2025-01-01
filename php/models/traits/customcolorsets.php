<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2025  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * User-defined (aka. custom) colorsets and related parameters.
 */
trait RPBChessboardTraitCustomColorsets {

    private $availableColorsets;
    private $customColorsets;
    private $customColorsetLabels     = array();
    private $customColorsetAttributes = array();

    private static $DEFAULT_DARK_SQUARE_COLOR   = '#bbbbbb';
    private static $DEFAULT_LIGHT_SQUARE_COLOR  = '#f8f8f8';
    private static $DEFAULT_GREEN_MARKER_COLOR  = '#00ff00';
    private static $DEFAULT_RED_MARKER_COLOR    = '#ff0000';
    private static $DEFAULT_YELLOW_MARKER_COLOR = '#ffff00';
    private static $DEFAULT_BLUE_MARKER_COLOR   = '#0044ff';

    private static $BUILTIN_COLORSETS = array(
        'coral'      => 'Coral',
        'dusk'       => 'Dusk',
        'emerald'    => 'Emerald',
        'gray'       => 'Gray',
        'marine'     => 'Marine',
        'original'   => 'Original',
        'sandcastle' => 'Sandcastle',
        'scid'       => 'Scid',
        'wikipedia'  => 'Wikipedia',
        'wheat'      => 'Wheat',
        'xboard'     => 'XBoard',
    );


    /**
     * Return all the available colorsets.
     *
     * @return array
     */
    public function getAvailableColorsets() {
        if ( ! isset( $this->availableColorsets ) ) {
            $builtinColorsets         = array_keys( self::$BUILTIN_COLORSETS );
            $customColorsets          = $this->getCustomColorsets();
            $this->availableColorsets = array_merge( $builtinColorsets, $customColorsets );
            asort( $this->availableColorsets );
        }
        return $this->availableColorsets;
    }


    /**
     * Return the user-defined (aka. custom) colorsets.
     *
     * @return array
     */
    public function getCustomColorsets() {
        if ( ! isset( $this->customColorsets ) ) {
            $value                 = RPBChessboardHelperValidation::validateSetCodeList( get_option( 'rpbchessboard_custom_colorsets' ) );
            $this->customColorsets = isset( $value ) ? $value : array();
        }
        return $this->customColorsets;
    }


    /**
     * Check whether the given colorset is built-in or not.
     *
     * @return boolean
     */
    public function isBuiltinColorset( $colorset ) {
        return isset( self::$BUILTIN_COLORSETS[ $colorset ] );
    }


    /**
     * Return the label of the given colorset.
     *
     * @return string
     */
    public function getColorsetLabel( $colorset ) {
        if ( $this->isBuiltinColorset( $colorset ) ) {
            return self::$BUILTIN_COLORSETS[ $colorset ];
        } else {
            $result = $this->getCustomColorsetLabel( $colorset );
            return '' === $result ? __( '(no name)', 'rpb-chessboard' ) : $result;
        }
    }


    /**
     * Return the label of the given custom colorset.
     *
     * @return string
     */
    private function getCustomColorsetLabel( $customColorset ) {
        if ( ! isset( $this->customColorsetLabels[ $customColorset ] ) ) {
            $value = get_option( 'rpbchessboard_custom_colorset_label_' . $customColorset, null );
            $this->customColorsetLabels[ $customColorset ] = isset( $value ) ? $value : ucfirst( str_replace( '-', ' ', $customColorset ) );
        }
        return $this->customColorsetLabels[ $customColorset ];
    }


    /**
     * Return the dark-square color defined for the given colorset.
     *
     * @return string
     */
    public function getDarkSquareColor( $customColorset ) {
        $this->initializeCustomColorsetAttributes( $customColorset );
        return $this->customColorsetAttributes[ $customColorset ]->darkSquareColor;
    }


    /**
     * Return the light-square color defined for the given colorset.
     *
     * @return string
     */
    public function getLightSquareColor( $customColorset ) {
        $this->initializeCustomColorsetAttributes( $customColorset );
        return $this->customColorsetAttributes[ $customColorset ]->lightSquareColor;
    }


    /**
     * Return the green marker color defined for the given colorset.
     *
     * @return string
     */
    public function getGreenMarkerColor( $customColorset ) {
        $this->initializeCustomColorsetAttributes( $customColorset );
        return $this->customColorsetAttributes[ $customColorset ]->greenMarkerColor;
    }


    /**
     * Return the red marker color defined for the given colorset.
     *
     * @return string
     */
    public function getRedMarkerColor( $customColorset ) {
        $this->initializeCustomColorsetAttributes( $customColorset );
        return $this->customColorsetAttributes[ $customColorset ]->redMarkerColor;
    }


    /**
     * Return the yellow marker color defined for the given colorset.
     *
     * @return string
     */
    public function getYellowMarkerColor( $customColorset ) {
        $this->initializeCustomColorsetAttributes( $customColorset );
        return $this->customColorsetAttributes[ $customColorset ]->yellowMarkerColor;
    }


    /**
     * Return the blue marker color defined for the given colorset.
     *
     * @return string
     */
    public function getBlueMarkerColor( $customColorset ) {
        $this->initializeCustomColorsetAttributes( $customColorset );
        return $this->customColorsetAttributes[ $customColorset ]->blueMarkerColor;
    }


    private function initializeCustomColorsetAttributes( $customColorset ) {
        if ( isset( $this->customColorsetAttributes[ $customColorset ] ) ) {
            return;
        }

        // Default attributes
        $this->customColorsetAttributes[ $customColorset ] = (object) array(
            'darkSquareColor'   => self::$DEFAULT_DARK_SQUARE_COLOR,
            'lightSquareColor'  => self::$DEFAULT_LIGHT_SQUARE_COLOR,
            'greenMarkerColor'  => self::$DEFAULT_GREEN_MARKER_COLOR,
            'redMarkerColor'    => self::$DEFAULT_RED_MARKER_COLOR,
            'yellowMarkerColor' => self::$DEFAULT_YELLOW_MARKER_COLOR,
            'blueMarkerColor'   => self::$DEFAULT_BLUE_MARKER_COLOR,
        );

        // Retrieve the attributes from the database
        $values = explode( '|', get_option( 'rpbchessboard_custom_colorset_attributes_' . $customColorset, '' ) );

        // First 2 tokens: dark and light squares
        if ( count( $values ) >= 2 ) {
            $darkSquareColor  = RPBChessboardHelperValidation::validateColor( $values[0] );
            $lightSquareColor = RPBChessboardHelperValidation::validateColor( $values[1] );
            if ( isset( $darkSquareColor ) ) {
                $this->customColorsetAttributes[ $customColorset ]->darkSquareColor = $darkSquareColor;
            }
            if ( isset( $lightSquareColor ) ) {
                $this->customColorsetAttributes[ $customColorset ]->lightSquareColor = $lightSquareColor;
            }
        }

        // Next 3 tokens: legacy colors (G, R, Y)
        if ( count( $values ) >= 5 ) {
            $greenMarkerColor  = RPBChessboardHelperValidation::validateColor( $values[2] );
            $redMarkerColor    = RPBChessboardHelperValidation::validateColor( $values[3] );
            $yellowMarkerColor = RPBChessboardHelperValidation::validateColor( $values[4] );
            if ( isset( $greenMarkerColor ) ) {
                $this->customColorsetAttributes[ $customColorset ]->greenMarkerColor = $greenMarkerColor;
            }
            if ( isset( $redMarkerColor ) ) {
                $this->customColorsetAttributes[ $customColorset ]->redMarkerColor = $redMarkerColor;
            }
            if ( isset( $yellowMarkerColor ) ) {
                $this->customColorsetAttributes[ $customColorset ]->yellowMarkerColor = $yellowMarkerColor;
            }
        }

        // Next token: blue color
        if ( count( $values ) >= 6 ) {
            $blueMarkerColor = RPBChessboardHelperValidation::validateColor( $values[5] );
            if ( isset( $blueMarkerColor ) ) {
                $this->customColorsetAttributes[ $customColorset ]->blueMarkerColor = $blueMarkerColor;
            }
        }
    }

}
