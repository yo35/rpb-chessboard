<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3 id="rpbchessboard-pgnAttributeBoardAspect"><?php _e('Chessboard aspect', 'rpbchessboard'); ?></h3>

<div class="rpbchessboard-sourceCode">
	<?php echo sprintf(
		'[%1$s <strong>flip</strong>=... <strong>square_size</strong>=... <strong>show_coordinates</strong>=...] ... [/%1$s]',
		htmlspecialchars($model->getPGNShortcode())
	); ?>
</div>

<p>
	<?php echo sprintf(
		__(
			'The %1$s, %2$s and %3$s attributes control the aspect of both the navigation board and the chessboard diagrams '.
			'inserted using tag %4$s. These attributes are identical to those used to customize the aspect of standalone FEN diagrams '.
			'(those inserted using tag %5$s): see %6$shelp on FEN diagram attributes%7$s for more details about them.',
		'rpbchessboard'),
		'<span class="rpbchessboard-sourceCode">flip</span>',
		'<span class="rpbchessboard-sourceCode">square_size</span>',
		'<span class="rpbchessboard-sourceCode">show_coordinates</span>',
		'<span class="rpbchessboard-sourceCode">[pgndiagram]</span>',
		sprintf('<span class="rpbchessboard-sourceCode">[%1$s][/%1$s]</span>', htmlspecialchars($model->getFENShortcode())),
		'<a href="' . htmlspecialchars($model->getHelpOnFENAttributesURL()) . '">',
		'</a>'
	); ?>
</p>
