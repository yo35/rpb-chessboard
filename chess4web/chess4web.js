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
 * Base URL to the chess4web data
 */
var chess4webBaseURL = "sprite/";

/**
 * Default square size for the board
 */
var chess4webDefaultSquareSize = 32;

/**
 * Default show coordinate option
 */
var chess4webDefaultShowCoordinate = false;

/**
 * Default square size for the board
 */
var chess4webDefaultMiniboardSquareSize = 24;

/**
 * Default show coordinate option
 */
var chess4webDefaultMiniboardShowCoordinate = false;

/**
 * Default black square color
 */
var chess4webDefaultBlackSquare = "#b5876b";

/**
 * Default white square color
 */
var chess4webDefaultWhiteSquare = "#f0dec7";

/**
 * Month names
 */
var chess4webMonthName =
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
}

/**
 * Piece symbols
 */
var chess4webPieceSymbol =
{
	"K": "\u265a",
	"Q": "\u265b",
	"R": "\u265c",
	"B": "\u265d",
	"N": "\u265e",
	"P": "\u265f"
}

/**
 * Nags
 */
var chess4webNag =
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
}

/**
 * Retrieve all the elements with a given class
 * \param searchClass Name of the targeted class
 * \param tagName Type of node to search for (optional, default: '*')
 * \param domNode Root node to search (default: document)
 */
function getElementsByClass(searchClass, tagName, domNode)
{
	if(domNode==null) domNode = document;
	if(tagName==null) tagName = "*";
	var retVal   = new Array();
	var elements = domNode.getElementsByTagName(tagName);
	for(var k=0; k<elements.length; ++k) {
		if(elements[k].classList.contains(searchClass)) {
			retVal.push(elements[k]);
		}
	}
	return retVal;
}

/**
 * Date formating function
 */
function formatDate(date)
{
	if(date.match(/([0-9]{4})\.([0-9]{2})\.([0-9]{2})/)) {
		var month = parseInt(RegExp.$2);
		if(month>=1 && month<=12)
			return RegExp.$3 + " " + chess4webMonthName[month] + " " + RegExp.$1;
		else
			return RegExp.$1
	}
	else if(date.match(/([0-9]{4})\.([0-9]{2})\.\?\?/)) {
		var month = parseInt(RegExp.$2);
		if(month>=1 && month<=12)
			return chess4webMonthName[month] + " " + RegExp.$1;
		else
			return RegExp.$1
	}
	else if(date.match(/([0-9]{4})\.\?\?\.\?\?/)) {
		return RegExp.$1;
	}
	else
		return date;
}

/**
 * Move notation formating function
 */
function formatMoveNotation(notation)
{
	var retVal = "";
	for(var k=0; k<notation.length; ++k) {
		switch(notation.charAt(k)) {
			case "K": retVal+=chess4webPieceSymbol["K"]; break;
			case "Q": retVal+=chess4webPieceSymbol["Q"]; break;
			case "R": retVal+=chess4webPieceSymbol["R"]; break;
			case "B": retVal+=chess4webPieceSymbol["B"]; break;
			case "N": retVal+=chess4webPieceSymbol["N"]; break;
			case "P": retVal+=chess4webPieceSymbol["P"]; break;
			default:
				retVal += notation.charAt(k);
				break;
		}
	}
	return retVal;
}

/**
 * Nag formating function
 */
function formatNag(nag)
{
	if(chess4webNag[nag]===undefined)
		return "$" + nag;
	else
		return chess4webNag[nag];
}

/**
 * Return a table DOM node representing the given position
 */
