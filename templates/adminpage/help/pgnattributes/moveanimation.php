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

<h3 id="rpbchessboard-pgnAttributeMoveAnimation"><?php esc_html_e( 'Move animation', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<p>
			<?php
				printf(
					esc_html__( 'The %1$s attribute controls the duration of the move animation on the navigation board.', 'rpb-chessboard' ),
					'<span class="rpbchessboard-sourceCode">animation_speed</span>'
				);
				?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php esc_html_e( 'Value', 'rpb-chessboard' ); ?></th>
					<th><?php esc_html_e( 'Default', 'rpb-chessboard' ); ?></th>
					<th><?php esc_html_e( 'Description', 'rpb-chessboard' ); ?></th>
				</tr>
				<?php foreach ( $model->getAnimationSpeedList() as $animationSpeed ) : ?>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeAnimationSpeed-value"><?php echo esc_html( $animationSpeed ); ?></a></td>
					<td><?php echo $model->getDefaultAnimationSpeed() === $animationSpeed ? '<div class="rpbchessboard-tickIcon"></div>' : ''; ?></td>
					<td>
						<?php
							echo 0 === $animationSpeed ? esc_html__( 'No animation', 'rpb-chessboard' ) :
								sprintf( esc_html__( 'The animation lasts %1$s milliseconds.', 'rpb-chessboard' ), esc_html( $animationSpeed ) );
						?>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td><?php esc_html_e( 'etc...', 'rpb-chessboard' ); ?></td>
					<td></td>
					<td>
						<?php
							printf(
								esc_html__( 'Any value between %1$s and %2$s can be used.', 'rpb-chessboard' ),
								'0',
								esc_html( $model->getMaximumAnimationSpeed() )
							);
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<p>
			<?php
				printf(
					esc_html__(
						'The %1$s attribute controls whether an arrow should be used or not ' .
						'to highlight the moves on the navigation board.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">animation_speed</span>'
				);
			?>
		</p>

		<table class="rpbchessboard-attributeTable">
			<tbody>
				<tr>
					<th><?php esc_html_e( 'Value', 'rpb-chessboard' ); ?></th>
					<th><?php esc_html_e( 'Default', 'rpb-chessboard' ); ?></th>
					<th><?php esc_html_e( 'Description', 'rpb-chessboard' ); ?></th>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowMoveArrow-value">false</a></td>
					<td><?php echo $model->getDefaultShowMoveArrow() ? '' : '<div class="rpbchessboard-tickIcon">'; ?></td>
					<td><?php esc_html_e( 'No arrow is shown.', 'rpb-chessboard' ); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-pgnAttributeShowMoveArrow-value">true</a></td>
					<td><?php echo $model->getDefaultShowMoveArrow() ? '<div class="rpbchessboard-tickIcon">' : ''; ?></td>
					<td><?php esc_html_e( 'The moves are highlighted by an arrow.', 'rpb-chessboard' ); ?></td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php
				printf(
					'[%1$s <strong>animation_speed=<span id="rpbchessboard-pgnAttributeAnimationSpeed-sourceCodeExample">0</span></strong> ' .
					'<strong>show_move_arrow=<span id="rpbchessboard-pgnAttributeShowMoveArrow-sourceCodeExample">false</span></strong>] ... [/%1$s]',
					esc_html( $model->getPGNShortcode() )
				);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnAttributeMoveAnimation-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'floatLeft',
							navigationBoardOptions: { squareSize: 28, animationSpeed: 0, showMoveArrow: false },
							pgn:
								'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
								'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
								'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
						}));
						$('.rpbchessboard-pgnAttributeAnimationSpeed-value').click(function(e) {
							e.preventDefault();
							var options = $('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions');
							options.animationSpeed = $(this).text();
							$('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions', options);
							$('#rpbchessboard-pgnAttributeAnimationSpeed-sourceCodeExample').text(options.animationSpeed);
						});
						$('.rpbchessboard-pgnAttributeShowMoveArrow-value').click(function(e) {
							e.preventDefault();
							var options = $('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions');
							options.showMoveArrow = $(this).text();
							$('#rpbchessboard-pgnAttributeMoveAnimation-anchor').chessgame('option', 'navigationBoardOptions', options);
							$('#rpbchessboard-pgnAttributeShowMoveArrow-sourceCodeExample').text(options.showMoveArrow);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
