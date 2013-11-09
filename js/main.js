/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>            *
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
 * Default options applicable to the chess widgets.
 * @type {ChessWidget.Options}
 */
var defaultChessWidgetOptions = null;



/**
 * Hide the node corresponding to the given ID.
 *
 * @param {string} nodeID
 */
function hideJavascriptWarning(nodeID)
{
	jQuery('#' + nodeID).addClass('rpbchessboard-invisible');
}



/**
 * Define the default aspect options for the chess widgets.
 *
 * @param {ChessWidget.Attributes} chessWidgetAttributes
 */
function defineDefaultChessWidgetOptions(chessWidgetAttributes)
{
	defaultChessWidgetOptions = new ChessWidget.Options(null, chessWidgetAttributes);
}



/**
 * Read the text in the DOM node identified by `nodeInID`, try to interpret it
 * as a FEN string, and render the corresponding chessboard widget
 * in the DOM node identified by `nodeOutID`.
 *
 * @param {string} nodeInID
 * @param {string} nodeOutID
 * @param {ChessWidget.Attributes} [chessWidgetAttributes=null]
 */
function processFEN(nodeInID, nodeOutID, chessWidgetAttributes)
{
	// Retrieve the nodes
	var nodeIn  = jQuery('#' + nodeInID );
	var nodeOut = jQuery('#' + nodeOutID);

	// Read the FEN string from the nodeIn
	var fen = nodeIn.text();

	// Clear nodeOut and put the chess widget as its child
	var options = new ChessWidget.Options(defaultChessWidgetOptions, chessWidgetAttributes);
	nodeOut.empty();
	nodeOut.append(ChessWidget.make(fen, options));

	// Make nodeIn invisible
	nodeIn .addClass   ('rpbchessboard-invisible');
	nodeOut.removeClass('rpbchessboard-invisible');
}



/**
 * Read the text in the DOM node identified by `nodeInID`, try to interpret it
 * as a PGN string, and render the corresponding information
 * in the DOM node identified by `nodeOutID`.
 *
 * @param {string} nodeInID
 * @param {string} nodeOutID
 * @param {ChessWidget.Attributes} [chessWidgetAttributes=null]
 */
function processPGN(nodeInID, nodeOutID, chessWidgetAttributes)
{
	// Retrieve the nodes
	var nodeIn  = jQuery('#' + nodeInID );
	var nodeOut = jQuery('#' + nodeOutID);

	// Read the PGN string from the nodeIn
	var pgn = nodeIn.text();

	// PGN rendering
	var inlineOptions = new ChessWidget.Options(defaultChessWidgetOptions, chessWidgetAttributes);
	var navOptions    = new ChessWidget.Options(defaultChessWidgetOptions, {flip: inlineOptions.getFlip()});
	var parsingSucceeded = PgnWidget.makeAt(pgn, nodeOut, inlineOptions, navOptions);

	// Make nodeIn invisible, and nodeOut visible.
	if(parsingSucceeded) {
		nodeIn.addClass('rpbchessboard-invisible');
	}
	nodeOut.removeClass('rpbchessboard-invisible');
}
