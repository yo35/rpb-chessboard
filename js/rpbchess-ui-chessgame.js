/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * jQuery widget to display a chess game.
 *
 * @requires rpbchess-core.js
 * @requires rpbchess-pgn.js
 * @requires rpbchess-ui-chessboard.js
 * @requires Moment.js {@link http://momentjs.com/}
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery UI Button (optional, only if the navigation board feature is enabled)
 * @requires jQuery UI Icons (optional, only if the navigation board feature is enabled)
 * @requires jQuery UI Selectable (optional, only if the navigation board feature is enabled)
 * @requires jQuery Color (optional, only if the navigation board feature is enabled)
 * @requires jQuery UI Dialog (optional, only if the framed navigation board feature is enabled)
 * @requires jQuery UI Resizable (optional, only if the framed navigation board feature is enabled)
 */
(function(RPBChess, $, moment)
{
	'use strict';


	/**
	 * Public static properties.
	 */
	$.chessgame =
	{
		/**
		 * Default options for the chessboard in the navigation frame.
		 * @type {object}
		 */
		navigationFrameOptions: {},


		/**
		 * Dialog class for the navigation frame.
		 * @type {string}
		 */
		navigationFrameClass: '',


		/**
		 * Button class for the navigation buttons.
		 * @type {string}
		 */
		navigationButtonClass: '',


		/**
		 * Default chess font to use for figurines.
		 * @type {string}
		 */
		chessFont: 'alpha',


		/**
		 * Localization constants.
		 */
		i18n:
		{
			/**
			 * Annotator field template.
			 * @type {string}
			 */
			ANNOTATED_BY: 'Annotated by %1$s',

			/**
			 * Initial position label.
			 * @type {string}
			 */
			INITIAL_POSITION: 'Initial position',

			/**
			 * Chess piece symbols.
			 * @type {{K:string, Q:string, R:string, B:string, N:string, P:string}}
			 */
			PIECE_SYMBOLS: { 'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P' },

			/**
			 * Tooltip for the "go-first-move" button.
			 * @type {string}
			 */
			GO_FIRST_MOVE_TOOLTIP: 'Go to the beginning of the game',

			/**
			 * Tooltip for the "go-previous-move" button.
			 * @type {string}
			 */
			GO_PREVIOUS_MOVE_TOOLTIP: 'Go to the previous move',

			/**
			 * Tooltip for the "go-next-move" button.
			 * @type {string}
			 */
			GO_NEXT_MOVE_TOOLTIP: 'Go to the next move',

			/**
			 * Tooltip for the "go-last-move" button.
			 * @type {string}
			 */
			GO_LAST_MOVE_TOOLTIP: 'Go to the end of the game',

			/**
			 * Tooltip for the "flip" button.
			 * @type {string}
			 */
			FLIP_TOOLTIP: 'Flip the board',

			/**
			 * Tooltip for the "download PGN" button.
			 * @type {string}
			 */
			DOWNLOAD_PGN_TOOLTIP: 'Download the game',

			/**
			 * Error message in case of download failure.
			 * @type {string}
			 */
			PGN_DOWNLOAD_ERROR_MESSAGE: 'Cannot download the PGN file.',

			/**
			 * Error message in case of download failure.
			 * @type {string}
			 */
			PGN_PARSING_ERROR_MESSAGE: 'Error while analysing a PGN string.'
		}

	}; /* $.chessgame = { ... } */


	/**
	 * Singleton used for dynamic-URL generation.
	 */
	var dynamicURL = null;


	/**
	 * Convert a PGN standard field into a human-readable site string.
	 * Return null if the special code "?" is detected.
	 *
	 * The fields processed with this filter include "Site", "Event", "Round",
	 * "White", "Black", "WhiteElo", "BlackElo".
	 *
	 * @param {string} value Value of a PGN standard field.
	 * @returns {string}
	 */
	function formatDefault(value)
	{
		return (value===null || value==='?') ? null : value;
	}


	/**
	 * Convert a PGN date field value into a human-readable date string.
	 * Return null if the special code "????.??.??" is detected.
	 * Otherwise, if the input is badly-formatted, it is returned "as-is".
	 *
	 * @param {string} date Value of a PGN date field.
	 * @returns {string}
	 */
	function formatDate(date)
	{
		// Null input or case "????.??.??" -> no date is defined.
		if(date===null || date==='????.??.??') {
			return null;
		}

		// Case "2013.05.20" -> return "May 20, 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.([0-9]{2})/)) {
			var dateObj = moment(RegExp.$1 + '-' + RegExp.$2 + '-' + RegExp.$3);
			return dateObj.isValid() ? capitalize(dateObj.format('LL')) : date;
		}

		// Case "2013.05.??" -> return "May 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.\?\?/)) {
			var dateObj = moment(RegExp.$1 + '-' + RegExp.$2 + '-01');
			return dateObj.isValid() ? capitalize(dateObj.format('MMMM YYYY')) : date;
		}

		// Case "2013.??.??" -> return "2013"
		else if(date.match(/([0-9]{4})\.\?\?\.\?\?/)) {
			return RegExp.$1;
		}

		// Badly-formatted input -> return it "as-is"
		else {
			return date;
		}
	}


	/**
	 * Convert a PGN result field value into a human-readable string.
	 * Return null if the special code "*" is detected.
	 *
	 * @param {string} result PGN-like game result code.
	 * @returns {string}
	 */
	function formatResult(result)
	{
		switch(result) {
			case '*'      : return null;
			case '1/2-1/2': return '&#189;&#8211;&#189;';
			case '1-0'    : return '1&#8211;0';
			case '0-1'    : return '0&#8211;1';
			default: return result;
		}
	}


	/**
	 * Convert a PGN title field value into a human-readable title string.
	 * Return null if the special code "-" is detected.
	 *
	 * @param {string} title Value of a PGN title field.
	 * @returns {string}
	 */
	function formatTitle(title)
	{
		return (title===null || title==='-') ? null : title;
	}


	/**
	 * The human-readable symbols corresponding to most common NAGs.
	 *
	 * @constant
	 */
	var SPECIAL_NAGS_LOOKUP = {
		 3: '!!',      // very good move
		 1: '!',       // good move
		 5: '!?',      // interesting move
		 6: '?!',      // questionable move
		 2: '?',       // bad move
		 4: '??',      // very bad move
		18: '+\u2212', // White has a decisive advantage
		16: '\u00b1',  // White has a moderate advantage
		14: '\u2a72',  // White has a slight advantage
		10: '=',       // equal position
		11: '=',       // equal chances, quiet position
		13: '\u221e',  // unclear position
		15: '\u2a71',  // Black has a slight advantage
		17: '\u2213',  // Black has a moderate advantage
		19: '\u2212+'  // Black has a decisive advantage
	};


	/**
	 * Return the annotation symbol (e.g. "+-", "!?") associated to a numeric NAG code.
	 *
	 * @param {number} nag Numeric NAG code.
	 * @returns {string} Human-readable NAG symbol.
	 */
	function formatNag(nag)
	{
		if(nag===null) { return null; }
		else if(nag in SPECIAL_NAGS_LOOKUP) { return SPECIAL_NAGS_LOOKUP[nag]; }
		else { return '$' + nag; }
	}


	/**
	 * Capitalization function.
	 *
	 * Example: `'hello world'` is turned into `'Hello world'`.
	 *
	 * @param {string} text Text to capitalize.
	 * @returns {string}
	 */
	function capitalize(text)
	{
		return text.length===0 ? '' : text.charAt(0).toUpperCase() + text.slice(1);
	}


	/**
	 * Ellipsis function.
	 *
	 * Example: if `text` is `0123456789`, then `ellipsis(text, 5, 2, 1)` returns
	 * the following string:
	 *
	 * ```
	 * ...4567...
	 *     ^
	 * ```
	 *
	 * @param {string} text Text from a substring must be extracted.
	 * @param {number} pos Index of the character in `text` around which the substring must be extracted.
	 * @param {number} backwardCharacters Number of characters to keep before `pos`.
	 * @param {number} forwardCharacters Number of characters to keep after `pos`.
	 * @returns {string}
	 */
	function ellipsisAt(text, pos, backwardCharacters, forwardCharacters)
	{
		// p1 => begin of the extracted sub-string
		var p1 = pos - backwardCharacters;
		var e1 = '...';
		if(p1<=0) {
			p1 = 0;
			e1 = '';
		}

		// p2 => one character after the end of the extracted sub-string
		var p2 = pos + 1 + forwardCharacters;
		var e2 = '...';
		if(p2 >= text.length) {
			p2 = text.length;
			e2 = '';
		}

		// Extract the sub-string around the requested position.
		var retVal = e1 + text.substr(p1, p2-p1) + e2;
		retVal = retVal.replace(/\n|\t/g, ' ');
		return retVal + '\n' + new Array(1 + e1.length + pos - p1).join(' ') + '^';
	}


	/**
	 * Return a contrasted color.
	 *
	 * @param {string} colorString Color specification as mentioned in a CSS property.
	 * @returns {string}
	 */
	function contrastedColor(colorString)
	{
		var color = $.Color(colorString); // Require the jQuery Color plugin.
		return color.lightness() > 0.5 ? 'black' : 'white';
	}


	/**
	 * Ensure that the given value is a valid boolean.
	 *
	 * @param {mixed} value
	 * @param {boolean} defaultValue
	 * @returns {boolean}
	 */
	function filterBoolean(value, defaultValue) {
		if(typeof value === 'boolean') {
			return value;
		}
		else if(typeof value === 'number') {
			return Boolean(value);
		}
		else if(typeof value === 'string') {
			value = value.toLowerCase();
			if(value === 'true' || value === '1' || value === 'on') {
				return true;
			}
			else if(value === 'false' || value === '0' || value === 'off') {
				return false;
			}
			else {
				return defaultValue;
			}
		}
		else {
			return defaultValue;
		}
	}


	/**
	 * Ensure that the given argument is a valid value for the navigation board property.
	 *
	 * @param {string} value
	 * @returns {string}
	 */
	function filterNavigationBoard(value)
	{
		switch(value) {
			case 'frame':
			case 'floatLeft':
			case 'floatRight':
			case 'scrollLeft':
			case 'scrollRight':
			case 'above':
			case 'below':
				return value;
			default:
				return 'none';
		}
	}


	/**
	 * Ensure that the given argument is a valid chess font name.
	 *
	 * @param {string} font
	 * @returns {{font:string, pieceSymbolTable:object}}
	 */
	function filterChessFontName(font)
	{
		// Ensure that the input is a valid chess font name.
		switch (font) {
			case 'merida':
			case 'pirat':
				break;
			default:
				font = 'alpha';
				break;
		}

		// Build the corresponding chess font set.
		var span;
		var pieceSymbolTable = {};
		var currentPiece;
		var pieceTypes = [ 'K', 'Q', 'R', 'B', 'N', 'P' ];
		for ( var index = 0; index < pieceTypes.length; index++ ) {
			currentPiece = pieceTypes[index];
			span = document.createElement( 'span' );
			span.setAttribute( 'class', 'rpbui-chessgame-' + font + 'Font' );
			span.textContent = currentPiece;
			pieceSymbolTable[currentPiece] = span.outerHTML;
		}

		// Return the result.
		return { font: font, pieceSymbolTable: pieceSymbolTable };
	}


	/**
	 * Filter the options passed to the chessboard widgets.
	 *
	 * @param {object} value
	 * @returns {object}
	 */
	function filterChessboardOptions(value)
	{
		var result = {};
		if(typeof value.flip            !== 'undefined') { result.flip            = value.flip           ; }
		if(typeof value.squareSize      !== 'undefined') { result.squareSize      = value.squareSize     ; }
		if(typeof value.showCoordinates !== 'undefined') { result.showCoordinates = value.showCoordinates; }
		if(typeof value.colorset        !== 'undefined') { result.colorset        = value.colorset       ; }
		if(typeof value.pieceset        !== 'undefined') { result.pieceset        = value.pieceset       ; }
		if(typeof value.animationSpeed  !== 'undefined') { result.animationSpeed  = value.animationSpeed ; }
		if(typeof value.showMoveArrow   !== 'undefined') { result.showMoveArrow   = value.showMoveArrow  ; }
		return result;
	}


	/**
	 * Initialize the internal URL attribute, and initiate the asynchrone PGN retrieval if necessary.
	 */
	function initializeURL(widget, url) {

		// Nothing to do if no URL is defined.
		if(typeof url !== 'string' || url === '') {
			return '';
		}

		widget.options.pgn = '';
		widget._game = null;

		$.get(url).done(function(data) {
			widget.options.pgn = initializePGN(widget, data);
			refresh(widget);
		}).fail(function() {
			widget._game = { title: $.chessgame.i18n.PGN_DOWNLOAD_ERROR_MESSAGE, message: url };
			refresh(widget);
		});

		return url;
	}


	/**
	 * Initialize the internal `RPBChess.pgn.Item` object that contains the parsed PGN data.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {string} pgn
	 * @returns {string}
	 */
	function initializePGN(widget, pgn) {

		// Ensure that the input is actually a string.
		if(typeof pgn !== 'string') {
			pgn = '*';
		}

		// Trim the input.
		pgn = pgn.replace(/^\s+|\s+$/g, '');

		// Parse the input assuming a PGN format.
		try {
			widget._game = RPBChess.pgn.parseOne(pgn, widget.options.gameIndex);
		}
		catch(error) {
			if(error instanceof RPBChess.exceptions.InvalidPGN) { // Parsing errors are reported to the user.
				widget._game = error;
			}
			else { // Unknown exceptions are re-thrown.
				widget._game = null;
				throw error;
			}
		}

		// Return the validated PGN string.
		return pgn;
	}


	/**
	 * Initialize the internal object that describes how to represent the chess pieces
	 * in SAN notation.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {string} pieceSymbols
	 * @returns {string}
	 */
	function initializePieceSymbols(widget, pieceSymbols) {

		var FIELDS = ['K', 'Q', 'R', 'B', 'N', 'P'];

		// Descriptors: 6 custom letters.
		if(/^\([a-zA-Z]{6}\)$/.test(pieceSymbols)) {
			pieceSymbols = pieceSymbols.toUpperCase();
			widget._pieceSymbolTable = {};
			for(var k=0; k<6; ++k) {
				widget._pieceSymbolTable[FIELDS[k]] = pieceSymbols.substr(k+1, 1);
			}
		}

		// Descriptors: figurines, using a custom chess font.
		else if(/^:\w+$/.test(pieceSymbols)) {
			var info = filterChessFontName(pieceSymbols.substr(1));
			pieceSymbols = ':' + info.font;
			widget._pieceSymbolTable = info.pieceSymbolTable;
		}

		// Special values: native (English initials, localized initials, or figurines using the default chess font).
		else {
			switch(pieceSymbols) {

				// Figurines using the default chess font.
				case 'figurines':
					widget._pieceSymbolTable = filterChessFontName($.chessgame.chessFont).pieceSymbolTable;
					break;

				// Localized initials.
				case 'localized':
					widget._pieceSymbolTable = {};
					for(var k=0; k<6; ++k) {
						var field = FIELDS[k];
						widget._pieceSymbolTable[field] = (field in $.chessgame.i18n.PIECE_SYMBOLS) ? $.chessgame.i18n.PIECE_SYMBOLS[field] : field;
					}
					break;

				// English initials (also the fallback case).
				default:
					widget._pieceSymbolTable = { 'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P' };
					pieceSymbols = 'native';
					break;
			}
		}

		// Return the validated input.
		return pieceSymbols;
	}



	// ---------------------------------------------------------------------------
	// Widget rendering
	// ---------------------------------------------------------------------------

	/**
	 * Destroy the widget content, prior to a refresh or a widget destruction.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 */
	function destroyContent(widget) {
		var navigationFrameTarget = $('#rpbui-chessgame-navigationFrameTarget', widget.element);
		if(navigationFrameTarget.length !== 0) {
			$('#rpbui-chessgame-navigationFrame').dialog('close');
		}
		widget.element.empty();
	}


	/**
	 * Refresh the widget.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 */
	function refresh(widget) {
		destroyContent(widget);
		if(widget._game === null) {
			return;
		}

		// Handle parsing error problems.
		if(!(widget._game instanceof RPBChess.pgn.Item)) {
			$(buildErrorMessage(widget)).appendTo(widget.element);
			return;
		}

		// Headers
		var headers = '';
		headers += playerNameHeader(widget, 'White');
		headers += playerNameHeader(widget, 'Black');
		headers += eventHeader(widget);
		headers += datePlaceHeader(widget);
		headers += annotatorHeader(widget);
		if(headers !== '') {
			headers = '<div class="rpbui-chessgame-headers">' + headers + '</div>';
		}

		// Body and initial move
		var move0 = buildInitialMove(widget);
		var body  = buildBody(widget);

		// A hidden field to catch the keyboard events
		var focusField = '<div class="rpbui-chessgame-focusFieldContainer"><input class="rpbui-chessgame-focusField" type="text" readonly="true" /></div>';

		// Navigation board
		var prefix = '';
		var suffix = '';
		switch(widget.options.navigationBoard) {
			case 'floatLeft':
			case 'floatRight':
				suffix = '<div class="rpbui-chessgame-' + widget.options.navigationBoard.replace('float', 'clear') + '"></div>';
				prefix = '<div class="rpbui-chessgame-navigationBox rpbui-chessgame-' + widget.options.navigationBoard + '">' +
					buildNavigationSkeleton() + '</div>';
				break;
			case 'scrollLeft':
				prefix = '<div class="rpbui-chessgame-scrollBox"><div class="rpbui-chessgame-navigationBox rpbui-chessgame-scrollLeft">' +
					buildNavigationSkeleton() + '</div><div class="rpbui-chessgame-scrollArea">';
				suffix = '</div></div>';
				break;
			case 'scrollRight':
				prefix = '<div class="rpbui-chessgame-scrollBox"><div class="rpbui-chessgame-scrollArea">';
				suffix = '</div><div class="rpbui-chessgame-navigationBox rpbui-chessgame-scrollRight">' + buildNavigationSkeleton() + '</div></div>';
				break;
			case 'above':
				prefix = '<div class="rpbui-chessgame-navigationBox rpbui-chessgame-above">' + buildNavigationSkeleton() + '</div>';
				break;
			case 'below':
				suffix = '<div class="rpbui-chessgame-navigationBox rpbui-chessgame-below">' + buildNavigationSkeleton() + '</div>';
				break;
		}

		// Render the content.
		$(move0 + focusField + prefix + headers + body + suffix).appendTo(widget.element);

		// Render the diagrams in comments.
		makeDiagrams(widget);

		// Activate the navigation board, if required.
		if(widget.options.navigationBoard !== 'none') {
			makeMovesClickable(widget);
			makeMovesRelated(widget);
			$('.rpbui-chessgame-focusField', widget.element).keydown(function(event) {
				if(event.key === 'Home') { widget.goFirstMove(); }
				else if(event.key === 'ArrowLeft') { widget.goPreviousMove(); }
				else if(event.key === 'ArrowRight') { widget.goNextMove(); }
				else if(event.key === 'End') { widget.goLastMove(); }
			});
			if(widget.options.navigationBoard !== 'frame') {
				makeNavigationBoxWidgets(widget);
			}
			if(widget.options.navigationBoard === 'scrollLeft' || widget.options.navigationBoard === 'scrollRight') {
				$('.rpbui-chessgame-scrollArea', widget.element).css('height', $('.rpbui-chessgame-navigationBox', widget.element).height());
			}
		}
	}


	/**
	 * Render the diagrams inserted in text comments.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 */
	function makeDiagrams(widget) {
		$('.rpbui-chessgame-comment .rpbui-chessgame-diagramAnchor', widget.element).each(function(index, element) {
			var anchor = $(element);

			// Retrieve the position
			var commentNode = anchor.closest('.rpbui-chessgame-comment');
			var position = commentNode.data('position');
			var csl = commentNode.data('csl');
			var cal = commentNode.data('cal');

			// Build the option set to pass to the chessboard widget constructor.
			var options = { position: position };
			if(typeof csl !== 'undefined') { options.squareMarkers = csl; }
			if(typeof cal !== 'undefined') { options.arrowMarkers = cal; }
			$.extend(options, widget.options.diagramOptions);
			try {
				$.extend(options, filterChessboardOptions($.parseJSON(anchor.text())));
			}
			catch(error) {} // The content of the node is ignored if it is not a valid JSON-encoded object.

			// Render the diagram.
			anchor.empty().removeClass('rpbui-chessgame-diagramAnchor').addClass('rpbui-chessgame-diagram').chessboard(options);
		});
	}


	/**
	 * Make the moves clickable: when clicked, the navigation board is updated to show
	 * the position after the corresponding move.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 */
	function makeMovesClickable(widget) {
		$('.rpbui-chessgame-move', widget.element).click(function() {
			updateNavigationBoard(widget, $(this), true);
			widget.focus();
		});
	}


	/**
	 * For each move, add pointer to its predecessor and its successor in the variation.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 */
	function makeMovesRelated(widget) {

		// For each variation...
		$('.rpbui-chessgame-variation', widget.element).each(function(index, element) {

			// Retrieve the moves of the variation.
			var variation = $(element);
			var moves     = variation.is('div') ?
				variation.children('.rpbui-chessgame-moveGroup').children('.rpbui-chessgame-move') :
				variation.children('.rpbui-chessgame-move');

			// Link each move to its successor and its predecessor.
			var previousMove = null;
			moves.each(function(index, element) {
				var move = $(element);
				if(previousMove !== null) {
					move.data('prevMove', previousMove);
					previousMove.data('nextMove', move);
				}
				previousMove = move;
			});
		});

		// The initial move must be linked specifically since it does not belong to any variation.
		var initialMove = $('.rpbui-chessgame-initialMove', widget.element);
		var allMoves    = $('.rpbui-chessgame-variation .rpbui-chessgame-move', widget.element);
		if(allMoves.length !== 0) {
			var secondMove = allMoves.first();
			secondMove.data('prevMove', initialMove);
			initialMove.data('nextMove', secondMove);
		}
	}


	/**
	 * Initialize the navigation box widgets.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 */
	function makeNavigationBoxWidgets(widget) {

		// Set-up the navigation board.
		$('.rpbui-chessgame-navigationBoard', widget.element).chessboard(widget.options.navigationBoardOptions);

		// Navigation buttons
		initializeNavigationButtons(function(buttonClass) { return $(buttonClass, widget.element); }, function(methodName) {
			widget[methodName]();
			widget.focus();
		});

		// Show the initial position on the navigation board.
		updateNavigationBoard(widget, $('.rpbui-chessgame-initialMove', widget.element), false);
	}


	/**
	 * Build the error message resulting from a PGN parsing error.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {string}
	 */
	function buildErrorMessage(widget) {

		// Container error <div>
		var errorDiv = document.createElement( 'div' );
		errorDiv.setAttribute( 'class', 'rpbui-chessgame-error' );
		errorDiv.appendChild( errorTitleDiv );

		// Title
		var title = widget._game instanceof RPBChess.exceptions.InvalidPGN ? $.chessgame.i18n.PGN_PARSING_ERROR_MESSAGE : widget._game.title;
		var errorTitleDiv = document.createElement( 'div' );
		errorTitleDiv.setAttribute( 'class', 'rpbui-chessgame-errorTitle' );
		errorTitleDiv.textContent = title;

		// Optional message.
		if ( null !== widget._game.message ) {
			var errorMessageDiv = document.createElement( 'div' );
			errorMessageDiv.setAttribute( 'class', 'rpbui-chessgame-errorMessage' );
			errorMessageDiv.textContent = widget._game.message;
			errorDiv.appendChild( errorMessageDiv );
		}

		// Error location (and optional error code)
		if ( null !== widget._game.index && widget._game.index >= 0 ) {
			var errorAtDiv = document.createElement( 'div' );
			errorAtDiv.setAttribute( 'class', 'rpbui-chessgame-errorAt' );


			if ( widget._game.index >= widget._game.pgn.length ) {
				errorAtDiv.textContent = 'Occurred at the end of the string.';
			} else {
				errorAtDiv.textContent = 'Occurred at position ' + widget._game.index + ':';

				var errorCodeDiv = document.createElement( 'div' );
				errorCodeDiv.setAttribute( 'class', 'rpbui-chessgame-errorAtCode' );
				errorCodeDiv.textContent = ellipsisAt(widget._game.pgn, widget._game.index, 10, 40);
				errorAtDiv.appendChild( errorCodeDiv );
			}

			errorDiv.appendChild( errorAtDiv );
		}

		return errorDiv.outerHTML;
	}


	/**
	 * Build the header containing the player-related information (name, rating, title)
	 * corresponding to the requested color.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {string} color Either 'White' or 'Black'.
	 * @returns {string}
	 */
	function playerNameHeader( widget, color ) {

		// Retrieve the name of the player -> no header is returned if the name not available.
		var name = formatDefault( widget._game.header( color ) );
		if ( null === name ) {
			return '';
		}

		// Container header <div>
		var header = document.createElement( 'div' );
		header.setAttribute( 'class', 'rpbui-chessgame-' + color.toLowerCase() + 'Player' );

		// Color and player name
		var colorTag = document.createElement( 'span' );
		colorTag.setAttribute( 'class', 'rpbui-chessgame-colorTag' );

		var playerName = document.createElement( 'span' );
		playerName.setAttribute( 'class', 'rpbui-chessgame-playerName' );
		playerName.textCOntent = name;

		header.appendChild( colorTag );
		header.appendChild( playerName );

		// Title and rating
		var title  = formatTitle  (widget._game.header(color + 'Title'));
		var rating = formatDefault(widget._game.header(color + 'Elo'  ));

		if ( null !== title || null !== rating) {
			var titleRatingGroup = document.createElement( 'span' );
			titleRatingGroup.setAttribute( 'class', 'rpbui-chessgame-titleRatingGroup' );

			if ( null !== title ) {
				var playerTitle = document.createElement( 'span' );
				playerTitle.setAttribute( 'class', 'rpbui-chessgame-playerTitle' );
				playerTitle.textContent = title;

				titleRatingGroup.appendChild( playerTitle );
			}
			if ( null !== rating ) {
				var playerRating = document.createElement( 'span' );
				playerRating.setAttribute( 'class', 'rpbui-chessgame-playerRating' );
				playerRating.textContent = rating;
				titleRatingGroup.appendChild( playerRating );
			}

			header.appendChild( titleRatingGroup );
		}

		return header.outerHTML;
	}


	/**
	 * Build the header containing the event-related information (event + round).
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {string}
	 */
	function eventHeader(widget) {

		// Retrieve the event -> no header is returned if the name not available.
		var event = formatDefault(widget._game.header('Event'));
		if(event===null) {
			return '';
		}

		// Retrieve the round.
		var round = formatDefault(widget._game.header('Round'));

		// Build and return the header.
		var header = '<div class="rpbui-chessgame-event">' + event;
		if(round !== null) {
			header += '<span class="rpbui-chessgame-round">' + round + '</span>';
		}
		header += '</div>';
		return header;
	}


	/**
	 * Build the header containing the date/place information.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {string}
	 */
	function datePlaceHeader( widget ) {

		// Retrieve the date and the site field.
		var date = formatDate( widget._game.header( 'Date' ) );
		var site = formatDefault( widget._game.header( 'Site' ) );
		if ( null === date && null === site ) {
			return '';
		}

		// Build and return the header
		var header = document.createElement( 'div' );
		header.setAttribute( 'class', 'rpbui-chessgame-datePlaceGroup' );

		if ( null !== date ) {
			var dateSpan = document.createElement( 'span' );
			dateSpan.setAttribute( 'class', 'rpbui-chessgame-date' );
			dateSpan.textContent = date;
			header.appendChild( dateSpan );
		}

		if ( null !== site ) {
			var siteSpan = document.createElement( 'span' );
			siteSpan.setAttribute( 'class', 'rpbui-chessgame-site' );
			siteSpan.textContent = site;
			header.appendChild( siteSpan );
		}

		return header.outerHTML;
	}


	/**
	 * Build the header containing the annotator information.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {string}
	 */
	function annotatorHeader(widget) {

		// Retrieve the annotator field.
		var annotator = formatDefault(widget._game.header('Annotator'));
		if(annotator===null) {
			return '';
		}

		// Build and return the header.
		var header = '<div class="rpbui-chessgame-annotator">' + $.chessgame.i18n.ANNOTATED_BY.replace(/%1\$s/g,
			'<span class="rpbui-chessgame-annotatorName">' + annotator + '</span>') + '</div>';
		return header;
	}


	/**
	 * Build the move tree.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {string}
	 */
	function buildBody(widget) {
		var mainVariation = buildVariation(widget, widget._game.mainVariation(), true, formatResult(widget._game.result()));

		// Nothing to do if the main variation is empty.
		if(mainVariation.content === '') {
			return '';
		}

		// Otherwise, wrap it into a DIV node.
		var bodyClass = 'rpbui-chessgame-body';
		if(mainVariation.divCount > 1) { bodyClass += ' rpbui-chessgame-moreSpace'; }
		if(widget.options.navigationBoard !== 'none') { bodyClass += ' rpbui-chessgame-clickableMoves'; }
		return '<div class="' + bodyClass + '">' + mainVariation.content + '</div>';
	}


	/**
	 * Build the move tree corresponding to the given variation.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {RPBChess.pgn.Variation} variation
	 * @param {boolean} isMainVariation
	 * @param {null|string} result Must be set to null for sub-variations.
	 * @returns {string|{content:string, divCount:number}} The second form is only used for the main variation.
	 */
	function buildVariation(widget, variation, isMainVariation, result) {

		// Nothing to do if the variation is empty.
		if(variation.comment() === null && variation.first() === null && result === null) {
			return isMainVariation ? { content: '', divCount: 0 } : '';
		}

		// Open a new DOM node for the variation.
		var tag = variation.isLongVariation() ? 'div' : 'span';
		var retVal = '<' + tag + ' class="rpbui-chessgame-variation' + (isMainVariation ? '' : ' rpbui-chessgame-subVariation') + '">';

		// The flag `moveGroupOpened` indicates whether a `<div class="moveGroup">` node
		// is currently opened or not. Move group nodes are supposed to contain moves,
		// short-comments and short-variations when the parent variation is long.
		// In short variations, there is no move groups.
		var enableMoveGroups = variation.isLongVariation();
		var moveGroupOpened  = false;
		var divCount         = 0;

		// Open a new move group if necessary.
		function openMoveGroup() {
			if(enableMoveGroups && !moveGroupOpened) {
				retVal += '<div class="rpbui-chessgame-moveGroup">';
				moveGroupOpened = true;
				++divCount;
			}
		}

		// Close the current move group, if any.
		function closeMoveGroup() {
			if(moveGroupOpened) {
				retVal += '</div>';
				moveGroupOpened = false;
			}
		}

		// Write the initial comment, if any.
		if(variation.comment() !== null) {
			if(variation.isLongComment()) {
				++divCount;
			} else {
				openMoveGroup();
			}
			retVal += buildComment(variation);
		}

		// Visit all the PGN nodes (one node per move) within the variation.
		var forcePrintMoveNumber = true;
		var node = variation.first();
		while(node !== null)
		{
			// Write the move, including directly related information (i.e. move number + NAGs).
			openMoveGroup();
			retVal += buildMove(widget, node, forcePrintMoveNumber);

			// Write the comment (if any).
			if(node.comment() !== null) {
				if(node.isLongComment()) {
					closeMoveGroup();
					++divCount;
				}
				retVal += buildComment(node);
			}

			// Write the sub-variations.
			var nonEmptySubVariations = 0;
			var subVariations = node.variations();
			for(var k=0; k<subVariations.length; ++k) {
				var subVariationText = buildVariation(widget, subVariations[k], false, null);
				if(subVariationText !== '') {
					if(subVariations[k].isLongVariation()) {
						closeMoveGroup();
						++divCount;
					} else {
						openMoveGroup();
					}
					retVal += subVariationText;
					++nonEmptySubVariations;
				}
			}

			// Back to the current variation, go to the next move.
			forcePrintMoveNumber = (node.comment() !== null || nonEmptySubVariations > 0);
			node = node.next();
		}

		// Append the result and the end of the main variation.
		if(isMainVariation && result !== null) {
			openMoveGroup();
			retVal += '<span class="rpbui-chessgame-result">' + result + '</span>';
		}

		// Close the opened DOM nodes, and returned the result.
		closeMoveGroup();
		retVal += '</' + tag + '>';
		return isMainVariation ? { content: retVal, divCount: divCount } : retVal;
	}


	/**
	 * Build the DOM attributes to add to a DOM node to be able to reload the position associated to the current node.
	 *
	 * @param {RPBChess.pgn.Node|RPBChess.pgn.Variation} node
	 * @param {boolean} addAnimationSupport `true` to add the information required for move highlighting.
	 * @returns {string}
	 */
	function buildPositionInformation(node, moveHighlightSupport) {
		var res = 'data-position="' + node.position().fen() + '"';

		// Move highlighting
		if(moveHighlightSupport) {
			res += ' data-position-before="' + node.positionBefore().fen() + '" data-move-notation="' + node.move() + '"';
		}

		// Square markers
		var csl = node.tag('csl');
		if(csl !== null) {
			res += ' data-csl="' + csl + '"';
		}

		// Arrow markers
		var cal = node.tag('cal');
		if(cal !== null) {
			res += ' data-cal="' + cal + '"';
		}

		return res;
	}


	/**
	 * Build the DOM node corresponding to the given text comment.
	 *
	 * @param {RPBChess.pgn.Node|RPBChess.pgn.Variation} node
	 * @returns {string}
	 */
	function buildComment(node) {
		var tag = node.isLongComment() ? 'div' : 'span';
		return '<' + tag + ' class="rpbui-chessgame-comment" ' + buildPositionInformation(node, false) + '>' +
			node.comment() + '</' + tag + '>';
	}


	/**
	 * Build the DOM node corresponding to the given move (move number, SAN notation, NAGs).
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {RPBChess.pgn.Node} node
	 * @param {boolean} forcePrintMoveNumber
	 * @returns {string}
	 */
	function buildMove(widget, node, forcePrintMoveNumber) {

		// Create the DOM node.
		var retVal = '<span class="rpbui-chessgame-move" ' + buildPositionInformation(node, true) + '>';

		// Move number
		var printMoveNumber = forcePrintMoveNumber || node.moveColor() === 'w';
		var moveNumberClass = 'rpbui-chessgame-moveNumber' + (printMoveNumber ? '' : ' rpbui-chessgame-hidden');
		var moveNumberText  = node.fullMoveNumber() + (node.moveColor() === 'w' ? '.' : '\u2026');
		retVal += '<span class="' + moveNumberClass + '">' + moveNumberText + '</span>';

		// SAN notation.
		var pieceSymbolTable = widget._pieceSymbolTable;
		retVal += node.move().replace(/[KQRBNP]/g, function(match) {
			return pieceSymbolTable[match];
		});

		// NAGs
		var nags = node.nags();
		for(var k=0; k<nags.length; ++k) {
			retVal += ' ' + formatNag(nags[k]);
		}

		// Close the DOM node.
		retVal += '</span>';
		return retVal;
	}


	/**
	 * Build the DOM node corresponding to the "initial-position-move",
	 * which is always hidden, but must be added to make possible to display
	 * the initial position in the navigation board.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {string}
	 */
	function buildInitialMove(widget) {
		return '<div class="rpbui-chessgame-move rpbui-chessgame-initialMove" ' + buildPositionInformation(widget._game.mainVariation(), false) +
			'>' + $.chessgame.i18n.INITIAL_POSITION + '</div>';
	}


	/**
	 * Build the DOM nodes that will be used as a skeleton for the navigation board and buttons.
	 *
	 * @returns {string}
	 */
	function buildNavigationSkeleton() {
		return '<div class="rpbui-chessgame-navigationBoard"></div>' +
			'<div class="rpbui-chessgame-navigationButtons ' + $.chessgame.navigationButtonClass + '">' +
				'<div title="' + $.chessgame.i18n.GO_FIRST_MOVE_TOOLTIP    + '" class="rpbui-chessgame-navigationButtonFirst"></div>' +
				'<div title="' + $.chessgame.i18n.GO_PREVIOUS_MOVE_TOOLTIP + '" class="rpbui-chessgame-navigationButtonPrevious"></div>' +
				'<div title="' + $.chessgame.i18n.GO_NEXT_MOVE_TOOLTIP     + '" class="rpbui-chessgame-navigationButtonNext"></div>' +
				'<div title="' + $.chessgame.i18n.GO_LAST_MOVE_TOOLTIP     + '" class="rpbui-chessgame-navigationButtonLast rpbui-chessgame-spaceAfter"></div>' +
				'<div title="' + $.chessgame.i18n.FLIP_TOOLTIP             + '" class="rpbui-chessgame-navigationButtonFlip rpbui-chessgame-spaceAfter"></div>' +
				'<div title="' + $.chessgame.i18n.DOWNLOAD_PGN_TOOLTIP     + '" class="rpbui-chessgame-navigationButtonDownload rpbui-chessgame-spaceAfter"></div>' +
			'</div>' +
			'<a href="#" download="game.pgn" class="rpbui-chessgame-blobDownloadLink"></a>';
	}


	/**
	 * jQuerize the navigation buttons and bind their callbacks.
	 */
	function initializeNavigationButtons(selector, callback) {
		selector('.rpbui-chessgame-navigationButtons'       ).disableSelection();
		selector('.rpbui-chessgame-navigationButtonFirst'   ).button({ icons:{ primary:'ui-icon-seek-first' }, text:false }).click(function() { callback('goFirstMove'   ); });
		selector('.rpbui-chessgame-navigationButtonPrevious').button({ icons:{ primary:'ui-icon-seek-prev'  }, text:false }).click(function() { callback('goPreviousMove'); });
		selector('.rpbui-chessgame-navigationButtonNext'    ).button({ icons:{ primary:'ui-icon-seek-next'  }, text:false }).click(function() { callback('goNextMove'    ); });
		selector('.rpbui-chessgame-navigationButtonLast'    ).button({ icons:{ primary:'ui-icon-seek-end'   }, text:false }).click(function() { callback('goLastMove'    ); });
		selector('.rpbui-chessgame-navigationButtonFlip'    ).button({ icons:{ primary:'ui-icon-refresh'    }, text:false }).click(function() { callback('flip'          ); });
		selector('.rpbui-chessgame-navigationButtonDownload').button({ icons:{ primary:'ui-icon-extlink'    }, text:false }).click(function() { callback('downloadPGN'   ); });
	}


	/**
	 * Create the navigation frame, if it does not exist yet.
	 */
	function buildNavigationFrame() {
		if($('#rpbui-chessgame-navigationFrame').length !== 0) {
			return;
		}

		// Structure of the navigation frame.
		$('<div id="rpbui-chessgame-navigationFrame">' + buildNavigationSkeleton() + '</div>').appendTo($('body'));

		// Create the dialog widget.
		$('#rpbui-chessgame-navigationFrame').dialog({
			/* Hack to keep the dialog draggable after the page has being scrolled. */
			create     : function(event) { $(event.target).parent().css('position', 'fixed'); },
			resizeStart: function(event) { $(event.target).parent().css('position', 'fixed'); },
			resizeStop : function(event) { $(event.target).parent().css('position', 'fixed'); },
			/* End of hack */
			autoOpen   : false,
			dialogClass: $.chessgame.navigationFrameClass,
			width      : 'auto',
			close      : function() { unselectMove(); }
		});

		// Create the chessboard widget.
		var widget = $('#rpbui-chessgame-navigationFrame .rpbui-chessgame-navigationBoard');
		widget.chessboard(filterChessboardOptions($.chessgame.navigationFrameOptions));
		widget.chessboard('sizeControlledByContainer', $('#rpbui-chessgame-navigationFrame'), 'dialogresize');

		// Callback for the buttons.
		initializeNavigationButtons(function(buttonClass) { return $('#rpbui-chessgame-navigationFrame ' + buttonClass); }, function callback(methodName) {
			var gameWidget = $('#rpbui-chessgame-navigationFrameTarget').closest('.rpbui-chessgame');
			gameWidget.chessgame(methodName);
			gameWidget.chessgame('focus');
		});
	}



	// ---------------------------------------------------------------------------
	// Callbacks
	// ---------------------------------------------------------------------------

	/**
	 * Select the given move and update the navigation board accordingly.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {jQuery} [move] Nothing is done if null or undefined.
	 * @param {boolean} playTheMove
	 */
	function updateNavigationBoard(widget, move, playTheMove) {
		if(move === undefined || move === null || move.hasClass('rpbui-chessgame-selectedMove')) {
			return;
		}

		// If the navigation board should be shown within the dedicated frame,
		// ensure that the latter has been built.
		if(widget.options.navigationBoard === 'frame') {
			buildNavigationFrame();
		}

		// Update the selected move and the mini-board.
		updateNavigationBoardFlip(widget);
		updateNavigationButtons(widget);
		updateNavigationBoardPosition(widget, move, playTheMove);
		updateSelectedMove(widget, move);

		// If the navigation board is in the dedicated frame, update its title,
		// and ensure that it is visible.
		if(widget.options.navigationBoard === 'frame') {
			var frame = $('#rpbui-chessgame-navigationFrame');
			if(!frame.dialog('isOpen')) {
				frame.dialog('option', 'position', { my: 'center', at: 'center', of: window });
				frame.dialog('open');
			}
			$('.ui-dialog-title', frame.closest('.ui-dialog')).empty().append(move.html());
		}

		// Determine the scroll target (i.e. the selected move most of the time).
		var scrollTarget = $('.rpbui-chessgame-selectedMove', widget.element);
		var allowScrollDown = true;
		if(scrollTarget.hasClass('rpbui-chessgame-initialMove')) {
			scrollTarget = $('.rpbui-chessgame-headers', widget.element);
			if(scrollTarget.length === 0) { scrollTarget = $('.rpbui-chessgame-body', widget.element); }
			allowScrollDown = false;
		}

		// Scroll to the selected move if possible.
		if(widget.options.navigationBoard === 'scrollLeft' || widget.options.navigationBoard === 'scrollRight') {
			var scrollArea = $('.rpbui-chessgame-scrollArea', widget.element);
			var targetOffsetTop = scrollTarget.offset().top - scrollArea.offset().top;
			if(targetOffsetTop < 0) {
				scrollArea.stop().animate({ scrollTop: scrollArea.scrollTop() + targetOffsetTop }, 200);
			}
			else if(allowScrollDown && targetOffsetTop + scrollTarget.height() > scrollArea.height()) {
				scrollArea.stop().animate({ scrollTop: scrollArea.scrollTop() + targetOffsetTop + scrollTarget.height() - scrollArea.height() }, 200);
			}
		}
		else if(widget.options.navigationBoard === 'frame') {
			var targetOffset = scrollTarget.offset();
			if(targetOffset.top < $(window).scrollTop()) {
				$('html, body').stop().animate({ scrollTop: targetOffset.top }, 200);
			}
			else if(allowScrollDown && targetOffset.top + scrollTarget.height() > $(window).scrollTop() + window.innerHeight) {
				$('html, body').stop().animate({ scrollTop: targetOffset.top + scrollTarget.height() - window.innerHeight }, 200);
			}
		}
	}


	/**
	 * Refresh the orientation of the navigation chessboard widget.
	 */
	function updateNavigationBoardFlip(widget) {
		var navigationBoard = retrieveNavigationBoard(widget);

		// Flip the board if necessary.
		if(widget.options.navigationBoardOptions.flip !== navigationBoard.chessboard('option', 'flip')) {
			navigationBoard.chessboard('option', 'flip', widget.options.navigationBoardOptions.flip);
		}
	}


	/**
	 * Refresh the visibility of the navigation buttons.
	 */
	function updateNavigationButtons(widget) {
		updateNavigationButton(widget, '.rpbui-chessgame-navigationButtonFlip', widget.options.showFlipButton);
		updateNavigationButton(widget, '.rpbui-chessgame-navigationButtonDownload', widget.options.showDownloadButton);
	}


	/**
	 * Refresh the visibility of the navigation button identified by the given class.
	 */
	function updateNavigationButton(widget, buttonClass, isVisible) {
		var button = widget.options.navigationBoard === 'frame' ? $('#rpbui-chessgame-navigationFrame ' + buttonClass) : $(buttonClass, widget.element);
		if(isVisible) {
			button.show();
		}
		else {
			button.hide();
		}
	}


	/**
	 * Refresh the position displayed on the navigation chessboard widget.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {jQuery} move
	 * @param {boolean} playTheMove
	 */
	function updateNavigationBoardPosition(widget, move, playTheMove) {
		var navigationBoard = retrieveNavigationBoard(widget);

		// Update the position.
		if(playTheMove) {
			navigationBoard.chessboard('option', 'position', move.data('positionBefore'));
			navigationBoard.chessboard('play', move.data('moveNotation'));
		}
		else {
			navigationBoard.chessboard('option', 'position', move.data('position'));
		}

		// Update the square/arrow markers
		var csl = move.data('csl');
		var cal = move.data('cal');
		navigationBoard.chessboard('option', 'squareMarkers', typeof csl === 'undefined' ? '' : csl);
		navigationBoard.chessboard('option', 'arrowMarkers', typeof cal === 'undefined' ? '' : cal);
	}


	/**
	 * Return the navigation board of the given widget (either its own navigation board or the shared one in the popup frame).
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {jQuery}
	 */
	function retrieveNavigationBoard(widget) {
		return widget.options.navigationBoard === 'frame' ?
			$('#rpbui-chessgame-navigationFrame .rpbui-chessgame-navigationBoard') :
			$('.rpbui-chessgame-navigationBoard', widget.element);
	}


	/**
	 * Return the blob-download link of the given widget.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @returns {jQuery}
	 */
	function retrieveBlobDownloadLink(widget) {
		return widget.options.navigationBoard === 'frame' ?
			$('#rpbui-chessgame-navigationFrame .rpbui-chessgame-blobDownloadLink') :
			$('.rpbui-chessgame-blobDownloadLink', widget.element);
	}


	/**
	 * Make the given move appear as selected.
	 *
	 * @param {rpbchess-ui.chessgame} widget
	 * @param {jQuery} move
	 */
	function updateSelectedMove(widget, move) {
		var global = widget.options.navigationBoard === 'frame';

		// Unselect the previously selected move, if any.
		unselectMove(global ? null : $('.rpbui-chessgame-selectedMove', widget.element));

		// Update the ID/class attributes.
		move.addClass('rpbui-chessgame-selectedMove');
		if(global) {
			move.attr('id', 'rpbui-chessgame-navigationFrameTarget');
		}

		// Highlight the selected move.
		var color = move.css('color');
		move.css('background-color', color);
		move.css('color', contrastedColor(color));
	}


	/**
	 * Unselect the selected move, if any.
	 *
	 * @param {jQuery} [target] Move to unselect. If not provided, `#rpbui-chessgame-navigationFrameTarget` is targeted.
	 */
	function unselectMove(target) {
		if(target === undefined || target === null) {
			target = $('#rpbui-chessgame-navigationFrameTarget');
		}
		target.attr('style', null).attr('id', null).removeClass('rpbui-chessgame-selectedMove');
	}



	// ---------------------------------------------------------------------------
	// Widget registration in the jQuery widget framework.
	// ---------------------------------------------------------------------------

	/**
	 * Register a 'chessgame' widget in the jQuery widget framework.
	 */
	$.widget('rpbchess-ui.chessgame',
	{
		/**
		 * Default options.
		 */
		options:
		{
			/**
			 * String describing the game (PGN format).
			 */
			pgn: '*',

			/**
			 * URL from which the PGN data should be retrieved. If provided, the `pgn` attribute becomes read-only.
			 */
			url: '',

			/**
			 * Index of the game to consider in the PGN data.
			 */
			gameIndex: -1,

			/**
			 * Position of the navigation board.
			 *
			 * Available values are:
			 * - 'none': no navigation board.
			 * - 'frame': navigation board in a jQuery frame independent of the page content.
			 * - 'floatLeft' (or 'floatRight') : navigation board in floating node on the left (or right) of the headers and moves.
			 * - 'scrollLeft' (or 'scrollRight'): navigation board on the left (or right) of the headers and moves.
			 *   A vertical scrollbar is added to the move area if its height is larger than the one of the navigation board.
			 */
			navigationBoard: 'none',

			/**
			 * Options for the navigation chessboard widget.
			 */
			navigationBoardOptions: {},

			/**
			 * Options for the chessboard diagrams in the comments.
			 */
			diagramOptions: {},

			/**
			 * Type of piece symbols to use to render the move notation.
			 *
			 * Available values are:
			 * - 'native': use the first letter of the piece names (in English).
			 * - 'localized': use the symbols defined by `$.chessgame.i18n.PIECE_SYMBOLS`.
			 * - 'figurines': use the figurines defined by the default chess font `$.chessgame.chessFont`.
			 * - ':' + chess font name: use the figurines defined by the specified chess font.
			 * - '(' + six letters + ')': use custom letters.
			 */
			pieceSymbols: 'native',

			/**
			 * Whether the "flip" button should be visible or not below the navigation boards.
			 */
			showFlipButton: true,

			/**
			 * Whether the "download" button should be visible or not below the navigation boards.
			 */
			showDownloadButton: true
		},


		/**
		 * Hold the parsed information about the displayed chess game.
		 * @type {RPBChess.pgn.Item}
		 */
		_game: null,


		/**
		 * Translation table for chess piece symbols.
		 * @type {{K:string, Q:string, R:string, B:string, N:string, P:string}}
		 */
		_pieceSymbolTable: null,


		/**
		 * Constructor.
		 */
		_create: function()
		{
			this.element.addClass('rpbui-chessgame');

			this.options.url = initializeURL(this, this.options.url);
			if(!this.options.url) {
				this.options.pgn = initializePGN(this, this.options.pgn);
			}

			this.options.pieceSymbols = initializePieceSymbols(this, this.options.pieceSymbols);

			this.options.navigationBoard        = filterNavigationBoard  (this.options.navigationBoard       );
			this.options.navigationBoardOptions = filterChessboardOptions(this.options.navigationBoardOptions);
			this.options.diagramOptions         = filterChessboardOptions(this.options.diagramOptions        );

			this.options.showFlipButton     = filterBoolean(this.options.showFlipButton    , true);
			this.options.showDownloadButton = filterBoolean(this.options.showDownloadButton, true);

			refresh(this);
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			destroyContent(this);
			this.element.removeClass('rpbui-chessgame');
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value)
		{
			switch(key) {

				case 'url': value = initializeURL(this, value); break;

				case 'pgn':
					if(this.options.url) { return; }
					value = initializePGN(this, value);
					break;

				case 'pieceSymbols': value = initializePieceSymbols(this, value); break;
				case 'navigationBoard'       : value = filterNavigationBoard  (value); break;
				case 'navigationBoardOptions': value = filterChessboardOptions(value); break;
				case 'diagramOptions'        : value = filterChessboardOptions(value); break;

				case 'showFlipButton'    : value = filterBoolean(value, true); break;
				case 'showDownloadButton': value = filterBoolean(value, true); break;
			}

			this.options[key] = value;

			refresh(this);
		},


		/**
		 * Go to the first move of the variation of the currently selected move.
		 */
		goFirstMove: function() {
			var target = $('.rpbui-chessgame-selectedMove', this.element);
			if(target.length === 0) {
				return;
			}
			while(target.data('prevMove') !== undefined) {
				target = target.data('prevMove');
			}
			updateNavigationBoard(this, target, false);
		},


		/**
		 * Go to the previous move of the currently selected move.
		 */
		goPreviousMove: function() {
			updateNavigationBoard(this, $('.rpbui-chessgame-selectedMove', this.element).data('prevMove'), false);
		},


		/**
		 * Go to the next move of the currently selected move.
		 */
		goNextMove: function() {
			updateNavigationBoard(this, $('.rpbui-chessgame-selectedMove', this.element).data('nextMove'), true);
		},


		/**
		 * Go to the last move of the variation of the currently selected move.
		 */
		goLastMove: function() {
			var target = $('.rpbui-chessgame-selectedMove', this.element);
			if(target.length === 0) {
				return;
			}
			while(target.data('nextMove') !== undefined) {
				target = target.data('nextMove');
			}
			updateNavigationBoard(this, target, false);
		},


		/**
		 * Flip the navigation board.
		 */
		flip: function() {
			this.options.navigationBoardOptions.flip = !this.options.navigationBoardOptions.flip;
			updateNavigationBoardFlip(this);
		},


		/**
		 * Download the PGN data.
		 */
		downloadPGN: function() {
			if(this.options.url) {
				window.location.href = this.options.url;
			}
			else {
				var data = new Blob([this.options.pgn], { type: 'text/plain' });

				if(dynamicURL !== null) {
					window.URL.revokeObjectURL(dynamicURL);
				}
				dynamicURL = window.URL.createObjectURL(data);

				var link = retrieveBlobDownloadLink(this);
				link.attr('href', dynamicURL);
				link.get(0).click();
			}
		},

		/**
		 * Give the focus to the widget.
		 */
		focus: function() {
			$('.rpbui-chessgame-focusField', this.element).focus();
		}

	}); /* $.widget('rpbchess-ui.chessgame', { ... }) */

})(/* global RPBChess */ RPBChess, /* global jQuery */ jQuery, /* global moment */ moment);
