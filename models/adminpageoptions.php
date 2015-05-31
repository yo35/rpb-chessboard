<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Model associated to the 'Options' page in the backend.
 */
class RPBChessboardModelAdminPageOptions extends RPBChessboardAbstractModelAdminPage
{
	private $pieceSymbolCustomValues;


	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('DefaultOptions');
		$this->loadTrait('Compatibility' );
		$this->loadTrait('SmallScreens'  );
		$this->loadTrait('URLs'          );

		// Create the sub-pages.
		$this->addSubPage('optionsgeneral'      , __('Default aspect & behavior settings'    , 'rpbchessboard'), true);
		$this->addSubPage('optionscompatibility', __('Compatibility with other chess plugins', 'rpbchessboard'));
		$this->addSubPage('optionssmallscreens' , __('Small-screen devices'                  , 'rpbchessboard'));
	}


	/**
	 * URL to which the the request for modifying the options of the plugin will be dispatched.
	 *
	 * @return string
	 */
	public function getFormActionURL()
	{
		return $this->getSubPage($this->getSelectedSubPageName())->link;
	}


	/**
	 * Action code corresponding to the request for modifying the options of the plugin.
	 *
	 * @return string
	 */
	public function getFormAction()
	{
		return 'update-options';
	}


	/**
	 * Action code to reset the settings of the current page.
	 *
	 * @return string
	 */
	public function getFormResetAction()
	{
		return 'reset-' . $this->getSelectedSubPageName();
	}


	/**
	 * Number of digits of the maximum square size parameter.
	 *
	 * @return int
	 */
	public function getDigitNumberForSquareSize()
	{
		$maxVal = $this->getMaximumSquareSize();
		return 1 + floor(log10($maxVal));
	}


	/**
	 * Default value for the piece symbol custom fields.
	 *
	 * @param string $piece `'K'`, `'Q'`, `'R'`, `'B'`, `'N'`, or `'P'`.
	 * @return string
	 */
	public function getPieceSymbolCustomValue($piece)
	{
		if(!isset($this->pieceSymbolCustomValues)) {
			$this->pieceSymbolCustomValues = $this->getDefaultPieceSymbolCustomValues();
			if(empty($this->pieceSymbolCustomValues)) {
				$this->pieceSymbolCustomValues = array('K'=>'', 'Q'=>'', 'R'=>'', 'B'=>'', 'N'=>'', 'P'=>'');
			}
		}
		return $this->pieceSymbolCustomValues[$piece];
	}
}
