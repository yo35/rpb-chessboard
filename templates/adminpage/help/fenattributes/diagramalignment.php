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

<h3 id="rpbchessboard-fenAttributeDiagramAlignment"><?php esc_html_e( 'Diagram alignment', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<p>
			<?php
				printf(
					esc_html__( 'The %1$s attribute controls how the diagram is inserted within the rest of the text.', 'rpb-chessboard' ),
					'<span class="rpbchessboard-sourceCode">align</span>'
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
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeDiagramAlignment-value">center</a></td>
					<td><?php echo $model->getDefaultDiagramAlignment() === 'center' ? '<div class="rpbchessboard-tickIcon"></div>' : ''; ?></td>
					<td><?php esc_html_e( 'The diagram is centered.', 'rpb-chessboard' ); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeDiagramAlignment-value">floatLeft</a></td>
					<td><?php echo $model->getDefaultDiagramAlignment() === 'floatLeft' ? '<div class="rpbchessboard-tickIcon"></div>' : ''; ?></td>
					<td><?php esc_html_e( 'The diagram floats on the left of the text.', 'rpb-chessboard' ); ?></td>
				</tr>
				<tr>
					<td><a href="#" class="rpbchessboard-sourceCode rpbchessboard-fenAttributeDiagramAlignment-value">floatRight</a></td>
					<td><?php echo $model->getDefaultDiagramAlignment() === 'floatRight' ? '<div class="rpbchessboard-tickIcon"></div>' : ''; ?></td>
					<td><?php esc_html_e( 'The diagram floats on the right of the text.', 'rpb-chessboard' ); ?></td>
				</tr>
			</tbody>
		</table>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php
				printf(
					'[%1$s <strong>align=<span id="rpbchessboard-fenAttributeDiagramAlignment-sourceCodeExample">center</span></strong>] ... [/%1$s]',
					esc_html( $model->getFENShortcode() )
				);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum quis est a ligula placerat mollis vel a mi.
					Maecenas placerat cursus dolor non porta.
				</p>
				<div id="rpbchessboard-fenAttributeDiagramAlignment-wrapper">
					<div id="rpbchessboard-fenAttributeDiagramAlignment-anchor"></div>
				</div>
				<p>
					Phasellus sit amet arcu et ante bibendum tincidunt.
					In aliquam vitae lectus quis ornare. Integer aliquam aliquet lorem non congue.
					Cras facilisis mauris rhoncus mi malesuada, ut euismod ligula dictum.
					Aliquam vulputate mauris nunc, a pretium massa vehicula sed.
					Donec turpis nunc, vulputate pretium ullamcorper ut, malesuada in mauris.
					Nam aliquam at nunc sit amet pellentesque. Etiam sagittis aliquet nibh eu ultricies. Proin vitae malesuada augue.
					Integer laoreet, odio dapibus ornare dictum, est nisi scelerisque nisi, tincidunt ultricies nulla sapien ut nisl.
					Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
					Fusce ante nisl, auctor faucibus arcu vel, pretium aliquet odio.
				</p>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-fenAttributeDiagramAlignment-anchor').chessboard($.extend(<?php echo wp_json_encode( $model->getDefaultChessboardSettings() ); ?>, {
							position: 'start',
							squareSize: 28
						}));
						$('.rpbchessboard-fenAttributeDiagramAlignment-value').click(function(e) {
							e.preventDefault();
							var value = $(this).text();
							$('#rpbchessboard-fenAttributeDiagramAlignment-wrapper').attr('class', 'rpbchessboard-diagramAlignment-' + value);
							$('#rpbchessboard-fenAttributeDiagramAlignment-sourceCodeExample').text(value);
						});
					});
				</script>
			</div>
		</div>

	</div>
</div>
