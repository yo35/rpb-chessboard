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


require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * Process the request resulting from a click in the "Options" form in the backend.
 */
class RPBChessboardTraitPostOptions extends RPBChessboardAbstractTrait
{
	// General options
	private $squareSize;
	private $showCoordinates;
	private $pieceSymbols;
	private $navigationBoard;
	private $animationSpeed;
	private $showMoveArrow;

	// Compatibility options
	private $fenCompatibilityMode;
	private $pgnCompatibilityMode;

	// Small-screen options
	private $smallScreenCompatibility;
	private $smallScreenModes;


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

		// Load the animation speed parameter.
		if(isset($_POST['animationSpeed'])) {
			$this->animationSpeed = RPBChessboardHelperValidation::validateAnimationSpeed($_POST['animationSpeed']);
		}

		// Load the boolean parameters.
		$this->showCoordinates          = self::loadBooleanParameter('showCoordinates'         );
		$this->showMoveArrow            = self::loadBooleanParameter('showMoveArrow'           );
		$this->fenCompatibilityMode     = self::loadBooleanParameter('fenCompatibilityMode'    );
		$this->pgnCompatibilityMode     = self::loadBooleanParameter('pgnCompatibilityMode'    );
		$this->smallScreenCompatibility = self::loadBooleanParameter('smallScreenCompatibility');

		// Load the small-screen options
		if(isset($_POST['smallScreenModes'])) {
			$this->smallScreenModes = self::loadSmallScreenModes();
		}
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
	 * Load and validate the small-screen mode parameters.
	 *
	 * @return string
	 */
	private static function loadSmallScreenModes() {
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

		// Set the animation speed parameter.
		if(isset($this->animationSpeed)) {
			update_option('rpbchessboard_animationSpeed', $this->animationSpeed);
		}

		// Set the boolean parameters.
		self::updateBooleanParameter('showCoordinates'         , $this->showCoordinates         );
		self::updateBooleanParameter('showMoveArrow'           , $this->showMoveArrow           );
		self::updateBooleanParameter('fenCompatibilityMode'    , $this->fenCompatibilityMode    );
		self::updateBooleanParameter('pgnCompatibilityMode'    , $this->pgnCompatibilityMode    );
		self::updateBooleanParameter('smallScreenCompatibility', $this->smallScreenCompatibility);

		// Set the small-screen mode parameters.
		if(isset($this->smallScreenModes)) {
			update_option('rpbchessboard_smallScreenModes', $this->smallScreenModes);
		}

		// Notify the user.
		return __('Settings saved.', 'rpbchessboard');
	}


	/**
	 * Update a global boolean parameter.
	 *
	 * @param string $key Name of the parameter in the dedicated WP table.
	 * @param boolean $value New value (if null, nothing happens).
	 */
	private static function updateBooleanParameter($key, $value)
	{
		if(isset($value)) {
			update_option('rpbchessboard_' . $key, $value ? 1 : 0);
		}
	}
}
