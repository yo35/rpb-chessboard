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

<h3 id="rpbchessboard-fenAttributeColorsetPieceset"><?php esc_html_e( 'Colorset and pieceset', 'rpb-chessboard' ); ?></h3>

<div id="rpbchessboard-fenAttributeColorsetPieceset-content" class="rpbchessboard-columns">
	<div>

		<p>
			<?php
				printf(
					esc_html__( 'The %1$s and %2$s attributes controls respectively the colors of the chessboard and the piece theme.', 'rpb-chessboard' ),
					'<span class="rpbchessboard-sourceCode">colorset</span>',
					'<span class="rpbchessboard-sourceCode">pieceset</span>'
				);
			?>
		</p>

		<p>
			<label for="rpbchessboard-fenAttributeColorset-field"><?php esc_html_e( 'Colorset:', 'rpb-chessboard' ); ?></label>
			<select id="rpbchessboard-fenAttributeColorset-field">
				<?php foreach ( $model->getAvailableColorsets() as $colorset ) : ?>
				<option value="<?php echo esc_attr( $colorset ); ?>">
					<?php echo esc_html( $model->getColorsetLabel( $colorset ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="rpbchessboard-fenAttributePieceset-field"><?php esc_html_e( 'Pieceset:', 'rpb-chessboard' ); ?></label>
			<select id="rpbchessboard-fenAttributePieceset-field">
				<?php foreach ( $model->getAvailablePiecesets() as $pieceset ) : ?>
				<option value="<?php echo esc_attr( $pieceset ); ?>">
					<?php echo esc_html( $model->getPiecesetLabel( $pieceset ) ); ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php
				printf(
					'[%1$s <strong>colorset=<span id="rpbchessboard-fenAttributeColorset-sourceCodeExample">original</span></strong> ' .
					'<strong>pieceset=<span id="rpbchessboard-fenAttributePieceset-sourceCodeExample">cburnett</span></strong>] ... [/%1$s]',
					esc_html( $model->getFENShortcode() )
				);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-fenAttributeColorsetPieceset-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						var currentColorset = $('#rpbchessboard-fenAttributeColorset-sourceCodeExample').text();
						var currentPieceset = $('#rpbchessboard-fenAttributePieceset-sourceCodeExample').text();
						function refresh() {
							RPBChessboard.renderFEN($('#rpbchessboard-fenAttributeColorsetPieceset-anchor'), $.extend(<?php echo wp_json_encode( $model->getDefaultChessboardSettings() ); ?>, {
								position: 'start',
								squareSize: 48,
								colorset: currentColorset,
								pieceset: currentPieceset
							}));
						}
						refresh();
						$('#rpbchessboard-fenAttributeColorset-field').val(currentColorset);
						$('#rpbchessboard-fenAttributePieceset-field').val(currentPieceset);
						$('#rpbchessboard-fenAttributeColorset-field').change(function() {
							currentColorset = $(this).val();
							refresh();
							$('#rpbchessboard-fenAttributeColorset-sourceCodeExample').text(currentColorset);
						});
						$('#rpbchessboard-fenAttributePieceset-field').change(function() {
							currentPieceset = $(this).val();
							refresh();
							$('#rpbchessboard-fenAttributePieceset-sourceCodeExample').text(currentPieceset);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
