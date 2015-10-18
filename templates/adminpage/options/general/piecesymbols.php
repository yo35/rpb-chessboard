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

<h3><?php _e('Piece symbols', 'rpbchessboard'); ?></h3>


<p>
	<input type="radio" id="rpbchessboard-pieceSymbolButton-english" name="pieceSymbols" value="english"
		<?php if($model->getDefaultSimplifiedPieceSymbols()==='english'): ?>checked="yes"<?php endif; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-english"><?php _e('English initials', 'rpbchessboard'); ?></label>

	<?php if($model->isPieceSymbolLocalizationAvailable()): ?>
		<input type="radio" id="rpbchessboard-pieceSymbolButton-localized" name="pieceSymbols" value="localized"
			<?php if($model->getDefaultSimplifiedPieceSymbols()==='localized'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-pieceSymbolButton-localized"><?php _e('Localized initials', 'rpbchessboard'); ?></label>
	<?php endif; ?>

	<input type="radio" id="rpbchessboard-pieceSymbolButton-figurines" name="pieceSymbols" value="figurines"
		<?php if($model->getDefaultSimplifiedPieceSymbols()==='figurines'): ?>checked="yes"<?php endif; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-figurines"><?php _e('Figurines', 'rpbchessboard'); ?></label>

	<input type="radio" id="rpbchessboard-pieceSymbolButton-custom" name="pieceSymbols" value="custom"
		<?php if($model->getDefaultSimplifiedPieceSymbols()==='custom'): ?>checked="yes"<?php endif; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-custom"><?php _e('Custom', 'rpbchessboard'); ?></label>
</p>


<p class="uichess-chessboard-size40">
	<label for="rpbchessboard-kingSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-k uichess-chessboard-color-w uichess-chessboard-sized"></label>
	<input id="rpbchessboard-kingSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="kingSymbol" size="1" maxLength="1"
		value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('K')); ?>"
	/>

	<label for="rpbchessboard-queenSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-q uichess-chessboard-color-w uichess-chessboard-sized"></label>
	<input id="rpbchessboard-queenSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="queenSymbol" size="1" maxLength="1"
		value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('Q')); ?>"
	/>

	<label for="rpbchessboard-rookSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-r uichess-chessboard-color-w uichess-chessboard-sized"></label>
	<input id="rpbchessboard-rookSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="rookSymbol" size="1" maxLength="1"
		value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('R')); ?>"
	/>

	<label for="rpbchessboard-bishopSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-b uichess-chessboard-color-w uichess-chessboard-sized"></label>
	<input id="rpbchessboard-bishopSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="bishopSymbol" size="1" maxLength="1"
		value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('B')); ?>"
	/>

	<label for="rpbchessboard-knightSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-n uichess-chessboard-color-w uichess-chessboard-sized"></label>
	<input id="rpbchessboard-knightSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="knightSymbol" size="1" maxLength="1"
		value="<?php echo htmlspecialchars($model->getPieceSymbolCustomValue('N')); ?>"
	/>

	<label for="rpbchessboard-pawnSymbolField" class="rpbchessboard-pieceSymbolLabel uichess-chessboard-piece-p uichess-chessboard-color-w uichess-chessboard-sized"></label>
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
	jQuery(document).ready(function($) {

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
		$('#rpbchessboard-pieceSymbolButton-' + <?php echo json_encode($model->getDefaultSimplifiedPieceSymbols()); ?>).change();

	});
</script>
