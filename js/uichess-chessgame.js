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


/**
 * jQuery widget to display a chess game.
 *
 * @author Yoann Le Montagner
 *
 * @requires pgn.js
 * @requires uichess-chessboard.js
 * @requires Moment.js {@link http://momentjs.com/}
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery Color (optional, only if the navigation board feature is enabled)
 * @requires jQuery UI Dialog (optional, only if the framed navigation board feature is enabled)
 * @requires jQuery UI Resizable (optional, only if the framed navigation board feature is enabled)
 */
(function(Pgn, $, moment)
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
			PIECE_SYMBOLS: { 'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P' }
		}

	}; /* $.chessgame = { ... } */


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
	 * @param {string} result Value of a PGN result field.
	 * @returns {string}
	 */
	function formatResult(result)
	{
		switch(result) {
			case null: case '*': return null;
			case '1/2-1/2':      return '&#189;&#8211;&#189;';
			case '1-0':          return '1&#8211;0';
			case '0-1':          return '0&#8211;1';
			default:             return result;
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
		switch(font) {
			case 'merida':
			case 'pirat':
				break;
			default:
				font = 'alpha';
				break;
		}

		// Build the corresponding chess font set.
		var prefix = '<span class="uichess-chessgame-' + font + 'Font">';
		var suffix = '</span>';
		var pieceSymbolTable = {
			'K': prefix + 'K' + suffix,
			'Q': prefix + 'Q' + suffix,
			'R': prefix + 'R' + suffix,
			'B': prefix + 'B' + suffix,
			'N': prefix + 'N' + suffix,
			'P': prefix + 'P' + suffix
		};

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
		return {
			squareSize     : value.squareSize     ,
			showCoordinates: value.showCoordinates
		};
	}


	/**
	 * Register a 'chessgame' widget in the jQuery widget framework.
	 */
	$.widget('uichess.chessgame',
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
			 * Position of the navigation board.
			 *
			 * Available values are:
			 * - 'none': no navigation board.
			 * - 'frame': navigation board in a jQuery frame independent of the page content.
			 * - 'left': navigation board in floating node on the left of the headers and moves.
			 * - 'right': navigation board in floating node on the right of the headers and moves.
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
			 * Whether the navigation board and the diagrams are flipped or not.
			 */
			flip: false,

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
			pieceSymbols: 'native'
		},


		/**
		 * Hold the parsed information about the displayed chess game.
		 * @type {Pgn.Item}
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
			this.element.addClass('uichess-chessgame');
			this.options.pgn          = this._initializePGN         (this.options.pgn         );
			this.options.pieceSymbols = this._initializePieceSymbols(this.options.pieceSymbols);
			this.options.navigationBoard        = filterNavigationBoard  (this.options.navigationBoard       );
			this.options.navigationBoardOptions = filterChessboardOptions(this.options.navigationBoardOptions);
			this.options.diagramOptions         = filterChessboardOptions(this.options.diagramOptions        );
			this._refresh();
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			this._destroyContent();
			this.element.removeClass('uichess-chessgame');
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value)
		{
			switch(key) {
				case 'pgn'         : value = this._initializePGN         (value); break;
				case 'pieceSymbols': value = this._initializePieceSymbols(value); break;
				case 'navigationBoard'       : value = filterNavigationBoard  (value); break;
				case 'navigationBoardOptions': value = filterChessboardOptions(value); break;
				case 'diagramOptions'        : value = filterChessboardOptions(value); break;
			}

			this.options[key] = value;
			this._refresh();
		},


		/**
		 * Go to the first move of the variation of the currently selected move.
		 */
		goFirstMove: function()
		{
			var target = $('.uichess-chessgame-selectedMove', this.element);
			if(target.length === 0) {
				return;
			}
			while(target.data('prevMove') !== undefined) {
				target = target.data('prevMove');
			}
			this._updateNavigationBoard(target);
		},


		/**
		 * Go to the previous move of the currently selected move.
		 */
		goPreviousMove: function()
		{
			this._updateNavigationBoard($('.uichess-chessgame-selectedMove', this.element).data('prevMove'));
		},


		/**
		 * Go to the next move of the currently selected move.
		 */
		goNextMove: function()
		{
			this._updateNavigationBoard($('.uichess-chessgame-selectedMove', this.element).data('nextMove'));
		},


		/**
		 * Go to the last move of the variation of the currently selected move.
		 */
		goLastMove: function()
		{
			var target = $('.uichess-chessgame-selectedMove', this.element);
			if(target.length === 0) {
				return;
			}
			while(target.data('nextMove') !== undefined) {
				target = target.data('nextMove');
			}
			this._updateNavigationBoard(target);
		},


		/**
		 * Initialize the internal Pgn.Item object that contains the parsed PGN data.
		 *
		 * @param {string} pgn
		 * @returns {string}
		 */
		_initializePGN: function(pgn)
		{
			// Ensure that the input is actually a string.
			if(typeof pgn !== 'string') {
				pgn = '*';
			}

			// Trim the input.
			pgn = pgn.replace(/^\s+|\s+$/g, '');

			// Parse the input assuming a PGN format.
			try {
				this._game = Pgn.parseOne(pgn);
			}
			catch(error) {
				if(error instanceof Pgn.Error) { // Parsing errors are reported to the user.
					this._game = error;
				}
				else { // Unknown exceptions are re-thrown.
					this._game = null;
					throw error;
				}
			}

			// Return the validated PGN string.
			return pgn;
		},


		/**
		 * Initialize the internal object that describes how to represent the chess pieces
		 * in SAN notation.
		 *
		 * @param {string} pieceSymbols
		 * @returns {string}
		 */
		_initializePieceSymbols: function(pieceSymbols)
		{
			var FIELDS = ['K', 'Q', 'R', 'B', 'N', 'P'];

			// Descriptors: 6 custom letters.
			if(/^\([a-zA-Z]{6}\)$/.test(pieceSymbols)) {
				pieceSymbols = pieceSymbols.toUpperCase();
				this._pieceSymbolTable = {};
				for(var k=0; k<6; ++k) {
					this._pieceSymbolTable[FIELDS[k]] = pieceSymbols.substr(k+1, 1);
				}
			}

			// Descriptors: figurines, using a custom chess font.
			else if(/^:\w+$/.test(pieceSymbols)) {
				var info = filterChessFontName(pieceSymbols.substr(1));
				pieceSymbols = ':' + info.font;
				this._pieceSymbolTable = info.pieceSymbolTable;
			}

			// Special values: native (English initials, localized initials, or figurines
			// using the default chess font).
			else {
				switch(pieceSymbols) {

					// Figurines using the default chess font.
					case 'figurines':
						this._pieceSymbolTable = filterChessFontName($.chessgame.chessFont).pieceSymbolTable;
						break;

					// Localized initials.
					case 'localized':
						this._pieceSymbolTable = {};
						for(var k=0; k<6; ++k) {
							var field = FIELDS[k];
							this._pieceSymbolTable[field] = (field in $.chessgame.i18n.PIECE_SYMBOLS) ?
								$.chessgame.i18n.PIECE_SYMBOLS[field] : field;
						}
						break;

					// English initials (also the fallback case).
					default:
						this._pieceSymbolTable = { 'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P' };
						pieceSymbols = 'native';
						break;
				}
			}

			// Return the validated input.
			return pieceSymbols;
		},


		/**
		 * Destroy the widget content, prior to a refresh or a widget destruction.
		 */
		_destroyContent: function()
		{
			var navigationFrameTarget = $('#uichess-chessgame-navigationFrameTarget', this.element);
			if(navigationFrameTarget.length !== 0) {
				$('#uichess-chessgame-navigationFrame').dialog('close');
			}
			this.element.empty();
		},


		/**
		 * Refresh the widget.
		 */
		_refresh: function()
		{
			this._destroyContent();
			if(this._game === null) {
				return;
			}

			// Handle parsing error problems.
			if(this._game instanceof Pgn.Error) {
				$(this._buildErrorMessage()).appendTo(this.element);
				return;
			}

			// Headers
			var headers = '';
			headers += this._playerNameHeader('White');
			headers += this._playerNameHeader('Black');
			headers += this._eventHeader();
			headers += this._datePlaceHeader();
			headers += this._annotatorHeader();
			if(headers !== '') {
				headers = '<div class="uichess-chessgame-headers">' + headers + '</div>';
			}

			// Body and initial move
			var move0 = this._buildInitialMove();
			var body  = this._buildBody();

			// Navigation board
			var prefix = '';
			var suffix = '';
			switch(this.options.navigationBoard) {
				case 'floatLeft':
				case 'floatRight':
					suffix = '<div class="uichess-chessgame-' + this.options.navigationBoard.replace('float', 'clear') + '"></div>';
					prefix = '<div class="uichess-chessgame-navigationBox uichess-chessgame-' + this.options.navigationBoard + '">' +
						buildNavigationSkeleton() + '</div>';
					break;
			}

			// Render the content.
			$(prefix + move0 + headers + body + suffix).appendTo(this.element);

			// Render the diagrams in comments.
			this._makeDiagrams();

			// Activate the navigation board, if required.
			if(this.options.navigationBoard !== 'none') {
				this._makeMovesClickable();
				this._makeMovesRelated();
				if(this.options.navigationBoard !== 'frame') {
					this._makeNavigationBoxWidgets();
				}
			}
		},


		/**
		 * Render the diagrams inserted in text comments.
		 */
		_makeDiagrams: function()
		{
			var obj = this;
			$('.uichess-chessgame-comment .uichess-chessgame-diagramAnchor', this.element).each(function(index, element)
			{
				var anchor = $(element);

				// Retrieve the position
				var position = anchor.closest('.uichess-chessgame-comment').data('position');

				// Build the option set to pass to the chessboard widget constructor.
				var options = { position: position, flip: obj.options.flip };
				$.extend(options, obj.options.diagramOptions);
				try {
					$.extend(options, filterChessboardOptions($.parseJSON(anchor.text())));
				}
				catch(error) {} // The content of the node is ignored if it is not a valid JSON-encoded object.

				// Render the diagram.
				anchor.empty().removeClass('uichess-chessgame-diagramAnchor').addClass('uichess-chessgame-diagram').chessboard(options);
			});
		},


		/**
		 * Make the moves clickable: when clicked, the navigation board is updated to show
		 * the position after the corresponding move.
		 */
		_makeMovesClickable: function()
		{
			var obj = this;
			$('.uichess-chessgame-move', this.element).click(function() { obj._updateNavigationBoard($(this)); });
		},


		/**
		 * For each move, add pointer to its predecessor and its successor in the variation.
		 */
		_makeMovesRelated: function()
		{
			// For each variation...
			$('.uichess-chessgame-variation').each(function(index, element)
			{
				// Retrieve the moves of the variation.
				var variation = $(element);
				var moves     = variation.is('div') ?
					variation.children('.uichess-chessgame-moveGroup').children('.uichess-chessgame-move') :
					variation.children('.uichess-chessgame-move');

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
			var initialMove = $('.uichess-chessgame-initialMove', this.element);
			var allMoves    = $('.uichess-chessgame-variation .uichess-chessgame-move', this.element);
			if(allMoves.length !== 0) {
				var secondMove = allMoves.first();
				secondMove.data('prevMove', initialMove);
				initialMove.data('nextMove', secondMove);
			}
		},


		/**
		 * Initialize the navigation box widgets.
		 */
		_makeNavigationBoxWidgets: function()
		{
			// Set-up the navigation board.
			$('.uichess-chessgame-navigationBoard', this.element).chessboard(this.options.navigationBoardOptions);

			// Navigation buttons
			var obj = this;
			$('.uichess-chessgame-navigationButtonFrst', this.element).click(function(event) { event.preventDefault(); obj.goFirstMove   (); });
			$('.uichess-chessgame-navigationButtonPrev', this.element).click(function(event) { event.preventDefault(); obj.goPreviousMove(); });
			$('.uichess-chessgame-navigationButtonNext', this.element).click(function(event) { event.preventDefault(); obj.goNextMove    (); });
			$('.uichess-chessgame-navigationButtonLast', this.element).click(function(event) { event.preventDefault(); obj.goLastMove    (); });

			// Show the initial position on the navigation board.
			this._updateNavigationBoard($('.uichess-chessgame-initialMove', this.element));
		},


		/**
		 * Build the error message resulting from a PGN parsing error.
		 *
		 * @returns {string}
		 */
		_buildErrorMessage: function()
		{
			// Build the error report box.
			var retVal = '<div class="uichess-chessgame-error">' +
				'<div class="uichess-chessgame-errorTitle">Error while analysing a PGN string.</div>';

			// Optional message.
			if(this._game.message !== null) {
				retVal += '<div class="uichess-chessgame-errorMessage">' + this._game.message + '</div>';
			}

			// Display where the error has occurred.
			if(this._game.pos !== null && this._game.pos >= 0) {
				retVal += '<div class="uichess-chessgame-errorAt">';
				if(this._game.pos >= this._game.pgnString.length) {
					retVal += 'Occurred at the end of the string.';
				}
				else {
					retVal += 'Occurred at position ' + this._game.pos + ':' + '<div class="uichess-chessgame-errorAtCode">' +
						ellipsisAt(this._game.pgnString, this._game.pos, 10, 40) + '</div>';
				}
				retVal += '</div>';
			}

			// Close the error report box, and return the result.
			retVal += '</div>';
			return retVal;
		},


		/**
		 * Build the header containing the player-related information (name, rating, title)
		 * corresponding to the requested color.
		 *
		 * @param {string} color Either 'White' or 'Black'.
		 * @returns {string}
		 */
		_playerNameHeader: function(color)
		{
			// Retrieve the name of the player -> no header is returned if the name not available.
			var name = formatDefault(this._game.header(color));
			if(name===null) {
				return '';
			}

			// Build the returned header.
			var header = '<div class="uichess-chessgame-' + color.toLowerCase() + 'Player">' +
				'<span class="uichess-chessgame-colorTag"></span> ' +
				'<span class="uichess-chessgame-playerName">' + name + '</span>';

			// Title + rating
			var title  = formatTitle  (this._game.header(color + 'Title'));
			var rating = formatDefault(this._game.header(color + 'Elo'  ));
			if(title !== null || rating !== null) {
				header += '<span class="uichess-chessgame-titleRatingGroup">';
				if(title  !== null) { header += '<span class="uichess-chessgame-playerTitle">'  + title  + '</span>'; }
				if(rating !== null) { header += '<span class="uichess-chessgame-playerRating">' + rating + '</span>'; }
				header += '</span>';
			}

			// Add the closing tag and return the result.
			header += '</div>';
			return header;
		},


		/**
		 * Build the header containing the event-related information (event + round).
		 *
		 * @returns {string}
		 */
		_eventHeader: function()
		{
			// Retrieve the event -> no header is returned if the name not available.
			var event = formatDefault(this._game.header('Event'));
			if(event===null) {
				return '';
			}

			// Retrieve the round.
			var round = formatDefault(this._game.header('Round'));

			// Build and return the header.
			var header = '<div class="uichess-chessgame-event">' + event;
			if(round !== null) {
				header += '<span class="uichess-chessgame-round">' + round + '</span>';
			}
			header += '</div>';
			return header;
		},


		/**
		 * Build the header containing the date/place information.
		 *
		 * @returns {string}
		 */
		_datePlaceHeader: function()
		{
			// Retrieve the date and the site field.
			var date = formatDate   (this._game.header('Date'));
			var site = formatDefault(this._game.header('Site'));
			if(date===null && site===null) {
				return '';
			}

			// Build and return the header.
			var header = '<div class="uichess-chessgame-datePlaceGroup">';
			if(date !== null) { header += '<span class="uichess-chessgame-date">' + date + '</span>'; }
			if(site !== null) { header += '<span class="uichess-chessgame-site">' + site + '</span>'; }
			header += '</div>';
			return header;
		},


		/**
		 * Build the header containing the annotator information.
		 *
		 * @returns {string}
		 */
		_annotatorHeader: function()
		{
			// Retrieve the annotator field.
			var annotator = formatDefault(this._game.header('Annotator'));
			if(annotator===null) {
				return '';
			}

			// Build and return the header.
			var header = '<div class="uichess-chessgame-annotator">' + $.chessgame.i18n.ANNOTATED_BY.replace(/%1\$s/g,
				'<span class="uichess-chessgame-annotatorName">' + annotator + '</span>') + '</div>';
			return header;
		},


		/**
		 * Build the move tree.
		 *
		 * @returns {string}
		 */
		_buildBody: function()
		{
			var mainVariation = this._buildVariation(this._game.mainVariation(), true, formatResult(this._game.result()));

			// Nothing to do if the main variation is empty.
			if(mainVariation.content === '') {
				return '';
			}

			// Otherwise, wrap it into a DIV node.
			var bodyClass = 'uichess-chessgame-body';
			if(mainVariation.divCount > 1) { bodyClass += ' uichess-chessgame-moreSpace'; }
			if(this.options.navigationBoard !== 'none') { bodyClass += ' uichess-chessgame-clickableMoves'; }
			return '<div class="' + bodyClass + '">' + mainVariation.content + '</div>';
		},


		/**
		 * Build the move tree corresponding to the given variation.
		 *
		 * @param {Pgn.Variation} variation
		 * @param {boolean} isMainVariation
		 * @param {null|string} result Must be set to null for sub-variations.
		 * @returns {string|{content:string, divCount:number}} The second form is only used for the main variation.
		 */
		_buildVariation: function(variation, isMainVariation, result)
		{
			// Nothing to do if the variation if empty.
			if(variation.comment() === null && variation.first() === null && result === null) {
				return isMainVariation ? { content: '', divCount: 0 } : '';
			}

			// Open a new DOM node for the variation.
			var tag = variation.isLongVariation() ? 'div' : 'span';
			var retVal = '<' + tag + ' class="uichess-chessgame-variation' + (isMainVariation ? '' : ' uichess-chessgame-subVariation') + '">';

			// The flag `moveGroupOpened` indicates whether a `<div class="moveGroup">` node
			// is currently opened or not. Move group nodes are supposed to contain moves,
			// short-comments and short-variations when the parent variation is long.
			// In short variations, there is no move groups.
			var enableMoveGroups = variation.isLongVariation();
			var moveGroupOpened  = false;
			var divCount         = 0;

			// Open a new move group if necessary.
			function openMoveGroup()
			{
				if(enableMoveGroups && !moveGroupOpened) {
					retVal += '<div class="uichess-chessgame-moveGroup">';
					moveGroupOpened = true;
					++divCount;
				}
			}

			// Close the current move group, if any.
			function closeMoveGroup()
			{
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
				retVal += this._buildComment(variation);
			}

			// Visit all the PGN nodes (one node per move) within the variation.
			var forcePrintMoveNumber = true;
			var node = variation.first();
			while(node !== null)
			{
				// Write the move, including directly related information (i.e. move number + NAGs).
				openMoveGroup();
				retVal += this._buildMove(node, forcePrintMoveNumber);

				// Write the comment (if any).
				if(node.comment() !== null) {
					if(node.isLongComment()) {
						closeMoveGroup();
						++divCount;
					}
					retVal += this._buildComment(node);
				}

				// Write the sub-variations.
				var nonEmptySubVariations = 0;
				for(var k=0; k<node.variations(); ++k) {
					var subVariation = this._buildVariation(node.variation(k), false, null);
					if(subVariation !== '') {
						if(node.variation(k).isLongVariation()) {
							closeMoveGroup();
							++divCount;
						} else {
							openMoveGroup();
						}
						retVal += subVariation;
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
				retVal += '<span class="uichess-chessgame-result">' + result + '</span>';
			}

			// Close the opened DOM nodes, and returned the result.
			closeMoveGroup();
			retVal += '</' + tag + '>';
			return isMainVariation ? { content: retVal, divCount: divCount } : retVal;
		},


		/**
		 * Build the DOM node corresponding to the given text comment.
		 *
		 * @param {Pgn.Node|Pgn.Variation} node
		 * @returns {string}
		 */
		_buildComment: function(node)
		{
			var tag = node.isLongComment() ? 'div' : 'span';
			return '<' + tag + ' class="uichess-chessgame-comment" data-position="' + node.position() + '">' +
				node.comment() + '</' + tag + '>';
		},


		/**
		 * Build the DOM node corresponding to the given move (move number, SAN notation, NAGs).
		 *
		 * @param {Pgn.Node} node
		 * @param {boolean} forcePrintMoveNumber
		 * @returns {string}
		 */
		_buildMove: function(node, forcePrintMoveNumber)
		{
			// Create the DOM node.
			var retVal = '<span class="uichess-chessgame-move" data-position="' + node.position() + '">';

			// Move number
			var printMoveNumber = forcePrintMoveNumber || node.moveColor() === 'w';
			var moveNumberClass = 'uichess-chessgame-moveNumber' + (printMoveNumber ? '' : ' uichess-chessgame-hidden');
			var moveNumberText  = node.fullMoveNumber() + (node.moveColor() === 'w' ? '.' : '\u2026');
			retVal += '<span class="' + moveNumberClass + '">' + moveNumberText + '</span>';

			// SAN notation.
			var pieceSymbolTable = this._pieceSymbolTable;
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
		},


		/**
		 * Build the DOM node corresponding to the "initial-position-move",
		 * which is always hidden, but must be added to make possible to display
		 * the initial position in the navigation board.
		 *
		 * @returns {string}
		 */
		_buildInitialMove: function()
		{
			return '<div class="uichess-chessgame-move uichess-chessgame-initialMove" ' +
				'data-position="' + this._game.initialPosition() + '">' + $.chessgame.i18n.INITIAL_POSITION + '</div>';
		},


		/**
		 * Select the given move and update the navigation board accordingly.
		 *
		 * @param {jQuery} [move] Nothing is done if null or undefined.
		 */
		_updateNavigationBoard: function(move)
		{
			if(move === undefined || move === null || move.hasClass('uichess-chessgame-selectedMove')) {
				return;
			}

			// If the navigation board should be shown within the dedicated frame,
			// ensure that the latter has been built.
			if(this.options.navigationBoard === 'frame') {
				buildNavigationFrame();
			}

			// Update the selected move and the mini-board.
			this._updateNavigationBoardWidget(move);
			this._updateSelectedMove(move);

			// If the navigation board is in the dedicated frame, update its title,
			// and ensure that it is visible.
			if(this.options.navigationBoard === 'frame') {
				var frame = $('#uichess-chessgame-navigationFrame');
				if(!frame.dialog('isOpen')) {
					frame.dialog('option', 'position', { my: 'center', at: 'center', of: window });
					frame.dialog('open');
				}
				$('.ui-dialog-title', frame.closest('.ui-dialog')).empty().append(move.html());
			}
		},


		/**
		 * Refresh the navigation chessboard widget.
		 *
		 * @param {jQuery} move
		 */
		_updateNavigationBoardWidget: function(move)
		{
			var widget = this.options.navigationBoard === 'frame' ?
				$('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationBoard') :
				$('.uichess-chessgame-navigationBoard', this.element);

			// Update the position.
			widget.chessboard('option', 'position', move.data('position'));

			// Flip the board if necessary.
			if(this.options.flip !== widget.chessboard('option', 'flip')) {
				widget.chessboard('option', 'flip', this.options.flip);
			}
		},


		/**
		 * Make the given move appear as selected.
		 *
		 * @param {jQuery} move
		 */
		_updateSelectedMove: function(move)
		{
			var global = this.options.navigationBoard === 'frame';

			// Unselect the previously selected move, if any.
			unselectMove(global ? null : $('.uichess-chessgame-selectedMove', this.element));

			// Update the ID/class attributes.
			move.addClass('uichess-chessgame-selectedMove');
			if(global) {
				move.attr('id', 'uichess-chessgame-navigationFrameTarget');
			}

			// Highlight the selected move.
			var color = move.css('color');
			move.css('background-color', color);
			move.css('color', contrastedColor(color));
		}

	}); /* $.widget('uichess.chessgame', { ... }) */


	/**
	 * Unselect the selected move, if any.
	 *
	 * @param {jQuery} [target] Move to unselect. If not provided, `#uichess-chessgame-navigationFrameTarget` is targeted.
	 */
	function unselectMove(target)
	{
		if(target === undefined || target === null) {
			target = $('#uichess-chessgame-navigationFrameTarget');
		}
		target.attr('style', null).attr('id', null).removeClass('uichess-chessgame-selectedMove');
	}


	/**
	 * Build the DOM nodes that will be used as a skeleton for the navigation board and buttons.
	 *
	 * @returns {string}
	 */
	function buildNavigationSkeleton()
	{
		return '<div class="uichess-chessgame-navigationBoard"></div>' +
			'<div class="uichess-chessgame-navigationButtons">' +
				'<button class="uichess-chessgame-navigationButtonFrst">&lt;&lt;</button>' +
				'<button class="uichess-chessgame-navigationButtonPrev">&lt;</button>' +
				'<button class="uichess-chessgame-navigationButtonNext">&gt;</button>' +
				'<button class="uichess-chessgame-navigationButtonLast">&gt;&gt;</button>' +
			'</div>';
	}


	/**
	 * Create the navigation frame, if it does not exist yet.
	 */
	function buildNavigationFrame()
	{
		if($('#uichess-chessgame-navigationFrame').length !== 0) {
			return;
		}

		// Structure of the navigation frame.
		$('<div id="uichess-chessgame-navigationFrame">' + buildNavigationSkeleton() + '</div>').appendTo($('body'));

		// Create the dialog widget.
		$('#uichess-chessgame-navigationFrame').dialog({
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
		var widget = $('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationBoard');
		widget.chessboard(filterChessboardOptions($.chessgame.navigationFrameOptions));
		widget.chessboard('sizeControlledByContainer', $('#uichess-chessgame-navigationFrame'), 'dialogresize');

		// Callback for the buttons.
		function callback(methodName) {
			$('#uichess-chessgame-navigationFrameTarget').closest('.uichess-chessgame').chessgame(methodName);
		}

		// Create the buttons.
		$('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationButtonFrst').click(function(event) { event.preventDefault(); callback('goFirstMove'   ); });
		$('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationButtonPrev').click(function(event) { event.preventDefault(); callback('goPreviousMove'); });
		$('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationButtonNext').click(function(event) { event.preventDefault(); callback('goNextMove'    ); });
		$('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationButtonLast').click(function(event) { event.preventDefault(); callback('goLastMove'    ); });
	}

})(/* global Pgn */ Pgn, /* global jQuery */ jQuery, /* global moment */ moment);
