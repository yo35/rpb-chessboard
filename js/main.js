
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
	var options = new ChessWidget.Options(defaultChessWidgetOptions, chessWidgetAttributes);
	var parsingSucceeded = PgnWidget.makeAt(pgn, nodeOut, options);

	// Make nodeIn invisible, and nodeOut visible.
	if(parsingSucceeded) {
		nodeIn.addClass('rpbchessboard-invisible');
	}
	nodeOut.removeClass('rpbchessboard-invisible');
}