function renderPosition(position, squareSize, showCoordinate, blackSquare, whiteSquare)
{
	if(squareSize    ===undefined) squareSize    =chess4webDefaultSquareSize    ;
	if(showCoordinate===undefined) showCoordinate=chess4webDefaultShowCoordinate;
	if(blackSquare   ===undefined) blackSquare   =chess4webDefaultBlackSquare   ;
	if(whiteSquare   ===undefined) whiteSquare   =chess4webDefaultWhiteSquare   ;

	// Return the URL to the sprite corresponding to a given colored piece
	function getColoredPieceURL(coloredPiece)
	{
		var retVal = chess4webBaseURL + squareSize + "/";
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

	// Create the table
	var table = document.createElement("table");
	var tbody = document.createElement("tbody");
	for(var r=ROW_8; r>=ROW_1; --r) {
		var tr = document.createElement("tr");
		if(showCoordinate) {
			var th = document.createElement("th");
			th.setAttribute("scope", "row");
			th.innerHTML = rowToString(r);
			tr.appendChild(th);
		}
		for(var c=COLUMN_A; c<=COLUMN_H; ++c) {
			var squareColor  = (r+c)%2==0 ? blackSquare : whiteSquare;
			var coloredPiece = position.board[makeSquare(r, c)];
			var td = document.createElement("td");
			td.setAttribute("style", "background-color: " + squareColor + ";");
			var img = document.createElement("img");
			img.setAttribute("src", getColoredPieceURL(coloredPiece));
			td.appendChild(img);
			tr.appendChild(td);
		}
		tbody.appendChild(tr);
	}
	if(showCoordinate) {
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
	table.appendChild(tbody);
	return table;
}

/**
 * Replace the content of a DOM node with a text value from a PGN field
 */
function substituteSimpleField(domNode, fieldName, pgnItem, formatFunc)
{
	if(pgnItem[fieldName]===undefined) {
		return;
	}
	domNode.innerHTML = (formatFunc===undefined) ? pgnItem[fieldName] : formatFunc(pgnItem[fieldName]);
	domNode.classList.add   ("chess4web-" + fieldName);
	domNode.classList.remove("chess4web-template-" + fieldName);
}

/**
 * Replace the content of a DOM node with a full player ID (name + elo if available)
 */
function substituteFullName(domNode, color, pgnItem)
{
	var nameField  = color==WHITE ? "White" : "Black";
	var eloField   = nameField + "Elo";
	var titleField = nameField + "Title";
	var className  = nameField + "FullName";
	if(pgnItem[nameField]===undefined) {
		return;
	}

	// Name substitution
	domNode.innerHTML = "";
	var nameSpan = document.createElement("span");
	nameSpan.className = "chess4web-playername";
	nameSpan.innerHTML = pgnItem[nameField];
	domNode.appendChild(nameSpan);

	// Elo substitution
	if(pgnItem[eloField]!==undefined) {
		var eloSpan = document.createElement("span");
		eloSpan.className = "chess4web-elo";
		if(pgnItem[titleField]!==undefined && pgnItem[titleField]!="-") {
			eloSpan.innerHTML = pgnItem[titleField] + " " + pgnItem[eloField];
		}
		else {
			eloSpan.innerHTML = pgnItem[eloField];
		}
		domNode.appendChild(eloSpan);
	}

	// CSS classes
	domNode.classList.add   ("chess4web-" + className);
	domNode.classList.remove("chess4web-template-" + className);
}

/**
 * Replace the content of a DOM node a full event description (event + round + date)
 */
function substituteFullEvent(domNode, pgnItem)
{
	// Event substitution
	domNode.innerHTML = "";
	var eventSpan = document.createElement("span");
	eventSpan.className = "chess4web-eventname";
	eventSpan.innerHTML = pgnItem["Event"];
	domNode.appendChild(eventSpan);

	// Round substitution
	if(pgnItem["Round"]!==undefined && pgnItem["Round"]!="?") {
		var roundSpan = document.createElement("span");
		roundSpan.className = "chess4web-round";
		roundSpan.innerHTML = pgnItem["Round"];
		domNode.appendChild(roundSpan);
	}

	// Date substitution
	if(pgnItem["Date"]!==undefined && pgnItem["Date"]!="????.??.??") {
		var dateSpan = document.createElement("span");
		dateSpan.className = "chess4web-date";
		dateSpan.innerHTML = formatDate(pgnItem["Date"]);
		domNode.appendChild(dateSpan);
	}

	// CSS classes
	domNode.classList.add   ("chess4web-FullEvent");
	domNode.classList.remove("chess4web-template-FullEvent");
}

/**
 * Replace the content of a DOM node with the list of moves
 */
function substituteMoves(domNode, pgnItem)
{
	// Initialize the tree walk
	if(pgnItem.mainVariation==null) {
		return;
	}
	domNode.innerHTML = "";

	// Auxiliary function for commentary printing
	function printCommentary(currentDomNode, currentPgnNode)
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
		var isLongCommentary = (textLength>=30) || (inlinedPositionNodes.length>0);
		commentary.className = "chess4web-" + (isLongCommentary ? "long-commentary" : "commentary");
		currentDomNode.appendChild(commentary);
	}

	// Recursive function for variation printing
	function printVariation(currentDomNode, variation, currentMoveNumber)
	{
		// First commentary
		printCommentary(currentDomNode, variation);
		var forcePrintMoveNumber = true;

		// Start iterating over the variation main line
		var currentPgnNode = variation.next;
		while(currentPgnNode!=null) {

			// Move (move number + notation + nags + miniboard)
			var move = document.createElement("span");
			move.className = "chess4web-move";
			if(currentPgnNode.parent.position.turn==WHITE) {
				var moveNumber = document.createTextNode(currentMoveNumber + ".");
				move.appendChild(moveNumber);
			}
			else if(forcePrintMoveNumber) {
				var moveNumber = document.createTextNode(currentMoveNumber + "\u2026");
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
			move.setAttribute("onmouseover", "expandMiniboard(event, this)");
			move.appendChild(miniboard);
			currentDomNode.appendChild(move);

			// Commentary
			printCommentary(currentDomNode, currentPgnNode);

			// Variations
			for(var k=0; k<currentPgnNode.variations.length; ++k) {
				var newVariation = document.createElement("span");
				newVariation.className = "chess4web-variation";
				printVariation(newVariation, currentPgnNode.variations[k], currentMoveNumber);
				currentDomNode.appendChild(newVariation);
			}

			// Back to the main line
			if(currentPgnNode.parent.position.turn==BLACK) {
				++currentMoveNumber;
			}
			forcePrintMoveNumber = (currentPgnNode.commentary!=null) || (currentPgnNode.variations.length>0);
			currentPgnNode = currentPgnNode.next;
		}
	}
	printVariation(domNode, pgnItem.mainVariation, 1);

	// Append the result
	var result = document.createElement("span");
	result.className = "chess4web-Result";
	result.innerHTML = pgnItem["Result"];
	domNode.appendChild(result);

	// CSS classes
	domNode.classList.add   ("chess4web-Moves");
	domNode.classList.remove("chess4web-template-Moves");
}

/**
 * Function used to expand the mini-board DIV elements, and to place them according
 * to the mouse pointer position
 */
function expandMiniboard(event, domNode)
{
	var targets = getElementsByClass("chess4web-position-miniature", "div", domNode);
	if(targets.length==0) {
		return;
	}
	var target = targets[0];
	if(target.childNodes.length==1 && (target.childNodes[0] instanceof Text)) {
		var fen = target.innerHTML;
		target.innerHTML = "";
		try {
			var position  = parseFEN(fen);
			var miniboard = renderPosition(position, chess4webDefaultMiniboardSquareSize, chess4webDefaultMiniboardShowCoordinate);
			target.appendChild(miniboard);
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
	target.style.left = event.pageX + "px";
	target.style.top  = (event.pageY+20) + "px";
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
function substituteOutputNode(outputDomNode, currentPgnItem)
{
	// Auxiliary recursive function
	function auxRecursiveSubstitution(node, pgnItem)
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
				case "chess4web-template-Moves": substituteMoves   (node, pgnItem); return;
				default:
					break;
			}
		}
		for(var k=0; k<node.childNodes.length; ++k) {
			auxRecursiveSubstitution(node.childNodes[k], pgnItem);
		}
	}

	// Root call
	auxRecursiveSubstitution(outputDomNode, currentPgnItem);
	outputDomNode.classList.remove("chess4web-hide-this");
}

/**
 * Hide the given node
 */
function chess4webHideNode(domNode)
{
	domNode.classList.add("chess4web-hide-this");
}

/**
 * Print a debug message
 */
function printDebug(message)
{
	var debugNode = document.getElementById("chess4web-debug");
	if(debugNode!=null) {
		debugNode.innerHTML += message + "\n";
	}
}

// Entry point
var chess4webConfigureExecuted = false;
function chess4webConfigure()
{
	if(chess4webConfigureExecuted) {
		return;
	}

	// Optional function to initialize chess4web
	if(typeof(chess4webInit)=="function") {
		chess4webInit();
	}
	chess4webConfigureExecuted = true;
}
