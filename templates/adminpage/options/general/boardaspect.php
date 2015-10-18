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

<h3><?php _e('Chessboard aspect', 'rpbchessboard'); ?></h3>


<div class="rpbchessboard-columns">
	<div id="rpbchessboard-tuningChessboardParameterColumn">

		<p>
			<?php
				echo sprintf(__('Square size: %1$s pixels', 'rpbchessboard'),
					'<input type="text" id="rpbchessboard-squareSizeField" class="rpbchessboard-squareSizeField" name="squareSize" ' .
						'size="'      . htmlspecialchars($model->getDigitNumberForSquareSize()) . '" ' .
						'maxLength="' . htmlspecialchars($model->getDigitNumberForSquareSize()) . '" ' .
						'value="'     . htmlspecialchars($model->getDefaultSquareSize       ()) . '"/>'
				);
			?>
		</p>

		<div id="rpbchessboard-squareSizeSlider" class="rpbchessboard-slider"></div>

		<p>
			<input type="hidden" name="showCoordinates" value="0" />
			<input type="checkbox" id="rpbchessboard-showCoordinatesField" name="showCoordinates" value="1"
				<?php if($model->getDefaultShowCoordinates()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-showCoordinatesField"><?php _e('Show coordinates', 'rpbchessboard'); ?></label>
		</p>

	</div>
	<div>

		<div id="rpbchessboard-tuningChessboardWidget"></div>

	</div>
</div>


<p class="description">
	<?php echo sprintf(
		__(
			'Notice that specific chessboard aspect settings can be defined for %1$ssmall-screen devices%2$s (such as smartphones).',
		'rpbchessboard'),
		'<a href="' . htmlspecialchars($model->getOptionsSmallScreensURL()) . '">',
		'</a>'
	); ?>
</p>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		// State variables
		var squareSize      = $('#rpbchessboard-squareSizeField'     ).val();
		var showCoordinates = $('#rpbchessboard-showCoordinatesField').prop('checked');

		// Callback for the squareSize slider
		function onSquareSizeChange(newSquareSize) {
			if(newSquareSize === squareSize) {
				return;
			}
			squareSize = newSquareSize;
			$('#rpbchessboard-squareSizeField'       ).val(squareSize);
			$('#rpbchessboard-tuningChessboardWidget').chessboard('option', 'squareSize', squareSize);
		}

		// Callback for the showCoordinates checkbox
		function onShowCoordinatesChange(newShowCoordinates) {
			if(newShowCoordinates === showCoordinates) {
				return;
			}
			showCoordinates = newShowCoordinates;
			$('#rpbchessboard-tuningChessboardWidget').chessboard('option', 'showCoordinates', showCoordinates);
		}

		// Disable the square-size text field, create a slider instead.
		$('#rpbchessboard-squareSizeField').prop('readonly', true);
		$('#rpbchessboard-squareSizeSlider').slider({
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
