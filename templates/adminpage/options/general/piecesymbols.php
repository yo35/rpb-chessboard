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

<h3><?php esc_html_e( 'Piece symbols', 'rpb-chessboard' ); ?></h3>


<p>
	<input type="radio" id="rpbchessboard-pieceSymbolButton-english" name="pieceSymbols" value="english"
		<?php echo $model->getDefaultSimplifiedPieceSymbols() === 'english' ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-english"><?php esc_html_e( 'English initials', 'rpb-chessboard' ); ?></label>

	<?php if ( $model->isPieceSymbolLocalizationAvailable() ) : ?>
	<input type="radio" id="rpbchessboard-pieceSymbolButton-localized" name="pieceSymbols" value="localized"
		<?php echo $model->getDefaultSimplifiedPieceSymbols() === 'localized' ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-localized"><?php esc_html_e( 'Localized initials', 'rpb-chessboard' ); ?></label>
	<?php endif; ?>

	<input type="radio" id="rpbchessboard-pieceSymbolButton-figurines" name="pieceSymbols" value="figurines"
		<?php echo $model->getDefaultSimplifiedPieceSymbols() === 'figurines' ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-figurines"><?php esc_html_e( 'Figurines', 'rpb-chessboard' ); ?></label>

	<input type="radio" id="rpbchessboard-pieceSymbolButton-custom" name="pieceSymbols" value="custom"
		<?php echo $model->getDefaultSimplifiedPieceSymbols() === 'custom' ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-pieceSymbolButton-custom"><?php esc_html_e( 'Custom', 'rpb-chessboard' ); ?></label>
</p>


<p class="rpbui-chessboard-size40">
	<label for="rpbchessboard-kingSymbolField" class="rpbchessboard-pieceSymbolLabel rpbui-chessboard-piece-k rpbui-chessboard-color-w rpbui-chessboard-sized"></label>
	<input id="rpbchessboard-kingSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="kingSymbol" size="1"
		value="<?php echo esc_attr( $model->getPieceSymbolCustomValue( 'K' ) ); ?>"
	/>

	<label for="rpbchessboard-queenSymbolField" class="rpbchessboard-pieceSymbolLabel rpbui-chessboard-piece-q rpbui-chessboard-color-w rpbui-chessboard-sized"></label>
	<input id="rpbchessboard-queenSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="queenSymbol" size="1"
		value="<?php echo esc_attr( $model->getPieceSymbolCustomValue( 'Q' ) ); ?>"
	/>

	<label for="rpbchessboard-rookSymbolField" class="rpbchessboard-pieceSymbolLabel rpbui-chessboard-piece-r rpbui-chessboard-color-w rpbui-chessboard-sized"></label>
	<input id="rpbchessboard-rookSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="rookSymbol" size="1"
		value="<?php echo esc_attr( $model->getPieceSymbolCustomValue( 'R' ) ); ?>"
	/>

	<label for="rpbchessboard-bishopSymbolField" class="rpbchessboard-pieceSymbolLabel rpbui-chessboard-piece-b rpbui-chessboard-color-w rpbui-chessboard-sized"></label>
	<input id="rpbchessboard-bishopSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="bishopSymbol" size="1"
		value="<?php echo esc_attr( $model->getPieceSymbolCustomValue( 'B' ) ); ?>"
	/>

	<label for="rpbchessboard-knightSymbolField" class="rpbchessboard-pieceSymbolLabel rpbui-chessboard-piece-n rpbui-chessboard-color-w rpbui-chessboard-sized"></label>
	<input id="rpbchessboard-knightSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="knightSymbol" size="1"
		value="<?php echo esc_attr( $model->getPieceSymbolCustomValue( 'N' ) ); ?>"
	/>

	<label for="rpbchessboard-pawnSymbolField" class="rpbchessboard-pieceSymbolLabel rpbui-chessboard-piece-p rpbui-chessboard-color-w rpbui-chessboard-sized"></label>
	<input id="rpbchessboard-pawnSymbolField" class="rpbchessboard-pieceSymbolField" type="text" name="pawnSymbol" size="1"
		value="<?php echo esc_attr( $model->getPieceSymbolCustomValue( 'P' ) ); ?>"
	/>
</p>


<p class="description">
	<?php
		esc_html_e(
			'This setting only affects how chess moves are rendered to post/page readers. ' .
			'Authors must always use English initials when writting PGN content into posts and pages.',
			'rpb-chessboard'
		);
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
			$('#rpbchessboard-kingSymbolField'  ).val(<?php /*i18n King symbol   */ echo wp_json_encode( __( 'K', 'rpb-chessboard' ) ); ?>);
			$('#rpbchessboard-queenSymbolField' ).val(<?php /*i18n Queen symbol  */ echo wp_json_encode( __( 'Q', 'rpb-chessboard' ) ); ?>);
			$('#rpbchessboard-rookSymbolField'  ).val(<?php /*i18n Rook symbol   */ echo wp_json_encode( __( 'R', 'rpb-chessboard' ) ); ?>);
			$('#rpbchessboard-bishopSymbolField').val(<?php /*i18n Bishop symbol */ echo wp_json_encode( __( 'B', 'rpb-chessboard' ) ); ?>);
			$('#rpbchessboard-knightSymbolField').val(<?php /*i18n Knight symbol */ echo wp_json_encode( __( 'N', 'rpb-chessboard' ) ); ?>);
			$('#rpbchessboard-pawnSymbolField'  ).val(<?php /*i18n Pawn symbol   */ echo wp_json_encode( __( 'P', 'rpb-chessboard' ) ); ?>);
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
		$('#rpbchessboard-pieceSymbolButton-' + <?php echo wp_json_encode( $model->getDefaultSimplifiedPieceSymbols() ); ?>).change();

	});
</script>
