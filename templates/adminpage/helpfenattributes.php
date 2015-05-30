<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
				'Several parameters may be passed to the %1$s[%3$s][/%3$s]%2$s tags '.
				'in order to customize how the FEN diagrams are displayed. '.
				'All these parameters are optional: if not specified, the default setting '.
				'(defined by the website administrator) applies. '.
				'These parameters are presented in this page.',
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
	</ol>



	<h3 id="rpbchessboard-fenAttributeFlip"><?php _e('Board flipping', 'rpbchessboard'); ?></h3>

	<p>TODO</p>



	<h3 id="rpbchessboard-fenAttributeSquareSize"><?php _e('Square size', 'rpbchessboard'); ?></h3>

	<p>TODO</p>



	<h3 id="rpbchessboard-fenAttributeShowCoordinates"><?php _e('Show coordinates', 'rpbchessboard'); ?></h3>

	<p>TODO</p>

</div>
