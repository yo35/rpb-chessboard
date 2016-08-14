<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php');
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * User-defined (aka. custom) piecesets and related parameters.
 */
class RPBChessboardModelCommonCustomPiecesets extends RPBChessboardAbstractModel {

	private static $customPiecesets;
	private static $customPiecesetLabels = array();
	private static $customPiecesetAttributes = array();


	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods('getCustomPiecesets', 'getCustomPiecesetLabel');
	}


	/**
	 * Return the user-defined (aka. custom) piecesets.
	 *
	 * @return array
	 */
	public function getCustomPiecesets() {
		if(!isset(self::$customPiecesets)) {
			$value = RPBChessboardHelperValidation::validateSetCodeList(get_option('rpbchessboard_custom_piecesets'));
			self::$customPiecesets = isset($value) ? $value : array();
		}
		return self::$customPiecesets;
	}


	/**
	 * Return the label of the given custom pieceset.
	 *
	 * @param string $pieceset
	 * @return string
	 */
	public function getCustomPiecesetLabel($pieceset) {
		if(!isset(self::$customPiecesetLabels[$pieceset])) {
			$value = get_option('rpbchessboard_custom_pieceset_label_' . $pieceset, null);
			self::$customPiecesetLabels[$pieceset] = isset($value) ? $value : ucfirst(str_replace('-', ' ', $pieceset));
		}
		return self::$customPiecesetLabels[$pieceset];
	}
}
