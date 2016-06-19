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
 * User-defined (aka. custom) colorsets and related parameters.
 */
class RPBChessboardModelCommonCustomColorsets extends RPBChessboardAbstractModel {

	private static $customColorsets;
	private static $customColorsetLabels = array();
	private static $customColorsetAttributes = array();

	const DEFAULT_DARK_SQUARE_COLOR = '#b5876b';
	const DEFAULT_LIGHT_SQUARE_COLOR = '#f0dec7';


	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods('getCustomColorsets', 'getCustomColorsetLabel', 'getDarkSquareColor', 'getLightSquareColor');
	}


	/**
	 * Return the user-defined (aka. custom) colorsets.
	 *
	 * @return array
	 */
	public function getCustomColorsets() {
		if(!isset(self::$customColorsets)) {
			$value = RPBChessboardHelperValidation::validateSetCodeList(get_option('rpbchessboard_custom_colorsets'));
			self::$customColorsets = isset($value) ? $value : array();
		}
		return self::$customColorsets;
	}


	/**
	 * Return the label of the given custom colorset.
	 *
	 * @param string $colorset
	 * @return string
	 */
	public function getCustomColorsetLabel($colorset) {
		if(!isset(self::$customColorsetLabels[$colorset])) {
			self::$customColorsetLabels[$colorset] = get_option('rpbchessboard_custom_colorset_label_' . $colorset, $colorset);
		}
		return self::$customColorsetLabels[$colorset];
	}


	/**
	 * Return the dark-square color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getDarkSquareColor($colorset) {
		self::initializeCustomColorsetAttributes($colorset);
		return self::$customColorsetAttributes[$colorset]->darkSquareColor;
	}


	/**
	 * Return the light-square color defined for the given colorset.
	 *
	 * @return string
	 */
	public function getLightSquareColor($colorset) {
		self::initializeCustomColorsetAttributes($colorset);
		return self::$customColorsetAttributes[$colorset]->lightSquareColor;
	}


	private static function initializeCustomColorsetAttributes($colorset) {
		if(isset(self::$customColorsetAttributes[$colorset])) {
			return;
		}

		// Default attributes
		self::$customColorsetAttributes[$colorset] = (object) array(
			'darkSquareColor' => self::DEFAULT_DARK_SQUARE_COLOR,
			'lightSquareColor' => self::DEFAULT_LIGHT_SQUARE_COLOR
		);

		// Retrieve the attributes from the database
		$values = explode('|', get_option('rpbchessboard_custom_colorset_attributes_' . $colorset, ''));
		if(count($values) !== 2) {
			return;
		}

		// Validate the values retrieved from the database
		$darkSquareColor = RPBChessboardHelperValidation::validateColor($values[0]);
		$lightSquareColor = RPBChessboardHelperValidation::validateColor($values[1]);
		if(isset($darkSquareColor)) { self::$customColorsetAttributes[$colorset]->darkSquareColor = $darkSquareColor; }
		if(isset($lightSquareColor)) { self::$customColorsetAttributes[$colorset]->lightSquareColor = $lightSquareColor; }
	}
}
