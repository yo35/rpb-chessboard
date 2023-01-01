<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2023  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div class="rpbchessboard-columns">
	<div class="rpbchessboard-tuningChessboardParameterColumn">

		<p>
			<?php
				printf(
					esc_html__( 'Square size: %1$s pixels', 'rpb-chessboard' ),
					'<input type="text" id="rpbchessboard-squareSizeField-' . esc_attr( $key ) . '" class="rpbchessboard-squareSizeField" name="' .
						esc_attr( $key ) . 'SquareSize" size="3" value="' . esc_attr( $model->getDefaultSquareSize( $key ) ) . '"/>'
				);
			?>
		</p>

		<div id="rpbchessboard-squareSizeSlider-<?php echo esc_attr( $key ); ?>" class="rpbchessboard-squareSizeSlider"></div>

		<p>
			<input type="hidden" name="<?php echo esc_attr( $key ); ?>ShowCoordinates" value="0" />
			<input type="checkbox" id="rpbchessboard-showCoordinatesField-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>ShowCoordinates" value="1"
				<?php echo $model->getDefaultShowCoordinates( $key ) ? 'checked="yes"' : ''; ?>
			/>
			<label for="rpbchessboard-showCoordinatesField-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Show coordinates', 'rpb-chessboard' ); ?></label>
		</p>

		<p>
			<label for="rpbchessboard-colorsetField-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Colorset:', 'rpb-chessboard' ); ?></label>
			<select id="rpbchessboard-colorsetField-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>Colorset">
				<?php foreach ( $model->getAvailableColorsets() as $colorset ) : ?>
				<option value="<?php echo esc_attr( $colorset ); ?>" <?php echo $model->isDefaultColorset( $key, $colorset ) ? 'selected="yes"' : ''; ?> >
					<?php echo esc_html( $model->getColorsetLabel( $colorset ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="rpbchessboard-piecesetField-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Pieceset:', 'rpb-chessboard' ); ?></label>
			<select id="rpbchessboard-piecesetField-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>Pieceset">
				<?php foreach ( $model->getAvailablePiecesets() as $pieceset ) : ?>
				<option value="<?php echo esc_attr( $pieceset ); ?>" <?php echo $model->isDefaultPieceset( $key, $pieceset ) ? 'selected="yes"' : ''; ?> >
					<?php echo esc_html( $model->getPiecesetLabel( $pieceset ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>

		<?php if ( $withMoveAttributes ) : ?>

			<p>
				<input type="hidden" name="animated" value="0" />
				<input type="checkbox" id="rpbchessboard-animatedField" name="animated" value="1"
					<?php echo $model->getDefaultAnimated() ? 'checked="yes"' : ''; ?>
				/>
				<label for="rpbchessboard-animatedField"><?php esc_html_e( 'Move animation', 'rpb-chessboard' ); ?></label>
			</p>

			<p>
				<input type="hidden" name="showMoveArrow" value="0" />
				<input type="checkbox" id="rpbchessboard-showMoveArrowField" name="showMoveArrow" value="1"
					<?php echo $model->getDefaultShowMoveArrow() ? 'checked="yes"' : ''; ?>
				/>
				<label for="rpbchessboard-showMoveArrowField"><?php esc_html_e( 'Show move arrow', 'rpb-chessboard' ); ?></label>
			</p>

			<p>
				<span class="rpbchessboard-smallGraphicRadioButtonLabel"><?php esc_html_e( 'Move arrow color:', 'rpb-chessboard' ); ?></span>
				<span class="rpbchessboard-smallGraphicRadioButtonFields">
					<?php foreach ( array( 'b', 'g', 'r', 'y' ) as $color ) : ?>
					<span>
						<input type="radio" id="rpbchessboard-moveArrowColorButton-<?php esc_attr_e( $color ); ?>" name="moveArrowColor" value="<?php esc_attr_e( $color ); ?>"
							<?php echo $model->getDefaultMoveArrowColor() === $color ? 'checked="yes"' : ''; ?>
						/>
						<label class="rpbchessboard-moveArrowColorButton" data-color="<?php esc_attr_e( $color ); ?>" for="rpbchessboard-moveArrowColorButton-<?php esc_attr_e( $color ); ?>"></label>
					</span>
					<?php endforeach; ?>
				</span>
			</p>

			<p>
				<a href="#" class="button" id="rpbchessboard-movePreview">
					<?php esc_html_e( 'Move preview', 'rpb-chessboard' ); ?>
				</a>
			</p>

		<?php endif; ?>

	</div>

	<div>
		<div id="rpbchessboard-tuningChessboard-<?php echo esc_attr( $key ); ?>"></div>
	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var key = <?php echo wp_json_encode( $key ); ?>;

		// State variables
		var squareSize      = $('#rpbchessboard-squareSizeField-' + key).val();
		var showCoordinates = $('#rpbchessboard-showCoordinatesField-' + key).prop('checked');
		var colorset        = $('#rpbchessboard-colorsetField-' + key).val();
		var pieceset        = $('#rpbchessboard-piecesetField-' + key).val();
		var movePreview     = false;

		// Refresh the widget
		function refresh() {
			RPBChessboard.renderAdminChessboard($('#rpbchessboard-tuningChessboard-' + key), {
				position: 'start',
				squareSize: squareSize,
				coordinateVisible: showCoordinates,
				colorset: colorset,
				pieceset: pieceset,
				move: movePreview ? 'e4' : undefined,
				animated: movePreview ? $('#rpbchessboard-animatedField').prop('checked') : undefined,
				moveArrowVisible: movePreview ? $('#rpbchessboard-showMoveArrowField').prop('checked') : undefined,
				moveArrowColor: movePreview ? $('input[name="moveArrowColor"]:checked').val() : undefined,
			});
		}
		refresh();

		// Disable the square-size text field, create a slider instead.
		$('#rpbchessboard-squareSizeField-' + key).prop('readonly', true);
		$('#rpbchessboard-squareSizeSlider-' + key).slider({
			value: squareSize,
			min: RPBChessboard.availableSquareSize.min,
			max: RPBChessboard.availableSquareSize.max,
			slide: function(event, ui) {
				squareSize = ui.value;
				$('#rpbchessboard-squareSizeField-' + key).val(squareSize);
				refresh();
			}
		});

		// Initialize the other callbacks.
		$('#rpbchessboard-showCoordinatesField-' + key).change(function() {
			showCoordinates = $('#rpbchessboard-showCoordinatesField-' + key).prop('checked');
			refresh();
		});
		$('#rpbchessboard-colorsetField-' + key).change(function() {
			colorset = $('#rpbchessboard-colorsetField-' + key).val();
			refresh();
		});
		$('#rpbchessboard-piecesetField-' + key).change(function() {
			pieceset = $('#rpbchessboard-piecesetField-' + key).val();
			refresh();
		});

		<?php if ( $withMoveAttributes ) : ?>

			// Move arrow color buttons
			$('.rpbchessboard-moveArrowColorButton').each(function() {
				var element = $(this);
				var color = element.data('color');
				var mainColorset = RPBChessboard.colorsetData['original'];
				RPBChessboard.renderArrowMarkerIcon(element, { size: 20, color: mainColorset['c' + color] });
			});
			$('input[name="moveArrowColor"]').change(function() {
				refresh();
			});

			// Move preview
			$('#rpbchessboard-movePreview').click(function(e) {
				e.preventDefault();
				if (movePreview) {
					return;
				}

				// Disable the preview button
				$('#rpbchessboard-movePreview').addClass('rpbchessboard-disabled');

				// Refresh the widget
				movePreview = true;
				refresh();

				// Restore the initial state.
				setTimeout(function() {
					$('#rpbchessboard-movePreview').removeClass('rpbchessboard-disabled');
					movePreview = false;
					refresh();
				}, 1200);
			});

		<?php endif; ?>

	});
</script>
