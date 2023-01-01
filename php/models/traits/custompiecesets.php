<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2023  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * User-defined (aka. custom) piecesets and related parameters.
 */
trait RPBChessboardTraitCustomPiecesets {

	public static $COLORED_PIECE_CODES = array( 'bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx' );

	private $availablePiecesets;
	private $customPiecesets;
	private $customPiecesetLabels     = array();
	private $customPiecesetAttributes = array();

	private static $BUILTIN_PIECESETS = array(
		'cburnett' => 'CBurnett',
		'celtic'   => 'Celtic',
		'eyes'     => 'Eyes',
		'fantasy'  => 'Fantasy',
		'skulls'   => 'Skulls',
		'spatial'  => 'Spatial',
	);


	/**
	 * Return all the available piecesets.
	 *
	 * @return array
	 */
	public function getAvailablePiecesets() {
		if ( ! isset( $this->availablePiecesets ) ) {
			$builtinPiecesets         = array_keys( self::$BUILTIN_PIECESETS );
			$customPiecesets          = $this->getCustomPiecesets();
			$this->availablePiecesets = array_merge( $builtinPiecesets, $customPiecesets );
			asort( $this->availablePiecesets );
		}
		return $this->availablePiecesets;
	}


	/**
	 * Return the user-defined (aka. custom) piecesets.
	 *
	 * @return array
	 */
	public function getCustomPiecesets() {
		if ( ! isset( $this->customPiecesets ) ) {
			$value                 = RPBChessboardHelperValidation::validateSetCodeList( get_option( 'rpbchessboard_custom_piecesets' ) );
			$this->customPiecesets = isset( $value ) ? $value : array();
		}
		return $this->customPiecesets;
	}


	/**
	 * Check whether the given pieceset is built-in or not.
	 *
	 * @return boolean
	 */
	public function isBuiltinPieceset( $pieceset ) {
		return isset( self::$BUILTIN_PIECESETS[ $pieceset ] );
	}


	/**
	 * Return the label of the given pieceset.
	 *
	 * @return string
	 */
	public function getPiecesetLabel( $pieceset ) {
		if ( $this->isBuiltinPieceset( $pieceset ) ) {
			return self::$BUILTIN_PIECESETS[ $pieceset ];
		} else {
			$result = $this->getCustomPiecesetLabel( $pieceset );
			return '' === $result ? __( '(no name)', 'rpb-chessboard' ) : $result;
		}
	}


	/**
	 * Return the label of the given custom pieceset.
	 *
	 * @return string
	 */
	private function getCustomPiecesetLabel( $customPieceset ) {
		if ( ! isset( $this->customPiecesetLabels[ $customPieceset ] ) ) {
			$value = get_option( 'rpbchessboard_custom_pieceset_label_' . $customPieceset, null );
			$this->customPiecesetLabels[ $customPieceset ] = isset( $value ) ? $value : ucfirst( str_replace( '-', ' ', $customPieceset ) );
		}
		return $this->customPiecesetLabels[ $customPieceset ];
	}


	/**
	 * Return the image ID corresponding to the image to use for the given colored piece in the given pieceset.
	 *
	 * @return integer
	 */
	public function getCustomPiecesetImageId( $customPieceset, $coloredPiece ) {
		$this->initializeCustomPiecesetAttributes( $customPieceset );
		return $this->customPiecesetAttributes[ $customPieceset ]->imageId[ $coloredPiece ];
	}


	/**
	 * Return the URL to the image for the given colored piece in the given pieceset.
	 *
	 * @return string
	 */
	public function getCustomPiecesetImageURL( $customPieceset, $coloredPiece ) {
		$this->initializeCustomPiecesetAttributes( $customPieceset );
		return $this->customPiecesetAttributes[ $customPieceset ]->imageURL[ $coloredPiece ];
	}


	private function initializeCustomPiecesetAttributes( $customPieceset ) {
		if ( isset( $this->customPiecesetAttributes[ $customPieceset ] ) ) {
			return;
		}

		$this->customPiecesetAttributes[ $customPieceset ] = (object) array(
			'imageId'  => array(),
			'imageURL' => array(),
		);

		// Retrieve the attributes from the database
		$ids = explode( '|', get_option( 'rpbchessboard_custom_pieceset_attributes_' . $customPieceset, '' ) );
		if ( count( $ids ) !== count( self::$COLORED_PIECE_CODES ) ) {
			foreach ( self::$COLORED_PIECE_CODES as $coloredPiece ) {
				$this->initializeCustomPiecesetAttributesAsInvalid( $customPieceset, $coloredPiece );
			}
			return;
		}

		// Validate the values retrieved from the database
		$counter = 0;
		foreach ( self::$COLORED_PIECE_CODES as $coloredPiece ) {

			$currentId  = RPBChessboardHelperValidation::validateInteger( $ids[ $counter++ ] );
			$currentURL = isset( $currentId ) ? wp_get_attachment_image_url( $currentId ) : false;

			if ( $currentURL ) {
				$this->customPiecesetAttributes[ $customPieceset ]->imageId[ $coloredPiece ]  = $currentId;
				$this->customPiecesetAttributes[ $customPieceset ]->imageURL[ $coloredPiece ] = $currentURL;
			} else {
				$this->initializeCustomPiecesetAttributesAsInvalid( $customPieceset, $coloredPiece );
			}
		}
	}


	private function initializeCustomPiecesetAttributesAsInvalid( $customPieceset, $coloredPiece ) {
		$this->customPiecesetAttributes[ $customPieceset ]->imageId[ $coloredPiece ]  = -1;
		$this->customPiecesetAttributes[ $customPieceset ]->imageURL[ $coloredPiece ] = RPBCHESSBOARD_URL . 'images/piece-invalid.png';
	}
}
