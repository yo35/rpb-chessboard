
<div id="rpbchessboard-admin-options">

	<h3><?php _e('Default chessboard aspect', 'rpbchessboard'); ?></h3>

	<div class="rpbchessboard-admin-columns">

		<div class="rpbchessboard-admin-column-left">
			<p>
				<input type="hidden" id="rpbchessboard-admin-squareSize" />
				<?php echo sprintf(
					__('Square size: %1$s pixels', 'rpbchessboard'),
					'<span id="rpbchessboard-admin-squareSize-label"></span>'
				); ?>
				<div id="rpbchessboard-admin-squareSize-slider"></div>
			</p>
			<p>
				<input type="checkbox" id="rpbchessboard-admin-showCoordinates"></input>
				<label for="rpbchessboard-admin-showCoordinates">
					<?php _e('Show coordinates', 'rpbchessboard'); ?>
				</label>
			</p>
		</div>

		<div class="rpbchessboard-admin-column-right">
			<div id="rpbchessboard-admin-tuning-widget"></div>
		</div>

	</div>

	<script type="text/javascript">

		// Initial variables
		var squareSize      = <?php echo json_encode($model->getDefaultSquareSize     ()); ?>;
		var showCoordinates = <?php echo json_encode($model->getDefaultShowCoordinates()); ?>;


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
			$('#rpbchessboard-admin-squareSize-label').text(squareSize);
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
			// Create the squareSize slider
			$('#rpbchessboard-admin-squareSize-slider').slider({
				value: squareSize,
				min: 24, max: 64, step: 4,
				slide: function( event, ui ) { onSquareSizeChange($, ui.value); }
			});

			// Create the showCoordinates checkbox
			$('#rpbchessboard-admin-showCoordinates').prop('checked', showCoordinates);
			$('#rpbchessboard-admin-showCoordinates').change(function() {
				onShowCoordinatesChange($, $('#rpbchessboard-admin-showCoordinates').prop('checked'));
			});

			// Create the tuning widget
			refreshTuningWidget($);
		});

	</script>


</div>
