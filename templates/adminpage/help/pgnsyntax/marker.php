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

<h3 id="rpbchessboard-pgnMarker"><?php esc_html_e( 'Square, arrow, and text markers', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			1. e4 e5 2. Nf3 Nc6 3. Bb5<br/>
			<br/>
			{[#][%csl Re5,Gc6][%cal Rf3e5,Rb5c6,Gc6e5]
			<?php
				esc_html_e(
					'The Ruy Lopez: White\'s third move attacks the knight which defends the e5-pawn from the attack by the f3-knight.',
					'rpb-chessboard'
				);
			?>
			}<br/>
			<br/>
			*<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			[SetUp "1"]<br/>
			[FEN "8/2k5/p1P5/P1K5/8/8/8/8 w - - 0 1"]<br/>
			<br/>
			{[#][%ctl RAc5,GAc7,RBd6,GBd8,RCd5,GCc8] <?php echo esc_html_e( 'Typical conjugate square situation.', 'rpb-chessboard' ); ?>}<br/>
			1. Kd5 Kc8 2. Kd4 Kd8 3. Kc4 Kc8 4. Kd5 Kc7 5. Kc5 Kc8 6. Kb6 +- *<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'Squares can be highlighted by inserting the tag %1$s[%%csl ...]%2$s in a comment. ' .
						'The squares to highlight are represented by a group of 3 characters: ' .
						'the first one represents the color to use (%1$sG%2$s for green, %1$sR%2$s for red, %1$sY%2$s for yellow), ' .
						'the second and third ones represent the targeted square. ' .
						'For instance, %1$s[%%csl Re5,Gc6,Yf3,Yb5]%2$s highlights square e5 in red, square c6 in green, ' .
						'and squares f3 and b5 in yellow.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">',
					'</span>'
				);
			?>
		</p>

		<p>
			<?php
				printf(
					esc_html__(
						'Likewise, arrows can be added by inserting the tag %1$s[%%cal ...]%2$s in a comment. ' .
						'An arrow is encoded by a group of 5 characters: the first one is the color to use (%1$sG%2$s, %1$sR%2$s, or %1$sY%2$s), ' .
						'the second and third ones represent the origin square, the fourth and fifth ones the destination square. ' .
						'For instance, %1$s[%%cal Rf3e5,Gd8d4]%2$s creates a red arrow from f3 to e5, and a green arrow from d8 to d4.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">',
					'</span>'
				);
			?>
		</p>

		<p>
			<?php
				printf(
					esc_html__(
						'Finally, a letter or a digit can be used to decorate a square: this is achieved by using the tag %1$s[%%ctl ...]%2$s in a comment. ' .
						'An character decoration is encoded by a group of 4 characters: the first one is the color to use (%1$sG%2$s, %1$sR%2$s, or %1$sY%2$s), ' .
						'the second one is the character to use (must be an upper-case letter %1$sA-Z%2$s, a lower-case letter %1$sa-z%2$s, or a digit %1$s0-9%2$s), ' .
						'the third and fourth ones represent the targeted square. ' .
						'For instance, %1$s[%%ctl GAd4,Yzd5]%2$s decorates d4 with a red A, and d5 with a yellow z.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">',
					'</span>'
				);
			?>
		</p>

		<p>
			<?php
				printf(
					esc_html__(
						'Square and arrow markers that are created in %3$sChessbase softwares%4$s are exported in PGN format ' .
						'using these %1$s[%%csl ...]%2$s and %1$s[%%cal ...]%2$s notations. ' .
						'On the other hand, the %1$s[%%ctl ...]%2$s notation for text markers is specific to RPB Chessboard.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">',
					'</span>',
					'<a href="http://www.chessbase.com/" target="_blank">',
					'</a>'
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnMarker-anchor1"></div>
				<div id="rpbchessboard-pgnMarker-anchor2"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnMarker-anchor1').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							diagramOptions: { squareSize: 28 },
							pgn:
								'1. e4 e5 2. Nf3 Nc6 3. Bb5\n' +
								'\n' +
								'{[#][%csl Re5,Gc6][%cal Rf3e5,Rb5c6,Gc6e5] ' +
								<?php echo wp_json_encode( __( 'The Ruy Lopez: White\'s third move attacks the knight which defends the e5-pawn from the attack by the f3-knight.', 'rpb-chessboard' ) ); ?> +
								'}\n' +
								'\n' +
								'*'
						}));
						$('#rpbchessboard-pgnMarker-anchor2').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							diagramOptions: { squareSize: 28 },
							pgn:
								'[SetUp "1"]\n' +
								'[FEN "8/2k5/p1P5/P1K5/8/8/8/8 w - - 0 1"]\n' +
								'\n' +
								'{[#][%ctl RAc5,GAc7,RBd6,GBd8,RCd5,GCc8] ' +
								<?php echo wp_json_encode( __( 'Typical conjugate square situation.', 'rpb-chessboard' ) ); ?> +
								'}\n' +
								'1. Kd5 Kc8 2. Kd4 Kd8 3. Kc4 Kc8 4. Kd5 Kc7 5. Kc5 Kc8 6. Kb6 +- *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
