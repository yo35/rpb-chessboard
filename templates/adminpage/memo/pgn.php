<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2020  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php esc_html_e( 'PGN game', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>
		<p><?php esc_html_e( 'Two different syntaxes exist for PGN games:', 'rpb-chessboard' ); ?></p>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?> url="<?php echo esc_html( $model->getPGNExampleURL() ); ?>" game=0]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'Here, the URL targets a .pgn file that contains the game. ' .
						'This type of file can be generated by any chess database software, ' .
						'including %1$sChessbase%4$s, %2$sScid%4$s, etc... ' .
						'See an example of a .pgn file %3$shere%4$s.',
						'rpb-chessboard'
					),
					'<a href="http://www.chessbase.com/" target="_blank">',
					'<a href="http://scid.sourceforge.net/" target="_blank">',
					sprintf( '<a href="%s" target="_blank">', esc_url( $model->getPGNExampleURL() ) ),
					'</a>'
				);
			?>
		</p>

		<p>
			<?php
				printf(
					esc_html__(
						'Please note that a .pgn file may contain several games. ' .
						'The attribute %1$s might be used to select which game in the file ' .
						'should be displayed (for instance, use %2$s to display the first game in the file, ' .
						'%3$s for the second game, etc...).',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">game=...</span>',
					'<span class="rpbchessboard-sourceCode">game=0</span>',
					'<span class="rpbchessboard-sourceCode">game=1</span>'
				);
			?>
		</p>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]
			<br/><br/>
			[Event &quot;1&lt;sup&gt;st&lt;/sup&gt; American Chess Congress&quot;]<br/>
			[Site &quot;New York, NY USA&quot;]<br/>
			[Date &quot;1857.11.03&quot;]<br/>
			[Round &quot;4.6&quot;]<br/>
			[White &quot;Paulsen, Louis&quot;]<br/>
			[Black &quot;Morphy, Paul&quot;]<br/>
			[Result &quot;0-1&quot;]<br/>
			<br/>
			1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 4. Bb5 Bc5 5. O-O O-O 6. Nxe5 Re8
			7. Nxc6 dxc6 8. Bc4 b5 9. Be2 Nxe4 10. Nxe4 Rxe4 11. Bf3 Re6
			12. c3 Qd3 13. b4 Bb6 14. a4 bxa4 15. Qxa4 Bd7 16. Ra2 Rae8
			17. Qa6<br/>
			<br/>
			{[#]
			<?php
				esc_html_e(
					'Morphy took twelve minutes over his next move, ' .
					'probably to assure himself that the combination was sound and ' .
					'that he had a forced win in every variation.',
					'rpb-chessboard'
				);
			?>
			}<br/>
			<br/>
			17... Qxf3 !! 18. gxf3 Rg6+ 19. Kh1 Bh3 20. Rd1
			({<?php esc_html_e( 'Not', 'rpb-chessboard' ); ?>} 20. Rg1 Rxg1+ 21. Kxg1 Re1+ -+)
			20... Bg2+ 21. Kg1 Bxf3+ 22. Kf1 Bg2+<br/>
			<br/>
			(22...Rg2 ! {<?php esc_html_e( 'would have won more quickly. For instance:', 'rpb-chessboard' ); ?>}
			23. Qd3 Rxf2+ 24. Kg1 Rg2+ 25. Kh1 Rg1#)<br/>
			<br/>
			23. Kg1 Bh3+ 24. Kh1 Bxf2 25. Qf1
			{<?php esc_html_e( 'Absolutely forced.', 'rpb-chessboard' ); ?>}
			25... Bxf1 26. Rxf1 Re2 27. Ra1 Rh6 28. d4 Be3 0-1
			<br/><br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'With this second syntax, the game is described by the code between the %1$s tags. ' .
						'The used notation follows the standard %2$sPGN format%3$s. ' .
						'It can be copy-pasted from a .pgn file generated by any chess database software.',
						'rpb-chessboard'
					),
					sprintf( '<span class="rpbchessboard-sourceCode">[%1$s][/%1$s]</span>', esc_html( $model->getPGNShortcode() ) ),
					sprintf( '<a href="%s" target="_blank">', esc_url( __( 'http://en.wikipedia.org/wiki/Portable_Game_Notation', 'rpb-chessboard' ) ) ),
					'</a>'
				);
			?>
		</p>

		<p>
			<?php
				printf(
					esc_html__( 'Please note the %1$s tag placed inside a comment to insert a diagram showing the current position.', 'rpb-chessboard' ),
					'<span class="rpbchessboard-sourceCode">[#]</span>'
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-example2"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-example2').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							diagramOptions: { squareSize: 28 },
							navigationBoard: 'frame',
							pgn:
								'[Event "1<sup>st</sup> American Chess Congress"]\n' +
								'[Site "New York, NY USA"]\n' +
								'[Date "1857.11.03"]\n' +
								'[Round "4.6"]\n' +
								'[White "Paulsen, Louis"]\n' +
								'[Black "Morphy, Paul"]\n' +
								'[Result "0-1"]\n' +
								'1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 4. Bb5 Bc5 5. O-O O-O 6. Nxe5 Re8\n' +
								'7. Nxc6 dxc6 8. Bc4 b5 9. Be2 Nxe4 10. Nxe4 Rxe4 11. Bf3 Re6\n' +
								'12. c3 Qd3 13. b4 Bb6 14. a4 bxa4 15. Qxa4 Bd7 16. Ra2 Rae8 17. Qa6\n' +
								'\n' +
								'{[#]' +
								<?php
									echo wp_json_encode(
										__(
											'Morphy took twelve minutes over his next move, ' .
											'probably to assure himself that the combination was sound and ' .
											'that he had a forced win in every variation.',
											'rpb-chessboard'
										)
									);
								?>
								+
								'}\n' +
								'\n' +
								'17... Qxf3 !! 18. gxf3 Rg6+ 19. Kh1 Bh3 20. Rd1\n' +
								'({' + <?php echo wp_json_encode( __( 'Not', 'rpb-chessboard' ) ); ?> + '}\n' +
								'20. Rg1 Rxg1+ 21. Kxg1 Re1+ -+)\n' +
								'20... Bg2+ 21. Kg1 Bxf3+ 22. Kf1 Bg2+\n' +
								'\n' +
								'(22...Rg2 ! {' +
								<?php echo wp_json_encode( __( 'would have won more quickly. For instance:', 'rpb-chessboard' ) ); ?> +
								'} 23. Qd3 Rxf2+ 24. Kg1 Rg2+ 25. Kh1 Rg1#)\n' +
								'\n' +
								'23. Kg1 Bh3+ 24. Kh1 Bxf2 25. Qf1\n' +
								'{' + <?php echo wp_json_encode( __( 'Absolutely forced.', 'rpb-chessboard' ) ); ?> + '}\n' +
								'25... Bxf1 26. Rxf1 Re2 27. Ra1 Rh6 28. d4 Be3 0-1'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
