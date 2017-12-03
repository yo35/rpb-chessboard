<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2017  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Process the request resulting from a click in the "Options" form in the backend.
 */
class RPBChessboardModelPostSaveOptions extends RPBChessboardAbstractModel {

	/**
	 * Update the plugin general options.
	 *
	 * @return string
	 */
	public function updateOptions() {

		// General parameters
		self::processSquareSize();
		self::processBooleanParameter('showCoordinates');
		self::processSetCodeParameter('colorset');
		self::processSetCodeParameter('pieceset');
		self::processDiagramAlignment();
		self::processPieceSymbols();
		self::processNavigationBoard();
		self::processBooleanParameter('showFlipButton');
		self::processBooleanParameter('showDownloadButton');
		self::processAnimationSpeed();
		self::processBooleanParameter('showMoveArrow');

		// Compatibility parameters.
		self::processBooleanParameter('fenCompatibilityMode');
		self::processBooleanParameter('pgnCompatibilityMode');
		self::processBooleanParameter('noConflictForButton');

		// Small-screen parameters
		self::processBooleanParameter('smallScreenCompatibility');
		self::processSmallScreenModes();
		RPBChessboardHelperCache::remove('small-screens.css');

		// Notify the user.
		return __('Settings saved.', 'rpb-chessboard');
	}


	/**
	 * Change the default colorset.
	 */
	public function updateDefaultColorset() {
		return self::processSetCodeParameter('colorset') ? __('Default colorset changed.', 'rpb-chessboard') : null;
	}


	/**
	 * Change the default pieceset.
	 */
	public function updateDefaultPieceset() {
		return self::processSetCodeParameter('pieceset') ? __('Default pieceset changed.', 'rpb-chessboard') : null;
	}


	private static function processSquareSize() {
		if(isset($_POST['squareSize'])) {
			$value = RPBChessboardHelperValidation::validateSquareSize($_POST['squareSize']);
			if(isset($value)) {
				update_option('rpbchessboard_squareSize', $value);
			}
		}
	}


	private static function processDiagramAlignment() {
		if(isset($_POST['diagramAlignment'])) {
			$value = RPBChessboardHelperValidation::validateDiagramAlignment($_POST['diagramAlignment']);
			if(isset($value)) {
				update_option('rpbchessboard_diagramAlignment', $value);
			}
		}
	}


	private static function processPieceSymbols() {
		$value = self::loadPieceSymbols();
		if(isset($value)) {
			update_option('rpbchessboard_pieceSymbols', $value);
		}
	}


	private static function processNavigationBoard() {
		if(isset($_POST['navigationBoard'])) {
			$value = RPBChessboardHelperValidation::validateNavigationBoard($_POST['navigationBoard']);
			if(isset($value)) {
				update_option('rpbchessboard_navigationBoard', $value);
			}
		}
	}


	private static function processAnimationSpeed() {
		if(isset($_POST['animationSpeed'])) {
			$value = RPBChessboardHelperValidation::validateAnimationSpeed($_POST['animationSpeed']);
			if(isset($value)) {
				update_option('rpbchessboard_animationSpeed', $value);
			}
		}
	}


	private static function processSmallScreenModes() {
		$value = self::loadSmallScreenModes();
		if(isset($value)) {
			update_option('rpbchessboard_smallScreenModes', $value);
		}
	}


	private static function processSetCodeParameter($key) {
		if(isset($_POST[$key])) {
			$value = RPBChessboardHelperValidation::validateSetCode($_POST[$key]);
			if(isset($value)) {
				update_option('rpbchessboard_' . $key, $value);
				return true;
			}
		}
		return false;
	}


	private static function processBooleanParameter($key) {
		if(isset($_POST[$key])) {
			$value = RPBChessboardHelperValidation::validateBooleanFromInt($_POST[$key]);
			if(isset($value)) {
				update_option('rpbchessboard_' . $key, $value ? 1 : 0);
			}
		}
	}


	/**
	 * Load and validate the piece symbol parameter.
	 *
	 * @return string
	 */
	private static function loadPieceSymbols() {
		if(!isset($_POST['pieceSymbols'])) {
			return null;
		}

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
	private static function loadPieceSymbol($fieldName) {
		return isset($_POST[$fieldName]) ? RPBChessboardHelperValidation::validatePieceSymbol($_POST[$fieldName]) : null;
	}


	/**
	 * Load and validate the small-screen mode parameters.
	 *
	 * @return string
	 */
	private static function loadSmallScreenModes() {
		if(!isset($_POST['smallScreenModes'])) {
			return null;
		}

		$smallScreenModeCount = RPBChessboardHelperValidation::validateInteger($_POST['smallScreenModes'], 0);
		$smallScreenModes = array();
		for($index=0; $index<$smallScreenModeCount; ++$index) {
			$smallScreenMode = self::loadSmallScreenMode($index);
			if(isset($smallScreenMode)) {
				array_push($smallScreenModes, $smallScreenMode);
			}
		}
		return implode(',', $smallScreenModes);
	}


	/**
	 * Load and validate the small-screen mode corresponding to the given index.
	 *
	 * @param int $index
	 * @return string
	 */
	private static function loadSmallScreenMode($index) {
		$screenWidthKey     = 'smallScreenMode' . $index . '-screenWidth'    ;
		$squareSizeKey      = 'smallScreenMode' . $index . '-squareSize'     ;
		$hideCoordinatesKey = 'smallScreenMode' . $index . '-hideCoordinates';
		if(isset($_POST[$screenWidthKey]) && isset($squareSizeKey) && isset($hideCoordinatesKey)) {
			$screenWidth     = RPBChessboardHelperValidation::validateInteger       ($_POST[$screenWidthKey    ], 1);
			$squareSize      = RPBChessboardHelperValidation::validateSquareSize    ($_POST[$squareSizeKey     ]);
			$hideCoordinates = RPBChessboardHelperValidation::validateBooleanFromInt($_POST[$hideCoordinatesKey]);
			if(isset($screenWidth) && isset($squareSize) && isset($hideCoordinates)) {
				return $screenWidth . ':' . $squareSize . ':' . ($hideCoordinates ? 'true' : 'false');
			}
		}
		return null;
	}
}
