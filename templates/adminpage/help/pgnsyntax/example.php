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

<h3 id="rpbchessboard-pgnExample"><?php _e('Standard PGN game', 'rpb-chessboard'); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
			<br/>
			[Event &quot;Linares 16&lt;sup&gt;th&lt;/sup&gt;&quot;]<br/>
			[Site &quot;Linares, ESP&quot;]<br/>
			[Date &quot;1999.02.25&quot;]<br/>
			[Round &quot;4&quot;]<br/>
			[White &quot;Kasparov, Garry&quot;]<br/>
			[Black &quot;Kramnik, Vladimir&quot;]<br/>
			[Result &quot;1/2-1/2&quot;]<br/>
			[WhiteElo &quot;2812&quot;]<br/>
			[BlackElo &quot;2751&quot;]<br/>
			[WhiteTitle &quot;WCH&quot;]<br/>
			[BlackTitle &quot;GM&quot;]<br/>
			[Annotator &quot;?&quot;]<br/>
			<br/>
			1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8.
			a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15.
			Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2<br/>
			<br/>
			[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
		</div>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnExample-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnExample-anchor').chessgame($.extend(<?php echo json_encode($model->getDefaultChessgameSettings()); ?>, {
							navigationBoard: 'none',
							pgn:
								'[Event "Linares 16<sup>th</sup>"]\n' +
								'[Site "Linares, ESP"]\n' +
								'[Date "1999.02.25"]\n' +
								'[Round "4"]\n' +
								'[White "Kasparov, Garry"]\n' +
								'[Black "Kramnik, Vladimir"]\n' +
								'[Result "1/2-1/2"]\n' +
								'[WhiteElo "2812"]\n' +
								'[BlackElo "2751"]\n' +
								'[WhiteTitle "WCH"]\n' +
								'[BlackTitle "GM"]\n' +
								'[Annotator "?"]\n' +
								'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
								'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
								'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
