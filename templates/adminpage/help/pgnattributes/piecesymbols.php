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

<h3 id="rpbchessboard-pgnAttributePieceSymbols"><?php _e( 'Piece symbols', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<p>
			<?php
			echo sprintf(
				__( 'The %1$s attribute controls how chess pieces are denoted in the move list.', 'rpb-chessboard' ),
				'<span class="rpbchessboard-sourceCode">piece_symbols</span>'
			);
				?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e( 'Value', 'rpb-chessboard' ); ?></th>
					<th><?php _e( 'Default', 'rpb-chessboard' ); ?></th>
					<th><?php _e( 'Description', 'rpb-chessboard' ); ?></th>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">native</a></td>
					<td>
					<?php
					if ( $model->getDefaultSimplifiedPieceSymbols() === 'english' ) :
?>
<div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e( 'First character of the piece name in English.', 'rpb-chessboard' ); ?></td>
				</tr>
				<?php if ( $model->isPieceSymbolLocalizationAvailable() ) : ?>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">localized</a></td>
						<td>
						<?php
						if ( $model->getDefaultSimplifiedPieceSymbols() === 'localized' ) :
?>
<div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php _e( 'First character of the piece name in the blog language.', 'rpb-chessboard' ); ?></td>
					</tr>
				<?php endif; ?>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">figurines</a></td>
					<td>
					<?php
					if ( $model->getDefaultSimplifiedPieceSymbols() === 'figurines' ) :
?>
<div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><span class="rpbui-chessgame-alphaFont">K Q R B N P</span></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">(
					<?php
						echo htmlspecialchars( $model->getPieceSymbolCustomValue() );
						?>
						)</a></td>
					<td>
					<?php
					if ( $model->getDefaultSimplifiedPieceSymbols() === 'custom' ) :
?>
<div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td>
						<?php
						echo sprintf(
							__(
								'Any sequence of 6 capital letters surrounded with parenthesis is allowed to set custom symbols. ' .
								'For instance, with %1$s(%3$s%4$s%5$s%6$s%7$s%8$s)%2$s, %3$s will be used to denote a king, ' .
								'%4$s for a queen, %5$s for a rook, %6$s for a bishop, %7$s for a knight and %8$s for a pawn.',
								'rpb-chessboard'
							),
							'<span class="rpbchessboard-sourceCode">',
							'</span>',
							htmlspecialchars( $model->getPieceSymbolCustomValue( 'K' ) ),
							htmlspecialchars( $model->getPieceSymbolCustomValue( 'Q' ) ),
							htmlspecialchars( $model->getPieceSymbolCustomValue( 'R' ) ),
							htmlspecialchars( $model->getPieceSymbolCustomValue( 'B' ) ),
							htmlspecialchars( $model->getPieceSymbolCustomValue( 'N' ) ),
							htmlspecialchars( $model->getPieceSymbolCustomValue( 'P' ) )
						);
						?>
					</td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php
			echo sprintf(
				'[%1$s <strong>piece_symbols=<span id="rpbchessboard-pgnAttributePieceSymbols-sourceCodeExample">native</span></strong>] ... [/%1$s]',
				htmlspecialchars( $model->getPGNShortcode() )
			);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnAttributePieceSymbols-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnAttributePieceSymbols-anchor').chessgame($.extend(<?php echo json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							pieceSymbols: 'native',
							pgn:
								'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
								'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
								'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
						}));
						$('.rpbchessboard-pgnAttributePieceSymbols-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							$('#rpbchessboard-pgnAttributePieceSymbols-anchor').chessgame('option', 'pieceSymbols', value);
							$('#rpbchessboard-pgnAttributePieceSymbols-sourceCodeExample').text(value);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
