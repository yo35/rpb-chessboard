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
var chess4webDefaultMiniboardSquareSize = 36;

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
	var result = document.createElement("span");
	result.className = "chess4web-Result";
	result.innerHTML = pgnItem["Result"];
	domNode.appendChild(result);

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
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, true);
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
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, true);
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
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, true);
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
	var moveNodes = getElementsByClass("chess4web-move", "span", currentSelectedNode.parentNode, true);
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
 * Substitute the FEN inlined positions
 */
function substituteFenInlined(domNode) {
	try {
		var position = parseFEN(domNode.innerHTML);
		var table = renderPosition(position);
		var divIn = document.createElement("div");
		divIn.className = "chess4web-InlinedPosition";
		divIn.appendChild(table);
		var divOut = document.createElement("div");
		divOut.className = "chess4web-out";
		divOut.appendChild(divIn);
		domNode.parentNode.replaceChild(divOut, domNode);
	}
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
