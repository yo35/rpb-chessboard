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

<h3 id="rpbchessboard-pgnCustomStartingPosition"><?php _e( 'Custom starting position', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			<br/>
			[Event &quot;<?php _e( 'Endgame example', 'rpb-chessboard' ); ?>&quot;]<br/>
			[SetUp &quot;1&quot;]<br/>
			[FEN &quot;k7/n1PB4/1K6/8/8/8/8/8 w - - 0 50&quot;]<br/>
			<br/>
			{[pgndiagram]}<br/>
			<br/>
			50.Bc6+ Nxc6 51.c8=Q+ Nb8 52.Qb7# 1-0<br/>
			<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'The %1$s[FEN "..."]%2$s header might be used to specify that the game starts with a custom position. Additionally, the strict ' .
						'PGN syntax requires that %1$s[SetUp "1"]%2$s is added when using the %1$s[FEN "..."]%2$s header.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">',
					'</span>'
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnCustomStartingPosition-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnCustomStartingPosition-anchor').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							diagramOptions: { squareSize: 28 },
							pgn:
								'[Event "' + <?php echo wp_json_encode( __( 'Endgame example', 'rpb-chessboard' ) ); ?> + '"]\n' +
								'[SetUp "1"]\n' +
								'[FEN "k7/n1PB4/1K6/8/8/8/8/8 w - - 0 50"]\n' +
								'\n' +
								'{<div class="rpbui-chessgame-diagramAnchor"></div>}\n' +
								'\n' +
								'50.Bc6+ Nxc6 51.c8=Q+ Nb8 52.Qb7# 1-0'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
