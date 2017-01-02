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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/shortcode.php');


/**
 * Model associated to the [pgn][/pgn] shortcode.
 */
class RPBChessboardModelShortcodePGN extends RPBChessboardAbstractModelShortcode {

	private $widgetArgs;
	private $navigationFrameArgs;


	public function __construct($atts, $content) {
		parent::__construct($atts, $content);
		$this->loadDelegateModel('Common/DefaultOptions');
	}


	/**
	 * Return the arguments to pass to the uichess-chessboard widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {
		if(!isset($this->widgetArgs)) {
			$this->widgetArgs = array('pgn' => $this->getContent());
			$atts = $this->getAttributes();
			$chessboardOptions = array();

			// Orientation
			$value = isset($atts['flip']) ? RPBChessboardHelperValidation::validateBoolean($atts['flip']) : null;
			if(isset($value)) {
				$chessboardOptions['flip'] = $value;
			}

			// Square size
			$value = isset($atts['square_size']) ? RPBChessboardHelperValidation::validateSquareSize($atts['square_size']) : null;
			$chessboardOptions['squareSize'] = isset($value) ? $value : $this->getDefaultSquareSize();

			// Show coordinates
			$value = isset($atts['show_coordinates']) ? RPBChessboardHelperValidation::validateBoolean($atts['show_coordinates']) : null;
			$chessboardOptions['showCoordinates'] = isset($value) ? $value : $this->getDefaultShowCoordinates();

			// Colorset
			$value = isset($atts['colorset']) ? RPBChessboardHelperValidation::validateSetCode($atts['colorset']) : null;
			$chessboardOptions['colorset'] = isset($value) ? $value : $this->getDefaultColorset();

			// Pieceset
			$value = isset($atts['pieceset']) ? RPBChessboardHelperValidation::validateSetCode($atts['pieceset']) : null;
			$chessboardOptions['pieceset'] = isset($value) ? $value : $this->getDefaultPieceset();

			// Animation speed
			$value = isset($atts['animation_speed']) ? RPBChessboardHelperValidation::validateAnimationSpeed($atts['animation_speed']) : null;
			$chessboardOptions['animationSpeed'] = isset($value) ? $value : $this->getDefaultAnimationSpeed();

			// Move arrow
			$value = isset($atts['show_move_arrow']) ? RPBChessboardHelperValidation::validateBoolean($atts['show_move_arrow']) : null;
			$chessboardOptions['showMoveArrow'] = isset($value) ? $value : $this->getDefaultShowMoveArrow();

			// Piece symbols
			$value = isset($atts['piece_symbols']) ? RPBChessboardHelperValidation::validatePieceSymbols($atts['piece_symbols']) : null;
			$this->widgetArgs['pieceSymbols'] = isset($value) ? $value : $this->getDefaultPieceSymbols();

			// Navigation board
			$value = isset($atts['navigation_board']) ? RPBChessboardHelperValidation::validateNavigationBoard($atts['navigation_board']) : null;
			$this->widgetArgs['navigationBoard'] = isset($value) ? $value : $this->getDefaultNavigationBoard();

			// Use the same aspect parameters for the navigation board and the text comment diagrams.
			$this->widgetArgs['navigationBoardOptions'] = $chessboardOptions;
			$this->widgetArgs['diagramOptions'        ] = $chessboardOptions;
		}
		return $this->widgetArgs;
	}


	/**
	 * Return the arguments to pass to the navigation frame constructor.
	 *
	 * @return array
	 */
	public function getNavigationFrameArgs() {
		if(!isset($this->navigationFrameArgs)) {
			$this->navigationFrameArgs = array(
				'squareSize'      => $this->getDefaultSquareSize     (),
				'showCoordinates' => $this->getDefaultShowCoordinates(),
				'colorset'        => $this->getDefaultColorset       (),
				'pieceset'        => $this->getDefaultPieceset       (),
				'animationSpeed'  => $this->getDefaultAnimationSpeed (),
				'showMoveArrow'   => $this->getDefaultShowMoveArrow  ()
			);
		}
		return $this->navigationFrameArgs;
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
	protected function filterShortcodeContent($content) {
		return preg_replace_callback('/{((?:\\\\\\\\|\\\\{|\\\\}|[^{}])*)}/', array(__CLASS__, 'processTextComment'), trim($content));
	}


	/**
	 * Callback called to process the matched comments between the [pgn][/pgn] tags.
	 */
	private static function processTextComment($m) {
		$comment = convert_chars(convert_smilies(wptexturize($m[1])));
		return '{' . do_shortcode($comment) . '}';
	}
}
