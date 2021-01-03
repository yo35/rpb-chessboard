<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php';
require_once RPBCHESSBOARD_ABSPATH . 'helpers/validation.php';


/**
 * User-defined (aka. custom) colorsets and related parameters.
 */
class RPBChessboardModelCommonCustomColorsets extends RPBChessboardAbstractModel {

	private static $customColorsets;
	private static $customColorsetLabels     = array();
	private static $customColorsetAttributes = array();

	const DEFAULT_DARK_SQUARE_COLOR   = '#bbbbbb';
	const DEFAULT_LIGHT_SQUARE_COLOR  = '#f8f8f8';
	const DEFAULT_GREEN_MARKER_COLOR  = '#00ff00';
	const DEFAULT_RED_MARKER_COLOR    = '#ff0000';
	const DEFAULT_YELLOW_MARKER_COLOR = '#ffff00';

	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods(
			'getCustomColorsets',
			'getCustomColorsetLabel',
			'getDarkSquareColor',
			'getLightSquareColor',
			'getGreenMarkerColor',
			'getRedMarkerColor',
			'getYellowMarkerColor'
		);
	}


	/**
	 * Return the user-defined (aka. custom) colorsets.
	 *
	 * @return array
	 */
	public function getCustomColorsets() {
		if ( ! isset( self::$customColorsets ) ) {
			$value                 = RPBChessboardHelperValidation::validateSetCodeList( get_option( 'rpbchessboard_custom_colorsets' ) );
			self::$customColorsets = isset( $value ) ? $value : array();
		}
		return self::$customColorsets;
	}


	/**
	 * Return the label of the given custom colorset.
	 *
	 * @param string $colorset
	 * @return string
	 */
	public function getCustomColorsetLabel( $colorset ) {
		if ( ! isset( self::$customColorsetLabels[ $colorset ] ) ) {
			$value                                   = get_option( 'rpbchessboard_custom_colorset_label_' . $colorset, null );
			self::$customColorsetLabels[ $colorset ] = isset( $value ) ? $value : ucfirst( str_replace( '-', ' ', $colorset ) );
		}
		return self::$customColorsetLabels[ $colorset ];
	}


	/**
	 * Return the dark-square color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getDarkSquareColor( $colorset ) {
		self::initializeCustomColorsetAttributes( $colorset );
		return self::$customColorsetAttributes[ $colorset ]->darkSquareColor;
	}


	/**
	 * Return the light-square color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getLightSquareColor( $colorset ) {
		self::initializeCustomColorsetAttributes( $colorset );
		return self::$customColorsetAttributes[ $colorset ]->lightSquareColor;
	}


	/**
	 * Return the green marker color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getGreenMarkerColor( $colorset ) {
		self::initializeCustomColorsetAttributes( $colorset );
		return self::$customColorsetAttributes[ $colorset ]->greenMarkerColor;
	}


	/**
	 * Return the red marker color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getRedMarkerColor( $colorset ) {
		self::initializeCustomColorsetAttributes( $colorset );
		return self::$customColorsetAttributes[ $colorset ]->redMarkerColor;
	}


	/**
	 * Return the yellow marker color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getYellowMarkerColor( $colorset ) {
		self::initializeCustomColorsetAttributes( $colorset );
		return self::$customColorsetAttributes[ $colorset ]->yellowMarkerColor;
	}


	private static function initializeCustomColorsetAttributes( $colorset ) {
		if ( isset( self::$customColorsetAttributes[ $colorset ] ) ) {
			return;
		}

		// Default attributes
		self::$customColorsetAttributes[ $colorset ] = (object) array(
			'darkSquareColor'   => self::DEFAULT_DARK_SQUARE_COLOR,
			'lightSquareColor'  => self::DEFAULT_LIGHT_SQUARE_COLOR,
			'greenMarkerColor'  => self::DEFAULT_GREEN_MARKER_COLOR,
			'redMarkerColor'    => self::DEFAULT_RED_MARKER_COLOR,
			'yellowMarkerColor' => self::DEFAULT_YELLOW_MARKER_COLOR,
		);

		// Retrieve the attributes from the database
		$values = explode( '|', get_option( 'rpbchessboard_custom_colorset_attributes_' . $colorset, '' ) );

		// First 2 tokens: dark and light squares
		if ( count( $values ) >= 2 ) {
			$darkSquareColor  = RPBChessboardHelperValidation::validateColor( $values[0] );
			$lightSquareColor = RPBChessboardHelperValidation::validateColor( $values[1] );
			if ( isset( $darkSquareColor ) ) {
				self::$customColorsetAttributes[ $colorset ]->darkSquareColor = $darkSquareColor;
			}
			if ( isset( $lightSquareColor ) ) {
				self::$customColorsetAttributes[ $colorset ]->lightSquareColor = $lightSquareColor;
			}
		}

		// Next 3 tokens: marker colors
		if ( count( $values ) >= 5 ) {
			$greenMarkerColor  = RPBChessboardHelperValidation::validateColor( $values[2] );
			$redMarkerColor    = RPBChessboardHelperValidation::validateColor( $values[3] );
			$yellowMarkerColor = RPBChessboardHelperValidation::validateColor( $values[4] );
			if ( isset( $greenMarkerColor ) ) {
				self::$customColorsetAttributes[ $colorset ]->greenMarkerColor = $greenMarkerColor;
			}
			if ( isset( $redMarkerColor ) ) {
				self::$customColorsetAttributes[ $colorset ]->redMarkerColor = $redMarkerColor;
			}
			if ( isset( $yellowMarkerColor ) ) {
				self::$customColorsetAttributes[ $colorset ]->yellowMarkerColor = $yellowMarkerColor;
			}
		}
	}
}
