/******************************************************************************
 *                                                                            *
 *    This file is part of chess4web, a javascript library for displaying     *
 *    chessboards or full chess game in a web page.                           *
 *                                                                            *
 *    Copyright (C) 2011  Yoann Le Montagner <yo35(at)melix(dot)net>          *
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
 * Rendering methods for chess-related objects.
 */
var jsChessRenderer = (function()
{
	/**
	 * Module object, that will returned as global variable jsChessRenderer.
	 */
	var module = {};

	/**
	 * URL to the folder containing the current file.
	 */
	var baseURL = null;

	/**
	 * Configuration function, that must be call before using the other object
	 * defined by the jsChessRenderer module.
	 *
	 * @param {String} url URL to the root folder of the JsChessLib library,
	 *        without the trailing '/' character.
	 */
	module.configureBaseURL = function(url)
	{
		baseURL = url;
	}

	/**
	 * Check whether the base URL has already been configured or not.
	 */
	module.isBaseURLConfigured = function()
	{
		baseURL!=null;
	}

	/**
	 * The behavior of the module can be modified by changing the properties of
	 * this object.
	 */
	module.option = {};

	/**
	 * Folder where the sprite are located.
	 */
	var spriteFolder = "sprite";

	/**
	 * Black square color (CSS color string).
	 */
	var blackSquareColor = "#b5876b";

	/**
	 * White square color (CSS color string).
	 */
	var whiteSquareColor = "#f0dec7";

	/**
	 * Default size for the squares of displayed chessboards
	 */
	module.option.defaultSquareSize = 32;

	/**
	 * Whether row and column coordinates should be visible or not on chessboards by default.
	 */
	module.option.defaultShowCoordinates = false;

	/**
	 * Month names
	 */
	module.option.monthName =
	{
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
	};

	/**
	 * Piece symbols
	 */
	module.option.pieceSymbol =
	{
		"K": "\u265a",
		"Q": "\u265b",
		"R": "\u265c",
		"B": "\u265d",
		"N": "\u265e",
		"P": "\u265f"
	};

	/**
	 * Nags
	 */
	module.option.nag =
	{
		 1: "!",
		 2: "?",
		 3: "!!",
		 4: "??",
		 5: "!?",
		 6: "?!",
		10: "=",
		13: "\u221e",
		14: "+=",
		15: "=+",
		16: "\u00b1",
		17: "\u2213",
		18: "+\u2212",
		19: "\u2212+"
	};

	/**
	 * Print a debug message. The text is appended in a DOM node identified by
	 * the ID "jsChessLib-debug". Nothing happens is this DOM node does not exist.
	 *
	 * @param {String} message Message to print.
	 */
	function printDebug(message)
	{
		var debugNode = document.getElementById("jsChessLib-debug");
		if(debugNode!=null) {
			debugNode.innerHTML += message + "\n";
		}
	}

	/**
	 * Return the URL to the folder containing the sprites of the given size.
	 *
	 * @param {Number} squareSize Size of the sprite to use.
	 */
	function spriteBaseURL(squareSize)
	{
		var retVal = spriteFolder + "/" + squareSize + "/";
		if(baseURL!=null) {
			retVal = baseURL + "/" + retVal;
		}
		return retVal;
	}

	/**
	 * Return the URL to the sprite corresponding to a given colored piece.
	 *
	 * @param {Number} coloredPiece Colored piece code (e.g. WHITE_KING), or null for an empty sprite.
	 * @param {Number} squareSize Size of the sprite to use.
	 */
	function coloredPieceURL(coloredPiece, squareSize)
	{
		var retVal = spriteBaseURL(squareSize);
		switch(coloredPiece) {
			case WHITE_KING  : retVal+="wk.png"; break;
			case WHITE_QUEEN : retVal+="wq.png"; break;
			case WHITE_ROOK  : retVal+="wr.png"; break;
			case WHITE_BISHOP: retVal+="wb.png"; break;
			case WHITE_KNIGHT: retVal+="wn.png"; break;
			case WHITE_PAWN  : retVal+="wp.png"; break;
			case BLACK_KING  : retVal+="bk.png"; break;
			case BLACK_QUEEN : retVal+="bq.png"; break;
			case BLACK_ROOK  : retVal+="br.png"; break;
			case BLACK_BISHOP: retVal+="bb.png"; break;
			case BLACK_KNIGHT: retVal+="bn.png"; break;
			case BLACK_PAWN  : retVal+="bp.png"; break;
			default: retVal+="clear.png"; break;
		}
		return retVal;
	}

	/**
	 * Return the URL to the sprite corresponding to a given color flag.
	 *
	 * @param {Number} color Color code (either BLACK or WHITE).
	 * @param {Number} squareSize Size of the sprite to use.
	 */
	function colorURL(color, squareSize)
	{
		return spriteBaseURL(squareSize) + (color==WHITE ? "white" : "black") + ".png";
	}

	/**
	 * Return a new DOM node representing the given position.
	 *
	 * @param {Position} position The chess position to render.
	 * @param {Number} squareSize Size of the sprite to use.
	 * @param {Boolean} showCoordinates Whether the row and column coordinates should be displayed.
	 */
	function renderPosition(position, squareSize, showCoordinates)
	{
		// Create the returned node
		var retVal = document.createElement("div");
		retVal.classList.add("jsChessLib-chessboard");

		// Prepare the table
		var table = document.createElement("table");
		var tbody = document.createElement("tbody");
		table.appendChild(tbody);
		retVal.appendChild(table);

		// For each row...
		for(var r=ROW_8; r>=ROW_1; --r) {
			var tr = document.createElement("tr");
			tbody.appendChild(tr);

			// If visible, the row coordinates are shown in the left-most column.
			if(showCoordinates) {
				var th = document.createElement("th");
				th.setAttribute("scope", "row");
				th.innerHTML = rowToString(r);
				tr.appendChild(th);
			}

			// Print the squares belonging to the current column
			for(var c=COLUMN_A; c<=COLUMN_H; ++c) {
				var squareColor  = (r+c)%2==0 ? blackSquareColor : whiteSquareColor;
				var coloredPiece = position.board[makeSquare(r, c)];
				var td  = document.createElement("td" );
				var img = document.createElement("img");
				td .setAttribute("style", "background-color: " + squareColor + ";");
				img.setAttribute("src", coloredPieceURL(coloredPiece, squareSize));
				td.appendChild(img);
				tr.appendChild(td);
			}
		}

		// If visible, the column coordinates are shown at the bottom of the table.
		if(showCoordinates) {
			var tr  = document.createElement("tr");
			var th0 = document.createElement("th");
			tr.appendChild(th0);
			for(var c=COLUMN_A; c<=COLUMN_H; ++c) {
				var th = document.createElement("th");
				th.setAttribute("scope", "column");
				th.innerHTML = columnToString(c);
				tr.appendChild(th);
			}
			tbody.appendChild(tr);
		}

		// Create the black or white circle on the right of the table,
		// indicating which player is about to play.
		var turnNode = document.createElement("img");
		turnNode.classList.add("jsChessLib-" + (position.turn==WHITE ? "white" : "black") + "-to-play");
		turnNode.setAttribute("src", colorURL(position.turn, squareSize));
		retVal.appendChild(turnNode);

		// Return the result
		return retVal;
	}

	/**
	 * Return a new DOM node holding information about the one of the players.
	 *
	 * @param {PGNItem} pgnItem PGN item object holding the information to render.
	 * @param {Number} color Color code (either BLACK or WHITE) corresponding to the player to consider.
	 */
	function renderPlayerInfo(pgnItem, color)
	{
		// Name of the PGN fields
		var nameField  = color==WHITE ? "White" : "Black";
		var eloField   = nameField + "Elo";
		var titleField = nameField + "Title";

		// Returned node
		var retVal = document.createElement("span");
		retVal.classList.add("jsChessLib-value-fullName" + nameField);

		// Player name sub-field
		var nameSubField = document.createElement("span");
		nameSubField.classList.add("jsChessLib-subfield-playerName");
		nameSubField.innerHTML = pgnItem[nameField];
		retVal.appendChild(nameSubField);

		// Group title + elo
		var titleDefined = (pgnItem[titleField]!=null) && pgnItem[titleField]!="-";
		var eloDefined   = (pgnItem[eloField  ]!=null) && pgnItem[eloField  ]!="?";
		if(titleDefined || eloDefined) {
			var groupTitleEloSubField = document.createElement("span");
			groupTitleEloSubField.classList.add("jsChessLib-subfield-groupTitleElo");
			retVal.appendChild(groupTitleEloSubField);

			// Title sub-field
			if(titleDefined) {
				var titleSubField = document.createElement("span");
				titleSubField.classList.add("jsChessLib-subfield-title");
				titleSubField.innerHTML = pgnItem[titleField];
				groupTitleEloSubField.appendChild(titleSubField);
			}

			// Separator
			if(titleDefined && eloDefined) {
				var separator = document.createTextNode(" ");
				groupTitleEloSubField.appendChild(separator);
			}

			// Elo sub-field
			if(eloDefined) {
				var eloSubField = document.createElement("span");
				eloSubField.classList.add("jsChessLib-subfield-elo");
				eloSubField.innerHTML = pgnItem[eloField];
				groupTitleEloSubField.appendChild(eloSubField);
			}
		}

		// Return the result
		return retVal;
	}

	/**
	 * Convert a PGN date field value into a human-readable date string. If the
	 * input is badly-formatted, it is returned "as-is".
	 *
	 * @param {String} date Value of a PGN date field.
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
				return RegExp.$3 + " " + module.option.monthName[month] + " " + RegExp.$1;
			else
				return RegExp.$1
		}

		// Case "2013.05.??" -> return "may 2013"
		else if(date.match(/([0-9]{4})\.([0-9]{2})\.\?\?/)) {
			var month = parseInt(RegExp.$2);
			if(month>=1 && month<=12)
				return module.option.monthName[month] + " " + RegExp.$1;
			else
				return RegExp.$1
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
	 * @param {String} round Value of a PGN round field.
	 */
	function formatRound(round)
	{
		if(round==null || round=="?") {
			return null;
		}
		else {
			return round;
		}
	}

	/**
	 * Convert a PGN-like move notation into a localized move notation
	 * (the characters used to specify the pieces is the only localized element).
	 *
	 * @param {String} notation PGN-like move notation to convert.
	 */
	function formatMoveNotation(notation)
	{
		if(notation==null) {
			return null;
		}
		else {
			var retVal = "";
			for(var k=0; k<notation.length; ++k) {
				switch(notation.charAt(k)) {
					case "K": retVal+=module.option.pieceSymbol["K"]; break;
					case "Q": retVal+=module.option.pieceSymbol["Q"]; break;
					case "R": retVal+=module.option.pieceSymbol["R"]; break;
					case "B": retVal+=module.option.pieceSymbol["B"]; break;
					case "N": retVal+=module.option.pieceSymbol["N"]; break;
					case "P": retVal+=module.option.pieceSymbol["P"]; break;
					default:
						retVal += notation.charAt(k);
						break;
				}
			}
			return retVal;
		}
	}

	/**
	 * Return the annotation symbol (e.g. "+-", "!?") associated to a numeric NAG code.
	 *
	 * @param {Number} nag Numeric NAG code.
	 */
	function formatNag(nag)
	{
		if(nag==null) {
			return null;
		}
		else if(module.option.nag[nag]==null)
			return "$" + nag;
		else
			return module.option.nag[nag];
	}

	/**
	 * Replace the content of a DOM node with a text value from a PGN field. Here
	 * is an example, for the event field (i.e. fieldName=='Event'):
	 *
	 * Before substitution:
	 * <div class="jsChessLib-field-Event">
	 *   Event: <span class="jsChessLib-anchor-Event"></span>
	 * </div>
	 *
	 * After substitution, if the event field is defined:
	 * <div class="jsChessLib-field-Event">
	 *   Event: <span class="jsChessLib-value-Event">Aeroflot Open</span>
	 * </div>
	 *
	 * After substitution, if the event field is undefined or null:
	 * <div class="jsChessLib-field-Event jsChessLib-invisible">
	 *   Event: <span class="jsChessLib-value-Event"></span>
	 * </div>
	 *
	 * @param {Element} parentNode Each child of this node having a class attribute
	 *        set to "jsChessLib-field-[fieldName]" will be targeted by the substitution.
	 * @param {String} fieldName Name of the PGN field to process.
	 * @param {PGNItem} pgnItem PGN item object corresponding to the game to process.
	 * @param {Function} [formatFunc] If provided, the content of the current PGN field
	 *        will be passed to this function, and the returned value will be used as
	 *        substitution text. Otherwise, the content of the PGN field is used "as-is".
	 */
	function substituteSimpleField(parentNode, fieldName, pgnItem, formatFunc)
	{
		var fieldNodes = parentNode.getElementsByClassName("jsChessLib-field-" + fieldName);
		for(var k=0; k<fieldNodes.length; ++k) {
			var fieldNode = fieldNodes[k];

			// Determine the text that is to be inserted
			var value = pgnItem[fieldName];
			if(formatFunc!=null) {
				value = formatFunc(value);
			}

			// Hide the field if no value is available
			if(value==null) {
				fieldNode.classList.add("jsChessLib-invisible");
				value = "";
			}

			// Process each anchor node
			var anchorNodes = fieldNode.getElementsByClassName("jsChessLib-anchor-" + fieldName);
			for(var l=0; l<fieldNodes.length; ++l) {
				var anchorNode = anchorNodes[l];
				anchorNode.innerHTML = value;
				anchorNode.classList.add   ("jsChessLib-value-"  + fieldName);
				anchorNode.classList.remove("jsChessLib-anchor-" + fieldName);
			}
		}
	}

	/**
	 * Substitution method for the special replacement tokens fullNameWhite and
	 * fullNameBlack. Example:
	 *
	 * Before substitution:
	 * <div class="jsChessLib-field-fullNameWhite">
	 *   White player: <span class="jsChessLib-anchor-fullNameWhite"></span>
	 * </div>
	 *
	 * After substitution:
	 * <div class="jsChessLib-field-fullNameWhite">
	 *   White player: <span class="jsChessLib-value-fullNameWhite">
	 *     <span class="jsChessLib-subfield-playerName">Kasparov, Garry</span>
	 *     <span class="jsChessLib-subfield-groupTitleElo">
	 *       <span class="jsChessLib-subfield-title">GM</span>
	 *       <span class="jsChessLib-subfield-elo">2812</span>
	 *     </span>
	 *   </span>
	 * </div>
	 *
	 * @param {Element} parentNode Each child of this node having a class attribute
	 *        set to "jsChessLib-field-fullNameColor" will be targeted by the substitution.
	 * @param {Number} color Color code corresponding to the player to consider (either BLACK or WHITE).
	 * @param {PGNItem} pgnItem PGN item object corresponding to the game to process.
	 */
	function substituteFullName(parentNode, color, pgnItem)
	{
		var nameField = color==WHITE ? "White" : "Black";

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
			for(var l=0; l<fieldNodes.length; ++l) {
				var anchorNode = anchorNodes[l];
				var valueNode  = renderPlayerInfo(pgnItem, color);
				anchorNode.parentNode.replaceChild(valueNode, anchorNode);
			}
		}
	}

	/**
	 * Interpret the text in the given DOM node as a FEN string, and replace the
	 * node with a graphically-rendered chessboard corresponding to the FEN string.
	 *
	 * @param {Element} domNode DOM node containing the FEN string to interpret.
	 * @param {Number} [squareSize] Size of the sprite to use.
	 * @param {Boolean} [showCoordinates] Whether the row and column coordinates should be displayed.
	 */
	module.processFEN = function(domNode, squareSize, showCoordinates)
	{
		// Nothing to do if the DOM node is not valid
		if(domNode==null) {
			return;
		}

		// Default arguments
		if(squareSize     ==null) squareSize     =module.option.defaultSquareSize     ;
		if(showCoordinates==null) showCoordinates=module.option.defaultShowCoordinates;

		try
		{
			// Interpret the text within the given DOM node as a FEN string, and
			// construct the associated chess position.
			var position = parseFEN(domNode.innerHTML);

			// Render the parsed position in a new DOM node
			var positionNode = renderPosition(position, squareSize, showCoordinates);

			// The rendered position is encapsulate in a new DIV,
			// which replaces the input DOM node.
			var outputNode = document.createElement("div");
			outputNode.className = "jsChessLib-out";
			outputNode.appendChild(positionNode);
			domNode.parentNode.replaceChild(outputNode, domNode);
		}

		// Parsing exception are caught, while other kind of exceptions are forwarded.
		catch(err) {
			if(err instanceof ParsingException) {
				return;
			}
			else {
				throw err;
			}
		}
	}

	/**
	 * Call the processFEN method on the node identified by the given ID.
	 *
	 * @see {@link module.processFEN}
	 * @param {String} domID ID of the DOM node to process.
	 * @param {Number} [squareSize] Size of the sprite to use.
	 * @param {Boolean} [showCoordinates] Whether the row and column coordinates should be displayed.
	 */
	module.processFENByID = function(domID, squareSize, showCoordinates)
	{
		module.processFEN(document.getElementById(domID), squareSize, showCoordinates);
	}

	/**
	 * Interpret the text in the DOM node domNodeIn as a PGN string, and process
	 * the replacement tokens within domNodeOut with the values parsed from this
	 * PGN string.
	 *
	 * @see {@link substituteSimpleField}
	 * @param {Element} domNodeIn
	 * @param {Element} domNodeOut
	 */
	module.processPGN = function(domNodeIn, domNodeOut)
	{
		// Nothing to do if one of the DOM node is not valid
		if(domNodeIn==null || domNodeOut==null) {
			return;
		}

		try
		{
			// Interpret the text within the input DOM node as a PGN string, and
			// construct the associated PGN item object.
			var pgnItems = parsePGN(domNodeIn.innerHTML);
			var pgnItem  = pgnItems[0]; // only the first item is taken into account

			// Substitution
			substituteSimpleField(domNodeOut, "Event"    , pgnItem);
			substituteSimpleField(domNodeOut, "Site"     , pgnItem);
			substituteSimpleField(domNodeOut, "Date"     , pgnItem, formatDate );
			substituteSimpleField(domNodeOut, "Round"    , pgnItem, formatRound);
			substituteSimpleField(domNodeOut, "White"    , pgnItem);
			substituteSimpleField(domNodeOut, "Black"    , pgnItem);
			substituteSimpleField(domNodeOut, "Result"   , pgnItem);
			substituteSimpleField(domNodeOut, "Annotator", pgnItem);
			substituteFullName(domNodeOut, WHITE, pgnItem);
			substituteFullName(domNodeOut, BLACK, pgnItem);

			// The input node is made invisible, while the output node is revealed.
			domNodeIn .classList.add   ("jsChessLib-invisible");
			domNodeOut.classList.remove("jsChessLib-invisible");
			domNodeOut.classList.add   ("jsChessLib-out"      );
		}

		// Parsing exception are caught, while other kind of exceptions are forwarded.
		catch(err) {
			if(err instanceof PGNException) {
				printDebug(err.message);
				var pos1 = Math.max(0, err.position-50);
				var lg1  = err.position-pos1;
				var pos2 = err.position;
				var lg2  = Math.min(50, err.pgnString.length-pos2);
				printDebug(
					"..." + err.pgnString.substr(pos1, lg1) + "{{{ERROR THERE}}}"
					+ err.pgnString.substr(pos2, lg2) + "..."
				);
			}
			else {
				throw err;
			}
		}
	}

	/**
	 * Call the processPGN method using the node identified by ID 'domID+"-in"' as
	 * input, and the node identified by ID 'domID+"-out"' as output.
	 *
	 * @see {@link module.processPGN}
	 * @param {String} domID ID prefix of the DOM nodes to process.
	 */
	module.processPGNByID = function(domID)
	{
		module.processPGN(
			document.getElementById(domID + "-in" ),
			document.getElementById(domID + "-out")
		);
	}







/**
 * Retrieve all the elements with a given class
 * \param searchClass Name of the targeted class
 * \param tagName Type of node to search for (optional, default: '*')
 * \param domNode Root node to search (optional, default: document)
 * \param recursive Set to false to search only within the direct children of the root node (optional, default: true)
 */
function getElementsByClass(searchClass, tagName, domNode, recursive)
{
	if(domNode  ==null) domNode   = document;
	if(tagName  ==null) tagName   = "*";
	if(recursive==null) recursive = true;
	var retVal   = new Array();
	var elements = domNode.getElementsByTagName(tagName);
	for(var k=0; k<elements.length; ++k) {
		if(elements[k].classList.contains(searchClass) && (recursive || domNode==elements[k].parentNode)) {
			retVal.push(elements[k]);
		}
	}
	return retVal;
}






/**
 * Replace the content of a DOM node with the list of moves
 */
function substituteMoves(domNode, pgnItem, hideResult)
{
	// Initialize the tree walk
	if(pgnItem.mainVariation==null) {
		return;
	}
	domNode.innerHTML = "";

	// Auxiliary function for commentary printing
	function printCommentary(currentDomNode, currentPgnNode, depth)
	{
		if(currentPgnNode.commentary==null) {
			return;
		}
		var commentary = document.createElement("span");
		var textLength = HTMLtoDOM(currentPgnNode.commentary, commentary, document);
		var inlinedPositionNodes = getElementsByClass("chess4web-template-InlinedPosition", "*", commentary);
		for(var k=0; k<inlinedPositionNodes.length; ++k) {
			var inlinedPositionNode = inlinedPositionNodes[k];
			var positionTable = renderPosition(currentPgnNode.position);
			inlinedPositionNode.innerHTML = "";
			inlinedPositionNode.appendChild(positionTable);
			inlinedPositionNode.classList.add   ("chess4web-InlinedPosition");
			inlinedPositionNode.classList.remove("chess4web-template-InlinedPosition");
		}
		var isLongCommentary = (depth==0) && ((textLength>=30) || (inlinedPositionNodes.length>0));
		commentary.className = "chess4web-" + (isLongCommentary ? "long-commentary" : "commentary");
		currentDomNode.appendChild(commentary);
	}

	// Recursive function for variation printing
	function printVariation(currentDomNode, variation, depth)
	{
		// First commentary
		printCommentary(currentDomNode, variation, depth);
		var forcePrintMoveNumber = true;

		// Start iterating over the variation main line
		var currentPgnNode = variation.next;
		while(currentPgnNode!=null) {

			// Move (move number + notation + nags + miniboard)
			var move = document.createElement("span");
			move.className = "chess4web-move";
			if(currentPgnNode.parent.position.turn==WHITE) {
				var moveNumber = document.createTextNode(currentPgnNode.counter + ".");
				move.appendChild(moveNumber);
			}
			else if(forcePrintMoveNumber) {
				var moveNumber = document.createTextNode(currentPgnNode.counter + "\u2026");
				move.appendChild(moveNumber);
			}
			var notation = document.createTextNode(formatMoveNotation(currentPgnNode.notation));
			move.appendChild(notation);
			for(var k=0; k<currentPgnNode.nags.length; ++k) {
				var nag = document.createTextNode(" " + formatNag(currentPgnNode.nags[k]));
				move.appendChild(nag);
			}
			var miniboard = document.createElement("div");
			miniboard.className = "chess4web-position-miniature";
			miniboard.innerHTML = positionToFEN(currentPgnNode.position);
			move.setAttribute("onclick", "showNavigationFrame(this)");
			move.appendChild(miniboard);
			currentDomNode.appendChild(move);

			// Commentary
			printCommentary(currentDomNode, currentPgnNode, depth);

			// Variations
			for(var k=0; k<currentPgnNode.variations.length; ++k) {
				var newVariation = document.createElement("span");
				newVariation.className = "chess4web-variation";
				printVariation(newVariation, currentPgnNode.variations[k], depth+1);
				currentDomNode.appendChild(newVariation);
			}

			// Back to the main line
			forcePrintMoveNumber = (currentPgnNode.commentary!=null) || (currentPgnNode.variations.length>0);
			currentPgnNode = currentPgnNode.next;
		}
	}
	printVariation(domNode, pgnItem.mainVariation, 0);

	// Append the result
	if(!hideResult) {
		var result = document.createElement("span");
		result.className = "chess4web-Result";
		result.innerHTML = pgnItem["Result"];
		domNode.appendChild(result);
	}

	// CSS classes
	domNode.classList.add   ("chess4web-Moves");
	domNode.classList.remove("chess4web-template-Moves");
}

/**
 * Creates the chess4web-navigation-frame if it does not exist
 */
function makeNavigationFrame(parentNode)
{
	if(document.getElementById("chess4web-navigation-frame")!=null) {
		return;
	}
	var retVal = document.createElement("div");
	retVal.setAttribute("id", "chess4web-navigation-frame");
	jQuery(document).ready(function($){
		if(typeof($("#chess4web-navigation-frame").draggable)=="function") {
			$("#chess4web-navigation-frame").draggable({ handle: "#chess4web-navigation-title" });
		}
	});
	parentNode.appendChild(retVal);

	// Function that creates a button
	function makeNewButton(label)
	{
		var newButton = document.createElement("input");
		newButton.setAttribute("type", "button");
		newButton.setAttribute("value", label);
		return newButton;
	}

	// Close button
	var closeButtonDiv = document.createElement("div");
	closeButtonDiv.setAttribute("id", "chess4web-navigation-close");
	var closeButton = makeNewButton("x");
	closeButton.setAttribute("onclick", "hideNavigationFrame()");
	closeButtonDiv.appendChild(closeButton);
	retVal.appendChild(closeButtonDiv);

	// Title bar
	var titleBar = document.createElement("div");
	titleBar.setAttribute("id", "chess4web-navigation-title");
	retVal.appendChild(titleBar);

	// Board container
	var boardData = document.createElement("div");
	boardData.setAttribute("id", "chess4web-navigation-content");
	retVal.appendChild(boardData);

	// Buttons
	var buttonBar = document.createElement("div");
	buttonBar.setAttribute("id", "chess4web-navigation-buttons");
	retVal.appendChild(buttonBar);
	var firstButton = makeNewButton("<<");
	var prevButton  = makeNewButton("<" );
	var nextButton  = makeNewButton(">" );
	var lastButton  = makeNewButton(">>");
	firstButton.setAttribute("onclick", "goFirstMove()");
	prevButton .setAttribute("onclick", "goPrevMove()" );
	nextButton .setAttribute("onclick", "goNextMove()" );
	lastButton .setAttribute("onclick", "goLastMove()" );
	buttonBar.appendChild(firstButton);
	buttonBar.appendChild(prevButton );
	buttonBar.appendChild(nextButton );
	buttonBar.appendChild(lastButton );
}

/**
 * Go to the first move
 */
function goFirstMove()
{
	// Retrieve node corresponding to the current move
	var currentSelectedNode = document.getElementById("chess4web-selected-move");
	if(currentSelectedNode==null) {
		return;
	}

	// All the move nodes in with the same parent
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, false);
	if(moveNodes.length>0) {
		showNavigationFrame(moveNodes[0]);
	}
}

