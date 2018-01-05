<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php _e('Move animation', 'rpb-chessboard'); ?></h3>


<div class="rpbchessboard-columns">
	<div id="rpbchessboard-tuningMoveAnimationParameterColumn">

		<p>
			<?php
				echo sprintf(__('Animation speed: %1$s milliseconds', 'rpb-chessboard'),
					'<input type="text" id="rpbchessboard-animationSpeedField" class="rpbchessboard-animationSpeedField" name="animationSpeed" ' .
						'size="'      . htmlspecialchars($model->getDigitNumberForAnimationSpeed()) . '" ' .
						'maxLength="' . htmlspecialchars($model->getDigitNumberForAnimationSpeed()) . '" ' .
						'value="'     . htmlspecialchars($model->getDefaultAnimationSpeed       ()) . '" />'
				);
			?>
		</p>

		<div id="rpbchessboard-animationSpeedSlider" class="rpbchessboard-slider"></div>

		<p class="description">
			<?php _e('Set the animation speed to 0 to disable animations.', 'rpb-chessboard'); ?>
		</p>

		<p>
			<input type="hidden" name="showMoveArrow" value="0" />
			<input type="checkbox" id="rpbchessboard-showMoveArrowField" name="showMoveArrow" value="1"
				<?php if($model->getDefaultShowMoveArrow()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-showMoveArrowField"><?php _e('Show move arrow', 'rpb-chessboard'); ?></label>
		</p>

		<p>
			<a href="#" class="button rpbchessboard-testMoveAnimation" id="rpbchessboard-testMoveAnimation1">
				<?php _e('Test move', 'rpb-chessboard'); ?>
			</a>
			<a href="#" class="button rpbchessboard-testMoveAnimation" id="rpbchessboard-testMoveAnimation2">
				<?php _e('Test capture', 'rpb-chessboard'); ?>
			</a>
		</p>

	</div>
	<div>

		<div id="rpbchessboard-tuningMoveAnimationWidget"></div>

	</div>
</div>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		// Disable the animationSpeed text field, create a slider instead.
		var animationSpeed = $('#rpbchessboard-animationSpeedField').val();
		$('#rpbchessboard-animationSpeedField').prop('readonly', true);
		$('#rpbchessboard-animationSpeedSlider').slider({
			value: animationSpeed,
			min: 0,
			max: <?php echo wp_json_encode($model->getMaximumAnimationSpeed()); ?>,
			step: <?php echo wp_json_encode($model->getStepAnimationSpeed()); ?>,
			slide: function(event, ui) { $('#rpbchessboard-animationSpeedField').val(ui.value); }
		});

		// Create the chessboard widget.
		var widget = $('#rpbchessboard-tuningMoveAnimationWidget');
		var initialPosition = 'r1bqkbnr/pppp1ppp/2n5/8/3pP3/5N2/PPP2PPP/RNBQKB1R w KQkq - 0 4';
		widget.chessboard({
			position: initialPosition,
			squareSize: 32, showCoordinates: false
		});

		// Test buttons
		function doTest(move) {
			if($('.rpbchessboard-testMoveAnimation').hasClass('rpbchessboard-disabled')) { return; }

			// Disable the test buttons
			$('.rpbchessboard-testMoveAnimation').addClass('rpbchessboard-disabled');

			// Set the animation parameter to the test widget
			var currentAnimationSpeed = Number($('#rpbchessboard-animationSpeedField').val());
			widget.chessboard('option', 'animationSpeed', currentAnimationSpeed);
			widget.chessboard('option', 'showMoveArrow', $('#rpbchessboard-showMoveArrowField').prop('checked'));

			// Play the test move
			widget.chessboard('play', move);

			// Restore the initial state.
			setTimeout(function() {
				widget.chessboard('option', 'position', initialPosition);
				$('.rpbchessboard-testMoveAnimation').removeClass('rpbchessboard-disabled');
			}, currentAnimationSpeed + 1000);
		}
		$('#rpbchessboard-testMoveAnimation1').click(function(e) { e.preventDefault(); doTest('Bc4'); });
		$('#rpbchessboard-testMoveAnimation2').click(function(e) { e.preventDefault(); doTest('Nxd4'); });

	});
</script>
