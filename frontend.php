<?php

// Short-code [fen][/fen]
add_shortcode('fen', 'rpbchessboard_shortcode_fen');
function rpbchessboard_shortcode_fen($atts, $content)
{
	return rpbchessboard_load_controller('Fen', $atts, $content);
}


// Short-code [pgndiagram] (to be used only within the commentary of a PGN game)
add_shortcode('pgndiagram', 'rpbchessboard_shortcode_diagram');
function rpbchessboard_shortcode_diagram($atts)
{
	return rpbchessboard_load_controller('PgnDiagram', $atts, '');
}


// Short-code [pgn][/pgn]
add_shortcode('pgn', 'rpbchessboard_shortcode_pgn');
function rpbchessboard_shortcode_pgn($atts, $content)
{
	return rpbchessboard_load_controller('Pgn', $atts, $content);
}


// Load the controller with the corresponding model name, and execute it.
function rpbchessboard_load_controller($modelName, $atts, $content)
{
	require_once(RPBCHESSBOARD_ABSPATH.'controllers/shortcode.php');
	$controller = new RPBChessboardControllerShortcode($modelName, $atts, $content);
	return $controller->run();
}
