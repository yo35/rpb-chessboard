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


/**
 * Specific settings to deal with small-screen devices (such as smartphones).
 */
trait RPBChessboardTraitSmallScreens {

    private $smallScreenCompatibility;
    private $smallScreenModes;


    /**
     * Whether the small-screen compatibility mode is enabled or not.
     *
     * @return boolean
     */
    public function getSmallScreenCompatibility() {
        if ( ! isset( $this->smallScreenCompatibility ) ) {
            $value                          = RPBChessboardHelperValidation::validateBooleanFromInt( get_option( 'rpbchessboard_smallScreenCompatibility' ) );
            $this->smallScreenCompatibility = isset( $value ) ? $value : true;
        }
        return $this->smallScreenCompatibility;
    }


    /**
     * Return the small-screen modes.
     *
     * @return array
     */
    public function getSmallScreenModes() {
        if ( ! isset( $this->smallScreenModes ) ) {
            $this->loadSmallScreenModes();
        }
        return $this->smallScreenModes;
    }


    /**
     * Load the small-screen mode specifications.
     */
    private function loadSmallScreenModes() {

        // Load the raw data
        $data = RPBChessboardHelperValidation::validateSmallScreenModes( get_option( 'rpbchessboard_smallScreenModes' ) );
        $data = isset( $data ) ? $data : array(
            240 => (object) array(
                'squareSize'      => 18,
                'hideCoordinates' => true,
                'hideTurn'        => false,
            ),
            320 => (object) array(
                'squareSize'      => 24,
                'hideCoordinates' => true,
                'hideTurn'        => false,
            ),
            480 => (object) array(
                'squareSize'      => 32,
                'hideCoordinates' => false,
                'hideTurn'        => false,
            ),
            768 => (object) array(
                'squareSize'      => 56,
                'hideCoordinates' => false,
                'hideTurn'        => false,
            ),
        );

        // Format the mode entries
        $this->smallScreenModes   = array();
        $previousScreenWidthBound = 0;
        foreach ( $data as $screenWidthBound => $mode ) {
            $mode->minScreenWidth = $previousScreenWidthBound;
            $mode->maxScreenWidth = $screenWidthBound;
            array_push( $this->smallScreenModes, $mode );
            $previousScreenWidthBound = $screenWidthBound;
        }
    }

}
