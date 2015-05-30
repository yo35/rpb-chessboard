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

	<div class="rpbchessboard-columns">
		<div>
			<p>
				<?php echo sprintf(__('The %1$s parameter controls whether the chessboard is rotated or not.', 'rpbchessboard'),
					'<span class="rpbchessboard-sourceCode">flip</span>'); ?>
			</p>
			<table>
				<tbody>
					<tr>
						<th><?php _e('Value', 'rpbchessboard'); ?></th>
						<th><?php _e('Description', 'rpbchessboard'); ?></th>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeFlip-value">false</a></td>
						<td><?php _e('The board is seen from White\'s point of view.', 'rpbchessboard'); ?></td>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeFlip-value">true</a></td>
						<td><?php _e('The board is seen from Black\'s point of view.', 'rpbchessboard'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<div class="rpbchessboard-sourceCode">
				<?php echo sprintf(
					'[%1$s <strong>flip=<span id="rpbchessboard-fenAttributeFlip-sourceCodeExample">false</span></strong>] ... [/%1$s]',
					htmlspecialchars($model->getFENShortcode())
				); ?>
			</div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-fenAttributeFlip-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-fenAttributeFlip-anchor').chessboard({ position: 'start', squareSize: 28 });
							$('.rpbchessboard-fenAttributeFlip-value').click(function(e) {
								e.preventDefault();
								var value = $(this).text();
								$('#rpbchessboard-fenAttributeFlip-anchor').chessboard('option', 'flip', value);
								$('#rpbchessboard-fenAttributeFlip-sourceCodeExample').text(value);
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>



	<h3 id="rpbchessboard-fenAttributeSquareSize"><?php _e('Square size', 'rpbchessboard'); ?></h3>

	<p>TODO</p>



	<h3 id="rpbchessboard-fenAttributeShowCoordinates"><?php _e('Show coordinates', 'rpbchessboard'); ?></h3>

	<div class="rpbchessboard-columns">
		<div>
			<p>
				<?php echo sprintf(__('The %1$s parameter controls whether the row and column coordinates are visible or not.', 'rpbchessboard'),
					'<span class="rpbchessboard-sourceCode">show_coordinates</span>'); ?>
			</p>
			<table>
				<tbody>
					<tr>
						<th><?php _e('Value', 'rpbchessboard'); ?></th>
						<th><?php _e('Description', 'rpbchessboard'); ?></th>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeShowCoordinates-value">false</a></td>
						<td><?php _e('The row and column coordinates are hidden.', 'rpbchessboard'); ?></td>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeShowCoordinates-value">true</a></td>
						<td><?php _e('The row and column coordinates are visible.', 'rpbchessboard'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<div class="rpbchessboard-sourceCode">
				<?php echo sprintf(
					'[%1$s <strong>show_coordinates=<span id="rpbchessboard-fenAttributeShowCoordinates-sourceCodeExample">true</span></strong>] ... [/%1$s]',
					htmlspecialchars($model->getFENShortcode())
				); ?>
			</div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-fenAttributeShowCoordinates-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-fenAttributeShowCoordinates-anchor').chessboard({ position: 'start', squareSize: 28, showCoordinates: true });
							$('.rpbchessboard-fenAttributeShowCoordinates-value').click(function(e) {
								e.preventDefault();
								var value = $(this).text();
								$('#rpbchessboard-fenAttributeShowCoordinates-anchor').chessboard('option', 'showCoordinates', value);
								$('#rpbchessboard-fenAttributeShowCoordinates-sourceCodeExample').text(value);
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>

</div>
