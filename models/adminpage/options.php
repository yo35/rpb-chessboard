<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/compatibility.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/customcolorsets.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/custompiecesets.php';
require_once RPBCHESSBOARD_ABSPATH . 'php/models/traits/smallscreens.php';


/**
 * Model associated to the 'Options' page in the backend.
 */
class RPBChessboardModelAdminPageOptions extends RPBChessboardAbstractModelAdminPage {

	use RPBChessboardTraitCompatibility, RPBChessboardTraitCustomColorsets, RPBChessboardTraitCustomPiecesets, RPBChessboardTraitSmallScreens;

	private $pieceSymbolCustomValues;


	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel( 'Common/DefaultOptions' );

		// Create the sub-pages.
		$this->addSubPage( 'General', __( 'Default aspect & behavior settings', 'rpb-chessboard' ), true );
		$this->addSubPage( 'Compatibility', __( 'Compatibility settings', 'rpb-chessboard' ) );
		$this->addSubPage( 'SmallScreens', __( 'Small-screen devices', 'rpb-chessboard' ) );
	}


	/**
	 * URL to which the the request for modifying the options of the plugin will be dispatched.
	 *
	 * @return string
	 */
	public function getFormActionURL() {
		return $this->getSubPage( $this->getSelectedSubPageName() )->link;
	}


	/**
	 * Action code corresponding to the request for modifying the options of the plugin.
	 *
	 * @return string
	 */
	public function getFormAction() {
		return 'update-options';
	}


	/**
	 * Action code to reset the settings of the current page.
	 *
	 * @return string
	 */
	public function getFormResetAction() {
		return 'reset-' . strtolower( $this->getSelectedSubPageName() );
	}


	/**
	 * URL to the main settings page.
	 *
	 * @return string
	 */
	public function getOptionsGeneralURL() {
		return admin_url( 'admin.php' ) . '?page=rpbchessboard';
	}


	/**
	 * URL to the small-screen settings page.
	 *
	 * @return string
	 */
	public function getOptionsSmallScreensURL() {
		return admin_url( 'admin.php' ) . '?page=rpbchessboard&subpage=smallscreens';
	}


	/**
	 * URL to the theming page.
	 *
	 * @return string
	 */
	public function getThemingURL() {
		return admin_url( 'admin.php' ) . '?page=rpbchessboard-theming';
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
	 * Simplified version of the default piece symbol mode.
	 *
	 * @return boolean
	 */
	public function getDefaultSimplifiedPieceSymbols() {
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
