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


require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * Process the request resulting from a click in the "Options" form in the backend.
 */
class RPBChessboardTraitPostOptions extends RPBChessboardAbstractTrait
{
	private $squareSize;
	private $showCoordinates;
	private $pieceSymbols;
	private $navigationBoard;
	private $fenCompatibilityMode;
	private $pgnCompatibilityMode;


	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Load the square-size parameter.
		if(isset($_POST['squareSize'])) {
			$this->squareSize = RPBChessboardHelperValidation::validateSquareSize($_POST['squareSize']);
		}

		// Load the piece symbol parameter.
		if(isset($_POST['pieceSymbols'])) {
			$this->pieceSymbols = self::loadPieceSymbols();
		}

		// Load the navigation board parameter.
		if(isset($_POST['navigationBoard'])) {
			$this->navigationBoard = RPBChessboardHelperValidation::validateNavigationBoard($_POST['navigationBoard']);
		}

		// Load the boolean parameters.
		$this->showCoordinates      = self::loadBooleanParameter('showCoordinates'     );
		$this->fenCompatibilityMode = self::loadBooleanParameter('fenCompatibilityMode');
		$this->pgnCompatibilityMode = self::loadBooleanParameter('pgnCompatibilityMode');
	}


	/**
	 * Load and validate the piece symbol parameter.
	 *
	 * @return string
	 */
	private static function loadPieceSymbols()
	{
		switch($_POST['pieceSymbols']) {
			case 'english'  : return 'native'   ;
			case 'localized': return 'localized';
			case 'figurines': return 'figurines';

			case 'custom':
				$kingSymbol   = self::loadPieceSymbol('kingSymbol'  );
				$queenSymbol  = self::loadPieceSymbol('queenSymbol' );
				$rookSymbol   = self::loadPieceSymbol('rookSymbol'  );
				$bishopSymbol = self::loadPieceSymbol('bishopSymbol');
				$knightSymbol = self::loadPieceSymbol('knightSymbol');
				$pawnSymbol   = self::loadPieceSymbol('pawnSymbol'  );
				return
					isset($kingSymbol  ) && isset($queenSymbol ) && isset($rookSymbol) &&
					isset($bishopSymbol) && isset($knightSymbol) && isset($pawnSymbol) ?
					'(' . $kingSymbol . $queenSymbol . $rookSymbol . $bishopSymbol . $knightSymbol . $pawnSymbol . ')' : null;

			default: return null;
		}
	}


	/**
	 * Load a single piece symbol.
	 *
	 * @param string $fieldName
	 * @return string
	 */
	private static function loadPieceSymbol($fieldName)
	{
		return isset($_POST[$fieldName]) ? RPBChessboardHelperValidation::validatePieceSymbol($_POST[$fieldName]) : null;
	}


	/**
	 * Load and validate a boolean post parameter with the given name.
	 *
	 * @param string $fieldName
	 * @return boolean
	 */
	private static function loadBooleanParameter($fieldName)
	{
		return isset($_POST[$fieldName]) ? RPBChessboardHelperValidation::validateBooleanFromInt($_POST[$fieldName]) : null;
	}


	/**
	 * Update the plugin general options.
	 *
	 * @return string
	 */
	public function updateOptions()
	{
		// Set the square size parameter.
		if(isset($this->squareSize)) {
			update_option('rpbchessboard_squareSize', $this->squareSize);
		}

		// Set the piece symbol parameter.
		if(isset($this->pieceSymbols)) {
			update_option('rpbchessboard_pieceSymbols', $this->pieceSymbols);
		}

		// Set the navigation board parameter.
		if(isset($this->navigationBoard)) {
			update_option('rpbchessboard_navigationBoard', $this->navigationBoard);
		}

		// Set the boolean parameters.
		$this->updateBooleanParameter('showCoordinates'     , $this->showCoordinates     );
		$this->updateBooleanParameter('fenCompatibilityMode', $this->fenCompatibilityMode);
		$this->updateBooleanParameter('pgnCompatibilityMode', $this->pgnCompatibilityMode);

		// Notify the user.
		return __('Settings saved.', 'rpbchessboard');
	}


	/**
	 * Update a global boolean parameter.
	 *
	 * @param string $key Name of the parameter in the dedicated WP table.
	 * @param boolean $value New value (if null, nothing happens).
	 */
	private function updateBooleanParameter($key, $value)
	{
		if(isset($value)) {
			update_option('rpbchessboard_' . $key, $value ? 1 : 0);
		}
	}
}
