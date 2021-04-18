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

<h3 id="rpbchessboard-fenAttributeSquareSize"><?php esc_html_e( 'Square size', 'rpb-chessboard' ); ?></h3>

<div id="rpbchessboard-fenAttributeSquareSize-content" class="rpbchessboard-columns">
	<div>

		<p>
			<?php
				printf(
					esc_html__( 'The %1$s attribute controls the size of the chessboard squares.', 'rpb-chessboard' ),
					'<span class="rpbchessboard-sourceCode">square_size</span>'
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
				<?php foreach ( $model->getSquareSizeList() as $squareSize ) : ?>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeSquareSize-value"><?php echo esc_html( $squareSize ); ?></a></td>
					<td><?php echo $model->getDefaultSquareSize() === $squareSize ? '<div class="rpbchessboard-tickIcon"></div>' : ''; ?></td>
					<td><?php printf( esc_html__( 'The square width is %1$s pixels.', 'rpb-chessboard' ), esc_html( $squareSize ) ); ?></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td><?php esc_html_e( 'etc...', 'rpb-chessboard' ); ?></td>
					<td></td>
					<td>
						<?php
							printf(
								esc_html__( 'Any value between %1$s and %2$s can be used.', 'rpb-chessboard' ),
								esc_html( $model->getMinimumSquareSize() ),
								esc_html( $model->getMaximumSquareSize() )
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
				printf(
					'[%1$s <strong>square_size=<span id="rpbchessboard-fenAttributeSquareSize-sourceCodeExample">%2$s</span></strong>] ... [/%1$s]',
					esc_html( $model->getFENShortcode() ),
					esc_html( $model->getSquareSizeInitialExample() )
				);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-fenAttributeSquareSize-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						function refresh(value) {
							var props = $.extend(<?php echo wp_json_encode( $model->getDefaultChessboardSettings() ); ?>, {
								position: 'start',
								squareSize: <?php echo wp_json_encode( $model->getSquareSizeInitialExample() ); ?>
							});
							if (value) {
								props.squareSize = value;
							}
							RPBChessboard.renderFEN($('#rpbchessboard-fenAttributeSquareSize-anchor'), props);
						}
						refresh();
						$('.rpbchessboard-fenAttributeSquareSize-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							refresh(value);
							$('#rpbchessboard-fenAttributeSquareSize-sourceCodeExample').text(value);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
