<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/adminpage.php';


/**
 * Model associated to the 'Help' page in the backend.
 */
class RPBChessboardModelAdminPageHelp extends RPBChessboardAbstractModelAdminPage {

	private $squareSizeList;
	private $animationSpeedList;
	private $pieceSymbolCustomValues;


	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel( 'Common/Compatibility' );
		$this->loadDelegateModel( 'Common/DefaultOptionsEx' );
		$this->loadDelegateModel( 'Common/URLs' );

		$fenAttrLabel = sprintf(
			__( '%1$s[%3$s][/%3$s]%2$s tag attributes', 'rpb-chessboard' ),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars( $this->getFENShortcode() )
		);

		$pgnAttrLabel = sprintf(
			__( '%1$s[%3$s][/%3$s]%2$s tag attributes', 'rpb-chessboard' ),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars( $this->getPGNShortcode() )
		);

		// Create the sub-pages.
		$this->addSubPage( 'PGNSyntax', __( 'PGN game syntax', 'rpb-chessboard' ), true );
		$this->addSubPage( 'FENSyntax', __( 'FEN diagram syntax', 'rpb-chessboard' ) );
		$this->addSubPage( 'PGNAttributes', $pgnAttrLabel );
		$this->addSubPage( 'FENAttributes', $fenAttrLabel );
	}


	/**
	 * Return the list of square size values to present as example.
	 *
	 * @return int[]
	 */
	public function getSquareSizeList() {
		if ( ! isset( $this->squareSizeList ) ) {
			$defaultSquareSize = $this->getDefaultSquareSize();
			if ( $defaultSquareSize <= 24 ) {
				$this->squareSizeList = array( $defaultSquareSize, 35, 56 );
			} elseif ( $defaultSquareSize <= 48 ) {
				$this->squareSizeList = array( 16, $defaultSquareSize, 56 );
			} else {
				$this->squareSizeList = array( 16, 35, $defaultSquareSize );
			}
		}
		return $this->squareSizeList;
	}


	/**
	 * Return the initial square size value to use for the square size attribute presentation section.
	 */
	public function getSquareSizeInitialExample() {
		$squareSizeList = $this->getSquareSizeList();
		return $squareSizeList[1];
	}


	/**
	 * Return the list of animation speed values to present as example.
	 *
	 * @return int[]
	 */
	public function getAnimationSpeedList() {
		if ( ! isset( $this->animationSpeedList ) ) {
			$defaultAnimationSpeed = $this->getDefaultAnimationSpeed();
			if ( $defaultAnimationSpeed === 0 ) {
				$this->animationSpeedList = array( 0, 200, 800 );
			} elseif ( $defaultAnimationSpeed <= 500 ) {
				$this->animationSpeedList = array( 0, $defaultAnimationSpeed, 800 );
			} else {
				$this->animationSpeedList = array( 0, 200, $defaultAnimationSpeed );
			}
		}
		return $this->animationSpeedList;
	}


	/**
	 * Default value for the piece symbol custom fields.
	 *
	 * @param string $piece `'K'`, `'Q'`, `'R'`, `'B'`, `'N'`, `'P'`, or `null` to concatenate all the values.
	 * @return string
	 */
	public function getPieceSymbolCustomValue( $piece = null ) {
		if ( ! isset( $this->pieceSymbolCustomValues ) ) {
			$this->pieceSymbolCustomValues = $this->getDefaultPieceSymbolCustomValues();
			if ( empty( $this->pieceSymbolCustomValues ) ) {
				$this->pieceSymbolCustomValues = array(
					'K' => 'K',
					'Q' => 'D',
					'R' => 'T',
					'B' => 'L',
					'N' => 'S',
					'P' => 'B',
				);
			}
		}
		$t = $this->pieceSymbolCustomValues;
		return $piece === null ? $t['K'] . $t['Q'] . $t['R'] . $t['B'] . $t['N'] . $t['P'] : $t[ $piece ];
	}
}
