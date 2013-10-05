
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
 */
var PgnWidget = (function(Chess, Pgn, ChessWidget, $)
{
	/**
	 * The behavior of the module can be modified by changing the properties of
	 * this object.
	 *
	 * @public
	 */
	var option =
	{
		/**
		 * Month names
		 */
		monthName: {
			 1: "january"  ,
			 2: "february" ,
			 3: "march"    ,
			 4: "april"    ,
			 5: "may"      ,
			 6: "june"     ,
			 7: "july"     ,
			 8: "august"   ,
			 9: "september",
			10: "october"  ,
			11: "november" ,
			12: "december"
		},

		/**
		 * Piece symbols
		 */
		pieceSymbol: {
			"K": "\u265a",
			"Q": "\u265b",
			"R": "\u265c",
			"B": "\u265d",
			"N": "\u265e",
			"P": "\u265f"
		},

		/**
		 * Nags
		 */
		nag: {
			 1: "!",       // good move
			 2: "?",       // poor move, mistake
			 3: "!!",      // very good move
			 4: "??",      // very poor move, blunder
			 5: "!?",      // speculative or interesting move
			 6: "?!",      // questionable or dubious move
			10: "=",       // equal position
			13: "\u221e",  // unclear position
			14: "+=",      // White has a slight advantage
			15: "=+",      // Black has a slight advantage
			16: "\u00b1",  // White has a moderate advantage
			17: "\u2213",  // Black has a moderate advantage
			18: "+\u2212", // White has a decisive advantage
			19: "\u2212+"  // Black has a decisive advantage
		}
	};

	/**
	 * Various strings used by the library and printed out to the screen at some
	 * point. They are made public so that they can be localized.
	 *
	 * @public
	 */
	var text =
	{
		initialPosition: "Initial position"
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
		// Null input
		if(date==null) {
			return null;
		}

		// Case "2013.05.20" -> return "20 may 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.([0-9]{2})/)) {
			var month = parseInt(RegExp.$2);
			if(month>=1 && month<=12)
				return parseInt(RegExp.$3) + " " + option.monthName[month] + " " + RegExp.$1;
			else
				return RegExp.$1;
		}

		// Case "2013.05.??" -> return "may 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.\?\?/)) {
			var month = parseInt(RegExp.$2);
			if(month>=1 && month<=12)
				return option.monthName[month] + " " + RegExp.$1;
			else
				return RegExp.$1;
		}

		// Case "2013.??.??" -> return "2013"
		else if(date.match(/([0-9]{4})\.\?\?\.\?\?/)) {
			return RegExp.$1;
		}

		// Case "????.??.??" -> no date is defined
		else if(date=="????.??.??") {
			return null;
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
		return (round==null || round=="?") ? null : round;
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
					retVal += option.pieceSymbol[c];
				}
				else {
					retVal += c;
				}
			}
			return retVal;
		}
	}

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
		if(nag==null) {
			return null;
		}
		else if(option.nag[nag]==null)
			return "$" + nag;
		else
			return option.nag[nag];
	}

	/**
	 * Return a new DOM node holding information about the one of the players.
	 *
	 * @private
	 *
	 * @param {Pgn.Item} pgnItem The player information are read for this Pgn.Item object.
	 * @param {string} color Either `'w'` or `'b'`.
	 * @returns {jQuery}
	 *
	 * @memberof PgnWidget
	 */
	/*function renderPlayerInfo(pgnItem, color)
	{
	///TODO: remove
	}*/

	/**
	 * Create a new DOM node to render the text commentary associated to the given PGN node.
	 * This function may return null if no commentary is associated to the PGN node.
	 *
	 * @private
	 *
	 * @param {(Pgn.Node|Pgn.Variation)} pgnNode Node or variation object containing the commentary to render.
	 * @param {number} depth Depth of the PGN node within its belonging PGN tree (0 for the main variation, 1 for a direct sub-variation, etc...)
	 * @param {ChessWidget.Options} options Default set of options for displaying inline diagrams.
	 * @returns {jQuery}
	 *
	 * @memberof PgnWidget
	 */
	function renderCommentary(pgnNode, depth, options)
	{
		// Nothing to do if no commentary is defined on the current PGN node.
		if(pgnNode.commentary=='') {
			return null;
		}

		// Create the returned object, and parse the commentary string
		var retVal = $('<span class="PgnWidget-commentary">' + pgnNode.commentary + '</span>');

		// Render diagrams where requested
		$(".PgnWidget-anchor-diagram", retVal).each(function(index, e) {
			$(e).replaceWith(ChessWidget.make(pgnNode.position(), options));
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
	 * @param {ChessWidget.Options} options Default set of options for displaying inline diagrams.
	 * @returns {jQuery}
	 *
	 * @memberof PgnWidget
	 */
	function renderVariation(pgnVariation, depth, options)
	{
		// Allocate the returned DOM node
		var retVal = $('<span class="PgnWidget-variation-'+(depth==0 ? 'main' : 'sub')+'"></span>');

		// Append a fake move at the beginning of the main variation,
		// so that it will be possible to display the starting position
		// in the navigation frame.
		if(depth==0) {
			var move = $('<span class="PgnWidget-move PgnWidget-invisible">' + text.initialPosition + '</span>');
			move.data('position', pgnVariation.position());
			move.click(function() { showNavigationFrame($(this)); });
			retVal.append(move);
		}

		// The variation may start with an initial commentary.
		retVal.append(renderCommentary(pgnVariation, depth, options));

		// Visit all the PGN nodes (one node per move) within the variation
		var forcePrintMoveNumber = true;
		var pgnNode              = pgnVariation.first();
		while(currentPgnNode!=null)
		{
			// Create the DOM node that will contains the basic move informations
			// (i.e. move number, notation, NAGs)
			var move = $('<span class="PgnWidget-move"></span>').appendTo(retVal);
			move.data("position", pgnNode.position());
			move.click(function() { showNavigationFrame($(this)); });

			// Write the move number
			var moveNumber = $(
				'<span class="PgnWidget-move-number">' +
					pgnNode.fullMoveNumber() + (pgnNode.moveColor()=='w' ? "." : "\u2026") +
				'</span>'
			).appendTo(move);
			if(!(forcePrintMoveNumber || pgnNode.moveColor()=='w')) {
				moveNumber.addClass("PgnWidget-invisible");
			}

			// Write the notation
			move.append(formatMoveNotation(pgnNode.move()));

			// Write the NAGs (if any)
			for(var k=0; k<pgnNode.nags.length; ++k) {
				move.append(' ' + formatNag(pgnNode.nags[k]));
			}

			// Write the commentary (if any)
			retVal.append(renderCommentary(pgnNode, depth, options));

			// Sub-variations starting from the current point in PGN tree
			for(var k=0; k<pgnNode.variations(); ++k) {
				retVal.append(renderVariation(pgnNode.variation(k), depth+1, options));
			}

			// Back to the current variation
			forcePrintMoveNumber = (pgnNode.commentary!='' || pgnNode.variations()>0);
			currentPgnNode = currentPgnNode.next;
		}

		// Return the result
		return retVal;
	}

	/**
	 * Create a new DOM node to render the main variation (with its associated sub-variation if any).
	 *
	 * @private
	 *
	 * @param {Pgn.Item} pgnItem PGN item object whose associated move tree should be rendered.
	 * @param {ChessWidget.Options} options Default set of options for displaying inline diagrams.
	 * @returns {jQuery}
	 *
	 * @memberof PgnWidget
	 */
	function renderMoves(pgnItem, options)
	{
		return renderVariation(pgnItem.mainVariation(), 0, options);
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
		$('.PgnWidget-field-' + fieldName, parentNode).each(function(index, e)
		{
			// Determine the text that is to be inserted.
			var value = pgnItem.header(fieldName);
			if(formatFunc!=null) {
				value = formatFunc(value);
			}

			// Hide the field if no value is available.
			if(value==null) {
				e.addClass('PgnWidget-invisible');
				value = '';
			}

			// Process each anchor node
			var anchors = $('.PgnWidget-anchor-' + fieldName, e);
			anchors.text(value);
			anchors.addClass   ('PgnWidget-value-'  + fieldName);
			anchors.removeClass('PgnWidget-anchor-' + fieldName);
		});
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
		color = (color=='w') ? 'White' : 'Black';
		$('.PgnWidget-field-fullName' + color, parentNode).each(function(index, e)
		{
			// Hide the field if no name is available
			var name = pgnItem.header(color);
			if(name==null) {
				e.addClass('PgnWidget-invisible');
			}

			// Title + elo
			var title = pgnItem.header(color + 'Title');
			var elo   = pgnItem.header(color + 'Elo'  );
			var titleDefined = (title!=null && title!="-");
			var eloDefined   = (elo  !=null && elo  !="?");

			// Process each anchor node
			var anchors = $('.PgnWidget-anchor-fullName' + color, e);
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
		});
	}
	/*

		// For all the replacement fields...
		var fieldNodes = parentNode.getElementsByClassName("jsChessLib-field-fullName" + nameField);
		for(var k=0; k<fieldNodes.length; ++k) {
			var fieldNode = fieldNodes[k];

			// Hide the field if no name is available
			if(pgnItem[nameField]==null) {
				fieldNode.classList.add("jsChessLib-invisible");
			}

			// Process each anchor node
			var anchorNodes = fieldNode.getElementsByClassName("jsChessLib-anchor-fullName" + nameField);
			for(var l=0; l<anchorNodes.length; ++l) {
				var anchorNode = anchorNodes[l];
				var valueNode  = renderPlayerInfo(pgnItem, color);
				anchorNode.parentNode.replaceChild(valueNode, anchorNode);
			}
		}


		// Returned node
		color = (color=='w') ? 'White' : 'Black';
		var retVal = $('<span class="PgnWidget-value-fullName'+color+'"></span>');

		// Player name sub-field
		retVal.append($('<span class="PgnWidget-subfield-playerName">' + pgnItem.header(color) + '</span>'));

		// Group title + elo
		var title = pgnItem.header(color + 'Title');
		var elo   = pgnItem.header(color + 'Elo'  );
		var titleDefined = (title!=null && title!="-");
		var eloDefined   = (elo  !=null && elo  !="?");
		if(titleDefined || eloDefined) {
			var groupTitleEloSubField = $('<span class="PgnWidget-subfield-groupTitleElo"></span>');
			retVal.append(groupTitleEloSubField);

			// Title sub-field
			if(titleDefined) {
				groupTitleEloSubField.append($('<span class="PgnWidget-subfield-title">' + title + '</span>'));
			}

			// Separator
			if(titleDefined && eloDefined) {
				groupTitleEloSubField.append(' ');
			}

			// Elo sub-field
			if(eloDefined) {
				groupTitleEloSubField.append($('<span class="PgnWidget-subfield-elo">' + elo + '</span>'));
			}
		}

		// Return the result
		return retVal;
	}*/

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
	 * @param {ChessWidget.Options} options Default set of options for displaying inline diagrams.
	 *
	 * @memberof PgnWidget
	 */
	function substituteMoves(parentNode, pgnItem, options)
	{
		$('.PgnWidget-field-moves', parentNode).each(function(index, e)
		{
			// Hide the field if no move tree is available
			if(pgnItem.mainVariation().first()==null && pgnItem.mainVariation().commentary=='') {
				e.addClass('PgnWidget-invisible');
			}

			// Process each anchor node
			var anchors = $('.PgnWidget-anchor-moves' + color, e);
			anchors.append(renderMoves(pgnItem, options));
			anchors.addClass   ('PgnWidget-value-moves' );
			anchors.removeClass('PgnWidget-anchor-moves');
		});
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
		targetNode.empty();
		$('<span class="PgnWidget-error-title">Error while analysing a PGN string.</span>').appendTo(targetNode);
		$('<span class="PgnWidget-error-message">' + error.message + '</span>').appendTo(targetNode);
		if(error.pos>=0) {
			var at = $('<span class="PgnWidget-error-at"></span>').appendTo(targetNode);

			// Special case: error at the end of the string
			if(error.pos>=error.pgnString.length) {
				at.append('Occurred at the end of string.');
			}

			// Otherwise, extract a sub-string of the PGN source around the position where the parsing fails.
			else {
				at.append('Occurred at position ' + error.pos + ':');

				// p1 => begin of the extracted sub-string
				var p1 = pos - 20;
				var e1 = '...';
				if(p1<=0) {
					p1 = 0;
					e1 = '';
				}

				// p2 => end of the extracted sub-string (actually one character after)
				var p2 = pos + 40;
				var e2 = '...';
				if(p2>=error.pgnString.length) {
					p2 = error.pgnString.length;
					e2 = '';
				}

				// Extract the sub-string around the position where the parsing fails.
				var text = e1 + error.pgnString.substr(p1, p2-p1) + e2;
				text.replace(/\n/g, ' ');
				text += '\n' + Array(1 + e1.length + (pos-p1)).join(' ') + '^^^';
				$('<pre>' + text + '</pre>').appendTo(at);
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
	 * @param {ChessWidget.Options} [options=null] Default set of options for displaying inline diagrams.
	 *
	 * @memberof PgnWidget
	 */
	function makeAt(pgn, targetNode, options)
	{
		// Default options
		if(options==null) {
			options = new Options();
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
				}
				else { // unkown exception are re-thrown
					throw error;
				}
			}
		}

		// Create the navigation frame if necessary
		makeNavigationFrame(targetNode);

		// Substitution
		substituteSimpleField(targetNode, "Event"    , pgn);
		substituteSimpleField(targetNode, "Site"     , pgn);
		substituteSimpleField(targetNode, "Date"     , pgn, formatDate );
		substituteSimpleField(targetNode, "Round"    , pgn, formatRound);
		substituteSimpleField(targetNode, "White"    , pgn);
		substituteSimpleField(targetNode, "Black"    , pgn);
		substituteSimpleField(targetNode, "Result"   , pgn);
		substituteSimpleField(targetNode, "Annotator", pgn);
		substituteFullName(targetNode, 'w', pgn);
		substituteFullName(targetNode, 'b', pgn);
		substituteMoves(targetNode, pgn, options);
	}

	/**
	 * Create the navigation frame, if it does not exist yet. The frame is
	 * appended as a child of the given DOM node.
	 *
	 * @private
	 * @param {Element} parentNode The newly created navigation frame will be
	 *        appended as a child of this node.
	 */
	function makeNavigationFrame(parentNode)
	{
		if($("jsChessLib-navigation-frame").length!=0) {
			return;
		}

		// Structure of the navigation frame
		$(
			'<div id="jsChessLib-navigation-frame">' +
				'<div id="jsChessLib-navigation-content"></div>' +
				'<div id="jsChessLib-navigation-buttons">' +
					'<button id="jsChessLib-navigation-button-frst">&lt;&lt;</button>' +
					'<button id="jsChessLib-navigation-button-prev">&lt;</button>' +
					'<button id="jsChessLib-navigation-button-next">&gt;</button>' +
					'<button id="jsChessLib-navigation-button-last">&gt;&gt;</button>' +
				'</div>' +
			'</div>'
		).appendTo(parentNode);

		// Widgetization
		$(document).ready(function()
		{
			// Create the dialog structure
			$("#jsChessLib-navigation-frame").dialog({
				/* Hack to keep the dialog draggable after the page has being scrolled. */
				create     : function(event, ui) { $(event.target).parent().css("position", "fixed"); },
				resizeStart: function(event, ui) { $(event.target).parent().css("position", "fixed"); },
				resizeStop : function(event, ui) { $(event.target).parent().css("position", "fixed"); },
				/* End of hack */
				autoOpen: false,
				width   : "auto",
				close   : function(event, ui) { unselectMove(); }
			});

			// Create the buttons
			$("#jsChessLib-navigation-button-frst").button().click(function() { goFrstMove(); });
			$("#jsChessLib-navigation-button-prev").button().click(function() { goPrevMove(); });
			$("#jsChessLib-navigation-button-next").button().click(function() { goNextMove(); });
			$("#jsChessLib-navigation-button-last").button().click(function() { goLastMove(); });
		});
	}

	/**
	 * Show the navigation frame if not visible yet, and update the diagram in this
	 * frame with the position corresponding to the move that is referred by the
	 * given DOM node. By the way, this node must have class 'jsChessLib-move',
	 * otherwise nothing happens.
	 *
	 * @private
	 * @param {jQuery} domNode Node whose position should be displayed.
	 *        This node is supposed to be tagged with the class 'jsChessLib-move'.
	 */
	function showNavigationFrame(domNode)
	{
		// Nothing to do if the move is already selected
		if(domNode.attr("id")=="jsChessLib-selected-move") {
			return;
		}

		// Retrieve the position corresponding to the current node
		var position = $(domNode).data("position");
		if(position==null) {
			$("#jsChessLib-navigation-frame").dialog("close");
			return;
		}

		// Mark the current move as selected
		selectMove(domNode);

		// Fill the miniboard in the navigation frame
		var navFrameContent = $("#jsChessLib-navigation-content");
		navFrameContent.empty();
		navFrameContent.append(renderPosition(position, option.navigationFrameSquareSize,
			option.navigationFrameShowCoordinates));

		// Make the navigation frame visible
		var navFrame = $("#jsChessLib-navigation-frame");
		navFrame.dialog("option", "title", domNode.text());
		if(!navFrame.dialog("isOpen")) {
			navFrame.dialog("option", "position", { my: "center", at: "center", of: window });
		}
		navFrame.dialog("open");
	}

	/**
	 * Return a contrasted color.
	 *
	 * @private
	 * @param {String} cssColorString Color specification as mentioned in a CSS property.
	 */
	function contrastColor(cssColorString)
	{
		// Parsing
		var color = $.Color(cssColorString); // Require the jQuery-color plugin

		// Two cases based on the value of the lightness component
		if(color.lightness()>0.5) {
			return "black";
		}
		else {
			return "white";
		}
	}

	/**
	 * Make the given move appear as selected.
	 *
	 * @private
	 * @param {jQuery} domNode Node to select. This node is supposed to be tagged
	 *        with the class 'jsChessLib-move'.
	 */
	function selectMove(domNode)
	{
		unselectMove();
		domNode.attr("id", "jsChessLib-selected-move");
		var color = domNode.css("color");
		domNode.css("background-color", color);
		domNode.css("color", contrastColor(color));
	}

	/**
	 * Unselect the selected move, if any.
	 *
	 * @private
	 */
	function unselectMove()
	{
		var selectedMove = $("#jsChessLib-selected-move");
		selectedMove.attr("id"   , null);
		selectedMove.attr("style", null);
	}

	/**
	 * Extract the list of direct children (the grandchildren are not considered)
	 * of the given DOM node that have the class 'jsChessLib-move'.
	 *
	 * @private
	 * @param   {Element} domNode Node to search in.
	 * @returns {Array} List of DOM nodes whose parent is 'domNode' and who are
	 *          tagged with the class 'jsChessLib-move'.
	 */
	function extractChildMoves(domNode)
	{
		var retVal = new Array();
		for(var currentNode=domNode.firstChild; currentNode!=null; currentNode=currentNode.nextSibling) {
			if(currentNode.classList.contains("jsChessLib-move")) {
				retVal.push(currentNode);
			}
		}
		return retVal;
	}

	/**
	 * Go to the first move of the current variation.
	 *
	 * @private
	 */
	function goFrstMove()
	{
		// Retrieve node corresponding to the current move
		var currentSelectedNode = document.getElementById("jsChessLib-selected-move");
		if(currentSelectedNode==null) {
			return;
		}

		// All the move nodes in with the same parent
		var moveNodes = extractChildMoves(currentSelectedNode.parentNode);
		if(moveNodes.length>0) {
			showNavigationFrame($(moveNodes[0]));
		}
	}

	/**
	 * Go to the previous move of the current variation.
	 *
	 * @private
	 */
	function goPrevMove()
	{
		// Retrieve node corresponding to the current move
		var currentSelectedNode = document.getElementById("jsChessLib-selected-move");
		if(currentSelectedNode==null) {
			return;
		}

		// All the move nodes in with the same parent
		var moveNodes = extractChildMoves(currentSelectedNode.parentNode);
		for(var k=0; k<moveNodes.length; ++k) {
			if(moveNodes[k]==currentSelectedNode) {
				if(k>0) {
					showNavigationFrame($(moveNodes[k-1]));
				}
				return;
			}
		}
	}

	/**
	 * Go to the next move of the current variation.
	 *
	 * @private
	 */
	function goNextMove()
	{
		// Retrieve node corresponding to the current move
		var currentSelectedNode = document.getElementById("jsChessLib-selected-move");
		if(currentSelectedNode==null) {
			return;
		}

		// All the move nodes in with the same parent
		var moveNodes = extractChildMoves(currentSelectedNode.parentNode);
		for(var k=0; k<moveNodes.length; ++k) {
			if(moveNodes[k]==currentSelectedNode) {
				if(k<moveNodes.length-1) {
					showNavigationFrame($(moveNodes[k+1]));
				}
				return;
			}
		}
	}

	/**
	 * Go to the last move of the current variation.
	 *
	 * @private
	 */
	function goLastMove()
	{
		// Retrieve node corresponding to the current move
		var currentSelectedNode = document.getElementById("jsChessLib-selected-move");
		if(currentSelectedNode==null) {
			return;
		}

		// All the move nodes in with the same parent
		var moveNodes = extractChildMoves(currentSelectedNode.parentNode);
		if(moveNodes.length>0) {
			showNavigationFrame($(moveNodes[moveNodes.length-1]));
		}
	}

	// Return the module object
	return {
		option             : option             ,
		text               : text               ,
		processPGN         : processPGN         ,
		processPGNByID     : processPGNByID
	};
})(Chess, Pgn, ChessWidget, jQuery);
