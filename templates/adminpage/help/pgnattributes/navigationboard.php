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

<h3 id="rpbchessboard-pgnAttributeNavigationBoard"><?php _e('Navigation board', 'rpb-chessboard'); ?></h3>

<div id="rpbchessboard-pgnAttributeNavigationBoard-content" class="rpbchessboard-columns">
	<div>

		<p>
			<?php echo sprintf(__('The %1$s attribute controls the position of the navigation board.', 'rpb-chessboard'),
				'<span class="rpbchessboard-sourceCode">navigation_board</span>'); ?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php _e('Value', 'rpb-chessboard'); ?></th>
					<th><?php _e('Default', 'rpb-chessboard'); ?></th>
					<th><?php _e('Description', 'rpb-chessboard'); ?></th>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">none</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='none'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('No navigation board.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">frame</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='frame'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed in a popup frame, which becomes visible '.
						'when the user clicks on a move within the move list.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">above</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='above'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed above the game headers and the move list.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">below</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='below'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed below the move list.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">floatLeft</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='floatLeft'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed on the left of the move list.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">floatRight</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='floatRight'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed on the right of the move list.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">scrollLeft</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='scrollLeft'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed on the left of the move list. ' .
						'The move list becomes scrollable if it is higher than the navigation board.', 'rpb-chessboard'); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeNavigationBoard-value">scrollRight</a></td>
					<td><?php if($model->getDefaultNavigationBoard()==='scrollRight'): ?><div class="rpbchessboard-tickIcon"></div><?php endif; ?></td>
					<td><?php _e('The navigation board is displayed on the right of the move list. ' .
						'The move list becomes scrollable if it is higher than the navigation board.', 'rpb-chessboard'); ?></td>
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
						$('#rpbchessboard-pgnAttributeNavigationBoard-anchor').chessgame($.extend(true, <?php echo json_encode($model->getDefaultChessgameSettings()); ?>, {
							navigationBoard: 'none',
							navigationBoardOptions: { squareSize: 28 },
							pgn:
								'[Event "World Championship"]\n' +
								'[Site "Reykjavik, ISL"]\n' +
								'[Date "1972.08.10"]\n' +
								'[Round "13"]\n' +
								'[Result "0-1"]\n' +
								'[White "Spassky, Boris"]\n' +
								'[Black "Fischer, Robert J."]\n' +
								'\n' +
								'1. e4 Nf6 2. e5 Nd5 3. d4 d6 4. Nf3 g6 5. Bc4 Nb6 6. Bb3 Bg7 ' +
								'7. Nbd2 O-O 8. h3 a5 9. a4 dxe5 10. dxe5 Na6 11. O-O Nc5 ' +
								'12. Qe2 Qe8 13. Ne4 Nbxa4 14. Bxa4 Nxa4 15. Re1 Nb6 16. Bd2 a4 ' +
								'17. Bg5 h6 18. Bh4 Bf5 19. g4 Be6 20. Nd4 Bc4 21. Qd2 Qd7 ' +
								'22. Rad1 Rfe8 23. f4 Bd5 24. Nc5 Qc8 25. Qc3 e6 26. Kh2 Nd7 ' +
								'27. Nd3 c5 28. Nb5 Qc6 29. Nd6 Qxd6 30. exd6 Bxc3 31. bxc3 f6 ' +
								'32. g5 hxg5 33. fxg5 f5 34. Bg3 Kf7 35. Ne5+ Nxe5 36. Bxe5 b5 ' +
								'37. Rf1 Rh8 38. Bf6 a3 39. Rf4 a2 40. c4 Bxc4 41. d7 Bd5 ' +
								'42. Kg3 Ra3+ 43. c3 Rha8 44. Rh4 e5 45. Rh7+ Ke6 46. Re7+ Kd6 ' +
								'47. Rxe5 Rxc3+ 48. Kf2 Rc2+ 49. Ke1 Kxd7 50. Rexd5+ Kc6 ' +
								'51. Rd6+ Kb7 52. Rd7+ Ka6 53. R7d2 Rxd2 54. Kxd2 b4 55. h4 Kb5 ' +
								'56. h5 c4 57. Ra1 gxh5 58. g6 h4 59. g7 h3 60. Be7 Rg8 61. Bf8 ' +
								'h2 62. Kc2 Kc6 63. Rd1 b3+ 64. Kc3 h1=Q 65. Rxh1 Kd5 66. Kb2 ' +
								'f4 67. Rd1+ Ke4 68. Rc1 Kd3 69. Rd1+ Ke2 70. Rc1 f3 71. Bc5 ' +
								'Rxg7 72. Rxc4 Rd7 73. Re4+ Kf1 74. Bd4 f2 0-1'
						}));
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
