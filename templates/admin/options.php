
<div id="rpbchessboard-admin-options">

	<h3><?php _e('Options', 'rpbchessboard'); ?></h3>



	<h4><?php _e('Chessboard aspect', 'rpbchessboard'); ?></h4>

	<div class="rpbchessboard-admin-columns">

		<div class="rpbchessboard-admin-column-left">
			<p>
				<input type="hidden" id="rpbchessboard-admin-squareSize" />
				<?php echo sprintf(
					__('Square size: %1$s pixels.', 'rpbchessboard'),
					'<span id="rpbchessboard-admin-squareSize-label"></span>'
				); ?>
				<div id="rpbchessboard-admin-squareSize-slider"></div>
			</p>
		</div>

		<div class="rpbchessboard-admin-column-right">
			<div id="rpbchessboard-admin-tuning-widget"></div>
		</div>

	</div>

	<script type="text/javascript">

		// Initial variables
		var squareSize = 32;


		// Refresh the tuning widget
		function refreshTuningWidget($)
		{
			// Build the option specifier object
			var options = new ChessWidget.Options(null);
			options.setSquareSize(squareSize);

			// Actual refresh
			var widget = ChessWidget.make('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1', options);
			$('#rpbchessboard-admin-tuning-widget').empty();
			$('#rpbchessboard-admin-tuning-widget').append(widget);

			// Additional labels and form fields
			$('#rpbchessboard-admin-squareSize-label').text(squareSize);
			$('#rpbchessboard-admin-squareSize').val(squareSize);
		}


		// Callback for the slider
		function onSlide($, newSquareSize)
		{
			if(newSquareSize==squareSize) {
				return;
			}
			squareSize = newSquareSize;
			refreshTuningWidget($);
		}


		// Initialization
		jQuery(document).ready(function($)
		{
			// Create the slider
			$('#rpbchessboard-admin-squareSize-slider').slider({
				value: squareSize,
				min: 24, max: 64, step: 4,
				slide: function( event, ui ) { onSlide($, ui.value); }
			});

			// Create the tuning widget
			refreshTuningWidget($);
		});

	</script>


</div>
