<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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


/**
 * Register the plugin JavaScript scripts.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardScripts {

	public static function register() {
		$ext = self::getJSFileExtension();

		// Moment.js (http://momentjs.com/)
		self::registerLocalizedScript(
			'rpbchessboard-momentjs',
			'third-party-libs/moment-js/moment' . $ext,
			'third-party-libs/moment-js/locales/%1$s.js',
			false,
			'2.13.0',
			false
		);

		// Dependencies resolved using NPM
		$asset_file = include RPBCHESSBOARD_ABSPATH . 'build/index.asset.php';
		wp_register_script(
			'rpbchessboard-npm',
			RPBCHESSBOARD_URL . 'build/index.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			false
		);

		// Configure JS
		wp_localize_script(
			'rpbchessboard-npm',
			'RPBChessboard',
			array(
				'publicURL'           => RPBCHESSBOARD_URL,
				'customColorsets'     => self::getCustomColorsets(),
				'customPiecesets'     => self::getCustomPiecesets(),
				'availableColorsets'  => self::getAvailableColorsets(),
				'availablePiecesets'  => self::getAvailablePiecesets(),
				'availableSquareSize' => self::getAvailableSquareSize(),
				'fenShortcode'        => self::getFENShortcode(),
				'defaultSettings'     => self::getDefaultSettings(),
				'i18n'                => self::getJsI18n(),
			)
		);

		// Chessgame widget
		self::registerLocalizedScript(
			'rpbchessboard-chessgame',
			'js/rpbchess-ui-chessgame' . $ext,
			'js/chessgame-locales/%1$s' . $ext,
			array(
				'rpbchessboard-npm',
				'rpbchessboard-momentjs',
				'jquery-ui-widget',
				'jquery-ui-button',
				'jquery-ui-selectable',
				'jquery-color',
				'jquery-ui-dialog',
				'jquery-ui-resizable',
			),
			RPBCHESSBOARD_VERSION
		);

		// Additional scripts for the backend.
		// FIXME Those scripts should be enqueued only if necessary. To achieve that, we need to fix issue concerning inlined scripts,
		// interaction with the TinyMCE/QuickTag editors, and to carefully review what is used on which page.
		if ( is_admin() ) {
			wp_enqueue_script( 'rpbchessboard-chessgame' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'iris' );
			wp_enqueue_media();

		} else {

			// In frontend, force jQuery to be loaded in the header (should be the case anyway in most themes).
			wp_enqueue_script( 'jquery' );

			// Enqueue the JS if lazy-loading is disabled.
			$compatibility = RPBChessboardHelperLoader::loadModel( 'Common/Compatibility' );
			if ( ! $compatibility->getLazyLoadingForCSSAndJS() ) {
				wp_enqueue_script( 'rpbchessboard-chessgame' );
			}
		}
	}


	private static function getCustomColorsets() {
		$model  = RPBChessboardHelperLoader::loadModel( 'Common/CustomColorsets' );
		$result = array();
		foreach ( $model->getCustomColorsets() as $colorset ) {
			$result[ $colorset ] = array(
				'b'         => $model->getDarkSquareColor( $colorset ),
				'w'         => $model->getLightSquareColor( $colorset ),
				'g'         => $model->getGreenMarkerColor( $colorset ),
				'r'         => $model->getRedMarkerColor( $colorset ),
				'y'         => $model->getYellowMarkerColor( $colorset ),
				'highlight' => $model->getHighlightColor( $colorset ),
			);
		}
		return $result;
	}


	private static function getCustomPiecesets() {
		$model  = RPBChessboardHelperLoader::loadModel( 'Common/CustomPiecesets' );
		$result = array();
		foreach ( $model->getCustomPiecesets() as $pieceset ) {
			$current = array();
			foreach ( RPBChessboardModelCommonCustomPiecesets::$COLORED_PIECE_CODES as $coloredPiece ) {
				$current[ $coloredPiece ] = $model->getCustomPiecesetImageURL( $pieceset, $coloredPiece );
			}
			$result[ $pieceset ] = $current;
		}
		return $result;
	}


	private static function getAvailableColorsets() {
		$model  = RPBChessboardHelperLoader::loadModel( 'Common/DefaultOptionsEx' );
		$result = array();
		foreach ( $model->getAvailableColorsets() as $colorset ) {
			$result[ $colorset ] = $model->getColorsetLabel( $colorset );
		}
		return $result;
	}


	private static function getAvailablePiecesets() {
		$model  = RPBChessboardHelperLoader::loadModel( 'Common/DefaultOptionsEx' );
		$result = array();
		foreach ( $model->getAvailablePiecesets() as $pieceset ) {
			$result[ $pieceset ] = $model->getPiecesetLabel( $pieceset );
		}
		return $result;
	}


	private static function getAvailableSquareSize() {
		$model = RPBChessboardHelperLoader::loadModel( 'Common/DefaultOptionsEx' );
		return array(
			'min' => $model->getMinimumSquareSize(),
			'max' => $model->getMaximumSquareSize(),
		);
	}


	private static function getFENShortcode() {
		$model = RPBChessboardHelperLoader::loadModel( 'Common/Compatibility' );
		return $model->getFENShortcode();
	}


	private static function getDefaultSettings() {
		$model = RPBChessboardHelperLoader::loadModel( 'Common/DefaultOptions' );
		return array(
			'squareSize'      => $model->getDefaultSquareSize(),
			'showCoordinates' => $model->getDefaultShowCoordinates(),
			'colorset'        => $model->getDefaultColorset(),
			'pieceset'        => $model->getDefaultPieceset(),
		);
	}


	private static function getJsI18n() {
		return array(
			'FEN_EDITOR_TITLE'                     => __( 'Chess diagram', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_MOVE_PIECES'         => __( 'Move pieces', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_ADD_PIECES'          => array(
				'w' => __( 'Add white pieces', 'rpb-chessboard' ),
				'b' => __( 'Add black pieces', 'rpb-chessboard' ),
			),
			'FEN_EDITOR_LABEL_ADD_PIECE'           => array(
				'wp' => __( 'Add white pawn', 'rpb-chessboard' ),
				'wn' => __( 'Add white knight', 'rpb-chessboard' ),
				'wb' => __( 'Add white bishop', 'rpb-chessboard' ),
				'wr' => __( 'Add white rook', 'rpb-chessboard' ),
				'wq' => __( 'Add white queen', 'rpb-chessboard' ),
				'wk' => __( 'Add white king', 'rpb-chessboard' ),
				'bp' => __( 'Add black pawn', 'rpb-chessboard' ),
				'bn' => __( 'Add black knight', 'rpb-chessboard' ),
				'bb' => __( 'Add black bishop', 'rpb-chessboard' ),
				'br' => __( 'Add black rook', 'rpb-chessboard' ),
				'bq' => __( 'Add black queen', 'rpb-chessboard' ),
				'bk' => __( 'Add black king', 'rpb-chessboard' ),
			),
			'FEN_EDITOR_LABEL_TOGGLE_TURN'         => __( 'Toggle turn', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_FLIP'                => __( 'Flip board', 'rpb-chessboard' ),
			'FEN_EDITOR_PANEL_POSITION'            => __( 'Position & annotations', 'rpb-chessboard' ),
			'FEN_EDITOR_PANEL_APPEARANCE'          => __( 'Chessboard aspect', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_RESET_POSITION'      => __( 'Reset', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_CLEAR_POSITION'      => __( 'Clear', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_CLEAR_ANNOTATIONS'   => __( 'Clear annotations', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_SQUARE_MARKER'       => __( 'Square marker', 'rpb-chessboard'),
			'FEN_EDITOR_LABEL_ARROW_MARKER'        => __( 'Arrow marker', 'rpb-chessboard'),
			'FEN_EDITOR_TOOLTIP_RESET_POSITION'    => __( 'Reset to the startup position', 'rpb-chessboard' ),
			'FEN_EDITOR_TOOLTIP_CLEAR_POSITION'    => __( 'Remove all pieces', 'rpb-chessboard' ),
			'FEN_EDITOR_TOOLTIP_CLEAR_ANNOTATIONS' => __( 'Remove all square/arrow markers', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_ALIGNMENT'         => __( 'Diagram alignment', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_USE_DEFAULT_SIZE'  => __( 'Use default square size', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_SQUARE_SIZE'       => __( 'Square size', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_COORDINATES'       => __( 'Coordinate visibility', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_COLORSET'          => __( 'Colorset', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_PIECESET'          => __( 'Pieceset', 'rpb-chessboard' ),
			'FEN_EDITOR_USE_DEFAULT'               => __( 'Use default', 'rpb-chessboard' ),
			'FEN_EDITOR_CURRENT_EDITION_MODE'      => __( 'Current edition mode', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_CENTER'             => __( 'Centered', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_FLOAT_LEFT'         => __( 'Float on left', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_FLOAT_RIGHT'        => __( 'Float on right', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_HIDDEN'             => __( 'Hidden', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_VISIBLE'            => __( 'Visible', 'rpb-chessboard' ),
		);
	}


	/**
	 * Return the extension to use for the included JS files.
	 *
	 * @return string
	 */
	private static function getJSFileExtension() {
		return WP_DEBUG ? '.js' : '.min.js';
	}


	/**
	 * Determine the language code to use to configure a given JavaScript library, and enqueue the required file.
	 *
	 * @param string $handle Handle of the library.
	 * @param string $relativeBasePath Relative path to the core library file.
	 * @param string $relativeLocalizationPathTemplate Relative path to where the localized files should be searched.
	 * @param array $dependencies Dependencies of the core library.
	 * @param string $version Version the library.
	 */
	private static function registerLocalizedScript( $handle, $relativeBasePath, $relativeLocalizationPathTemplate, $dependencies, $version ) {

		$relativeLocalizationPath = self::computeLocalizationPath( $relativeLocalizationPathTemplate );

		if ( isset( $relativeLocalizationPath ) ) {
			$baseHandle = $handle . '-core';
			wp_register_script( $baseHandle, RPBCHESSBOARD_URL . $relativeBasePath, $dependencies, $version, false );
			wp_register_script( $handle, RPBCHESSBOARD_URL . $relativeLocalizationPath, array( $baseHandle ), $version, false );
		} else {
			wp_register_script( $handle, RPBCHESSBOARD_URL . $relativeBasePath, $dependencies, $version, false );
		}
	}


	/**
	 * Find the localization file of a library, based on the current locale.
	 *
	 * @param string $relativeLocalizationPathTemplate Relative path to where the localized files should be searched.
	 * @return string Relative path to the localization file of the target library, or `null` if no such file could be found.
	 */
	private static function computeLocalizationPath( $relativeLocalizationPathTemplate ) {
		foreach ( self::getBlogLangCodes() as $langCode ) {

			// Does the translation script file exist for the current language code?
			$relativeLocalizationPath = sprintf( $relativeLocalizationPathTemplate, $langCode );
			if ( file_exists( RPBCHESSBOARD_ABSPATH . $relativeLocalizationPath ) ) {
				return $relativeLocalizationPath;
			}
		}

		// Otherwise, if no translation file exists, return null.
		return null;
	}


	/**
	 * Return an array of language codes that may be relevant for the blog.
	 *
	 * @return array
	 */
	private static function getBlogLangCodes() {
		if ( ! isset( self::$blogLangCodes ) ) {
			$mainLanguage        = str_replace( '_', '-', strtolower( get_locale() ) );
			self::$blogLangCodes = array( $mainLanguage );

			if ( preg_match( '/([a-z]+)\\-([a-z]+)/', $mainLanguage, $m ) ) {
				self::$blogLangCodes[] = $m[1];
			}
		}
		return self::$blogLangCodes;
	}


	/**
	 * Blog language codes.
	 */
	private static $blogLangCodes;
}
