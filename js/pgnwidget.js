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
 * @namespace PgnWidget
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 * @requires pgn.js
 * @requires chesswidget.js
 * @requires jQuery
 * @requires jQuery-color
 */
var PgnWidget = (function(Chess, Pgn, ChessWidget, $)
{
	/**
	 * Various strings used by the library and printed out to the screen at some
	 * point. They are made public so that they can be localized.
	 *
	 * @public
	 * @memberof PgnWidget
	 */
	var text =
	{
		// Miscellaneous
		initialPosition: 'Initial position',

		// Month names
		months: [
			'January', 'February', 'March'    , 'April'  , 'May'     , 'June'    ,
			'July'   , 'August'  , 'September', 'October', 'November', 'December'
		],

		// Chess piece symbols
		pieceSymbols: {
			'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P'
		}
	};


	/**
	 * Convert a PGN date field value into a human-readable date string.
	 * Return null if the special code "????.??.??" is detected.
	 * Otherwise, if the input is badly-formatted, it is returned "as-is".
	 *
	 * @private
	 *
	 * @param {string} date Value of a PGN date field.
	 * @returns {string}
	 *
	 * @memberof PgnWidget
	 */
	function formatDate(date)
	{
		// Null input or case "????.??.??" -> no date is defined.
		if(date==null || date=='????.??.??') {
			return null;
		}

		// Case "2013.05.20" -> return "20 may 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.([0-9]{2})/)) {
			var month = parseInt(RegExp.$2);
			if(month>=1 && month<=12) {
				var dateObj = new Date(RegExp.$1, RegExp.$2-1, RegExp.$3);
				return dateObj.toLocaleDateString(); //null, { year: 'numeric', month: 'long', day: 'numeric' });
			}
			else
				return RegExp.$1;
		}

		// Case "2013.05.??" -> return "May 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.\?\?/)) {
			var month = parseInt(RegExp.$2);
			if(month>=1 && month<=12) {
				return text.months[month-1] + ' ' + RegExp.$1;
			}
			else
				return RegExp.$1;
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
	 * Convert a PGN round field value into a human-readable round string.
	 * Return null if the special code "?" is detected.
	 *
	 * @private
	 *
	 * @param {string} round Value of a PGN round field.
	 * @returns {string}
	 *
	 * @memberof PgnWidget
	 */
	function formatRound(round)
	{
		return (round==null || round=='?') ? null : round;
	}


	/**
	 * Convert a PGN result field value into a human-readable string.
	 * Return null if the special code "*" is detected.
	 *
	 * @private
	 *
	 * @param {string} result Value of a PGN result field.
	 * @returns {string}
	 *
	 * @memberof PgnWidget
	 */
	function formatResult(result)
	{
		if(result==null || result=='*') return null;
		else if(result=='1/2-1/2') return '&#189;&#8211;&#189;';
		else if(result=='1-0') return '1&#8211;0';
		else if(result=='0-1') return '0&#8211;1';
		else return result;
	}


	/**
	 * Convert a SAN move notation into a localized move notation
	 * (the characters used to specify the pieces is the only localized element).
	 *
	 * SAN notation is the format use for moves in PGN text files.
	 *
	 * @private
	 *
	 * @param {string} notation SAN move notation to convert.
	 * @returns {string} Localized move string.
	 *
	 * @memberof PgnWidget
	 */
	function formatMoveNotation(notation)
	{
		if(notation==null) {
			return null;
		}
		else {
			var retVal = '';
			for(var k=0; k<notation.length; ++k) {
				var c = notation.charAt(k);
				if(c=='K' || c=='Q' || c=='R' || c=='B' || c=='N' || c=='P') {
					retVal += text.pieceSymbols[c];
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
	 * @private
	 * @constant
	 *
	 * @memberof PgnWidget
	 */
	var SPECIAL_NAGS_LOOKUP = {
		 3: "!!",      // very good move
		 1: "!",       // good move
		 5: "!?",      // interesting move
		 6: "?!",      // questionable move
		 2: "?",       // bad move
		 4: "??",      // very bad move
		18: "+\u2212", // White has a decisive advantage
		16: "\u00b1",  // White has a moderate advantage
		14: "\u2a72",  // White has a slight advantage
		10: "=",       // equal position
		13: "\u221e",  // unclear position
		15: "\u2a71",  // Black has a slight advantage
		17: "\u2213",  // Black has a moderate advantage
		19: "\u2212+"  // Black has a decisive advantage
	};


	/**
	 * Return the annotation symbol (e.g. "+-", "!?") associated to a numeric NAG code.
	 *
	 * @private
	 *
	 * @param {number} nag Numeric NAG code.
	 * @returns {string} Human-readable NAG symbol.
	 *
	 * @memberof PgnWidget
	 */
	function formatNag(nag)
	{
		if(nag==null) return null;
		else if(SPECIAL_NAGS_LOOKUP[nag]==null) return '$' + nag;
		else return SPECIAL_NAGS_LOOKUP[nag];
	}


	/**
	 * Create a new DOM node to render the text commentary associated to the given PGN node.
	 * This function may return null if no commentary is associated to the PGN node.
	 *
	 * @private
	 *
	 * @param {(Pgn.Node|Pgn.Variation)} pgnNode Node or variation object containing the commentary to render.
	 * @param {ChessWidget.Attributes} options Default set of options for displaying inline diagrams.
	 * @returns {jQuery}
	 *
	 * @memberof PgnWidget
	 */
	function renderCommentary(pgnNode, options)
	{
		// Nothing to do if no commentary is defined on the current PGN node.
		if(pgnNode.commentary=='') {
			return null;
		}

		// Create the returned object, and parse the commentary string
		var retVal = $(pgnNode.isLongCommentary ?
			'<div class="PgnWidget-longCommentary">' + pgnNode.commentary + '</div>' :
			'<span class="PgnWidget-commentary">' + pgnNode.commentary + '</span>'
		);

		// Render diagrams where requested
		$('.PgnWidget-anchor-diagram', retVal).each(function(index, e)
		{
			// Try to parse the content of the node as a JSON string.
			var currentOptions = options;
			try {
				currentOptions = $.extend({}, options, $.parseJSON('{' + $(e).text() + '}'));
			}
			catch(err) {}

			// Render the diagram with the proper options
			$(e).replaceWith(ChessWidget.make(pgnNode.position(), currentOptions));
		});

		// Return the result
		return retVal;
	}


	/**
	 * Create a new DOM node to render a variation taken from a PGN tree. This function
	 * is recursive, and never returns null.
	 *
	 * @private
	 *
	 * @param {Pgn.Variation} pgnVariation PGN variation object to render.
	 * @param {number} depth Depth of the PGN node within its belonging PGN tree (0 for the main variation, 1 for a direct sub-variation, etc...)
	 * @param {ChessWidget.Attributes} inlineOptions Default set of options for displaying inline diagrams.
	 * @param {ChessWidget.Attributes} navOptions Set of options to use for the navigation frame.
	 * @returns {jQuery}
	 *
	 * @memberof PgnWidget
	 */
	function renderVariation(pgnVariation, depth, inlineOptions, navOptions)
	{
		// Allocate the returned DOM node
		var retVal = $(pgnVariation.isLongVariation() ?
			'<div class="PgnWidget-longVariation"></div>' :
			'<span class="PgnWidget-shortVariation"></span>'
		);
		retVal.addClass('PgnWidget-variation-' + (depth==0 ? 'main' : 'sub'));

		// The variation may start with an initial commentary.
		var initialCommentary = renderCommentary(pgnVariation, inlineOptions);
		if(initialCommentary!=null) {
			if(pgnVariation.isLongCommentary) { // Long commentaries do not belong to any move group.
				retVal.append(initialCommentary);
				initialCommentary = null;
			}
		}

		// State variables
		var moveGroup = $('<span class="PgnWidget-moveGroup"></span>').appendTo(retVal);
		var prevMove  = null;
		if(initialCommentary!=null) {
			moveGroup.append(initialCommentary);
		}

		// Append a fake move at the beginning of the main variation,
		// so that it will be possible to display the starting position
		// in the navigation frame.
		if(depth==0) {
			var move = $(
				'<span class="PgnWidget-move PgnWidget-invisible">' + text.initialPosition + '</span>'
			).appendTo(moveGroup);
			move.data('position'  , pgnVariation.position());
			move.data('navOptions', navOptions);
			move.click(function() { showNavigationFrame($(this)); });
			prevMove = move;
		}

		// Visit all the PGN nodes (one node per move) within the variation
		var forcePrintMoveNumber = true;
		var pgnNode              = pgnVariation.first();
		while(pgnNode!=null)
		{
			// Create the DOM node that will contains the basic move informations
			// (i.e. move number, notation, NAGs)
			var move = $('<span class="PgnWidget-move"></span>').appendTo(moveGroup);
			move.data('position'  , pgnNode.position());
			move.data('navOptions', navOptions);
			move.click(function() { showNavigationFrame($(this)); });

			// Link to the previous move, if any
			if(prevMove!=null) {
				prevMove.data('nextMove', move    );
				move    .data('prevMove', prevMove);
			}
			prevMove = move;

			// Write the move number
			var moveNumber = $(
				'<span class="PgnWidget-move-number">' +
					pgnNode.fullMoveNumber() + (pgnNode.moveColor()=='w' ? '.' : '\u2026') +
				'</span>'
			).appendTo(move);
			if(!(forcePrintMoveNumber || pgnNode.moveColor()=='w')) {
				moveNumber.addClass('PgnWidget-invisible');
			}

			// Write the notation
			move.append(formatMoveNotation(pgnNode.move()));

			// Write the NAGs (if any)
			for(var k=0; k<pgnNode.nags.length; ++k) {
				move.append(' ' + formatNag(pgnNode.nags[k]));
			}

			// Write the commentary (if any)
			var commentary = renderCommentary(pgnNode, inlineOptions);
			if(commentary!=null) {
				if(pgnNode.isLongCommentary) { // Long commentaries do not belong to any move group.
					retVal.append(commentary);
					moveGroup = $('<span class="PgnWidget-moveGroup"></span>').appendTo(retVal);
				}
				else {
					moveGroup.append(commentary);
				}
			}

			// Sub-variations starting from the current point in PGN tree
			if(pgnNode.variations()>0) {
				var variationParent = pgnNode.areLongVariations ? retVal : moveGroup;
				for(var k=0; k<pgnNode.variations(); ++k) {
					variationParent.append(renderVariation(pgnNode.variation(k), depth+1, inlineOptions, navOptions));
				}
				if(pgnNode.areLongVariations) {
					moveGroup = $('<span class="PgnWidget-moveGroup"></span>').appendTo(retVal);
				}
			}

			// Back to the current variation
			forcePrintMoveNumber = (pgnNode.commentary!='' || pgnNode.variations()>0);
			pgnNode = pgnNode.next();
		}

		// Return the result
		return retVal;
	}


	/**
	 * Replace the content of a DOM node with a text value from a PGN field. Here
	 * is an example, for the event field (i.e. fieldName=='Event'):
	 *
	 * Before substitution:
	 * <div class="PgnWidget-field-Event">
	 *   Event: <span class="PgnWidget-anchor-Event"></span>
	 * </div>
	 *
	 * After substitution, if the event field is defined:
	 * <div class="PgnWidget-field-Event">
	 *   Event: <span class="PgnWidget-value-Event">Aeroflot Open</span>
	 * </div>
	 *
	 * After substitution, if the event field is undefined or null:
	 * <div class="PgnWidget-field-Event PgnWidget-invisible">
	 *   Event: <span class="PgnWidget-value-Event"></span>
	 * </div>
	 *
	 * @private
	 *
	 * @param {jQuery} parentNode
	 *
	 * Each child of this node having a class attribute set to "PgnWidget-field-[fieldName]"
	 * will be targeted by the substitution.
	 *
	 * @param {string} fieldName Name of the PGN field to process.
	 * @param {Pgn.Item} pgnItem Contain the information to display.
	 * @param {function} [formatFunc=null]
	 *
	 * If provided, the content of the current PGN field will be filtered by this function,
	 * and the returned value will be used as substitution text. Otherwise, the content
	 * of the PGN field is used "as-is".
	 *
	 * @memberof PgnWidget
	 */
	function substituteSimpleField(parentNode, fieldName, pgnItem, formatFunc)
	{
		// Fields to target
		var fields = $('.PgnWidget-field-' + fieldName, parentNode);

		// Determine the text that is to be inserted.
		var value = pgnItem.header(fieldName);
		if(formatFunc!=null) {
			value = formatFunc(value);
		}

		// Hide the field if no value is available.
		if(value==null) {
			fields.addClass('PgnWidget-invisible');
			value = '';
		}

		// Process each anchor node
		var anchors = $('.PgnWidget-anchor-' + fieldName, fields);
		anchors.append($('<span>' + value + '</span>'));
		anchors.addClass   ('PgnWidget-value-'  + fieldName);
		anchors.removeClass('PgnWidget-anchor-' + fieldName);
	}


	/**
	 * Substitution method for the special replacement tokens fullNameWhite and
	 * fullNameBlack. Example:
	 *
	 * Before substitution:
	 * <div class="PgnWidget-field-fullNameWhite">
	 *   White player: <span class="PgnWidget-anchor-fullNameWhite"></span>
	 * </div>
	 *
	 * After substitution:
	 * <div class="PgnWidget-field-fullNameWhite">
	 *   White player: <span class="PgnWidget-value-fullNameWhite">
	 *     <span class="PgnWidget-subfield-playerName">Kasparov, Garry</span>
	 *     <span class="PgnWidget-subfield-groupTitleElo">
	 *       <span class="PgnWidget-subfield-title">GM</span>
	 *       <span class="PgnWidget-subfield-elo">2812</span>
	 *     </span>
	 *   </span>
	 * </div>
	 *
	 * @private
	 *
	 * @param {jQuery} parentNode
	 *
	 * Each child of this node having a class attribute set to "PgnWidget-field-fullNameColor"
	 * will be targeted by the substitution.
	 *
	 * @param {string} color Either 'w' or 'b'.
	 * @param {Pgn.Item} pgnItem Contain the information to display.
	 *
	 * @memberof PgnWidget
	 */
	function substituteFullName(parentNode, color, pgnItem)
	{
		// Fields to target
		color = (color=='w') ? 'White' : 'Black';
		var fields = $('.PgnWidget-field-fullName' + color, parentNode);

		// Hide the field if no name is available
		var name = pgnItem.header(color);
		if(name==null) {
			fields.addClass('PgnWidget-invisible');
		}

		// Title + elo
		var title = pgnItem.header(color + 'Title');
		var elo   = pgnItem.header(color + 'Elo'  );
		var titleDefined = (title!=null && title!='-');
		var eloDefined   = (elo  !=null && elo  !='?');

		// Process each anchor node
		var anchors = $('.PgnWidget-anchor-fullName' + color, fields);
		anchors.append($('<span class="PgnWidget-subfield-playerName">' + name + '</span>'));
		if(titleDefined || eloDefined) {
			var group = $('<span class="PgnWidget-subfield-groupTitleElo"></span>').appendTo(anchors);
			if(titleDefined) {
				group.append($('<span class="PgnWidget-subfield-title">' + title + '</span>'));
			}
			if(titleDefined && eloDefined) {
				group.append(' ');
			}
			if(eloDefined) {
				group.append($('<span class="PgnWidget-subfield-elo">' + elo + '</span>'));
			}
		}
		anchors.addClass   ('PgnWidget-value-fullName'  + color);
		anchors.removeClass('PgnWidget-anchor-fullName' + color);
	}


	/**
	 * Substitution method for the special replacement token moves (which stands
	 * for the move tree associated to a PGN item). Example:
	 *
	 * Before substitution:
	 * <div class="PgnWidget-field-moves">
	 *   <span class="PgnWidget-anchor-moves"></span>
	 * </div>
	 *
	 * After substitution:
	 * <div class="PgnWidget-field-moves">
	 *   <span class="PgnWidget-value-moves">1.e4 e5</span>
	 * </div>
	 *
	 * @private
	 *
	 * @param {jQuery} parentNode
	 *
	 * Each child of this node having a class attribute set to "PgnWidget-field-moves"
	 * will be targeted by the substitution.
	 *
	 * @param {Pgn.Item} pgnItem Contain the information to display.
	 * @param {ChessWidget.Attributes} inlineOptions Default set of options for displaying inline diagrams.
	 * @param {ChessWidget.Attributes} navOptions Set of options to use for the navigation frame.
	 *
	 * @memberof PgnWidget
	 */
	function substituteMoves(parentNode, pgnItem, inlineOptions, navOptions)
	{
		// Fields to target
		var fields = $('.PgnWidget-field-moves', parentNode);

		// Hide the field if no move tree is available
		if(pgnItem.mainVariation().first()==null && pgnItem.mainVariation().commentary=='') {
			fields.addClass('PgnWidget-invisible');
		}

		// Process each anchor node
		var anchors = $('.PgnWidget-anchor-moves', fields);
		anchors.append(renderVariation(pgnItem.mainVariation(), 0, inlineOptions, navOptions));
		anchors.addClass   ('PgnWidget-value-moves' );
		anchors.removeClass('PgnWidget-anchor-moves');
	}


	/**
	 * Display the error message resulting from a PGN parsing into the given DOM node.
	 * All the existing content of this node is cleared.
	 *
	 * @private
	 *
	 * @param {Pgn.ParsingException} error
	 * @param {jQuery} targetNode
	 *
	 * @memberof PgnWidget
	 */
	function displayErrorMessage(error, targetNode)
	{
		// Prepare the target node.
		targetNode.empty();
		targetNode.addClass('PgnWidget-error');
		$('<div class="PgnWidget-error-title">Error while analysing a PGN string.</div>').appendTo(targetNode);

		// Display the error message.
		if(!(error.message==null || error.message.length==0)) {
			$('<div class="PgnWidget-error-message">' + error.message + '</div>').appendTo(targetNode);
		}

		// Display where the error occurred.
		if(error.pos>=0) {
			var at = $('<div class="PgnWidget-error-at"></div>').appendTo(targetNode);

			// Special case: error at the end of the string
			if(error.pos>=error.pgnString.length) {
				at.append('Occurred at the end of string.');
			}

			// Otherwise, extract a sub-string of the PGN source around the position where the parsing fails.
			else {
				at.append('Occurred at position ' + error.pos + ':');

				// p1 => begin of the extracted sub-string
				var p1 = error.pos - 10;
				var e1 = '...';
				if(p1<=0) {
					p1 = 0;
					e1 = '';
				}

				// p2 => end of the extracted sub-string (actually one character after)
				var p2 = error.pos + 40;
				var e2 = '...';
				if(p2>=error.pgnString.length) {
					p2 = error.pgnString.length;
					e2 = '';
				}

				// Extract the sub-string around the position where the parsing fails.
				var text = e1 + error.pgnString.substr(p1, p2-p1) + e2;
				text = text.replace(/\n|\t/g, ' ');
				text += '\n' + Array(1 + e1.length + (error.pos-p1)).join(' ') + '^^^';
				$('<div class="PgnWidget-error-at-code">' + text + '</div>').appendTo(at);
			}
		}
	}


	/**
	 * Fill a DOM node with the information contained in a PGN item object.
	 *
	 * @param {(Pgn.Item|string)} pgn PGN data to represent.
	 *
	 * If the argument is a string, the function will try to parse it as a
	 * PGN-formatted string. If the parsing fails, or if the string contains no PGN item,
	 * the targeted DOM node is cleared, and an error message is displayed instead.
	 *
	 * @param {jQuery} targetNode
	 * @param {ChessWidget.Attributes} [inlineOptions=null] Default set of options for displaying inline diagrams.
	 * @param {ChessWidget.Attributes} [navOptions=null] Set of options to use for the navigation frame.
	 * @returns {boolean} False if the parsing of the PGN string fails, true otherwise.
	 *
	 * @memberof PgnWidget
	 */
	function makeAt(pgn, targetNode, inlineOptions, navOptions)
	{
		// Default options
		if(inlineOptions==null) {
			inlineOptions = null;
		}
		if(navOptions==null) {
			navOptions = null;
		}

		// PGN parsing
		if(typeof(pgn)=='string') {
			try {
				var items = Pgn.parse(pgn);
				if(items.length==0) {
					throw new Pgn.ParsingException(pgn, null, 'Unexpected empty PGN data.');
				}
				pgn = items[0];
			}

			// Catch the parsing errors
			catch(error) {
				if(error instanceof Pgn.ParsingException) {
					displayErrorMessage(error, targetNode);
					return false;
				}
				else { // unknown exception are re-thrown
					throw error;
				}
			}
		}

		// Create the navigation frame if necessary
		makeNavigationFrame(targetNode);

		// Substitution
		substituteSimpleField(targetNode, 'Event'    , pgn);
		substituteSimpleField(targetNode, 'Site'     , pgn);
		substituteSimpleField(targetNode, 'Date'     , pgn, formatDate );
		substituteSimpleField(targetNode, 'Round'    , pgn, formatRound);
		substituteSimpleField(targetNode, 'White'    , pgn);
		substituteSimpleField(targetNode, 'Black'    , pgn);
		substituteSimpleField(targetNode, 'Result'   , pgn, formatResult);
		substituteSimpleField(targetNode, 'Annotator', pgn);
		substituteFullName(targetNode, 'w', pgn);
		substituteFullName(targetNode, 'b', pgn);
		substituteMoves(targetNode, pgn, inlineOptions, navOptions);

		// Indicate that the parsing succeeded.
		return true;
	}


	/**
	 * Information relative to the navigation frame.
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	var navFrameInfo =
	{
		squareSize  : null,
		initialState: null
	};


	/**
	 * Create the navigation frame, if it does not exist yet. The frame is
	 * appended as a child of the given DOM node.
	 *
	 * @private
	 *
	 * @param {jQuery} parentNode
	 *
	 * @memberof PgnWidget
	 */
	function makeNavigationFrame(parentNode)
	{
		if($('#PgnWidget-navigation-frame').length!=0) {
			return;
		}

		// Structure of the navigation frame
		$(
			'<div id="PgnWidget-navigation-frame" class="PgnWidget-invisible">' +
				'<div id="PgnWidget-navigation-content"></div>' +
				'<div id="PgnWidget-navigation-buttons">' +
					'<button id="PgnWidget-navigation-button-frst">&lt;&lt;</button>' +
					'<button id="PgnWidget-navigation-button-prev">&lt;</button>' +
					'<button id="PgnWidget-navigation-button-next">&gt;</button>' +
					'<button id="PgnWidget-navigation-button-last">&gt;&gt;</button>' +
				'</div>' +
			'</div>'
		).appendTo(parentNode);

		// Widgetization
		$(document).ready(function()
		{
			// Remove the temporary "invisible" flag.
			$('#PgnWidget-navigation-frame').removeClass('PgnWidget-invisible');

			// Create the dialog structure
			$('#PgnWidget-navigation-frame').dialog({
				/* Hack to keep the dialog draggable after the page has being scrolled. */
				create     : function(event, ui) { $(event.target).parent().css('position', 'fixed'); },
				resizeStart: function(event, ui) { $(event.target).parent().css('position', 'fixed'); },
				resizeStop : function(event, ui) { $(event.target).parent().css('position', 'fixed'); },
				/* End of hack */
				autoOpen   : false,
				dialogClass: 'wp-dialog',
				width      : 'auto',
				resize     : function(event, ui) { onResize(ui); },
				close      : function(event, ui) { unselectMove(); }
			});

			// Create the buttons
			$('#PgnWidget-navigation-button-frst').button().click(function() { goFrstMove(); });
			$('#PgnWidget-navigation-button-prev').button().click(function() { goPrevMove(); });
			$('#PgnWidget-navigation-button-next').button().click(function() { goNextMove(); });
			$('#PgnWidget-navigation-button-last').button().click(function() { goLastMove(); });
		});
	}


	/**
	 * Handler for the navigation frame 'resize' event.
	 *
	 * @param {object} ui
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function onResize(ui)
	{
		// Save the reference state
		if(navFrameInfo.initialState==null) {
			navFrameInfo.initialState = {
				squareSize: navFrameInfo.squareSize,
				height    : ui.originalSize.height,
				width     : ui.originalSize.width
			};
		}

		// Determine the new square-size
		function newSqSz(deltaPerSquare)
		{
			var delta = Math.floor(deltaPerSquare / ChessWidget.STEP_SQUARE_SIZE) * ChessWidget.STEP_SQUARE_SIZE;
			return Math.min(Math.max(navFrameInfo.initialState.squareSize + delta,
				ChessWidget.MINIMUM_SQUARE_SIZE), ChessWidget.MAXIMUM_SQUARE_SIZE);
		}
		var newSqSzForH   = newSqSz((ui.size.height-navFrameInfo.initialState.height) / 8);
		var newSqSzForW   = newSqSz((ui.size.width -navFrameInfo.initialState.width ) / 9);
		var newSquareSize = Math.min(newSqSzForH, newSqSzForW);

		// Update the chessboard widget if necessary
		if(newSquareSize!=navFrameInfo.squareSize) {
			navFrameInfo.squareSize = newSquareSize;
			refreshNavigationFrameWidget($('#PgnWidget-selected-move'));
		}
	}


	/**
	 * Show the navigation frame if not visible yet, and update the diagram in this
	 * frame with the position corresponding to the move that is referred by the
	 * given DOM node. By the way, this node must have class 'PgnWidget-move',
	 * otherwise nothing happens.
	 *
	 * @private
	 *
	 * @param {jQuery} domNode
	 *
	 * @memberof PgnWidget
	 */
	function showNavigationFrame(domNode)
	{
		// Nothing to do if no node is provided or if the move is already selected.
		if(domNode==null || domNode.attr('id')=='PgnWidget-selected-move') {
			return;
		}

		// Mark the current move as selected
		selectMove(domNode);

		// Determine the options to use to render the chessboard widget
		if(navFrameInfo.squareSize==null) {
			navFrameInfo.squareSize = ChessWidget.validateSquareSize(domNode.data('navOptions').squareSize);
		}

		// Fill the miniboard in the navigation frame
		refreshNavigationFrameWidget(domNode);

		// Make the navigation frame visible
		var navFrame = $('#PgnWidget-navigation-frame');
		navFrame.dialog('option', 'title', domNode.text());
		if(!navFrame.dialog('isOpen')) {
			navFrame.dialog('option', 'position', { my: 'center', at: 'center', of: window });
		}
		navFrame.dialog('open');
	}


	/**
	 * Refresh the chessboard widget in the navigation frame.
	 *
	 * @param {jQuery} selectedMove
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function refreshNavigationFrameWidget(selectedMove)
	{
		var position = selectedMove.data('position'  );
		var opts     = $.extend({}, selectedMove.data('navOptions'), {squareSize: navFrameInfo.squareSize});
		$('#PgnWidget-navigation-content').empty().append(ChessWidget.make(position, opts));
	}


	/**
	 * Return a contrasted color.
	 *
	 * @private
	 *
	 * @param {String} colorString Color specification as mentioned in a CSS property.
	 * @returns {string}
	 *
	 * @memberof PgnWidget
	 */
	function contrastedColor(colorString)
	{
		// Parsing
		var color = $.Color(colorString); // Require the jQuery-color plugin

		// Two cases based on the value of the lightness component
		if(color.lightness()>0.5) {
			return 'black';
		}
		else {
			return 'white';
		}
	}


	/**
	 * Make the given move appear as selected.
	 *
	 * @private
	 *
	 * @param {jQuery} domNode Node to select (it is supposed to be have class 'PgnWidget-move').
	 *
	 * @memberof PgnWidget
	 */
	function selectMove(domNode)
	{
		unselectMove();
		domNode.attr('id', 'PgnWidget-selected-move');
		var color = domNode.css('color');
		domNode.css('background-color', color);
		domNode.css('color', contrastedColor(color));
	}


	/**
	 * Unselect the selected move, if any.
	 *
	 * @private
	 * @memberof PgnWidget
	 */
	function unselectMove()
	{
		var selectedMove = $('#PgnWidget-selected-move');
		selectedMove.attr('id'   , null);
		selectedMove.attr('style', null);
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


	// Return the module object
	return {
		text  : text  ,
		makeAt: makeAt
	};

})(Chess, Pgn, ChessWidget, jQuery);
