<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php esc_html_e( 'Move animation', 'rpb-chessboard' ); ?></h3>


<div class="rpbchessboard-columns">
	<div id="rpbchessboard-tuningMoveAnimationParameterColumn">

		<p>
			<input type="hidden" name="animated" value="0" />
			<input type="checkbox" id="rpbchessboard-animatedField" name="animated" value="1"
				<?php echo $model->getDefaultAnimated() ? 'checked="yes"' : ''; ?>
			/>
			<label for="rpbchessboard-animatedField"><?php esc_html_e( 'Animation', 'rpb-chessboard' ); ?></label>
		</p>

		<p>
			<input type="hidden" name="showMoveArrow" value="0" />
			<input type="checkbox" id="rpbchessboard-showMoveArrowField" name="showMoveArrow" value="1"
				<?php echo $model->getDefaultShowMoveArrow() ? 'checked="yes"' : ''; ?>
			/>
			<label for="rpbchessboard-showMoveArrowField"><?php esc_html_e( 'Show move arrow', 'rpb-chessboard' ); ?></label>
		</p>

		<p>
			<a href="#" class="button rpbchessboard-testMoveAnimation" id="rpbchessboard-testMoveAnimation1">
				<?php esc_html_e( 'Test move', 'rpb-chessboard' ); ?>
			</a>
			<a href="#" class="button rpbchessboard-testMoveAnimation" id="rpbchessboard-testMoveAnimation2">
				<?php esc_html_e( 'Test capture', 'rpb-chessboard' ); ?>
			</a>
		</p>

	</div>
	<div>

		<div id="rpbchessboard-tuningMoveAnimationWidget"></div>

	</div>
</div>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		// Create the chessboard widget.
		var widget = $('#rpbchessboard-tuningMoveAnimationWidget');
		var widgetState = {
			position: 'r1bqkbnr/pppp1ppp/2n5/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq - 0 4',
			squareSize: 32,
			showCoordinates: false
		};
		var initialPosition = 'r1bqkbnr/pppp1ppp/2n5/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq - 0 4';
		RPBChessboard.renderFEN(widget, widgetState, false);

		// Test buttons
		function doTest(move) {
			if($('.rpbchessboard-testMoveAnimation').hasClass('rpbchessboard-disabled')) { return; }

			// Disable the test buttons
			$('.rpbchessboard-testMoveAnimation').addClass('rpbchessboard-disabled');

			// Set the animation parameter to the test widget
			widgetState.animated = $('#rpbchessboard-animatedField').prop('checked');
			widgetState.showMoveArrow = $('#rpbchessboard-showMoveArrowField').prop('checked');
			widgetState.move = move;

			// Refresh the widget
			RPBChessboard.renderFEN(widget, widgetState, false);

			// Restore the initial state.
			setTimeout(function() {
				widgetState.move = undefined;
				RPBChessboard.renderFEN(widget, widgetState, false);
				$('.rpbchessboard-testMoveAnimation').removeClass('rpbchessboard-disabled');
			}, 1200);
		}
		$('#rpbchessboard-testMoveAnimation1').click(function(e) { e.preventDefault(); doTest('Bc4'); });
		$('#rpbchessboard-testMoveAnimation2').click(function(e) { e.preventDefault(); doTest('Nxd4'); });

	});
</script>
