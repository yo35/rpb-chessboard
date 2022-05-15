<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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

		// Dependencies resolved using NPM
		$asset_file = include RPBCHESSBOARD_ABSPATH . 'build/index.asset.php';
		wp_register_script(
			'rpbchessboard-npm',
			RPBCHESSBOARD_URL . 'build/index.js',
			array_merge( $asset_file['dependencies'], array( 'jquery-ui-dialog' ) ),
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
				'defaultSettings'     => self::getDefaultSettings(),
				'smallScreenLimits'   => self::getSmallScreenLimits(),
				'i18n'                => self::getJsI18n(),
			)
		);

		// Additional scripts for the backend.
		// FIXME Those scripts should be enqueued only if necessary. To achieve that, we need to fix issue concerning inlined scripts,
		// interaction with the TinyMCE/QuickTag editors, and to carefully review what is used on which page.
		if ( is_admin() ) {
			wp_enqueue_script( 'rpbchessboard-npm' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'iris' );
			wp_enqueue_media();

		} else {

			// In frontend, force jQuery to be loaded in the header (should be the case anyway in most themes).
			wp_enqueue_script( 'jquery' );

			// Enqueue the JS if lazy-loading is disabled.
			$compatibility = RPBChessboardHelperLoader::loadModel( 'Common/Compatibility' );
			if ( ! $compatibility->getLazyLoadingForCSSAndJS() ) {
				wp_enqueue_script( 'rpbchessboard-npm' );
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


	private static function getDefaultSettings() {
		$model = RPBChessboardHelperLoader::loadModel( 'Common/DefaultOptions' );
		return array(
			'squareSize'      => $model->getDefaultSquareSize(),
			'showCoordinates' => $model->getDefaultShowCoordinates(),
			'colorset'        => $model->getDefaultColorset(),
			'pieceset'        => $model->getDefaultPieceset(),
			'animated'        => $model->getDefaultAnimated(),
			'showMoveArrow'   => $model->getDefaultShowMoveArrow(),
			'pieceSymbols'    => $model->getDefaultPieceSymbols(),
			'navigationBoard' => $model->getDefaultNavigationBoard(),
		);
	}


	private static function getSmallScreenLimits() {
		$model  = RPBChessboardHelperLoader::loadModel( 'Common/SmallScreens' );
		$result = array();
		foreach ( $model->getSmallScreenModes() as $mode ) {
			array_push(
				$result,
				array(
					'width'             => $mode->maxScreenWidth,
					'squareSize'        => $mode->squareSize,
					'coordinateVisible' => ! $mode->hideCoordinates,
				)
			);
		}
		return $result;
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
			'FEN_EDITOR_LABEL_FEN'                 => __( 'FEN', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_SQUARE_MARKER'       => __( 'Square marker', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_ARROW_MARKER'        => __( 'Arrow marker', 'rpb-chessboard' ),
			'FEN_EDITOR_LABEL_TEXT_MARKER'         => __( 'Marker {0}', 'rpb-chessboard' ),
			'FEN_EDITOR_TOOLTIP_RESET_POSITION'    => __( 'Reset to the startup position', 'rpb-chessboard' ),
			'FEN_EDITOR_TOOLTIP_CLEAR_POSITION'    => __( 'Remove all pieces', 'rpb-chessboard' ),
			'FEN_EDITOR_TOOLTIP_CLEAR_ANNOTATIONS' => __( 'Remove all square/arrow/text markers', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_ALIGNMENT'         => __( 'Diagram alignment', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_FLIP'              => __( 'Flip the board', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_USE_DEFAULT_SIZE'  => __( 'Use default square size', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_SQUARE_SIZE'       => __( 'Square size', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_COORDINATES'       => __( 'Coordinates', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_COLORSET'          => __( 'Colorset', 'rpb-chessboard' ),
			'FEN_EDITOR_CONTROL_PIECESET'          => __( 'Pieceset', 'rpb-chessboard' ),
			'FEN_EDITOR_USE_DEFAULT'               => __( 'Use default', 'rpb-chessboard' ),
			'FEN_EDITOR_CURRENT_EDITION_MODE'      => __( 'Current edition mode', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_CENTER'             => __( 'Centered', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_FLOAT_LEFT'         => __( 'Float on left', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_FLOAT_RIGHT'        => __( 'Float on right', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_HIDDEN'             => __( 'Hidden', 'rpb-chessboard' ),
			'FEN_EDITOR_OPTION_VISIBLE'            => __( 'Visible', 'rpb-chessboard' ),

			'FEN_PARSING_ERROR_TITLE'              => __( 'Error in the FEN string describing the chess diagram.', 'rpb-chessboard' ),

			'PGN_PARSING_ERROR_TITLE'              => __( 'Error while analyzing the PGN string.', 'rpb-chessboard' ),
			'PGN_DOWNLOAD_ERROR_TITLE'             => __( 'Error while downloading the PGN file.', 'rpb-chessboard' ),
			'PGN_DOWNLOAD_ERROR_MESSAGE'           => __( 'Cannot download `{0}`. HTTP request returns status code {1}.', 'rpb-chessboard' ),
			'PGN_TOOLTIP_GO_FIRST'                 => __( 'Go to the beginning of the game', 'rpb-chessboard' ),
			'PGN_TOOLTIP_GO_PREVIOUS'              => __( 'Go to the previous move', 'rpb-chessboard' ),
			'PGN_TOOLTIP_GO_NEXT'                  => __( 'Go to the next move', 'rpb-chessboard' ),
			'PGN_TOOLTIP_GO_LAST'                  => __( 'Go to the end of the game', 'rpb-chessboard' ),
			'PGN_TOOLTIP_FLIP'                     => __( 'Flip the board', 'rpb-chessboard' ),
			'PGN_TOOLTIP_DOWNLOAD'                 => __( 'Download the game', 'rpb-chessboard' ),
			'PGN_ANNOTATED_BY'                     => __( 'Annotated by {0}', 'rpb-chessboard' ),
			'PGN_LINE_REF'                         => __( 'line {0}', 'rpb-chessboard' ),
			'PGN_INITIAL_POSITION'                 => __( 'Initial position', 'rpb-chessboard' ),

			'PIECE_SYMBOLS'                        => array(
				'K' => /*i18n King symbol   */ __( 'K', 'rpb-chessboard' ),
				'Q' => /*i18n Queen symbol  */ __( 'Q', 'rpb-chessboard' ),
				'R' => /*i18n Rook symbol   */ __( 'R', 'rpb-chessboard' ),
				'B' => /*i18n Bishop symbol */ __( 'B', 'rpb-chessboard' ),
				'N' => /*i18n Knight symbol */ __( 'N', 'rpb-chessboard' ),
				'P' => /*i18n Pawn symbol   */ __( 'P', 'rpb-chessboard' ),
			),

			'PGN_EDITOR_TITLE'                     => __( 'Chess game', 'rpb-chessboard' ),
			'PGN_EDITOR_TEXT_LABEL'                => __( 'PGN text', 'rpb-chessboard' ),
			'PGN_EDITOR_PANEL_GAME_OPTIONS'        => __( 'Game options', 'rpb-chessboard' ),
			'PGN_EDITOR_PANEL_PIECE_SYMBOLS'       => __( 'Piece symbols', 'rpb-chessboard' ),
			'PGN_EDITOR_PANEL_NAVIGATION_BOARD'    => __( 'Navigation board', 'rpb-chessboard' ),
			'PGN_EDITOR_PANEL_DIAGRAM_OPTIONS'     => __( 'Diagram options', 'rpb-chessboard' ),
			'PGN_EDITOR_USE_DEFAULT'               => __( 'Use default', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_NATIVE'             => __( 'English initials', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_LOCALIZED'          => __( 'Localized initials', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_FIGURINES'          => __( 'Figurines', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_CUSTOM'             => __( 'Custom', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_NONE'               => __( 'None', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_FRAME'              => __( 'Frame', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_ABOVE'              => __( 'Above', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_BELOW'              => __( 'Below', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_FLOAT_LEFT'         => __( 'Float on left', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_FLOAT_RIGHT'        => __( 'Float on right', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_SCROLL_LEFT'        => __( 'Scroll on left', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_SCROLL_RIGHT'       => __( 'Scroll on right', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_DISABLED'           => __( 'Disabled', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_ENABLED'            => __( 'Enabled', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_HIDDEN'             => __( 'Hidden', 'rpb-chessboard' ),
			'PGN_EDITOR_OPTION_VISIBLE'            => __( 'Visible', 'rpb-chessboard' ),
			'PGN_EDITOR_CONTROL_FLIP'              => __( 'Flip nav. board and diagrams', 'rpb-chessboard' ),
			'PGN_EDITOR_CONTROL_ANIMATED'          => __( 'Animation', 'rpb-chessboard' ),
			'PGN_EDITOR_CONTROL_MOVE_ARROW'        => __( 'Move arrow', 'rpb-chessboard' ),
			'PGN_EDITOR_CONTROL_FLIP_BUTTON'       => __( 'Flip button', 'rpb-chessboard' ),
			'PGN_EDITOR_CONTROL_DOWNLOAD_BUTTON'   => __( 'Download button', 'rpb-chessboard' ),
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
}
