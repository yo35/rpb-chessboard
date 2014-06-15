<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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
	private $localizedPieceSymbolButtonAvailable;
	private $selectedPieceSymbolButton;
	private $pieceSymbolCustomValues;


	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('DefaultOptions');
		$this->loadTrait('Compatibility' );
		$this->loadTrait('URLs'          );

		// Create the sub-pages.
		$this->addSubPage('optionsgeneral'      , __('Default aspect & behavior settings'    , 'rpbchessboard'), true);
		$this->addSubPage('optionscompatibility', __('Compatibility with other chess plugins', 'rpbchessboard'));
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
	 * Minimum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMinimumSquareSize()
	{
		return RPBChessboardHelperValidation::MINIMUM_SQUARE_SIZE;
	}


	/**
	 * Maximum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMaximumSquareSize()
	{
		return RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
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
	 * Whether the "localized piece symbol" radio button is available or not.
	 *
	 * @return boolean
	 */
	public function isLocalizedPieceSymbolButtonAvailable()
	{
		if(!isset($this->localizedPieceSymbolButtonAvailable)) {
			$englishPieceSymbols = 'KQRBNP';
			$localizedPieceSymbols =
				/*i18n King symbol   */ __('K', 'rpbchessboard') .
				/*i18n Queen symbol  */ __('Q', 'rpbchessboard') .
				/*i18n Rook symbol   */ __('R', 'rpbchessboard') .
				/*i18n Bishop symbol */ __('B', 'rpbchessboard') .
				/*i18n Knight symbol */ __('N', 'rpbchessboard') .
				/*i18n Pawn symbol   */ __('P', 'rpbchessboard');
			$this->localizedPieceSymbolButtonAvailable = ($englishPieceSymbols !== $localizedPieceSymbols);
		}
		return $this->localizedPieceSymbolButtonAvailable;
	}


	/**
	 * Piece symbol radio button that is initially selected when the form is displayed.
	 *
	 * @return boolean
	 */
	public function getSelectedPieceSymbolButton()
	{
		if(!isset($this->selectedPieceSymbolButton)) {
			switch($this->getDefaultPieceSymbols()) {
				case 'native'   : $this->selectedPieceSymbolButton = 'english'  ; break;
				case 'figurines': $this->selectedPieceSymbolButton = 'figurines'; break;
				case 'localized':
					$this->selectedPieceSymbolButton = $this->isLocalizedPieceSymbolButtonAvailable() ? 'localized' : 'english';
					break;
				default:
					$this->selectedPieceSymbolButton = 'custom';
					break;
			}
		}
		return $this->selectedPieceSymbolButton;
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
			if($this->getSelectedPieceSymbolButton() === 'custom') {
				$pieceSymbols = $this->getDefaultPieceSymbols();
				$this->pieceSymbolCustomValues = array(
					'K' => substr($pieceSymbols, 1, 1),
					'Q' => substr($pieceSymbols, 2, 1),
					'R' => substr($pieceSymbols, 3, 1),
					'B' => substr($pieceSymbols, 4, 1),
					'N' => substr($pieceSymbols, 5, 1),
					'P' => substr($pieceSymbols, 6, 1)
				);
			}
			else {
				$this->pieceSymbolCustomValues = array(
					'K' => '', 'Q' => '', 'R' => '', 'B' => '', 'N' => '', 'P' => ''
				);
			}
		}
		return $this->pieceSymbolCustomValues[$piece];
	}
}