/**
 * Go to the previous move
 */
function goPrevMove()
{
	// Retrieve node corresponding to the current move
	var currentSelectedNode = document.getElementById("chess4web-selected-move");
	if(currentSelectedNode==null) {
		return;
	}

	// All the move nodes in with the same parent
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, false);
	for(var k=0; k<moveNodes.length; ++k) {
		if(moveNodes[k]==currentSelectedNode) {
			if(k>0) {
				showNavigationFrame(moveNodes[k-1]);
			}
			return;
		}
	}
}

/**
 * Go to the next move
 */
function goNextMove()
{
	// Retrieve node corresponding to the current move
	var currentSelectedNode = document.getElementById("chess4web-selected-move");
	if(currentSelectedNode==null) {
		return;
	}

	// All the move nodes in with the same parent
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, false);
	for(var k=0; k<moveNodes.length; ++k) {
		if(moveNodes[k]==currentSelectedNode) {
			if(k<moveNodes.length-1) {
				showNavigationFrame(moveNodes[k+1]);
			}
			return;
		}
	}
}

/**
 * Go to the last move
 */
function goLastMove()
{
	// Retrieve node corresponding to the current move
	var currentSelectedNode = document.getElementById("chess4web-selected-move");
	if(currentSelectedNode==null) {
		return;
	}

	// All the move nodes in with the same parent
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, false);
	if(moveNodes.length>0) {
		showNavigationFrame(moveNodes[moveNodes.length-1]);
	}
}

