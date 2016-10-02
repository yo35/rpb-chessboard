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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/adminpage.php');


/**
 * Model associated to the 'Theming' page in the backend.
 */
class RPBChessboardModelAdminPageTheming extends RPBChessboardAbstractModelAdminPage {

	private static $piecesetEditionButtonTitle;

	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel('Common/DefaultOptionsEx');

		// Create the sub-pages.
		$this->addSubPage('Colorsets', __('Colorsets', 'rpbchessboard'), true);
		$this->addSubPage('Piecesets', __('Piecesets', 'rpbchessboard'));
	}


	/**
	 * Either `'pieceset'` or `'colorset'` depending on the active page.
	 */
	public function getManagedSetCode() {
		return $this->getSelectedSubPageName() === 'Colorsets' ? 'colorset' : 'pieceset';
	}


	/**
	 * Either the default pieceset or the default colorset depending on the active page.
	 */
	public function getDefaultSetCodeValue() {
		return $this->getSelectedSubPageName() === 'Colorsets' ? $this->getDefaultColorset() : $this->getDefaultPieceset();
	}


	/**
	 * Return the message to show to the user to confirm the removal of either a pieceset or a colorset.
	 */
	public function getDeleteConfirmMessage() {
		$text = $this->getSelectedSubPageName() === 'Colorsets' ?
			__('Delete colorset "%1$s"?. Press OK to confirm...', 'rpbchessboard') :
			__('Delete pieceset "%1$s"?. Press OK to confirm...', 'rpbchessboard');
		return sprintf($text, '{1}');
	}


	/**
	 * Return a label suggestion when creating a new colorset.
	 */
	public function getLabelProposalForNewSetCode() {
		$counter = 1;
		$base = $this->getSelectedSubPageName() === 'Colorsets' ? __('My colorset', 'rpbchessboard') : __('My pieceset', 'rpbchessboard');
		$result = $base;
		$method = $this->getSelectedSubPageName() === 'Colorsets' ? 'isColorsetLabelAlreadyUsed' : 'isPiecesetLabelAlreadyUsed';
		while($this->$method($result)) {
			++$counter;
			$result = $base . ' ' . $counter;
		}
		return $result;
	}


	/**
	 * URL to which the the request for modifying the colorsets/piecesets will be dispatched.
	 *
	 * @return string
	 */
	public function getFormActionURL() {
		return $this->getSubPage($this->getSelectedSubPageName())->link;
	}


	/**
	 * Action code corresponding to the request to create or edit an existing colorset.
	 *
	 * @return string
	 */
	public function getFormAction($isNew) {
		return ($isNew ? 'add-' : 'edit-') . $this->getManagedSetCode();
	}


	/**
	 * Action code corresponding to the request to delete an existing colorset.
	 *
	 * @return string
	 */
	public function getDeleteAction() {
		return 'delete-' . $this->getManagedSetCode();
	}


	/**
	 * Action code corresponding to the request to change the default colorset.
	 *
	 * @return string
	 */
	public function getSetDefaultAction() {
		return 'set-default-' . $this->getManagedSetCode();
	}


	/**
	 * Check whether the given label is already used or not by an existing colorset.
	 */
	private function isColorsetLabelAlreadyUsed($label) {
		foreach($this->getAvailableColorsets() as $colorset) {
			if($label === $this->getColorsetLabel($colorset)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Check whether the given label is already used or not by an existing pieceset.
	 */
	private function isPiecesetLabelAlreadyUsed($label) {
		foreach($this->getAvailablePiecesets() as $pieceset) {
			if($label === $this->getPiecesetLabel($pieceset)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Return a random color to be used for dark squares.
	 *
	 * @return string
	 */
	public function getRandomDarkSquareColor() {
		return self::getRandomColor(0x88, 0xbf);
	}


	/**
	 * Return a random color to be used for light squares.
	 *
	 * @return string
	 */
	public function getRandomLightSquareColor() {
		return self::getRandomColor(0xc0, 0xf7);
	}


	private static function getRandomColor($grayRangeMin, $grayRangeMax) {
		$red = rand($grayRangeMin, $grayRangeMax);
		$green = rand($grayRangeMin, $grayRangeMax);
		$blue = rand($grayRangeMin, $grayRangeMax);
		return sprintf('#%02x%02x%02x', $red, $green, $blue);
	}


	/**
	 * Whether the PHP image processing library is available or not (must be available for custom pieceset management).
	 */
	public function isGDLibraryAvailable() {
		return extension_loaded('gd') && function_exists('gd_info');
	}


	/**
	 * Text to use for the tooltip of the pieceset edition buttons.
	 */
	public function getPiecesetEditionButtonTitle($coloredPiece) {
		if(!isset(self::$piecesetEditionButtonTitle)) {
			self::$piecesetEditionButtonTitle = array(
				'bp' =>  __('Select the image to use for black pawns'     , 'rpbchessboard'),
				'bn' =>  __('Select the image to use for black knights'   , 'rpbchessboard'),
				'bb' =>  __('Select the image to use for black bishops'   , 'rpbchessboard'),
				'br' =>  __('Select the image to use for black rooks'     , 'rpbchessboard'),
				'bq' =>  __('Select the image to use for black queens'    , 'rpbchessboard'),
				'bk' =>  __('Select the image to use for black kings'     , 'rpbchessboard'),
				'bx' =>  __('Select the image to use for black turn flags', 'rpbchessboard'),
				'wp' =>  __('Select the image to use for white pawns'     , 'rpbchessboard'),
				'wn' =>  __('Select the image to use for white knights'   , 'rpbchessboard'),
				'wb' =>  __('Select the image to use for white bishops'   , 'rpbchessboard'),
				'wr' =>  __('Select the image to use for white rooks'     , 'rpbchessboard'),
				'wq' =>  __('Select the image to use for white queens'    , 'rpbchessboard'),
				'wk' =>  __('Select the image to use for white kings'     , 'rpbchessboard'),
				'wx' =>  __('Select the image to use for white turn flags', 'rpbchessboard')
			);
		}
		return self::$piecesetEditionButtonTitle[$coloredPiece];
	}
}
