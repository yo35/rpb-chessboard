<?php

// Short-code [fen][/fen]
add_shortcode('fen', 'rpbchessboard_shortcode_fen');
function rpbchessboard_shortcode_fen($atts, $content)
{
	ob_start();
	include(RPBCHESSBOARD_ABSPATH.'template-init.php');
	include(RPBCHESSBOARD_ABSPATH.'template-fen.php');
	return ob_get_clean();
}


// Short-code [pgndiagram] (to be used only within the commentary of a PGN game)
add_shortcode('pgndiagram', 'rpbchessboard_shortcode_diagram');
function rpbchessboard_shortcode_diagram($atts)
{
	return '<span class="jsChessLib-anchor-diagram"></span>';
}


// Short-code [pgn][/pgn]
add_shortcode('pgn', 'rpbchessboard_shortcode_pgn');
function rpbchessboard_shortcode_pgn($atts, $content='')
{
	ob_start();
	include(RPBCHESSBOARD_ABSPATH.'template-init.php');
	include(RPBCHESSBOARD_ABSPATH.'template-pgn.php');
	return ob_get_clean();
}