/**
 * Hide the navigation frame
 */
function hideNavigationFrame()
{
	var navigationFrame = document.getElementById("chess4web-navigation-frame");
	if(navigationFrame!=null) {
		navigationFrame.classList.remove("chess4web-show-me");
	}
	var selectedNode = document.getElementById("chess4web-selected-move");
	if(selectedNode!=null) {
		selectedNode.removeAttribute("id");
	}
}

/**
 * Show or update the navigation frame
 */
function showNavigationFrame(domNode)
{
	if(domNode==null) {
		return;
	}

	// Remove the selected-move flag from the previously selected node
	var prevSelectedNode = document.getElementById("chess4web-selected-move");
	if(domNode==prevSelectedNode) {
		return;
	}
	if(prevSelectedNode!=null) {
		prevSelectedNode.removeAttribute("id");
	}

	// Set the selected-move flag on the new node
	domNode.setAttribute("id", "chess4web-selected-move");

	// Fill the miniboard in the navigation frame
	var position = expandMiniboard(domNode);
	var target = document.getElementById("chess4web-navigation-content");
	target.innerHTML = "";
	target.appendChild(position);

	// Show the navigation frame
	var navigationFrame = document.getElementById("chess4web-navigation-frame");
	navigationFrame.classList.add("chess4web-show-me");
}

