<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>            *
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

<?php
	include(RPBCHESSBOARD_ABSPATH.'templates/localization.php');
?>

<div id="rpbchessboard-admin-help">

	<ul class="subsubsub">

		<li id="rpbchessboard-admin-help-fen-button">
			<a href="javascript: showTab(jQuery,'rpbchessboard-admin-help-fen');"><?php _e('FEN diagram', 'rpbchessboard'); ?></a>
		</li>

		<li id="rpbchessboard-admin-help-pgn-button">
			<a href="javascript: showTab(jQuery,'rpbchessboard-admin-help-pgn');"><?php _e('PGN game', 'rpbchessboard'); ?></a>
		</li>

	</ul>





	<div id="rpbchessboard-admin-help-fen">

		<h3>
			<?php echo sprintf(
				__('Chess diagrams with the %1$s[fen][/fen]%2$s tags', 'rpbchessboard'),
				'<span class="rpbchessboard-admin-code-inline">',
				'</span>'
			); ?>
		</h3>



		<h4><?php _e('FEN format', 'rpbchessboard'); ?></h4>

		<p>
			<?php echo sprintf(
				__(
					'The string between the %1$s[fen][/fen]%2$s tags describe the position. '.
					'The used notation follows the %3$sFEN format%4$s (Forsyth-Edwards Notation), '.
					'which is comprehensively described on %3$sWikipedia%4$s. '.
					'The FEN syntax is summarized here through a few representative examples.',
				'rpbchessboard'),
				'<span class="rpbchessboard-admin-code-inline">',
				'</span>',
				sprintf('<a href="%1$s" target="_blank">',
					__('http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpbchessboard')
				),
				'</a>'
			); ?>
		</p>



		<div id="rpbchessboard-admin-fenExamples" class="rpbchessboard-admin-tabs">

			<ul>
				<li><?php _e('Empty position'  , 'rpbchessboard'); ?></li>
				<li><?php _e('Initial position', 'rpbchessboard'); ?></li>
				<li><?php _e('After 1.e4'      , 'rpbchessboard'); ?></li>
				<li><?php _e('Who can castle?' , 'rpbchessboard'); ?></li>
				<li><?php _e('Légal Trap'      , 'rpbchessboard'); ?></li>
			</ul>

			<div>
				<div class="rpbchessboard-admin-code-block">
					[fen]8/8/8/8/8/8/8/8 w - - 0 1[/fen]
				</div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<p>
							<?php _e('An empty position.', 'rpbchessboard'); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-exampleFen0-in" class="rpbchessboard-in">
								8/8/8/8/8/8/8/8 w - - 0 1
							</div>
							<div id="rpbchessboard-admin-exampleFen0-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-exampleFen0-in', 'rpbchessboard-admin-exampleFen0-out',
									{squareSize: 28});
							</script>
						</div>
					</div>
				</div>
			</div>

			<div>
				<div class="rpbchessboard-admin-code-block">
					[fen]rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1[/fen]
				</div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<p>
							<?php _e('The initial position.', 'rpbchessboard'); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-exampleFen1-in" class="rpbchessboard-in">
								rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
							</div>
							<div id="rpbchessboard-admin-exampleFen1-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-exampleFen1-in', 'rpbchessboard-admin-exampleFen1-out',
									{squareSize: 28});
							</script>
						</div>
					</div>
				</div>
			</div>

			<div>
				<div class="rpbchessboard-admin-code-block">
					[fen]rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1[/fen]
				</div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<p>
							<?php echo sprintf(
								__(
									'After 1.e4. Notice the 4<sup>th</sup> field in the FEN string (%1$s), '.
									'indicating the square where <em>en passant</em> can be done '.
									'(if there were a black pawn either in d4 or in f4).',
								'rpbchessboard'),
								'<span class="rpbchessboard-admin-code-inline">&quot;e3&quot;</span>'
							); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-exampleFen2-in" class="rpbchessboard-in">
								rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3 0 1
							</div>
							<div id="rpbchessboard-admin-exampleFen2-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-exampleFen2-in', 'rpbchessboard-admin-exampleFen2-out',
									{squareSize: 28});
							</script>
						</div>
					</div>
				</div>
			</div>

			<div>
				<div class="rpbchessboard-admin-code-block">
					[fen]r3k2r/8/8/8/8/8/8/R3K2R b K - 0 1[/fen]
				</div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<p>
							<?php _e('Who can castle? And where?', 'rpbchessboard'); ?>
						</p>
						<p>
							<?php echo sprintf(
								__(
									'Here, the 3<sup>rd</sup> field in the FEN string (%1$s) indicates '.
									'that only king-side white castling is available. Other castling availabilities '.
									'might be indicated with characters %2$s (queen-side white castling), '.
									'%3$s (king-side black castling), and %4$s (queen-side black castling). '.
									'If neither side can castle, the 3<sup>rd</sup> FEN field is set to %5$s.',
								'rpbchessboard'),
								'<span class="rpbchessboard-admin-code-inline">&quot;K&quot;</span>',
								'<span class="rpbchessboard-admin-code-inline">&quot;Q&quot;</span>',
								'<span class="rpbchessboard-admin-code-inline">&quot;k&quot;</span>',
								'<span class="rpbchessboard-admin-code-inline">&quot;q&quot;</span>',
								'<span class="rpbchessboard-admin-code-inline">&quot;-&quot;</span>'
							); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-exampleFen3-in" class="rpbchessboard-in">
								r3k2r/8/8/8/8/8/8/R3K2R b K - 0 1
							</div>
							<div id="rpbchessboard-admin-exampleFen3-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-exampleFen3-in', 'rpbchessboard-admin-exampleFen3-out',
									{squareSize: 28});
							</script>
						</div>
					</div>
				</div>
			</div>

			<div>
				<div class="rpbchessboard-admin-code-block">
					[fen]r2q1bnr/ppp1kBpp/2np4/3NN3/4P3/8/PPPP1PPP/R1BbK2R b KQ - 2 7[/fen]
				</div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<p>
							<?php _e('The Légal Trap.', 'rpbchessboard'); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-exampleFen4-in" class="rpbchessboard-in">
								r2q1bnr/ppp1kBpp/2np4/3NN3/4P3/8/PPPP1PPP/R1BbK2R b KQ - 2 7
							</div>
							<div id="rpbchessboard-admin-exampleFen4-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-exampleFen4-in', 'rpbchessboard-admin-exampleFen4-out',
									{squareSize: 28});
							</script>
						</div>
					</div>
				</div>
			</div>

		</div>



		<h4><?php _e('Attributes', 'rpbchessboard'); ?></h4>

		<p>
			<?php
				_e('The aspect of the chess diagrams can be customized thanks to the following attributes.',
					'rpbchessboard');
			?>
		</p>



		<div id="rpbchessboard-admin-fenAttributes" class="rpbchessboard-admin-tabs">

			<ul>
				<li><?php _e('Square size', 'rpbchessboard'); ?></li>
				<li><?php _e('Coordinates', 'rpbchessboard'); ?></li>
			</ul>

			<div>
				<p>
					<?php echo sprintf(
						__(
							'The %1$s attribute controls the size (in pixels) of the chessboard squares.',
						'rpbchessboard'),
						'<span class="rpbchessboard-admin-code-inline">square_size</span>'
					); ?>
				</p>
				<div class="rpbchessboard-admin-columns">
					<div>
						<div class="rpbchessboard-admin-code-block">
							[fen square_size=24] ... [/fen]
						</div>
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-squareSize-example1-in" class="rpbchessboard-in">
								rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
							</div>
							<div id="rpbchessboard-admin-squareSize-example1-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-squareSize-example1-in', 'rpbchessboard-admin-squareSize-example1-out',
									{squareSize: 24});
							</script>
						</div>
					</div>
					<div>
						<div class="rpbchessboard-admin-code-block">
							[fen square_size=48] ... [/fen]
						</div>
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-squareSize-example2-in" class="rpbchessboard-in">
								rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
							</div>
							<div id="rpbchessboard-admin-squareSize-example2-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-squareSize-example2-in', 'rpbchessboard-admin-squareSize-example2-out',
									{squareSize: 48});
							</script>
						</div>
					</div>
				</div>
			</div>

			<div>
				<p>
					<?php echo sprintf(
						__(
							'The %1$s attribute controls whether the row and columns coordinates are displayed or not.',
						'rpbchessboard'),
						'<span class="rpbchessboard-admin-code-inline">show_coordinates</span>'
					); ?>
				</p>
				<div class="rpbchessboard-admin-columns">
					<div>
						<div class="rpbchessboard-admin-code-block">
							[fen show_coordinates=true] ... [/fen]
						</div>
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-showCoordinates-example1-in" class="rpbchessboard-in">
								rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
							</div>
							<div id="rpbchessboard-admin-showCoordinates-example1-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-showCoordinates-example1-in', 'rpbchessboard-admin-showCoordinates-example1-out',
									{squareSize: 28, showCoordinates: true});
							</script>
						</div>
					</div>
					<div>
						<div class="rpbchessboard-admin-code-block">
							[fen show_coordinates=false] ... [/fen]
						</div>
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-showCoordinates-example2-in" class="rpbchessboard-in">
								rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1
							</div>
							<div id="rpbchessboard-admin-showCoordinates-example2-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
							<script type="text/javascript">
								processFEN('rpbchessboard-admin-showCoordinates-example2-in', 'rpbchessboard-admin-showCoordinates-example2-out',
									{squareSize: 28, showCoordinates: false});
							</script>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>





	<div id="rpbchessboard-admin-help-pgn">

		<h3>
			<?php echo sprintf(
				__('Chess games with the %1$s[pgn][/pgn]%2$s tags', 'rpbchessboard'),
				'<span class="rpbchessboard-admin-code-inline">',
				'</span>'
			); ?>
		</h3>



		<h4><?php _e('PGN format', 'rpbchessboard'); ?></h4>

		<p>
			<?php echo sprintf(
				__(
					'The string between the %1$s[pgn][/pgn]%2$s tags describe the game. '.
					'The used notation follows the standard %3$sPGN format%4$s, and can '.
					'be automatically generated by the common chess database softwares, '.
					'including %5$sChessbase%6$s, %7$sScid%8$s, etc... '.
					'The PGN syntax is summarized here through a few representative examples.',
				'rpbchessboard'),
				'<span class="rpbchessboard-admin-code-inline">',
				'</span>',
				sprintf('<a href="%1$s" target="_blank">',
					__('http://en.wikipedia.org/wiki/Portable_Game_Notation', 'rpbchessboard')
				),
				'</a>',
				'<a href="http://www.chessbase.com/" target="_blank">',
				'</a>',
				'<a href="http://scid.sourceforge.net/" target="_blank">',
				'</a>'
			); ?>
		</p>



		<div id="rpbchessboard-admin-pgnExamples" class="rpbchessboard-admin-tabs">

			<ul>
				<li>Kasparov &ndash; Kramnik</li>
				<li><?php _e('Game with no headers'    , 'rpbchessboard'); ?></li>
				<li><?php _e('Custom starting position', 'rpbchessboard'); ?></li>
			</ul>

			<div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<div class="rpbchessboard-admin-code-block">
							[pgn]<br/>
							<br/>
							[Event &quot;Linares 16&lt;sup&gt;th&lt;sup&gt;&quot;]<br/>
							[Site &quot;Linares&quot;]<br/>
							[Date &quot;1999.02.25&quot;]<br/>
							[Round &quot;4&quot;]<br/>
							[White &quot;Kasparov, Garry&quot;]<br/>
							[Black &quot;Kramnik, Vladimir&quot;]<br/>
							[Result &quot;1/2-1/2&quot;]<br/>
							[WhiteElo &quot;2812&quot;]<br/>
							[BlackElo &quot;2751&quot;]<br/>
							<br/>
							1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8.
							a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15.
							Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2<br/>
							<br/>
							[/pgn]
						</div>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-examplePgn1-in" class="rpbchessboard-in">
								[Event &quot;Linares 16&lt;sup&gt;th&lt;sup&gt;&quot;]
								[Site &quot;Linares&quot;]
								[Date &quot;1999.02.25&quot;]
								[Round &quot;4&quot;]
								[White &quot;Kasparov, Garry&quot;]
								[Black &quot;Kramnik, Vladimir&quot;]
								[Result &quot;1/2-1/2&quot;]
								[WhiteElo &quot;2812&quot;]
								[BlackElo &quot;2751&quot;]
								1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8.
								a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15.
								Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2
							</div>
							<div id="rpbchessboard-admin-examplePgn1-out" class="rpbchessboard-out rpbchessboard-invisible">
								<?php include(RPBCHESSBOARD_ABSPATH.'templates/common/pgncontent.php'); ?>
							</div>
							<script type="text/javascript">
								processPGN('rpbchessboard-admin-examplePgn1-in', 'rpbchessboard-admin-examplePgn1-out');
							</script>
						</div>
					</div>
				</div>
			</div>

			<div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<div class="rpbchessboard-admin-code-block">
							[pgn]<br/>
							1. e3 a5 2. Qh5 Ra6 3. Qxa5 h5 4. Qxc7 Rah6 5. h4 f6 6. Qxd7+ Kf7 7. Qxb7 Qd3
							8. Qxb8 Qh7 9. Qxc8 Kg6 10. Qe6 {<?php _e('Stalemate.', 'rpbchessboard'); ?>} *<br/>
							[/pgn]
						</div>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-examplePgn2-in" class="rpbchessboard-in">
								1. e3 a5 2. Qh5 Ra6 3. Qxa5 h5 4. Qxc7 Rah6 5. h4 f6 6. Qxd7+ Kf7 7. Qxb7 Qd3
								8. Qxb8 Qh7 9. Qxc8 Kg6 10. Qe6 {<?php _e('Stalemate.', 'rpbchessboard'); ?>} *
							</div>
							<div id="rpbchessboard-admin-examplePgn2-out" class="rpbchessboard-out rpbchessboard-invisible">
								<?php include(RPBCHESSBOARD_ABSPATH.'templates/common/pgncontent.php'); ?>
							</div>
							<script type="text/javascript">
								processPGN('rpbchessboard-admin-examplePgn2-in', 'rpbchessboard-admin-examplePgn2-out');
							</script>
						</div>
					</div>
				</div>
				<p>
					<?php echo sprintf(
						__(
							'Normally, the strict PGN syntax requires that each PGN string starts with '.
							'7 compulsory headers: %1$s[Event &quot;...&quot;]%2$s, '.
							'%1$s[Site &quot;...&quot;]%2$s, %1$s[Date &quot;...&quot;]%2$s, '.
							'%1$s[Round &quot;...&quot;]%2$s, %1$s[White &quot;...&quot;]%2$s, '.
							'%1$s[Black &quot;...&quot;]%2$s, and %1$s[Result &quot;...&quot;]%2$s. '.
							'However, they are all optional here.',
						'rpbchessboard'),
						'<span class="rpbchessboard-admin-code-inline">',
						'</span>'
					); ?>
				</p>
			</div>

			<div>
				<div class="rpbchessboard-admin-columns">
					<div class="rpbchessboard-admin-column-left">
						<div class="rpbchessboard-admin-code-block">
							[pgn]<br/>
							<br/>
							[Event &quot;Wijk aan Zee&quot;]<br/>
							[Site &quot;Wijk aan Zee&quot;]<br/>
							[Date &quot;1999.01.20&quot;]<br/>
							[Round &quot;4&quot;]<br/>
							[White &quot;Kasparov, Garry&quot;]<br/>
							[Black &quot;Topalov, Veselin&quot;]<br/>
							[Result &quot;1-0&quot;]<br/>
							[WhiteElo &quot;2812&quot;]<br/>
							[BlackElo &quot;2700&quot;]<br/>
							[SetUp &quot;1&quot;]<br/>
							[FEN &quot;b2r3r/k4p1p/p2q1np1/NppP4/3p1Q2/P4PPB/1PP4P/1K1RR3 w - - 0 24&quot;]<br/>
							<br/>
							{[pgndiagram]}
							24. Rxd4 !! cxd4 25. Re7+ Kb6 26. Qxd4+ Kxa5 27. b4+ Ka4 28. Qc3 Qxd5 29. Ra7 Bb7
							30. Rxb7 Qc4 31. Qxf6 Kxa3 32. Qxa6+ Kxb4 33. c3+ Kxc3 34. Qa1+ Kd2 35. Qb2+
							Kd1 36. Bf1 Rd2 37. Rd7 Rxd7 38. Bxc4 bxc4 39. Qxh8 Rd3 40. Qa8 c3 41. Qa4+ Ke1
							42. f4 f5 43. Kc1 Rd2 44. Qa7 1-0<br/>
							<br/>
							[/pgn]
						</div>
						<p>
							<?php echo sprintf(
								__(
									'The %1$s[FEN &quot;...&quot;]%2$s header might be used to specify that the game '.
									'starts with a custom position. Additionally, the strict PGN syntax requires that '.
									'%1$s[SetUp &quot;1&quot;]%2$s is added when using the %1$s[FEN &quot;...&quot;]%2$s header.',
								'rpbchessboard'),
								'<span class="rpbchessboard-admin-code-inline">',
								'</span>'
							); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column-right">
						<div class="rpbchessboard-admin-visu-block">
							<div id="rpbchessboard-admin-examplePgn3-in" class="rpbchessboard-in">
								[Event &quot;Wijk aan Zee&quot;]
								[Site &quot;Wijk aan Zee&quot;]
								[Date &quot;1999.01.20&quot;]
								[Round &quot;4&quot;]
								[White &quot;Kasparov, Garry&quot;]
								[Black &quot;Topalov, Veselin&quot;]
								[Result &quot;1-0&quot;]
								[WhiteElo &quot;2812&quot;]
								[BlackElo &quot;2700&quot;]
								[SetUp &quot;1&quot;]
								[FEN &quot;b2r3r/k4p1p/p2q1np1/NppP4/3p1Q2/P4PPB/1PP4P/1K1RR3 w - - 0 24&quot;]
								{&lt;span class=&quot;PgnWidget-anchor-diagram&quot;&gt;&lt;/span&gt;}
								24. Rxd4 !! cxd4 25. Re7+ Kb6 26. Qxd4+ Kxa5 27. b4+ Ka4 28. Qc3 Qxd5 29. Ra7 Bb7
								30. Rxb7 Qc4 31. Qxf6 Kxa3 32. Qxa6+ Kxb4 33. c3+ Kxc3 34. Qa1+ Kd2 35. Qb2+
								Kd1 36. Bf1 Rd2 37. Rd7 Rxd7 38. Bxc4 bxc4 39. Qxh8 Rd3 40. Qa8 c3 41. Qa4+ Ke1
								42. f4 f5 43. Kc1 Rd2 44. Qa7 1-0
							</div>
							<div id="rpbchessboard-admin-examplePgn3-out" class="rpbchessboard-out rpbchessboard-invisible">
								<?php include(RPBCHESSBOARD_ABSPATH.'templates/common/pgncontent.php'); ?>
							</div>
							<script type="text/javascript">
								processPGN('rpbchessboard-admin-examplePgn3-in', 'rpbchessboard-admin-examplePgn3-out',
									{squareSize: 28});
							</script>
						</div>
					</div>
				</div>
			</div>

		</div>


	</div>





	<script type="text/javascript">

		// List of top-level tabs
		var tabs = [
			'rpbchessboard-admin-help-fen',
			'rpbchessboard-admin-help-pgn'
		];

		// Function to display a tab (top-level)
		function showTab($, tabID)
		{
			for(var k=0; k<tabs.length; ++k) {
				if(tabs[k]==tabID) {
					$('#' + tabID).removeClass('rpbchessboard-invisible');
					$('#' + tabID + '-button > a').addClass('current');
				}
				else {
					$('#' + tabs[k]).addClass('rpbchessboard-invisible');
					$('#' + tabs[k] + '-button > a').removeClass('current');
				}
			}
		}

		// Set-up the tab layout (sub-level)
		function tabLayoutSetUp($, tabContainerID, defaultIndex)
		{
			var clickHandler = function(index)
			{
				$('#' + tabContainerID + ' > div').addClass('rpbchessboard-invisible')
					.eq(index).removeClass('rpbchessboard-invisible');
				$('#' + tabContainerID + ' > ul > li').removeClass('rpbchessboard-admin-selected')
					.eq(index).addClass('rpbchessboard-admin-selected');
			};
			$('#' + tabContainerID + ' > ul > li').each(function(index, e)
			{
				$(e).click(function() { clickHandler(index); });
			});
			clickHandler(defaultIndex);
		}

		// Initialization
		jQuery(document).ready(function($)
		{
			// Main tabs
			showTab($, 'rpbchessboard-admin-help-fen');

			// Tab layouts
			tabLayoutSetUp($, 'rpbchessboard-admin-fenExamples'  , 1);
			tabLayoutSetUp($, 'rpbchessboard-admin-fenAttributes', 0);
			tabLayoutSetUp($, 'rpbchessboard-admin-pgnExamples'  , 0);
		});

	</script>

</div>
