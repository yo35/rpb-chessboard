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

<div id="rpbchessboard-admin-memo">

	<p>
		<?php
			_e(
				'This short reminder presents through examples the features provided by the RPB Chessboard plugin, '.
				'namely the insertion of chess diagrams and games in Wordpress websites. '.
				'On the left is the code written in posts and pages; '.
				'the right column shows the corresponding rendering.',
			'rpbchessboard');
		?>
	</p>





	<h3><?php _e('FEN diagram', 'rpbchessboard'); ?></h3>

	<div class="rpbchessboard-admin-columns">

		<div class="rpbchessboard-admin-column-left">
			<div class="rpbchessboard-admin-code-block">
				<?php _e('White to move and mate in two:', 'rpbchessboard'); ?>
				<br/><br/>
				[fen]r2qkbnr/ppp2ppp/2np4/4N3/2B1P3/2N5/PPPP1PPP/R1BbK2R w KQkq - 0 6[/fen]
				<br/><br/>
				<?php _e(
					'This position is known as the Légal Trap. '.
					'It is named after the French player François Antoine de Legall de Kermeur (1702&ndash;1792).'
				, 'rpbchessboard'); ?>
			</div>
			<p>
				<?php echo sprintf(
					__(
						'The string between the %1$s[fen][/fen]%2$s tags describe the position. '.
						'The used notation follows the %3$sFEN format%4$s (Forsyth-Edwards Notation). '.
						'A comprehensive description of this FEN notation is available on %3$sWikipedia%4$s.',
					'rpbchessboard'),
					'<span class="rpbchessboard-admin-code-inline">',
					'</span>',
					sprintf('<a href="%1$s" target="_blank">',
						__('http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpbchessboard')
					),
					'</a>'
				); ?>
			</p>
		</div>

		<div class="rpbchessboard-admin-column-right">
			<div class="rpbchessboard-admin-visu-block">
				<p><?php _e('White to move and mate in two:', 'rpbchessboard'); ?></p>
				<div id="rpbchessboard-admin-example1-in" class="rpbchessboard-in">
					r2qkbnr/ppp2ppp/2np4/4N3/2B1P3/2N5/PPPP1PPP/R1BbK2R w KQkq - 0 6
				</div>
				<div id="rpbchessboard-admin-example1-out" class="rpbchessboard-out rpbchessboard-invisible"></div>
				<script type="text/javascript">
					processFEN('rpbchessboard-admin-example1-in', 'rpbchessboard-admin-example1-out',
						{squareSize: 28});
				</script>
				<p>
					<?php _e(
						'This position is known as the Légal Trap. '.
						'It is named after the French player François Antoine de Legall de Kermeur (1702&ndash;1792).'
					, 'rpbchessboard'); ?>
				</p>
			</div>
		</div>

	</div>





	<h3><?php _e('PGN game', 'rpbchessboard'); ?></h3>

	<div class="rpbchessboard-admin-columns">

		<div class="rpbchessboard-admin-column-left">
			<div class="rpbchessboard-admin-code-block">
				[pgn]
				<br/><br/>
				[Event "1&lt;sup&gt;st&lt;/sup&gt; American Chess Congress"]<br/>
				[Site "New York, NY USA"]<br/>
				[Date "1857.11.03"]<br/>
				[Round "4.6"]<br/>
				[White "Paulsen, Louis"]<br/>
				[Black "Morphy, Paul"]<br/>
				[Result "0-1"]<br/>
				<br/>
				1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 4. Bb5 Bc5 5. O-O O-O 6. Nxe5 Re8
				7. Nxc6 dxc6 8. Bc4 b5 9. Be2 Nxe4 10. Nxe4 Rxe4 11. Bf3 Re6
				12. c3 Qd3 13. b4 Bb6 14. a4 bxa4 15. Qxa4 Bd7 16. Ra2 Rae8
				17. Qa6<br/>
				<br/>
				{[pgndiagram]
				<?php
					_e(
						'Morphy took twelve minutes over his next move, '.
						'probably to assure himself that the combination was sound and '.
						'that he had a forced win in every variation.',
					'rpbchessboard');
				?>}<br/>
				<br/>
				17... Qxf3 $3 18. gxf3 Rg6+ 19. Kh1 Bh3 20. Rd1<br/>
				({<?php _e('Not', 'rpbchessboard'); ?>} 20. Rg1 Rxg1+ 21. Kxg1 Re1+ $19)<br/>
				20... Bg2+ 21. Kg1 Bxf3+ 22. Kf1 Bg2+<br/>
				<br/>
				(22...Rg2 $1 {<?php _e('would have won more quickly. For instance:', 'rpbchessboard'); ?>}
				23. Qd3 Rxf2+ 24. Kg1 Rg2+ 25. Kh1 Rg1#)<br/>
				<br/>
				23. Kg1 Bh3+ 24. Kh1 Bxf2 25. Qf1
				{<?php _e('Absolutely forced.', 'rpbchessboard'); ?>}
				25... Bxf1 26. Rxf1 Re2 27. Ra1 Rh6 28. d4 Be3 0-1
				<br/><br/>
				[/pgn]
			</div>
			<p>
				<?php echo sprintf(
					__(
						'The code between the %1$s[pgn][/pgn]%2$s tags describe the game. '.
						'The used notation follows the standard %3$sPGN format%4$s. '.
						'It can be copy-pasted from a .pgn file generated by any chess database software, '.
						'including %5$sChessbase%6$s, %7$sScid%8$s, etc...',
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
			<p>
				<?php echo sprintf(
					__(
						'Please note the %1$s[pgndiagram]%2$s tag placed inside a commentary '.
						'to insert a diagram showing the current position.',
					'rpbchessboard'),
					'<span class="rpbchessboard-admin-code-inline">',
					'</span>'
				); ?>
			</p>
		</div>

		<div class="rpbchessboard-admin-column-right">
			<div class="rpbchessboard-admin-visu-block">
				<div id="rpbchessboard-admin-example2-in" class="rpbchessboard-in">
					[Event "1&lt;sup&gt;st&lt;/sup&gt; American Chess Congress"]
					[Site "New York, NY USA"]
					[Date "1857.11.03"]
					[Round "4.6"]
					[White "Paulsen, Louis"]
					[Black "Morphy, Paul"]
					[Result "0-1"]

					1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 4. Bb5 Bc5 5. O-O O-O 6. Nxe5 Re8
					7. Nxc6 dxc6 8. Bc4 b5 9. Be2 Nxe4 10. Nxe4 Rxe4 11. Bf3 Re6
					12. c3 Qd3 13. b4 Bb6 14. a4 bxa4 15. Qxa4 Bd7 16. Ra2 Rae8
					17. Qa6

					{
						&lt;span class="PgnWidget-anchor-diagram"&gt;&lt;/span&gt;
						<?php
							_e(
								'Morphy took twelve minutes over his next move, '.
								'probably to assure himself that the combination was sound and '.
								'that he had a forced win in every variation.',
							'rpbchessboard');
						?>
					}

					17... Qxf3 $3 18. gxf3 Rg6+ 19. Kh1 Bh3 20. Rd1
					({<?php _e('Not', 'rpbchessboard'); ?>} 20. Rg1 Rxg1+ 21. Kxg1 Re1+ $19)
					20... Bg2+ 21. Kg1 Bxf3+ 22. Kf1 Bg2+

					(22...Rg2 $1 {<?php _e('would have won more quickly. For instance:', 'rpbchessboard'); ?>}
					23. Qd3 Rxf2+ 24. Kg1 Rg2+ 25. Kh1 Rg1#)

					23. Kg1 Bh3+ 24. Kh1 Bxf2 25. Qf1
					{<?php _e('Absolutely forced.', 'rpbchessboard'); ?>}
					25... Bxf1 26. Rxf1 Re2 27. Ra1 Rh6 28. d4 Be3 0-1
				</div>
				<div id="rpbchessboard-admin-example2-out" class="rpbchessboard-out rpbchessboard-invisible">
					<?php include(RPBCHESSBOARD_ABSPATH.'templates/common/pgncontent.php'); ?>
				</div>
				<script type="text/javascript">
					processPGN('rpbchessboard-admin-example2-in', 'rpbchessboard-admin-example2-out',
						{squareSize: 28});
				</script>
			</div>
		</div>

	</div>

</div>
