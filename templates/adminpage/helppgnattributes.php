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

<div id="rpbchessboard-helpPGNAttributesPage" class="rpbchessboard-helpPage">

	<p>
		<?php echo sprintf(
			__(
				'Several parameters may be passed to the %1$s[%3$s][/%3$s]%2$s tags '.
				'in order to customize how the PGN games are displayed. '.
				'All these parameters are optional: if not specified, the default setting '.
				'(defined by the website administrator) applies. '.
				'These parameters are presented in this page.',
			'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getPGNShortcode())
		); ?>
	</p>

	<ol class="rpbchessboard-outline">
		<li><a href="#rpbchessboard-pgnAttributePieceSymbols"><?php _e('Piece symbols', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-pgnAttributeNavigationBoard"><?php _e('Navigation board', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-pgnAttributeFlip"><?php _e('Board flipping', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-pgnAttributeSquareSize"><?php _e('Square size', 'rpbchessboard'); ?></a></li>
		<li><a href="#rpbchessboard-pgnAttributeShowCoordinates"><?php _e('Show coordinates', 'rpbchessboard'); ?></a></li>
	</ol>



	<h3 id="rpbchessboard-pgnAttributePieceSymbols"><?php _e('Piece symbols', 'rpbchessboard'); ?></h3>

	<div class="rpbchessboard-columns">
		<div>
			<p>
				<?php echo sprintf(__('The %1$s parameter controls how chess pieces are denoted in the move list.', 'rpbchessboard'),
					'<span class="rpbchessboard-sourceCode">piece_symbols</span>'); ?>
			</p>
			<table class="rpbchessboard-attributeTable">
				<tbody>
					<tr>
						<th><?php _e('Value', 'rpbchessboard'); ?></th>
						<th><?php _e('Default', 'rpbchessboard'); ?></th>
						<th><?php _e('Description', 'rpbchessboard'); ?></th>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">native</a></td>
						<td><?php if($model->getDefaultPieceSymbols()==='native'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php _e('First character of the piece name in English.', 'rpbchessboard'); ?></td>
					</tr>
					<?php if($model->isPieceSymbolLocalizationAvailable()): ?>
						<tr>
							<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">localized</a></td>
							<td><?php if($model->getDefaultPieceSymbols()==='localized'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
							<td><?php _e('First character of the piece name in the blog language.', 'rpbchessboard'); ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">figurines</a></td>
						<td><?php if($model->getDefaultPieceSymbols()==='figurines'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><span class="uichess-chessgame-alphaFont">K Q R B N P</span></td>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributePieceSymbols-value">(KDTLSB)</a></td>
						<td></td>
						<td>
							<?php echo sprintf(
								__(
									'Any sequence of 6 capital letters surrounded with parenthesis is allowed to set custom symbols. '.
									'For instance, with %1$s(KDTLSB)%2$s, K will be used to denote a king, D for a queen, T for a rook, '.
									'L for a bishop, S for a knight and B for a pawn.',
								'rpbchessboard'),
								'<span class="rpbchessboard-sourceCode">',
								'</span>',
								'rto', 'gg'
							); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<div class="rpbchessboard-sourceCode">
				<?php echo sprintf(
					'[%1$s <strong>piece_symbols=<span id="rpbchessboard-pgnAttributePieceSymbols-sourceCodeExample">native</span></strong>] ... [/%1$s]',
					htmlspecialchars($model->getPGNShortcode())
				); ?>
			</div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-pgnAttributePieceSymbols-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-pgnAttributePieceSymbols-anchor').chessgame({
								pgn:
									'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
									'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
									'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
							});
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



	<h3 id="rpbchessboard-pgnAttributeNavigationBoard"><?php _e('Navigation board', 'rpbchessboard'); ?></h3>

	<div id="rpbchessboard-pgnAttributeNavigationBoard-content" class="rpbchessboard-columns">
		<div>
			<p>
				<?php echo sprintf(__('The %1$s parameter controls the position of the navigation board.', 'rpbchessboard'),
					'<span class="rpbchessboard-sourceCode">navigation_board</span>'); ?>
			</p>
			<table class="rpbchessboard-attributeTable">
				<tbody>
					<tr>
						<th><?php _e('Value', 'rpbchessboard'); ?></th>
						<th><?php _e('Default', 'rpbchessboard'); ?></th>
						<th><?php _e('Description', 'rpbchessboard'); ?></th>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">none</a></td>
						<td><?php if($model->getDefaultNavigationBoard()==='none'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php _e('No navigation board.', 'rpbchessboard'); ?></td>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">frame</a></td>
						<td><?php if($model->getDefaultNavigationBoard()==='frame'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php _e('The navigation board is displayed in a popup frame, which becomes visible '.
							'when the user clicks on a move within the move list.', 'rpbchessboard'); ?></td>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">floatLeft</a></td>
						<td><?php if($model->getDefaultNavigationBoard()==='floatLeft'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php _e('The navigation board is displayed on the left of the move list.', 'rpbchessboard'); ?></td>
					</tr>
					<tr>
						<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">floatRight</a></td>
						<td><?php if($model->getDefaultNavigationBoard()==='floatRight'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
						<td><?php _e('The navigation board is displayed on the right of the move list.', 'rpbchessboard'); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<div class="rpbchessboard-sourceCode">
				<?php echo sprintf(
					'[%1$s <strong>navigation_board=<span id="rpbchessboard-pgnAttributeNavigationBoard-sourceCodeExample">none</span></strong>] ... [/%1$s]',
					htmlspecialchars($model->getPGNShortcode())
				); ?>
			</div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-pgnAttributeNavigationBoard-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-pgnAttributeNavigationBoard-anchor').chessgame({
								navigationBoardOptions: { squareSize: 28 },
								pgn:
									'[Event "Linares 16<sup>th</sup>"]\n' +
									'[Site "Linares, ESP"]\n' +
									'[Date "1999.02.25"]\n' +
									'[Round "4"]\n' +
									'[White "Kasparov, Garry"]\n' +
									'[Black "Kramnik, Vladimir"]\n' +
									'[Result "1/2-1/2"]\n' +
									'[WhiteElo "2812"]\n' +
									'[BlackElo "2751"]\n' +
									'[WhiteTitle "WCH"]\n' +
									'[BlackTitle "GM"]\n' +
									'[Annotator "?"]\n' +
									'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
									'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
									'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
							});
							$('.rpbchessboard-pgnAttributeNavigationBoard-value').click(function(e) {
								e.preventDefault();
								var value = $(this).text();
								$('#rpbchessboard-pgnAttributeNavigationBoard-anchor').chessgame('option', 'navigationBoard', value);
								$('#rpbchessboard-pgnAttributeNavigationBoard-sourceCodeExample').text(value);
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>



	<h3 id="rpbchessboard-pgnAttributeFlip"><?php _e('Board flipping', 'rpbchessboard'); ?></h3>

	<p>TODO</p>



	<h3 id="rpbchessboard-pgnAttributeSquareSize"><?php _e('Square size', 'rpbchessboard'); ?></h3>

	<p>TODO</p>



	<h3 id="rpbchessboard-pgnAttributeShowCoordinates"><?php _e('Show coordinates', 'rpbchessboard'); ?></h3>

	<p>TODO</p>

</div>
