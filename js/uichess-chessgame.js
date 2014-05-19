/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Tools to represent PGN data in HTML pages.
 *
 * @author Yoann Le Montagner
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 * @requires pgn.js
 * @requires chesswidget.js
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery Color (optional, only if the navigation board feature is enabled)
 * @requires jQuery UI Dialog (optional, only if the framed navigation feature is enabled)
 *
 * TODO: check required packages
 */
(function(Chess, Pgn, $)
{
	'use strict';


	/**
	 * Internationalization constants.
	 */
	$.chessgame =
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
		 * Month names.
		 * @type {string[]}
		 */
		MONTHS: [
			'January', 'February', 'March'    , 'April'  , 'May'     , 'June'    ,
			'July'   , 'August'  , 'September', 'October', 'November', 'December'
		],

		/**
		 * Chess piece symbols.
		 * @type {{K:string, Q:string, R:string, B:string, N:string, P:string}}
		 */
		PIECE_SYMBOLS: {
			'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P'
		}
	};


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

		// Case "2013.05.20" -> return "20 may 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.([0-9]{2})/)) {
			var month = parseInt(RegExp.$2, 10);
			if(month>=1 && month<=12) {
				var dateObj = new Date(RegExp.$1, RegExp.$2-1, RegExp.$3);
				return dateObj.toLocaleDateString(); //null, { year: 'numeric', month: 'long', day: 'numeric' });
			}
			else {
				return RegExp.$1;
			}
		}

		// Case "2013.05.??" -> return "May 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.\?\?/)) {
			var month = parseInt(RegExp.$2, 10);
			if(month>=1 && month<=12) {
				return $.chessgame.MONTHS[month-1] + ' ' + RegExp.$1;
			}
			else {
				return RegExp.$1;
			}
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
	 * Convert a SAN move notation into a localized move notation
	 * (the characters used to specify the pieces is the only localized element).
	 *
	 * SAN notation is the format use for moves in PGN text files.
	 *
	 * @param {string} notation SAN move notation to convert.
	 * @returns {string} Localized move string.
	 */
	function formatMoveNotation(notation)
	{
		if(notation===null) {
			return null;
		}
		else {
			var retVal = '';
			for(var k=0; k<notation.length; ++k) {
				var c = notation.charAt(k);
				if(c==='K' || c==='Q' || c==='R' || c==='B' || c==='N' || c==='P') {
					retVal += $.chessgame.PIECE_SYMBOLS[c];
				}
				else {
					retVal += c;
				}
			}
			return retVal;
		}
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
	 * @return string
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
				return value;
			default:
				return 'none';
		}
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
			 */
			navigationBoard: 'frame',

			/**
			 * Whether the navigation board and the diagrams are flipped or not.
			 */
			flip: false
		},


		/**
		 * Hold the parsed information about the displayed chess game.
		 * @type {Pgn.Item}
		 */
		_game: null,


		/**
		 * Constructor.
		 */
		_create: function()
		{
			this.element.addClass('uichess-chessgame');
			this.options.pgn = this._initializePGN(this.options.pgn);
			this.options.navigationBoard = filterNavigationBoard(this.options.navigationBoard);
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
				case 'pgn': value = this._initializePGN(value); break;
				case 'navigationBoard': value = filterNavigationBoard(value); break;
			}

			this.options[key] = value;
			this._refresh();
		},


		/**
		 * Initialize the internal Pgn.Item object that contains the parsed PGN data.
		 *
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
				this._printErrorMessage();
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

			// Body
			var body = this._buildBody();

			// Render the content, and exit if the navigation board feature is disabled.
			$(headers + body).appendTo(this.element);
			if(this.options.navigationBoard === 'none') {
				return;
			}

			// Make the moves clickable.
			var obj = this;
			$('.uichess-chessgame-move', this.element).click(function() { obj._updateNavigationBoard($(this)); });

		},


		/**
		 * Build the error message resulting from a PGN parsing error.
		 */
		_printErrorMessage: function()
		{
			// Build the error report box.
			var content = '<div class="uichess-chessgame-error">' +
				'<div class="uichess-chessgame-errorTitle">Error while analysing a PGN string.</div>';

			// Optional message.
			if(this._game.message !== null) {
				content += '<div class="uichess-chessgame-errorMessage">' + this._game.message + '</div>';
			}

			// Display where the error has occurred.
			if(this._game.pos !== null && this._game.pos >= 0) {
				content += '<div class="uichess-chessgame-errorAt">';
				if(this._game.pos >= this._game.pgnString.length) {
					content += 'Occurred at the end of the string.';
				}
				else {
					content += 'Occurred at position ' + this._game.pos + ':' + '<div class="uichess-chessgame-errorAtCode">' +
						ellipsisAt(this._game.pgnString, this._game.pos, 10, 40) + '</div>';
				}
				content += '</div>';
			}

			// Close the error report box, and update the DOM element.
			content += '</div>';
			$(content).appendTo(this.element);
		},


		/**
		 * Build the header containing the player-related information (name, rating, title)
		 * corresponding to the requested color.
		 *
		 * @param {string} color Either 'White' or 'Black'.
		 * @return {string}
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
		 * @return {string}
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
		 * @return {string}
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
		 * @return {string}
		 */
		_annotatorHeader: function()
		{
			// Retrieve the annotator field.
			var annotator = formatDefault(this._game.header('Annotator'));
			if(annotator===null) {
				return '';
			}

			// Build and return the header.
			var header = '<div class="uichess-chessgame-annotator">' + $.chessgame.ANNOTATED_BY.replace(/%1\$s/g,
				'<span class="uichess-chessgame-annotatorName">' + annotator + '</span>') + '</div>';
			return header;
		},


		/**
		 * Build the move tree.
		 *
		 * @return {string}
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
		 * @return {string|{content:string, divCount:number}} The second form is only used for the main variation.
		 */
		_buildVariation: function(variation, isMainVariation, result)
		{
			// Nothing to do if the variation if empty.
			if(variation.comment() === null && variation.first() === null && result === null) {
				return isMainVariation ? { content: '', divCount: 0 } : '';
			}

			// Open a new DOM node for the variation.
			var tag = variation.isLongVariation() ? 'div' : 'span';
			var retVal = '<' + tag + ' class="uichess-chessgame-' + (isMainVariation ? 'main' : 'sub') + 'Variation ' +
				'uichess-chessgame-' + (variation.isLongVariation() ? 'long' : 'short') + 'Variation">';

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
				retVal += this._buildComment(variation.comment(), variation.isLongComment());
			}

			// Append a fake move at the beginning of the main variation, so that it will be possible
			// to display the starting position in the navigation frame.
			if(isMainVariation) {
				openMoveGroup();
				retVal += '<span class="uichess-chessgame-move uichess-chessgame-hidden" ' +
					'data-position="' + variation.position() + '">' + $.chessgame.INITIAL_POSITION + '</span>';
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
					retVal += this._buildComment(node.comment(), node.isLongComment());
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
		 * @param {string} comment
		 * @param {boolean} isLongComment
		 * @return {string}
		 */
		_buildComment: function(comment, isLongComment)
		{
			var tag = isLongComment ? 'div' : 'span';
			return '<' + tag + ' class="uichess-chessgame-' + (isLongComment ? 'long' : 'short') + 'Comment">' +
				comment + '</' + tag + '>';
		},


		/**
		 * Build the DOM node corresponding to the given move (move number, SAN notation, NAGs).
		 *
		 * @param {Pgn.Node} node
		 * @param {boolean} forcePrintMoveNumber
		 * @return {string}
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
			retVal += formatMoveNotation(node.move());

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
		 * Select the given move and update the navigation board accordingly.
		 *
		 * @param {jQuery} move
		 */
		_updateNavigationBoard: function(move)
		{
			if(move.hasClass('uichess-chessgame-selectedMove')) {
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
				frame.dialog('option', 'title', move.text());
				if(!frame.dialog('isOpen')) {
					frame.dialog('option', 'position', { my: 'center', at: 'center', of: window });
					frame.dialog('open');
				}
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
				null; // TODO: selector for the navigation board when not in the dedicated frame

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

	});


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
	 * Create the navigation frame, if it does not exist yet.
	 */
	function buildNavigationFrame()
	{
		if($('#uichess-chessgame-navigationFrame').length !== 0) {
			return;
		}

		// Structure of the navigation frame.
		$(
			'<div id="uichess-chessgame-navigationFrame">' +
				'<div class="uichess-chessgame-navigationBoard"></div>' +
				'<div class="uichess-chessgame-navigationButtons">TODO' +
					//'<button id="PgnWidget-navigation-button-frst">&lt;&lt;</button>' +
					//'<button id="PgnWidget-navigation-button-prev">&lt;</button>' +
					//'<button id="PgnWidget-navigation-button-next">&gt;</button>' +
					//'<button id="PgnWidget-navigation-button-last">&gt;&gt;</button>' +
				'</div>' +
			'</div>'
		).appendTo($('body'));

		// Create the dialog widget.
		$('#uichess-chessgame-navigationFrame').dialog({
			/* Hack to keep the dialog draggable after the page has being scrolled. */
			create     : function(event) { $(event.target).parent().css('position', 'fixed'); },
			resizeStart: function(event) { $(event.target).parent().css('position', 'fixed'); },
			resizeStop : function(event) { $(event.target).parent().css('position', 'fixed'); },
			/* End of hack */
			autoOpen   : false,
			//dialogClass: 'wp-dialog', // TODO: customize
			width      : 'auto',
			close      : function() { unselectMove(); }
		});

		// Create the chessboard widget.
		var widget = $('#uichess-chessgame-navigationFrame .uichess-chessgame-navigationBoard');
		widget.chessboard();
		widget.chessboard('sizeControlledByContainer', $('#uichess-chessgame-navigationFrame'), 'dialogresize');

		// Create the buttons.
		//$('#PgnWidget-navigation-button-frst').button().click(function() { goFrstMove(); });
		//$('#PgnWidget-navigation-button-prev').button().click(function() { goPrevMove(); });
		//$('#PgnWidget-navigation-button-next').button().click(function() { goNextMove(); });
		//$('#PgnWidget-navigation-button-last').button().click(function() { goLastMove(); });
	}




	/**
	 * Go to the first move of the current variation.
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function goFrstMove()
	{
		var target = $('#PgnWidget-selected-move');
		while(target.data('prevMove')!=null) {
			target = target.data('prevMove');
		}
		showNavigationFrame(target);
	}


	/**
	 * Go to the previous move of the current variation.
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function goPrevMove()
	{
		showNavigationFrame($('#PgnWidget-selected-move').data('prevMove'));
	}


	/**
	 * Go to the next move of the current variation.
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function goNextMove()
	{
		showNavigationFrame($('#PgnWidget-selected-move').data('nextMove'));
	}


	/**
	 * Go to the last move of the current variation.
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function goLastMove()
	{
		var target = $('#PgnWidget-selected-move');
		while(target.data('nextMove')!=null) {
			target = target.data('nextMove');
		}
		showNavigationFrame(target);
	}

})(Chess, Pgn, jQuery);
