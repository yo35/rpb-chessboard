<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php esc_html_e( 'Chessboard aspect', 'rpb-chessboard' ); ?></h3>


<div class="rpbchessboard-columns">
	<div id="rpbchessboard-tuningChessboardParameterColumn">

		<p>
			<?php
				printf(
					esc_html__( 'Square size: %1$s pixels', 'rpb-chessboard' ),
					'<input type="text" id="rpbchessboard-squareSizeField" class="rpbchessboard-squareSizeField" name="squareSize" ' .
						'size="' . esc_attr( $model->getDigitNumberForSquareSize() ) . '" ' .
						'maxLength="' . esc_attr( $model->getDigitNumberForSquareSize() ) . '" ' .
						'value="' . esc_attr( $model->getDefaultSquareSize() ) . '"/>'
				);
			?>
		</p>

		<div id="rpbchessboard-squareSizeSlider" class="rpbchessboard-slider"></div>

		<p>
			<input type="hidden" name="showCoordinates" value="0" />
			<input type="checkbox" id="rpbchessboard-showCoordinatesField" name="showCoordinates" value="1"
				<?php echo $model->getDefaultShowCoordinates() ? 'checked="yes"' : ''; ?>
			/>
			<label for="rpbchessboard-showCoordinatesField"><?php esc_html_e( 'Show coordinates', 'rpb-chessboard' ); ?></label>
		</p>

		<p>
			<label for="rpbchessboard-colorsetField"><?php esc_html_e( 'Colorset:', 'rpb-chessboard' ); ?></label>
			<select id="rpbchessboard-colorsetField" name="colorset">
				<?php foreach ( $model->getAvailableColorsets() as $colorset ) : ?>
				<option value="<?php echo esc_attr( $colorset ); ?>" <?php echo $model->isDefaultColorset( $colorset ) ? 'selected="yes"' : ''; ?> >
					<?php echo esc_html( $model->getColorsetLabel( $colorset ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="rpbchessboard-piecesetField"><?php esc_html_e( 'Pieceset:', 'rpb-chessboard' ); ?></label>
			<select id="rpbchessboard-piecesetField" name="pieceset">
				<?php foreach ( $model->getAvailablePiecesets() as $pieceset ) : ?>
				<option value="<?php echo esc_attr( $pieceset ); ?>" <?php echo $model->isDefaultPieceset( $pieceset ) ? 'selected="yes"' : ''; ?> >
					<?php echo esc_html( $model->getPiecesetLabel( $pieceset ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>

	</div>
	<div>

		<div id="rpbchessboard-tuningChessboardWidget"></div>

	</div>
</div>


<p class="description">
	<?php
		printf(
			esc_html__(
				'Note that specific chessboard aspect settings can be defined for %1$ssmall-screen devices%3$s (such as smartphones). ' .
				'Additional colorsets and piecesets can be created in the %2$stheming page%3$s.',
				'rpb-chessboard'
			),
			sprintf( '<a href="%s">', esc_url( $model->getOptionsSmallScreensURL() ) ),
			sprintf( '<a href="%s">', esc_url( $model->getThemingURL() ) ),
			'</a>'
		);
	?>
</p>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		// State variables
		var squareSize      = $('#rpbchessboard-squareSizeField').val();
		var showCoordinates = $('#rpbchessboard-showCoordinatesField').prop('checked');
		var colorset        = $('#rpbchessboard-colorsetField').val();
		var pieceset        = $('#rpbchessboard-piecesetField').val();

		// Refresh the widget
		function refresh() {
			RPBChessboard.renderAdminChessboard($('#rpbchessboard-tuningChessboardWidget'), {
				position: 'start',
				squareSize: squareSize,
				coordinateVisible: showCoordinates,
				colorset: colorset,
				pieceset: pieceset,
			});
		}
		refresh();

		// Disable the square-size text field, create a slider instead.
		$('#rpbchessboard-squareSizeField').prop('readonly', true);
		$('#rpbchessboard-squareSizeSlider').slider({
			value: squareSize,
			min: <?php echo wp_json_encode( $model->getMinimumSquareSize() ); ?>,
			max: <?php echo wp_json_encode( $model->getMaximumSquareSize() ); ?>,
			slide: function(event, ui) {
				squareSize = ui.value;
				$('#rpbchessboard-squareSizeField').val(squareSize);
				refresh();
			}
		});

		// Initialize the other callbacks.
		$('#rpbchessboard-showCoordinatesField').change(function() {
			showCoordinates = $('#rpbchessboard-showCoordinatesField').prop('checked');
			refresh();
		});
		$('#rpbchessboard-colorsetField').change(function() {
			colorset = $('#rpbchessboard-colorsetField').val();
			refresh();
		});
		$('#rpbchessboard-piecesetField').change(function() {
			pieceset = $('#rpbchessboard-piecesetField').val();
			refresh();
		});

	});
</script>
