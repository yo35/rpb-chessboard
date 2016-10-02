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
	private static $COLORED_PIECE_CODES = array('bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx');


	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods('getCustomPiecesets', 'getCustomPiecesetLabel', 'getCustomPiecesetImageId', 'isCustomPiecesetImageDefined',
			'getCustomPiecesetRawDataURL', 'getCustomPiecesetFormattedDataURL', 'computeCustomPiecesetFormattedDataPath');
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


	/**
	 * Return the image ID corresponding to the image to use for the given colored piece in the given pieceset.
	 *
	 * @param string $pieceset
	 * @param string $coloredPiece
	 * @return integer
	 */
	public function getCustomPiecesetImageId($pieceset, $coloredPiece) {
		self::initializeCustomPiecesetAttributes($pieceset);
		return self::$customPiecesetAttributes[$pieceset]->imageId[$coloredPiece];
	}


	/**
	 * Return whether the image to use for the given colored piece in the given pieceset is defined or not.
	 *
	 * @param string $pieceset
	 * @param string $coloredPiece
	 * @return boolean
	 */
	public function isCustomPiecesetImageDefined($pieceset, $coloredPiece) {
		self::initializeCustomPiecesetAttributes($pieceset);
		return self::$customPiecesetAttributes[$pieceset]->imageId[$coloredPiece] >= 0;
	}


	/**
	 * Return the URL to the image uploaded by the user for the given colored piece in the given pieceset.
	 *
	 * @param string $pieceset
	 * @param string $coloredPiece
	 * @return string
	 */
	public function getCustomPiecesetRawDataURL($pieceset, $coloredPiece) {
		self::initializeCustomPiecesetAttributes($pieceset);

		$attributes = self::$customPiecesetAttributes[$pieceset];
		if(!isset($attributes->rawDataURL[$coloredPiece])) {
			$imageId = $attributes->imageId[$coloredPiece];
			$url = $imageId >= 0 ? wp_get_attachment_image_url($imageId, array()) : false;
			$attributes->rawDataURL[$coloredPiece] = $url ? $url : '#';
		}

		return $attributes->rawDataURL[$coloredPiece];
	}


	/**
	 * Return the URL to the formatted image for the given colored piece in the given pieceset.
	 *
	 * @param string $pieceset
	 * @param string $coloredPiece
	 * @return string
	 */
	public function getCustomPiecesetFormattedDataURL($pieceset, $coloredPiece) {
		return $this->isCustomPiecesetImageDefined($pieceset, $coloredPiece) ?
			$this->computeCustomPiecesetFormattedDataPath($this->getCustomPiecesetRawDataURL($pieceset, $coloredPiece)) : '#';
	}


	/**
	 * Generate the path to the formatted image from the path to the raw image.
	 */
	public function computeCustomPiecesetFormattedDataPath($rawDataPath) {
		return preg_replace('/\.[^.]*$/', '', $rawDataPath) . '-sprite.png';
	}


	private static function initializeCustomPiecesetAttributes($pieceset) {
		if(isset(self::$customPiecesetAttributes[$pieceset])) {
			return;
		}

		self::$customPiecesetAttributes[$pieceset] = (object) array(
			'imageId' => array(), 'rawDataURL' => array()
		);

		// Retrieve the attributes from the database
		$values = explode('|', get_option('rpbchessboard_custom_pieceset_attributes_' . $pieceset, ''));
		if(count($values) !== count($COLORED_PIECE_CODES)) {
			foreach(self::$COLORED_PIECE_CODES as $coloredPiece) {
				self::$customPiecesetAttributes[$pieceset]->imageId[$coloredPiece] = -1;
			}
			return;
		}

		// Validate the values retrieved from the database
		$counter = 0;
		foreach(self::$COLORED_PIECE_CODES as $coloredPiece) {
			$currentId = RPBChessboardHelperValidation::validateInteger($values[$counter++]);
			self::$customPiecesetAttributes[$pieceset]->imageId[$coloredPiece] = isset($currentId) ? $currentId : -1;
		}
	}
}