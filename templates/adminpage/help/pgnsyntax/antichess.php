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

<h3 id="rpbchessboard-pgnAntichess"><?php esc_html_e( 'Antichess', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			<br/>
			[Variant "Antichess"]<br/>
			<br/>
			1.d3 ?? g5 2.Bxg5 Bg7 3.Bxe7 Bxb2 4.Bxd8 Bxa1 5.Bxc7 Bc3 6.Bxb8 Rxb8<br/>
			7.Nxc3 d5 8.Nxd5 Nf6 9.Nxf6 Rg8 10.Nxe8 Rxg2 11.Bxg2 f6 12.Bxb7 Rxb7<br/>
			13.Nxf6 Rb8 14.Nxh7 Rb1 15.Qxb1 Bb7 16.Qxb7 a6 17.Qxa6# {[#]} 0-1 *<br/>
			<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'The %1$s header indicates that the game is an Antichess game.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">[Variant "Antichess"]</span>',
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnAntichess-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnAntichess-anchor').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'frame',
							diagramOptions: { squareSize: 28 },
							pgn:
								'[Variant "Antichess"]\n' +
								'\n' +
								'1.d3 ?? g5 2.Bxg5 Bg7 3.Bxe7 Bxb2 4.Bxd8 Bxa1 5.Bxc7 Bc3 6.Bxb8 Rxb8 7.Nxc3 d5 8.Nxd5 Nf6 9.Nxf6 Rg8 10.Nxe8 Rxg2 11.Bxg2 f6 ' +
								'12.Bxb7 Rxb7 13.Nxf6 Rb8 14.Nxh7 Rb1 15.Qxb1 Bb7 16.Qxb7 a6 17.Qxa6# {[#]} 0-1 *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