/**
 * Return the table corresponding to the content of a DOM node
 */
function expandMiniboard(domNode)
{
	var target = getElementsByClass("chess4web-position-miniature", "div", domNode);
	var fen = target[0].innerHTML;
	try {
		var position  = parseFEN(fen);
		var miniboard = renderPosition(position, chess4webDefaultMiniboardSquareSize, chess4webDefaultMiniboardShowCoordinate);
		return miniboard;
	}
	catch(err) {
		if(err instanceof PGNException) {
			printDebug(err.message);
		}
		else {
			throw err;
		}
	}
}

/**
 * Parse and return the PGN data contained in a DOM node
 */
function parseInputNode(inputDomNode)
{
	inputDomNode.classList.add("chess4web-hide-this");
	try {
		var pgnItems = parsePGN(inputDomNode.innerHTML);
		return pgnItems;
	}
	catch(err) {
		if(err instanceof PGNException) {
			printDebug(err.message);
			var pos1 = Math.max(0, err.position-50);
			var lg1  = err.position-pos1;
			var pos2 = err.position;
			var lg2  = Math.min(50, err.pgnString.length-pos2);
			printDebug('...' + err.pgnString.substr(pos1, lg1) + "{{{ERROR THERE}}}"
				+ err.pgnString.substr(pos2, lg2)) + "...";
		}
		else {
			throw err;
		}
	}
}


