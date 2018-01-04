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

<h3 id="rpbchessboard-pgnDiagram"><?php _e( 'Diagrams', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo htmlspecialchars( $model->getPGNShortcode() ); ?>]<br/>
			1. e4 c5<br/>
			<br/>
			{[pgndiagram] <?php _e( 'This opening is called the Sicilian defence. A possible continuation is:', 'rpb-chessboard' ); ?>}<br/>
			<br/>
			2. Nf3 d6 *<br/>
			[/<?php echo htmlspecialchars( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
			echo sprintf(
				__(
					'Notice that %1$s[pgndiagram]%2$s tags must not be used outside a PGN game. ' .
					'To insert a diagram outside a PGN game, use the %1$s[%3$s][/%3$s]%2$s tag instead.',
					'rpb-chessboard'
				),
				'<span class="rpbchessboard-sourceCode">',
				'</span>',
				htmlspecialchars( $model->getFENShortcode() )
			);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnDiagram-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnDiagram-anchor').chessgame($.extend(true, <?php echo json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							diagramOptions: { squareSize: 28 },
							pgn:
								'1. e4 c5\n' +
								'\n' +
								'{<div class="rpbui-chessgame-diagramAnchor"></div> ' +
								<?php
									echo json_encode( __( 'This opening is called the Sicilian defence. A possible continuation is:', 'rpb-chessboard' ) );
								?>
								 + '}\n' +
								'\n' +
								'2. Nf3 d6 *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
