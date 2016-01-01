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

<h3 id="rpbchessboard-fenAttributeSquareSize"><?php _e('Square size', 'rpbchessboard'); ?></h3>

<div id="rpbchessboard-fenAttributeSquareSize-content" class="rpbchessboard-columns">
	<div>

		<p>
			<?php echo sprintf(__('The %1$s attribute controls the size of the chessboard squares.', 'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">square_size</span>'); ?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e('Value', 'rpbchessboard'); ?></th>
					<th><?php _e('Default', 'rpbchessboard'); ?></th>
					<th><?php _e('Description', 'rpbchessboard'); ?></th>
				</tr>
				<?php foreach($model->getSquareSizeList() as $squareSize): ?>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeSquareSize-value"><?php echo htmlspecialchars($squareSize); ?></a></td>
						<td><?php if($model->getDefaultSquareSize() === $squareSize): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php echo sprintf(__('The square width is %1$s pixels.', 'rpbchessboard'), htmlspecialchars($squareSize)); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td><?php _e('etc...', 'rpbchessboard'); ?></td>
					<td></td>
					<td><?php echo sprintf(__('Any value between %1$s and %2$s can be used.', 'rpbchessboard'),
						htmlspecialchars($model->getMinimumSquareSize()), htmlspecialchars($model->getMaximumSquareSize())); ?></td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php echo sprintf(
				'[%1$s <strong>square_size=<span id="rpbchessboard-fenAttributeSquareSize-sourceCodeExample">%2$s</span></strong>] ... [/%1$s]',
				htmlspecialchars($model->getFENShortcode()),
				htmlspecialchars($model->getSquareSizeInitialExample())
			); ?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-fenAttributeSquareSize-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-fenAttributeSquareSize-anchor').chessboard({ position: 'start', squareSize: <?php echo json_encode($model->getSquareSizeInitialExample()); ?> });
						$('.rpbchessboard-fenAttributeSquareSize-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							$('#rpbchessboard-fenAttributeSquareSize-anchor').chessboard('option', 'squareSize', value);
							$('#rpbchessboard-fenAttributeSquareSize-sourceCodeExample').text(value);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
