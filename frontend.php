<?php
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