/**
 * Substitute the template fields in a given output DOM node
 */
function substituteOutputNode(outputDomNode, currentPgnItem, hideResult)
{
	// Auxiliary recursive function
	function auxRecursiveSubstitution(node, pgnItem, hideResult)
	{
		if(node.classList===undefined) {
			return;
		}
		for(var k=0; k<node.classList.length; ++k) {
			switch(node.classList[k]) {
				case "chess4web-template-Event"    : substituteSimpleField(node, "Event"    , pgnItem); return;
				case "chess4web-template-Site"     : substituteSimpleField(node, "Site"     , pgnItem); return;
				case "chess4web-template-Date"     : substituteSimpleField(node, "Date"     , pgnItem, formatDate); return;
				case "chess4web-template-Round"    : substituteSimpleField(node, "Round"    , pgnItem); return;
				case "chess4web-template-White"    : substituteSimpleField(node, "White"    , pgnItem); return;
				case "chess4web-template-Black"    : substituteSimpleField(node, "Black"    , pgnItem); return;
				case "chess4web-template-Result"   : substituteSimpleField(node, "Result"   , pgnItem); return;
				case "chess4web-template-Annotator": substituteSimpleField(node, "Annotator", pgnItem); return;
				case "chess4web-template-WhiteFullName": substituteFullName(node, WHITE, pgnItem); return;
				case "chess4web-template-BlackFullName": substituteFullName(node, BLACK, pgnItem); return;
				case "chess4web-template-FullEvent": substituteFullEvent(node, pgnItem); return;
				case "chess4web-template-Moves": substituteMoves   (node, pgnItem, hideResult); return;
				default:
					break;
			}
		}
		for(var k=0; k<node.childNodes.length; ++k) {
			auxRecursiveSubstitution(node.childNodes[k], pgnItem, hideResult);
		}
	}

	// Root call
	auxRecursiveSubstitution(outputDomNode, currentPgnItem, hideResult);
	outputDomNode.classList.remove("chess4web-hide-this");
}




/**
 * Hide the given node
 */
function chess4webHideNode(domNode)
{
	domNode.classList.add("chess4web-hide-this");
}




	// Return the module object
	return module;
})();
