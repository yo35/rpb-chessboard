
/**
 * Read the text in the DOM node identified by `nodeInID`, try to interpret it
 * as a FEN string, and render the corresponding chessboard widget
 * in the DOM node identified by `nodeOutID`.
 *
 * @param {string} nodeInID
 * @param {string} nodeOutID
 */
function processFEN(nodeInID, nodeOutID)
{
	// Retrieve the nodes
	var nodeIn  = jQuery('#' + nodeInID );
	var nodeOut = jQuery('#' + nodeOutID);

	// Read the FEN string from the nodeIn
	var fen = nodeIn.text();

	// Clear nodeOut and put the chess widget as its child
	nodeOut.empty();
	nodeOut.append(ChessWidget.make(fen));

	// Make nodeIn invisible
	nodeIn.addClass('rpbchessboard-invisible');
}
