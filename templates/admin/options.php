
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
				var options = new ChessWidget.Options(null);
				options.setSquareSize     (squareSize     );
				options.setShowCoordinates(showCoordinates);

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
