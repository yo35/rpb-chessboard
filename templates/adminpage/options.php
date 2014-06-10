<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div id="rpbchessboard-optionPage" class="rpbchessboard-jQuery-enableSmoothness">

	<form action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars($model->getFormAction()); ?>" />





		<h3><?php _e('Default chessboard aspect', 'rpbchessboard'); ?></h3>

		<div class="rpbchessboard-be-columns">

			<div>
				<p>
					<?php
						echo sprintf(__('Square size: %1$s pixels', 'rpbchessboard'),
							'<input type="text" id="rpbchessboard-squareSizeField" name="squareSize" '.
								'size="'      . htmlspecialchars($model->getDigitNumberForSquareSize()) . '" '.
								'maxLength="' . htmlspecialchars($model->getDigitNumberForSquareSize()) . '" '.
								'value="'     . htmlspecialchars($model->getDefaultSquareSize       ()) . '"/>'
						);
					?>
				</p>
				<div id="rpbchessboard-squareSizeField-slider"></div>
				<p>
					<input type="hidden" name="showCoordinates" value="0" />
					<input type="checkbox" id="rpbchessboard-showCoordinatesField" name="showCoordinates" value="1"
						<?php if($model->getDefaultShowCoordinates()): ?>checked="yes"<?php endif; ?>
					/>
					<label for="rpbchessboard-showCoordinatesField">
						<?php _e('Show coordinates', 'rpbchessboard'); ?>
					</label>
				</p>
			</div>

			<div>
				<div id="rpbchessboard-tuningChessboardWidget"></div>
			</div>

		</div>

		<script type="text/javascript">

			jQuery(document).ready(function($)
			{
				// State variables
				var squareSize      = $('#rpbchessboard-squareSizeField'     ).val();
				var showCoordinates = $('#rpbchessboard-showCoordinatesField').prop('checked');

				// Callback for the squareSize slider
				function onSquareSizeChange(newSquareSize)
				{
					if(newSquareSize === squareSize) {
						return;
					}
					squareSize = newSquareSize;
					$('#rpbchessboard-squareSizeField'       ).val(squareSize);
					$('#rpbchessboard-tuningChessboardWidget').chessboard('option', 'squareSize', squareSize);
				}

				// Callback for the showCoordinates checkbox
				function onShowCoordinatesChange(newShowCoordinates)
				{
					if(newShowCoordinates === showCoordinates) {
						return;
					}
					showCoordinates = newShowCoordinates;
					$('#rpbchessboard-tuningChessboardWidget').chessboard('option', 'showCoordinates', showCoordinates);
				}

				// Disable the square-size text field, create a slider instead.
				$('#rpbchessboard-squareSizeField').prop('readonly', true);
				$('#rpbchessboard-squareSizeField-slider').slider({
					value: squareSize,
					min: <?php echo json_encode($model->getMinimumSquareSize()); ?>,
					max: <?php echo json_encode($model->getMaximumSquareSize()); ?>,
					slide: function(event, ui) { onSquareSizeChange(ui.value); }
				});

				// Initialize the show-coordinates checkbox.
				$('#rpbchessboard-showCoordinatesField').change(function() {
					onShowCoordinatesChange($('#rpbchessboard-showCoordinatesField').prop('checked'));
				});

				// Create the chessboard widget.
				$('#rpbchessboard-tuningChessboardWidget').chessboard({
					position       : 'start'        ,
					squareSize     : squareSize     ,
					showCoordinates: showCoordinates
				});
			});

		</script>





		<h3><?php _e('Compatibility with other chess plugins', 'rpbchessboard'); ?></h3>

		<p class="description">
			<?php echo sprintf(
				__(
					'By default, the RPB Chessboard plugin use the %1$s[fen][/fen]%2$s and %1$s[pgn][/pgn]%2$s tags '.
					'for FEN diagrams and PGN games. However, this behavior cause conflicts when other WordPress plugins '.
					'(typically chess plugins) that use the same tags are simultaneously in use. Activating the compatibility mode '.
					'for the FEN diagram tag makes RPB Chessboard use %1$s[fen_compat][/fen_compat]%2$s instead of %1$s[fen][/fen]%2$s '.
					'to avoid those conflicts. Similarly, with the PGN compatibility mode, %1$s[pgn_compat][/pgn_compat]%2$s '.
					'is used instead of %1$s[pgn][/pgn]%2$s.',
				'rpbchessboard'),
				'<span class="rpbchessboard-be-sourceCode">',
				'</span>'
			); ?>
		</p>

		<p>
			<input type="hidden" name="fenCompatibilityMode" value="0" />
			<input type="checkbox" id="rpbchessboard-fenCompatibilityModeField" name="fenCompatibilityMode" value="1"
				<?php if($model->getFENCompatibilityMode()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-fenCompatibilityModeField">
				<?php _e('Compatibility mode for the FEN diagram tag', 'rpbchessboard'); ?>
			</label>
		</p>

		<p>
			<input type="hidden" name="pgnCompatibilityMode" value="0" />
			<input type="checkbox" id="rpbchessboard-pgnCompatibilityModeField" name="pgnCompatibilityMode" value="1"
				<?php if($model->getPGNCompatibilityMode()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-pgnCompatibilityModeField">
				<?php _e('Compatibility mode for the PGN game tag', 'rpbchessboard'); ?>
			</label>
		</p>






		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save changes', 'rpbchessboard'); ?>" />
		</p>

	</form>

</div>
