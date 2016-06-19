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
 * Default general options with some additional information.
 */
class RPBChessboardModelCommonDefaultOptionsEx extends RPBChessboardAbstractModel {

	private static $availableColorsets;
	private static $customColorsetLabels = array();
	private static $pieceSymbolLocalizationAvailable;
	private static $simplifiedPieceSymbols;
	private static $pieceSymbolCustomValues;

	private static $BUILTIN_COLORSETS = array(
		'coral'      => 'Coral'     ,
		'dusk'       => 'Dusk'      ,
		'emerald'    => 'Emerald'   ,
		'gray'       => 'Gray'      ,
		'marine'     => 'Marine'    ,
		'original'   => 'Original'  ,
		'sandcastle' => 'Sandcastle',
		'scid'       => 'Scid'      ,
		'wikipedia'  => 'Wikipedia' ,
		'wheat'      => 'Wheat'     ,
		'xboard'     => 'XBoard'
	);

	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods('getMinimumSquareSize', 'getMaximumSquareSize',
			'getAvailableColorsets', 'isBuiltinColorset', 'isDefaultColorset', 'getColorsetLabel',
			'getAvailablePiecesets', 'isDefaultPieceset',
			'getMaximumAnimationSpeed', 'getStepAnimationSpeed',
			'isPieceSymbolLocalizationAvailable', 'getDefaultSimplifiedPieceSymbols', 'getDefaultPieceSymbolCustomValues');

		$this->loadDelegateModel('Common/DefaultOptions');
	}


	/**
	 * Minimum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMinimumSquareSize() {
		return RPBChessboardHelperValidation::MINIMUM_SQUARE_SIZE;
	}


	/**
	 * Maximum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMaximumSquareSize() {
		return RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Return all the available colorsets.
	 *
	 * @return array
	 */
	public function getAvailableColorsets() {
		if(!isset(self::$availableColorsets)) {
			self::$availableColorsets = array_keys(self::$BUILTIN_COLORSETS);

			$customColorsets = RPBChessboardHelperValidation::validateSetCodeList(get_option('rpbchessboard_custom_colorsets'));
			if(isset($customColorsets)) {
				self::$availableColorsets = array_merge(self::$availableColorsets, $customColorsets);
				asort(self::$availableColorsets);
			}
		}
		return self::$availableColorsets;
	}


	/**
	 * Check whether the given colorset is built-in or not.
	 *
	 * @return boolean
	 */
	public function isBuiltinColorset($colorset) {
		return isset(self::$BUILTIN_COLORSETS[$colorset]);
	}


	/**
	 * Check whether the given colorset is the default one or not.
	 *
	 * @param string $colorset
	 * @return boolean
	 */
	public function isDefaultColorset($colorset) {
		return $this->getDefaultColorset() === $colorset;
	}


	/**
	 * Return the label of the given colorset.
	 *
	 * @return string
	 */
	public function getColorsetLabel($colorset) {
		if(isset(self::$BUILTIN_COLORSETS[$colorset])) {
			return self::$BUILTIN_COLORSETS[$colorset];
		}
		else {
			if(!isset(self::$customColorsetLabels[$colorset])) {
				self::$customColorsetLabels[$colorset] = get_option('rpbchessboard_custom_colorset_label_' . $colorset, $colorset);
			}
			return self::$customColorsetLabels[$colorset];
		}
	}


	/**
	 * Return all the available piecesets.
	 *
	 * @return array
	 */
	public function getAvailablePiecesets() {
		return array(
			'cburnett' => (object) array('label' => 'CBurnett', 'builtin' => true),
			'celtic'   => (object) array('label' => 'Celtic'  , 'builtin' => true),
			'eyes'     => (object) array('label' => 'Eyes'    , 'builtin' => true),
			'fantasy'  => (object) array('label' => 'Fantasy' , 'builtin' => true),
			'skulls'   => (object) array('label' => 'Skulls'  , 'builtin' => true),
			'spatial'  => (object) array('label' => 'Spatial' , 'builtin' => true)
		);
	}


	/**
	 * Check whether the given pieceset is the default one or not.
	 *
	 * @param string $pieceset
	 * @return boolean
	 */
	public function isDefaultPieceset($pieceset) {
		return $this->getDefaultPieceset() === $pieceset;
	}


	/**
	 * Maximum value for the animation speed parameter.
	 *
	 * @return int
	 */
	public function getMaximumAnimationSpeed() {
		return RPBChessboardHelperValidation::MAXIMUM_ANIMATION_SPEED;
	}


	/**
	 * The animation speed parameter must be a multiple of this value.
	 */
	public function getStepAnimationSpeed() {
		return RPBChessboardHelperValidation::STEP_ANIMATION_SPEED;
	}


	/**
	 * Whether the localization is available for piece symbols or not.
	 *
	 * @return boolean
	 */
	public function isPieceSymbolLocalizationAvailable() {
		if(!isset(self::$pieceSymbolLocalizationAvailable)) {
			$englishPieceSymbols = 'KQRBNP';
			$localizedPieceSymbols =
				/*i18n King symbol   */ __('K', 'rpbchessboard') .
				/*i18n Queen symbol  */ __('Q', 'rpbchessboard') .
				/*i18n Rook symbol   */ __('R', 'rpbchessboard') .
				/*i18n Bishop symbol */ __('B', 'rpbchessboard') .
				/*i18n Knight symbol */ __('N', 'rpbchessboard') .
				/*i18n Pawn symbol   */ __('P', 'rpbchessboard');
			self::$pieceSymbolLocalizationAvailable = ($englishPieceSymbols !== $localizedPieceSymbols);
		}
		return self::$pieceSymbolLocalizationAvailable;
	}


	/**
	 * Simplified version of the default piece symbol mode.
	 *
	 * @return boolean
	 */
	public function getDefaultSimplifiedPieceSymbols() {
		if(!isset(self::$simplifiedPieceSymbols)) {
			switch($this->getDefaultPieceSymbols()) {
				case 'native'   : self::$simplifiedPieceSymbols = 'english'  ; break;
				case 'figurines': self::$simplifiedPieceSymbols = 'figurines'; break;
				case 'localized':
					self::$simplifiedPieceSymbols = $this->isPieceSymbolLocalizationAvailable() ? 'localized' : 'english';
					break;
				default:
					self::$simplifiedPieceSymbols = 'custom';
					break;
			}
		}
		return self::$simplifiedPieceSymbols;
	}


	/**
	 * Default values for the piece symbol custom fields.
	 *
	 * @return array
	 */
	public function getDefaultPieceSymbolCustomValues() {
		if(!isset(self::$pieceSymbolCustomValues)) {
			if($this->getDefaultSimplifiedPieceSymbols() === 'custom') {
				$pieceSymbols = $this->getDefaultPieceSymbols();
				self::$pieceSymbolCustomValues = array(
					'K' => substr($pieceSymbols, 1, 1),
					'Q' => substr($pieceSymbols, 2, 1),
					'R' => substr($pieceSymbols, 3, 1),
					'B' => substr($pieceSymbols, 4, 1),
					'N' => substr($pieceSymbols, 5, 1),
					'P' => substr($pieceSymbols, 6, 1)
				);
			}
			else {
				self::$pieceSymbolCustomValues = array();
			}
		}
		return self::$pieceSymbolCustomValues;
	}
}
