
/**
 *
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
