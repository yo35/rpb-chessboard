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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/block/abstract.php';


/**
 * Model associated to the [pgn][/pgn] shortcode.
 *
 * @deprecated Only for legacy content (old pages/posts that may contain this shortcode).
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

		if ( $this->isLoadedFromExternalPGNFile() ) {
			$widgetArgs['url'] = $this->getExternalPGNFile();
		} else {
			$widgetArgs['pgn'] = $this->getContent();
		}

		// Game index
		$value = isset( $atts['game'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['game'] ) : null;
		if ( isset( $value ) ) {
			$widgetArgs['gameIndex'] = $value;
		}

		// Orientation
		$value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['flip'] ) : null;
		if ( isset( $value ) ) {
			$widgetArgs['flipped'] = $value;
		}

		// Square size
		$value                       = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validateInteger( $atts['square_size'] ) : null;
		$widgetArgs['nboSquareSize'] = isset( $value ) ? $value : $this->mainModel->getDefaultSquareSize();
		$widgetArgs['idoSquareSize'] = $widgetArgs['nboSquareSize'];

		// Show coordinates
		$value                              = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_coordinates'] ) : null;
		$widgetArgs['nboCoordinateVisible'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowCoordinates();
		$widgetArgs['idoCoordinateVisible'] = $widgetArgs['nboCoordinateVisible'];

		// Colorset
		$value                     = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['colorset'] ) : null;
		$widgetArgs['nboColorset'] = isset( $value ) ? $value : $this->mainModel->getDefaultColorset();
		$widgetArgs['idoColorset'] = $widgetArgs['nboColorset'];

		// Pieceset
		$value                     = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validateSetCode( $atts['pieceset'] ) : null;
		$widgetArgs['nboPieceset'] = isset( $value ) ? $value : $this->mainModel->getDefaultPieceset();
		$widgetArgs['idoPieceset'] = $widgetArgs['nboPieceset'];

		// Animated
		$value = isset( $atts['animated'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['animated'] ) : null;
		if ( ! isset( $value ) && isset( $atts['animation_speed'] ) ) { // FIXME Compatibility with the parameter `animationSpeed` (deprecated since 6.0).
			$animationSpeed = RPBChessboardHelperValidation::validateInteger( $atts['animation_speed'] );
			if ( isset( $animationSpeed ) ) {
				$value = $animationSpeed > 0;
			}
		}
		$widgetArgs['nboAnimated'] = isset( $value ) ? $value : $this->mainModel->getDefaultAnimated();

		// Move arrow
		$value                             = isset( $atts['show_move_arrow'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_move_arrow'] ) : null;
		$widgetArgs['nboMoveArrowVisible'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowMoveArrow();

		// Piece symbols
		$value                      = isset( $atts['piece_symbols'] ) ? RPBChessboardHelperValidation::validatePieceSymbols( $atts['piece_symbols'] ) : null;
		$widgetArgs['pieceSymbols'] = isset( $value ) ? $value : $this->mainModel->getDefaultPieceSymbols();

		// Navigation board
		$value                         = isset( $atts['navigation_board'] ) ? RPBChessboardHelperValidation::validateNavigationBoard( $atts['navigation_board'] ) : null;
		$widgetArgs['navigationBoard'] = isset( $value ) ? $value : $this->mainModel->getDefaultNavigationBoard();

		// Navigation toolbar
		$value                            = isset( $atts['show_flip_button'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_flip_button'] ) : null;
		$widgetArgs['withFlipButton']     = isset( $value ) ? $value : $this->mainModel->getDefaultShowFlipButton();
		$value                            = isset( $atts['show_download_button'] ) ? RPBChessboardHelperValidation::validateBoolean( $atts['show_download_button'] ) : null;
		$widgetArgs['withDownloadButton'] = isset( $value ) ? $value : $this->mainModel->getDefaultShowDownloadButton();

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
