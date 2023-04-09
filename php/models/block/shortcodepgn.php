<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2023  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/block/abstract.php';


/**
 * Model associated to the [pgn][/pgn] shortcode.
 */
class RPBChessboardModelBlockShortcodePGN extends RPBChessboardAbstractModelBlock {

	private $loadedFromExternalPGNFile;
	private $externalPGNFile;


	/**
	 * Return whether the PGN data is loaded from an external file or not.
	 *
	 * @return boolean
	 */
	public function isLoadedFromExternalPGNFile() {
		$this->initializeExternalPGNFile();
		return $this->loadedFromExternalPGNFile;
	}


	/**
	 * Return the URL of the external PGN file to load.
	 *
	 * @return string
	 */
	public function getExternalPGNFile() {
		$this->initializeExternalPGNFile();
		return $this->externalPGNFile;
	}


	private function initializeExternalPGNFile() {
		if ( isset( $this->loadedFromExternalPGNFile ) ) {
			return;
		}

		$atts = $this->getAttributes();

		if ( isset( $atts['url'] ) && '' !== $atts['url'] ) {
			$this->externalPGNFile           = $atts['url'];
			$this->loadedFromExternalPGNFile = true;
		} else {
			$this->externalPGNFile           = '';
			$this->loadedFromExternalPGNFile = false;
		}
	}


	/**
	 * Return the arguments to pass to the JS chessboard widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {

		$atts       = $this->getAttributes();
		$widgetArgs = array();

		// Chessgame content
		if ( $this->isLoadedFromExternalPGNFile() ) {
			$widgetArgs['url'] = $this->getExternalPGNFile();
		} else {
			$widgetArgs['pgn'] = $this->getContent();
		}
		$value = isset( $atts['game'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['game'] ) : null;
		if ( isset( $value ) ) {
			$widgetArgs['gameIndex'] = $value;
		}

		// Content customization
		$value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['flip'] ) : null;
		if ( isset( $value ) ) {
			$widgetArgs['flipped'] = $value;
		}
		$value = isset( $atts['initial_selection'] ) ? RPBChessboardHelperValidation::validateString( $atts['initial_selection'] ) : null;
		if ( isset( $value ) ) {
			$widgetArgs['initialSelection'] = $value;
		}

		// Chessgame aspect
		$value                            = isset( $atts['piece_symbols'] ) ? RPBChessboardHelperValidation::validatePieceSymbols( $atts['piece_symbols'] ) : null;
		$widgetArgs['pieceSymbols']       = isset( $value ) ? $value : $this->mainModel->getDefaultPieceSymbols();
		$value                            = isset( $atts['navigation_board'] ) ? RPBChessboardHelperValidation::validateNavigationBoard( $atts['navigation_board'] ) : null;
		$widgetArgs['navigationBoard']    = isset( $value ) ? $value : $this->mainModel->getDefaultNavigationBoard();
		$value                            = isset( $atts['show_flip_button'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_flip_button'] ) : null;
		$widgetArgs['withFlipButton']     = isset( $value ) ? $value : $this->mainModel->getDefaultShowFlipButton();
		$value                            = isset( $atts['show_download_button'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_download_button'] ) : null;
		$widgetArgs['withDownloadButton'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowDownloadButton();

		// Legacy parameters
		$squareSize      = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['square_size'] ) : null;
		$showCoordinates = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_coordinates'] ) : null;
		$colorset        = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['colorset'] ) : null;
		$pieceset        = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['pieceset'] ) : null;
		$animated        = isset( $atts['animated'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['animated'] ) : null;
		$showMoveArrow   = isset( $atts['show_move_arrow'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_move_arrow'] ) : null;
		$moveArrowColor  = isset( $atts['move_arrow_color'] ) ? RPBChessboardHelperValidation::validateSymbolicColor( $atts['move_arrow_color'] ) : null;

		// Specific options for the navigation board.
		$value                              = isset( $atts['nav_square_size'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['nav_square_size'] ) : $squareSize;
		$widgetArgs['nboSquareSize']        = isset( $value ) ? $value : $this->mainModel->getDefaultSquareSize( 'nbo' );
		$value                              = isset( $atts['nav_show_coordinates'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['nav_show_coordinates'] ) : $showCoordinates;
		$widgetArgs['nboCoordinateVisible'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowCoordinates( 'nbo' );
		$value                              = isset( $atts['nav_colorset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['nav_colorset'] ) : $colorset;
		$widgetArgs['nboColorset']          = isset( $value ) ? $value : $this->mainModel->getDefaultColorset( 'nbo' );
		$value                              = isset( $atts['nav_pieceset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['nav_pieceset'] ) : $pieceset;
		$widgetArgs['nboPieceset']          = isset( $value ) ? $value : $this->mainModel->getDefaultPieceset( 'nbo' );
		$value                              = isset( $atts['nav_animated'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['nav_animated'] ) : $animated;
		$widgetArgs['nboAnimated']          = isset( $value ) ? $value : $this->mainModel->getDefaultAnimated();
		$value                              = isset( $atts['nav_show_move_arrow'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['nav_show_move_arrow'] ) : $showMoveArrow;
		$widgetArgs['nboMoveArrowVisible']  = isset( $value ) ? $value : $this->mainModel->getDefaultShowMoveArrow();
		$value                              = isset( $atts['nav_move_arrow_color'] ) ? RPBChessboardHelperValidation::validateSymbolicColor( $atts['nav_move_arrow_color'] ) : $moveArrowColor;
		$widgetArgs['nboMoveArrowColor']    = $this->mainModel->getDefaultMoveArrowColor();

		// Specific options for the diagrams.
		$value                              = isset( $atts['diag_square_size'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['diag_square_size'] ) : $squareSize;
		$widgetArgs['idoSquareSize']        = isset( $value ) ? $value : $this->mainModel->getDefaultSquareSize( 'ido' );
		$value                              = isset( $atts['diag_show_coordinates'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['diag_show_coordinates'] ) : $showCoordinates;
		$widgetArgs['idoCoordinateVisible'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowCoordinates( 'ido' );
		$value                              = isset( $atts['diag_colorset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['diag_colorset'] ) : $colorset;
		$widgetArgs['idoColorset']          = isset( $value ) ? $value : $this->mainModel->getDefaultColorset( 'ido' );
		$value                              = isset( $atts['diag_pieceset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['diag_pieceset'] ) : $pieceset;
		$widgetArgs['idoPieceset']          = isset( $value ) ? $value : $this->mainModel->getDefaultPieceset( 'ido' );

		return $widgetArgs;
	}


	/**
	 * Apply some auto-format traitments to the text comments.
	 *
	 * These traitments used to be performed by the WP engine on the whole post/page content
	 * (see wp-includes/default-filters.php, filter `'the_content'`).
	 * However, the [pgn][/pgn] shortcode is processed in a low-level manner,
	 * therefore its content is preserved from these traitments, that must be applied
	 * to each text comment individually.
	 */
	protected function filterShortcodeContent( $content ) {
		return preg_replace_callback( '/{((?:\\\\\\\\|\\\\{|\\\\}|[^{}])*)}/', array( __CLASS__, 'processTextComment' ), trim( $content ) );
	}


	/**
	 * Callback called to process the matched comments between the [pgn][/pgn] tags.
	 */
	private static function processTextComment( $m ) {
		$comment = convert_chars( convert_smilies( wptexturize( $m[1] ) ) );
		return '{' . do_shortcode( $comment ) . '}';
	}

}
