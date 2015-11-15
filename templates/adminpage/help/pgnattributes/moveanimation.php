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

<h3 id="rpbchessboard-pgnAttributeMoveAnimation"><?php _e('Move animation', 'rpbchessboard'); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<p>
			<?php echo sprintf(__('The %1$s attribute controls the duration of the move animation on the navigation board.', 'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">animation_speed</span>'); ?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e('Value', 'rpbchessboard'); ?></th>
					<th><?php _e('Default', 'rpbchessboard'); ?></th>
					<th><?php _e('Description', 'rpbchessboard'); ?></th>
				</tr>
				<?php foreach($model->getAnimationSpeedList() as $animationSpeed): ?>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeAnimationSpeed-value"><?php echo htmlspecialchars($animationSpeed); ?></a></td>
						<td><?php if($model->getDefaultAnimationSpeed() === $animationSpeed): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php echo $animationSpeed===0 ? __('No animation', 'rpbchessboard') : sprintf(__('The animation lasts %1$s milliseconds.',
							'rpbchessboard'), htmlspecialchars($animationSpeed)); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td><?php _e('etc...', 'rpbchessboard'); ?></td>
					<td></td>
					<td><?php echo sprintf(__('Any value between %1$s and %2$s can be used.', 'rpbchessboard'),
						'0', htmlspecialchars($model->getMaximumAnimationSpeed())); ?></td>
				</tr>
			</tbody>
		</table>

		<p>
			<?php echo sprintf(__('The %1$s attribute controls whether an arrow should be used or not '.
				'to highlight the moves on the navigation board.', 'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">animation_speed</span>'); ?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e('Value', 'rpbchessboard'); ?></th>
					<th><?php _e('Default', 'rpbchessboard'); ?></th>
					<th><?php _e('Description', 'rpbchessboard'); ?></th>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowMoveArrow-value">false</a></td>
					<td><?php if(!$model->getDefaultShowMoveArrow()): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('No arrow is shown.', 'rpbchessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowMoveArrow-value">true</a></td>
					<td><?php if($model->getDefaultShowMoveArrow()): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The moves are highlighted by an arrow.', 'rpbchessboard'); ?></td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php echo sprintf(
				'[%1$s <strong>animation_speed=<span id="rpbchessboard-pgnAttributeAnimationSpeed-sourceCodeExample">0</span></strong> ' .
				'<strong>show_move_arrow=<span id="rpbchessboard-pgnAttributeShowMoveArrow-sourceCodeExample">false</span></strong>] ... [/%1$s]',
				htmlspecialchars($model->getPGNShortcode())
			); ?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnAttributeMoveAnimation-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame({
							navigationBoard: 'floatLeft',
							navigationBoardOptions: { squareSize: 28, showCoordinates: false, animationSpeed: 0, showMoveArrow: false },
							pgn:
								'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
								'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
								'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
						});
						$('.rpbchessboard-pgnAttributeAnimationSpeed-value').click(function(e) {
							e.preventDefault();
							var options = $('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions');
							options.animationSpeed = $(this).text();
							$('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions', options);
							$('#rpbchessboard-pgnAttributeAnimationSpeed-sourceCodeExample').text(options.animationSpeed);
						});
						$('.rpbchessboard-pgnAttributeShowMoveArrow-value').click(function(e) {
							e.preventDefault();
							var options = $('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions');
							options.showMoveArrow = $(this).text();
							$('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions', options);
							$('#rpbchessboard-pgnAttributeShowMoveArrow-sourceCodeExample').text(options.showMoveArrow);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
