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

<h3 id="rpbchessboard-pgnAttributeNavigationButtons"><?php _e('Navigation toolbar', 'rpbchessboard'); ?></h3>

<div id="rpbchessboard-pgnAttributeNavigationButtons-content" class="rpbchessboard-columns">
	<div>

		<p>
			<?php echo sprintf(__('The %1$s attribute controls whether the button that allows to flip the board ' .
				'is available or not (below the navigation board).', 'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">show_flip_button</span>'); ?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e('Value', 'rpbchessboard'); ?></th>
					<th><?php _e('Default', 'rpbchessboard'); ?></th>
					<th><?php _e('Description', 'rpbchessboard'); ?></th>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowFlipButton-value">false</a></td>
					<td><?php if(!$model->getDefaultShowFlipButton()): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('No flip button.', 'rpbchessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowFlipButton-value">true</a></td>
					<td><?php if($model->getDefaultShowFlipButton()): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The flip button is visible.', 'rpbchessboard'); ?></td>
				</tr>
			</tbody>
		</table>

		<p>
			<?php echo sprintf(__('Similarly, the %1$s attribute affects whether the button that allows to ' .
				'download the game is available or not.', 'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">show_download_button</span>'); ?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e('Value', 'rpbchessboard'); ?></th>
					<th><?php _e('Default', 'rpbchessboard'); ?></th>
					<th><?php _e('Description', 'rpbchessboard'); ?></th>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowDownloadButton-value">false</a></td>
					<td><?php if(!$model->getDefaultShowDownloadButton()): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('No download button.', 'rpbchessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowDownloadButton-value">true</a></td>
					<td><?php if($model->getDefaultShowDownloadButton()): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The download button is visible.', 'rpbchessboard'); ?></td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php echo sprintf(
				'[%1$s <strong>show_flip_button=<span id="rpbchessboard-pgnAttributeShowFlipButton-sourceCodeExample">false</span></strong> ' .
				'<strong>show_download_button=<span id="rpbchessboard-pgnAttributeShowDownloadButton-sourceCodeExample">false</span></strong>] ... [/%1$s]',
				htmlspecialchars($model->getPGNShortcode())
			); ?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnAttributeNavigationButtons-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnAttributeNavigationButtons-anchor').chessgame($.extend(true, <?php echo json_encode($model->getDefaultChessgameSettings()); ?>, {
							navigationBoard: 'floatLeft',
							navigationBoardOptions: { squareSize: 28 },
							showFlipButton: false,
							showDownloadButton: false,
							pgn:
								'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
								'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
								'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
						}));
						$('.rpbchessboard-pgnAttributeShowFlipButton-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							$('#rpbchessboard-pgnAttributeNavigationButtons-anchor').chessgame('option', 'showFlipButton', value);
							$('#rpbchessboard-pgnAttributeShowFlipButton-sourceCodeExample').text(value);
						});
						$('.rpbchessboard-pgnAttributeShowDownloadButton-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							$('#rpbchessboard-pgnAttributeNavigationButtons-anchor').chessgame('option', 'showDownloadButton', value);
							$('#rpbchessboard-pgnAttributeShowDownloadButton-sourceCodeExample').text(value);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
