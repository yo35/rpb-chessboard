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


/**
 * Validation functions.
 */
abstract class RPBChessboardHelperValidation {

	/**
	 * Minimum square size of the chessboard widgets.
	 */
	const MINIMUM_SQUARE_SIZE = 12;


	/**
	 * Maximum square size of the chessboard widgets.
	 */
	const MAXIMUM_SQUARE_SIZE = 64;


	/**
	 * Maximum value for the animation speed parameter.
	 */
	const MAXIMUM_ANIMATION_SPEED = 1000;


	/**
	 * The animation speed parameter must be a multiple of this value.
	 */
	const STEP_ANIMATION_SPEED = 100;


	/**
	 * Validate a chessboard widget square size parameter.
	 *
	 * @param mixed $value
	 * @return int May be null is the value is not valid.
	 */
	public static function validateSquareSize( $value ) {
		return self::validateInteger( $value, self::MINIMUM_SQUARE_SIZE, self::MAXIMUM_SQUARE_SIZE );
	}


	/**
	 * Validate a color (defined as a hexadecimal string).
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateColor( $value ) {
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( preg_match( '/^#[0-9a-f]{6}$/', $value ) ) {
				return $value;
			}
		}
		return null;
	}


	/**
	 * Validate colorset/pieceset parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateSetCode( $value ) {
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value ) ) {
				return $value;
			}
		}
		return null;
	}


	/**
	 * Validate colorset/pieceset parameter list.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateSetCodeList( $value ) {
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( '' === $value ) {
				return array();
			} elseif ( preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*(?:\|[a-z0-9]+(?:-[a-z0-9]+)*)*$/', $value ) ) {
				return explode( '|', $value );
			}
		}
		return null;
	}


	/**
	 * Validate a diagram alignment parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateDiagramAlignment( $value ) {
		return ( 'floatLeft' === $value || 'floatRight' === $value || 'center' === $value ) ? $value : null;
	}


	/**
	 * Validate a piece symbol mode parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validatePieceSymbols( $value ) {
		if ( 'native' === $value || 'localized' === $value || 'figurines' === $value ) {
			return $value;
		} elseif ( is_string( $value ) && preg_match( '/^\([a-zA-Z]{6}\)$/', $value ) ) {
			return strtoupper( $value );
		} else {
			return null;
		}
	}


	/**
	 * Validate a single piece symbol.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validatePieceSymbol( $value ) {
		return is_string( $value ) && preg_match( '/^[a-zA-Z]$/', $value ) ? strtoupper( $value ) : null;
	}


	/**
	 * Validate a navigation board position parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateNavigationBoard( $value ) {
		return ( 'none' === $value || 'frame' === $value || 'above' === $value || 'below' === $value
			|| 'floatLeft' === $value || 'floatRight' === $value || 'scrollLeft' === $value || 'scrollRight' === $value ) ? $value : null;
	}


	/**
	 * Validate a set of small-screen mode specifications.
	 */
	public static function validateSmallScreenModes( $value ) {
		if ( ! is_string( $value ) ) {
			return null;
		}
		$res = array();

		// Split the input into a list of comma-separated tokens
		$modes = explode( ',', $value );
		foreach ( $modes as $mode ) {

			// Split each mode-encoding token into 3 colon-separated sub-tokens
			$tokens = explode( ':', $mode );
			if ( count( $tokens ) !== 3 ) {
				continue;
			}

			// Validate each sub-token
			$screenWidth     = self::validateInteger( $tokens[0], 1 );
			$squareSize      = self::validateSquareSize( $tokens[1] );
			$hideCoordinates = self::validateBoolean( $tokens[2] );
			if ( isset( $screenWidth ) && isset( $squareSize ) && isset( $hideCoordinates ) ) {
				$res[ $screenWidth ] = (object) array(
					'squareSize'      => $squareSize,
					'hideCoordinates' => $hideCoordinates,
				);
			}
		}

		// Sort by screen-width and return the result.
		ksort( $res );
		return $res;
	}


	/**
	 * Validate a chessboard widget animation speed parameter.
	 *
	 * @param mixed $value
	 * @return int May be null is the value is not valid.
	 */
	public static function validateAnimationSpeed( $value ) {
		$value = self::validateInteger( $value, 0, self::MAXIMUM_ANIMATION_SPEED );
		if ( isset( $value ) ) {
			$value = round( $value / self::STEP_ANIMATION_SPEED ) * self::STEP_ANIMATION_SPEED;
		}
		return $value;
	}


	/**
	 * Validate an integer.
	 *
	 * @param mixed $value
	 * @param int   $min Minimum value (optional).
	 * @param int   $max Maximum value (optional).
	 * @return int May be null is the value is not valid.
	 */
	public static function validateInteger( $value, $min = null, $max = null ) {
		$value = filter_var( $value, FILTER_VALIDATE_INT );
		return false === $value ? null : max( null === $max ? $value : min( $value, $max ), $min );
	}


	/**
	 * Validate a boolean.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBoolean( $value ) {
		return ( null === $value || '' === $value ) ? null : filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
	}


	/**
	 * Validate a boolean specified as an integer value.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateBooleanFromInt( $value ) {
		$value = filter_var( $value, FILTER_VALIDATE_INT );
		if ( 0 === $value ) {
			return false;
		} elseif ( 1 === $value ) {
			return true;
		} else {
			return null;
		}
	}


	/**
	 * Validate a boolean specified as a string.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validateString( $value ) {
		return is_string( $value ) ? $value : null;
	}
}
