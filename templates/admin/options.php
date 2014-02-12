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

<div id="rpbchessboard-admin-options">

	<form action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<input type="hidden" name="action" value="<?php echo htmlspecialchars($model->getFormAction()); ?>" />

		<h3><?php _e('Default chessboard aspect', 'rpbchessboard'); ?></h3>

		<div class="rpbchessboard-admin-columns">

			<div class="rpbchessboard-admin-column-left">
				<p>
					<?php
						echo sprintf(__('Square size: %1$s pixels', 'rpbchessboard'),
							'<input type="text" id="rpbchessboard-admin-squareSize" name="squareSize" '.
								'size="'.htmlspecialchars($model->getDigitNumberForSquareSize()).'" '.
								'maxLength="'.htmlspecialchars($model->getDigitNumberForSquareSize()).'" '.
								'value="'.htmlspecialchars($model->getDefaultSquareSize()).'" '.
							'/>'
						);
					?>
					<div id="rpbchessboard-admin-squareSize-slider"></div>
				</p>
				<p>
					<input type="hidden" name="showCoordinates" value="0" />
					<input type="checkbox" id="rpbchessboard-admin-showCoordinates" name="showCoordinates" value="1"
						<?php if($model->getDefaultShowCoordinates()): ?>checked="yes"<?php endif; ?>
					/>
					<label for="rpbchessboard-admin-showCoordinates">
						<?php _e('Show coordinates', 'rpbchessboard'); ?>
					</label>
				</p>
			</div>

			<div class="rpbchessboard-admin-column-right">
				<div id="rpbchessboard-admin-tuning-widget"></div>
			</div>

		</div>

		<h3><?php _e('Compatibility with other chess plugins', 'rpbchessboard'); ?></h3>

		<p class="description">
			<?php echo sprintf(
				__(
					'By default, the RPB Chessboard plugin use the %1$s[fen][/fen]%2$s '.
					'and %1$s[pgn][/pgn]%2$s tags for FEN diagrams and PGN games. '.
					'However, this behavior cause conflicts when other Wordpress plugins '.
					'(typically chess plugins) that use the same tags are simultaneously in use. '.
					'Activating the compatibility mode for the FEN diagram tag makes RPB Chessboard '.
					'use %1$s[fen_compat][/fen_compat]%2$s instead of %1$s[fen][/fen]%2$s '.
					'to avoid those conflicts. Similarly, with the PGN compatibility mode, '.
					'%1$s[pgn_compat][/pgn_compat]%2$s is used instead of %1$s[pgn][/pgn]%2$s.',
				'rpbchessboard'),
				'<span class="rpbchessboard-admin-code-inline">',
				'</span>'
			); ?>
		</p>

		<p>
			<input type="hidden" name="fenCompatibilityMode" value="0" />
			<input type="checkbox" id="rpbchessboard-admin-fenCompatibilityMode" name="fenCompatibilityMode" value="1"
				<?php if($model->getFENCompatibilityMode()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-admin-fenCompatibilityMode">
				<?php _e('Compatibility mode for the FEN diagram tag', 'rpbchessboard'); ?>
			</label>
		</p>

		<p>
			<input type="hidden" name="pgnCompatibilityMode" value="0" />
			<input type="checkbox" id="rpbchessboard-admin-pgnCompatibilityMode" name="pgnCompatibilityMode" value="1"
				<?php if($model->getPGNCompatibilityMode()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-admin-pgnCompatibilityMode">
				<?php _e('Compatibility mode for the PGN game tag', 'rpbchessboard'); ?>
			</label>
		</p>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save changes', 'rpbchessboard'); ?>" />
		</p>

		<script type="text/javascript">

			// State variables
			var squareSize     ;
			var showCoordinates;


			// Refresh the tuning widget
			function refreshTuningWidget($)
			{
				// Build the option specifier object
				var options = {
					squareSize     : squareSize     ,
					showCoordinates: showCoordinates
				};

				// Actual refresh
				var widget = ChessWidget.make('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', options);
				$('#rpbchessboard-admin-tuning-widget').empty();
				$('#rpbchessboard-admin-tuning-widget').append(widget);

				// Additional labels and form fields
				$('#rpbchessboard-admin-squareSize').val(squareSize);
			}


			// Callback for the squareSize slider
			function onSquareSizeChange($, newSquareSize)
			{
				if(newSquareSize==squareSize) {
					return;
				}
				squareSize = newSquareSize;
				refreshTuningWidget($);
			}


			// Callback for the showCoordinates checkbox
			function onShowCoordinatesChange($, newShowCoordinates)
			{
				if(newShowCoordinates==showCoordinates) {
					return;
				}
				showCoordinates = newShowCoordinates;
				refreshTuningWidget($);
			}


			// Initialization
			jQuery(document).ready(function($)
			{
				// Load the initial values
				squareSize      = $('#rpbchessboard-admin-squareSize'     ).val();
				showCoordinates = $('#rpbchessboard-admin-showCoordinates').prop('checked');

				// Disable the square-size text field
				$('#rpbchessboard-admin-squareSize').prop('readonly', true);

				// Create the squareSize slider
				$('#rpbchessboard-admin-squareSize-slider').slider({
					value: squareSize,
					min: <?php echo json_encode($model->getMinimumSquareSize()); ?>,
					max: <?php echo json_encode($model->getMaximumSquareSize()); ?>,
					step: <?php echo json_encode($model->getStepSquareSize()); ?>,
					slide: function( event, ui ) { onSquareSizeChange($, ui.value); }
				});

				// Create the showCoordinates checkbox
				$('#rpbchessboard-admin-showCoordinates').change(function() {
					onShowCoordinatesChange($, $('#rpbchessboard-admin-showCoordinates').prop('checked'));
				});

				// Create the tuning widget
				refreshTuningWidget($);
			});

		</script>

	</form>

</div>
