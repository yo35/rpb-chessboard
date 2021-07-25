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

<h3 id="rpbchessboard-pgnChess960"><?php esc_html_e( 'Chess960 (aka. Fischer Random Chess)', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			<br/>
			[SetUp "1"]<br/>
			[FEN "qbnnrkbr/pppppppp/8/8/8/8/PPPPPPPP/QBNNRKBR w KQkq - 0 1"]<br/>
			[Variant "Chess960"]<br/>
			<br/>
			{[#]} 1. f4 Nd6 2. Bc5 Ne6 3. O-O O-O-O {[#]} *<br/>
			<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'The %1$s header indicates that the game is a Chess960 game (%2$s is also supported), ' .
						'and the %3$s header defines the starting position. Please note that the %3$s header is mandatory for Chess960 games.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">[Variant "Chess960"]</span>',
					'<span class="rpbchessboard-sourceCode">[Variant "Fischerandom"]</span>',
					'<span class="rpbchessboard-sourceCode">[FEN "..."]</span>'
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnChess960-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnChess960-anchor').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							diagramOptions: { squareSize: 28 },
							pgn:
								'[SetUp "1"]\n' +
								'[FEN "qbnnrkbr/pppppppp/8/8/8/8/PPPPPPPP/QBNNRKBR w KQkq - 0 1"]\n' +
								'[Variant "Chess960"]\n' +
								'\n' +
								'{[#]} 1. f4 Nd6 2. Bc5 Ne6 3. O-O O-O-O {[#]} *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
