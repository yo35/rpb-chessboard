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

<h3 id="rpbchessboard-pgnNullMove"><?php _e('Null moves', 'rpb-chessboard'); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
			{<?php _e('A standard development scheme for white:', 'rpb-chessboard'); ?>}
			1. e4 -- 2. Nf3 -- 3. Bc4 -- 4. Nc3 -- 5. d4 -- 6. O-O {[pgndiagram]}<br/>
			[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
		</div>

		<p>
			<?php echo sprintf(
				__('A %1$s--%2$s token in the move list allows to skip the underlying move.', 'rpb-chessboard'),
				'<span class="rpbchessboard-sourceCode">',
				'</span>'
			); ?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnNullMove-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnNullMove-anchor').chessgame($.extend(true, <?php echo json_encode($model->getDefaultChessgameSettings()); ?>, {
							navigationBoard: 'none',
							diagramOptions: { squareSize: 28 },
							pgn:
								'{' + <?php echo json_encode(__('A standard development scheme for white:', 'rpb-chessboard')); ?> +
								'} 1. e4 -- 2. Nf3 -- 3. Bc4 -- 4. Nc3 -- 5. d4 -- 6. O-O {<div class="rpbui-chessgame-diagramAnchor"></div>} *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
