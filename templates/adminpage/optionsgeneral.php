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

<div id="rpbchessboard-optionPage" class="rpbchessboard-jQuery-enableSmoothness">

	<form action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<p>
			<?php echo sprintf(
				__(
					'These settings control the default aspect and behavior of the chess diagrams and games ' .
					'inserted in posts and pages with the %1$s[%3$s][/%3$s]%2$s and %1$s[%4$s][/%4$s]%2$s tags. ' .
					'They can be overridden at each tag by passing appropriate tag attributes: ' .
					'see %5$shelp on FEN diagram attributes%7$s and %6$shelp on PGN game attributes%7$s for more details.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">',
				'</span>',
				htmlspecialchars($model->getFENShortcode()),
				htmlspecialchars($model->getPGNShortcode()),
				'<a href="' . htmlspecialchars($model->getHelpOnFENAttributesURL()) . '">',
				'<a href="' . htmlspecialchars($model->getHelpOnPGNAttributesURL()) . '">',
				'</a>'
			); ?>
		</p>





		<h3><?php _e('Chessboard aspect', 'rpbchessboard'); ?></h3>

		<div class="rpbchessboard-columns">

			<div>
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
				<div id="rpbchessboard-squareSizeSlider" class="rpbchessboard-squareSizeSlider"></div>
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

		<p class="description">
			<?php
				echo sprintf(
					__(
						'Notice that specific chessboard aspect settings can be defined for %1$ssmall-screen devices%2$s (such as smartphones).',
					'rpbchessboard'),
					'<a href="' . htmlspecialchars($model->getOptionsSmallScreensURL()) . '">',
					'</a>'
				);
			?>
		</p>

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




		<h3><?php _e('Piece symbols', 'rpbchessboard'); ?></h3>

		<p>
			<input type="radio" id="rpbchessboard-pieceSymbolButton-english" name="pieceSymbols" value="english"
				<?php if($model->getSelectedPieceSymbolButton()==='english'): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-pieceSymbolButton-english"><?php _e('English initials', 'rpbchessboard'); ?></label>

			<?php if($model->isLocalizedPieceSymbolButtonAvailable()): ?>
				<input type="radio" id="rpbchessboard-pieceSymbolButton-localized" name="pieceSymbols" value="localized"
					<?php if($model->getSelectedPieceSymbolButton()==='localized'): ?>checked="yes"<?php endif; ?>
				/>
				<label for="rpbchessboard-pieceSymbolButton-localized"><?php _e('Localized initials', 'rpbchessboard'); ?></label>
			<?php endif; ?>

			<input type="radio" id="rpbchessboard-pieceSymbolButton-figurines" name="pieceSymbols" value="figurines"
				<?php if($model->getSelectedPieceSymbolButton()==='figurines'): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-pieceSymbolButton-figurines"><?php _e('Figurines', 'rpbchessboard'); ?></label>

			<input type="radio" id="rpbchessboard-pieceSymbolButton-custom" name="pieceSymbols" value="custom"
				<?php if($model->getSelectedPieceSymbolButton()==='custom'): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-pieceSymbolButton-custom"><?php _e('Custom', 'rpbchessboard'); ?></label>
		</p>

		<p>
			<label for="rpbchessboard-kingSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-k uichess-chessboard-color-w uichess-chessboard-size40"></label>
			<input id="rpbchessboard-kingSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="kingSymbol" size="1" maxLength="1"
				value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('K')); ?>"
			/>

			<label for="rpbchessboard-queenSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-q uichess-chessboard-color-w uichess-chessboard-size40"></label>
			<input id="rpbchessboard-queenSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="queenSymbol" size="1" maxLength="1"
				value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('Q')); ?>"
			/>

			<label for="rpbchessboard-rookSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-r uichess-chessboard-color-w uichess-chessboard-size40"></label>
			<input id="rpbchessboard-rookSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="rookSymbol" size="1" maxLength="1"
				value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('R')); ?>"
			/>

			<label for="rpbchessboard-bishopSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-b uichess-chessboard-color-w uichess-chessboard-size40"></label>
			<input id="rpbchessboard-bishopSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="bishopSymbol" size="1" maxLength="1"
				value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('B')); ?>"
			/>

			<label for="rpbchessboard-knightSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-n uichess-chessboard-color-w uichess-chessboard-size40"></label>
			<input id="rpbchessboard-knightSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="knightSymbol" size="1" maxLength="1"
				value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('N')); ?>"
			/>

			<label for="rpbchessboard-pawnSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-p uichess-chessboard-color-w uichess-chessboard-size40"></label>
			<input id="rpbchessboard-pawnSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="pawnSymbol" size="1" maxLength="1"
				value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('P')); ?>"
			/>
		</p>

		<p class="description">
			<?php
				_e(
					'This setting only affects how chess moves are rendered to post/page readers. ' .
					'Authors must always use English initials when writting PGN content into posts and pages.',
				'rpbchessboard');
			?>
		</p>

		<script type="text/javascript">

			jQuery(document).ready(function($)
			{
				var needInitIfCustomClicked = false;

				// Callback for the English piece symbol button.
				$('#rpbchessboard-pieceSymbolButton-english').change(function() {
					$('#rpbchessboard-kingSymbolField'  ).val('K');
					$('#rpbchessboard-queenSymbolField' ).val('Q');
					$('#rpbchessboard-rookSymbolField'  ).val('R');
					$('#rpbchessboard-bishopSymbolField').val('B');
					$('#rpbchessboard-knightSymbolField').val('N');
					$('#rpbchessboard-pawnSymbolField'  ).val('P');
					needInitIfCustomClicked = false;
					$('.rpbchessboard-pieceSymbolField').prop('readonly', true);
				});

				// Callback for the localized piece symbol button.
				$('#rpbchessboard-pieceSymbolButton-localized').change(function() {
					$('#rpbchessboard-kingSymbolField'  ).val(<?php /*i18n King symbol   */ echo json_encode(__('K', 'rpbchessboard')); ?>);
					$('#rpbchessboard-queenSymbolField' ).val(<?php /*i18n Queen symbol  */ echo json_encode(__('Q', 'rpbchessboard')); ?>);
					$('#rpbchessboard-rookSymbolField'  ).val(<?php /*i18n Rook symbol   */ echo json_encode(__('R', 'rpbchessboard')); ?>);
					$('#rpbchessboard-bishopSymbolField').val(<?php /*i18n Bishop symbol */ echo json_encode(__('B', 'rpbchessboard')); ?>);
					$('#rpbchessboard-knightSymbolField').val(<?php /*i18n Knight symbol */ echo json_encode(__('N', 'rpbchessboard')); ?>);
					$('#rpbchessboard-pawnSymbolField'  ).val(<?php /*i18n Pawn symbol   */ echo json_encode(__('P', 'rpbchessboard')); ?>);
					needInitIfCustomClicked = false;
					$('.rpbchessboard-pieceSymbolField').prop('readonly', true);
				});

				// Callback for the figurines piece symbol button.
				$('#rpbchessboard-pieceSymbolButton-figurines').change(function() {
					needInitIfCustomClicked = true;
					$('.rpbchessboard-pieceSymbolField').val('-').prop('readonly', true);
				});

				// Callback for the custom piece symbol button.
				$('#rpbchessboard-pieceSymbolButton-custom').change(function() {
					if(needInitIfCustomClicked) {
						$('#rpbchessboard-kingSymbolField'  ).val('K');
						$('#rpbchessboard-queenSymbolField' ).val('Q');
						$('#rpbchessboard-rookSymbolField'  ).val('R');
						$('#rpbchessboard-bishopSymbolField').val('B');
						$('#rpbchessboard-knightSymbolField').val('N');
						$('#rpbchessboard-pawnSymbolField'  ).val('P');
					}
					needInitIfCustomClicked = false;
					$('.rpbchessboard-pieceSymbolField').prop('readonly', false);
				});

				// Initialize the symbol fields.
				$('#rpbchessboard-pieceSymbolButton-' + <?php echo json_encode($model->getSelectedPieceSymbolButton()); ?>).change();
			});

		</script>





		<h3><?php _e('Position of the navigation board', 'rpbchessboard'); ?></h3>

		<div id="rpbchessboard-navigationBoardFields">

			<div>
				<input type="radio" id="rpbchessboard-navigationBoardButton-none" name="navigationBoard" value="none"
					<?php if($model->getDefaultNavigationBoard()==='none'): ?>checked="yes"<?php endif; ?>
				/>
				<label for="rpbchessboard-navigationBoardButton-none">
					<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-none.png'; ?>"
						title="<?php _e('No navigation board', 'rpbchessboard'); ?>"
						alt="<?php _e('No navigation board', 'rpbchessboard'); ?>"
					/>
				</label>
			</div>

			<div>
				<input type="radio" id="rpbchessboard-navigationBoardButton-frame" name="navigationBoard" value="frame"
					<?php if($model->getDefaultNavigationBoard()==='frame'): ?>checked="yes"<?php endif; ?>
				/>
				<label for="rpbchessboard-navigationBoardButton-frame">
					<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-frame.png'; ?>"
						title="<?php _e('In a popup frame', 'rpbchessboard'); ?>"
						alt="<?php _e('In a popup frame', 'rpbchessboard'); ?>"
					/>
				</label>
			</div>

			<div>
				<input type="radio" id="rpbchessboard-navigationBoardButton-floatLeft" name="navigationBoard" value="floatLeft"
					<?php if($model->getDefaultNavigationBoard()==='floatLeft'): ?>checked="yes"<?php endif; ?>
				/>
				<label for="rpbchessboard-navigationBoardButton-floatLeft">
					<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-floatleft.png'; ?>"
						title="<?php _e('On the left of the move list', 'rpbchessboard'); ?>"
						alt="<?php _e('On the left', 'rpbchessboard'); ?>"
					/>
				</label>
			</div>

			<div>
				<input type="radio" id="rpbchessboard-navigationBoardButton-floatRight" name="navigationBoard" value="floatRight"
					<?php if($model->getDefaultNavigationBoard()==='floatRight'): ?>checked="yes"<?php endif; ?>
				/>
				<label for="rpbchessboard-navigationBoardButton-floatRight">
					<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-floatright.png'; ?>"
						title="<?php _e('On the right of the move list', 'rpbchessboard'); ?>"
						alt="<?php _e('On the right', 'rpbchessboard'); ?>"
					/>
				</label>
			</div>

		</div>

		<p class="description">
			<?php
				_e(
					'A navigation board may be added to each PGN game to help post/page readers to follow the progress of the game. ' .
					'This navigation board is displayed either in a popup frame (in this case, it becomes visible only when the reader ' .
					'clicks on a move) or next to the move list (then it is visible as soon as the page is loaded).',
				'rpbchessboard');
			?>
		</p>





		<?php include(RPBCHESSBOARD_ABSPATH . 'templates/adminpage/optionsfooter.php'); ?>

	</form>

</div>
