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

<h3 id="rpbchessboard-fenAttributeShowCoordinates"><?php esc_html_e( 'Show coordinates', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<p>
			<?php
				printf(
					esc_html__( 'The %1$s attribute controls whether the row and column coordinates are visible or not.', 'rpb-chessboard' ),
					'<span class="rpbchessboard-sourceCode">show_coordinates</span>'
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
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeShowCoordinates-value">false</a></td>
					<td><?php echo $model->getDefaultShowCoordinates() ? '' : '<div class="rpbchessboard-tickIcon"></div>'; ?></td>
					<td><?php esc_html_e( 'The row and column coordinates are hidden.', 'rpb-chessboard' ); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeShowCoordinates-value">true</a></td>
					<td><?php echo $model->getDefaultShowCoordinates() ? '<div class="rpbchessboard-tickIcon"></div>' : ''; ?></td>
					<td><?php esc_html_e( 'The row and column coordinates are visible.', 'rpb-chessboard' ); ?></td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php
				printf(
					'[%1$s <strong>show_coordinates=<span id="rpbchessboard-fenAttributeShowCoordinates-sourceCodeExample">true</span></strong>] ... [/%1$s]',
					esc_html( $model->getFENShortcode() )
				);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-fenAttributeShowCoordinates-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						function refresh(value) {
							var props = $.extend(<?php echo wp_json_encode( $model->getDefaultChessboardSettings() ); ?>, {
								position: 'start',
								squareSize: 28,
								showCoordinates: true
							});
							if (value) {
								props.showCoordinates = value === 'true';
							}
							RPBChessboard.renderFEN($('#rpbchessboard-fenAttributeShowCoordinates-anchor'), props);
						}
						refresh();
						$('.rpbchessboard-fenAttributeShowCoordinates-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							refresh(value);
							$('#rpbchessboard-fenAttributeShowCoordinates-sourceCodeExample').text(value);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
