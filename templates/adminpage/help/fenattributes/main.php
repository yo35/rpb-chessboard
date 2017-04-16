<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2017  Yoann Le Montagner <yo35 -at- melix.net>       *
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
?>

<div id="rpbchessboard-helpFENAttributesPage" class="rpbchessboard-helpPage">

	<p>
		<?php echo sprintf(
			__(
				'Several attributes may be passed to the %1$s[%3$s][/%3$s]%2$s tags '.
				'in order to customize how the FEN diagrams are displayed. '.
				'All these attributes are optional: if not specified, the default setting '.
				'(defined by the blog administrator) applies. '.
				'These attributes are presented in this page.',
			'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getFENShortcode())
		); ?>
	</p>

	<ol class="rpbchessboard-outline">
		<li><a href="#rpbchessboard-fenAttributeFlip"><?php _e('Board flipping', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-fenAttributeSquareSize"><?php _e('Square size', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-fenAttributeShowCoordinates"><?php _e('Show coordinates', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-fenAttributeMarkers"><?php _e('Square and arrow markers', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-fenAttributeColorsetPieceset"><?php _e('Colorset and pieceset', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-fenAttributeDiagramAlignment"><?php _e('Diagram alignment', 'rpbchessboard'); ?></a></li>
	</ol>

	<?php
		RPBChessboardHelperLoader::printTemplate('AdminPage/Help/FENAttributes/Flip'            , $model);
		RPBChessboardHelperLoader::printTemplate('AdminPage/Help/FENAttributes/SquareSize'      , $model);
		RPBChessboardHelperLoader::printTemplate('AdminPage/Help/FENAttributes/ShowCoordinates' , $model);
		RPBChessboardHelperLoader::printTemplate('AdminPage/Help/FENAttributes/Markers'         , $model);
		RPBChessboardHelperLoader::printTemplate('AdminPage/Help/FENAttributes/ColorsetPieceset', $model);
		RPBChessboardHelperLoader::printTemplate('AdminPage/Help/FENAttributes/DiagramAlignment', $model);
	?>

</div>
