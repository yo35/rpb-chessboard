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



		<div id="rpbchessboard-admin-fenExamples">

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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-left">
						<p>
							<?php _e('An empty position.', 'rpbchessboard'); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-right">
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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-left">
						<p>
							<?php _e('The initial position.', 'rpbchessboard'); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-right">
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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-left">
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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-right">
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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-left">
						<p>
							<?php echo sprintf(
								__(
									'Who can castle? And where? Here, the 3<sup>rd</sup> field in the FEN string (%1$s) '.
									'indicates that only king-side white castling is available. Other castling availabilities '.
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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-right">
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
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-left">
						<p>
							<?php _e('The Légal Trap.', 'rpbchessboard'); ?>
						</p>
					</div>
					<div class="rpbchessboard-admin-column rpbchessboard-admin-column-right">
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

	</div>





	<div id="rpbchessboard-admin-help-pgn">
		TODO: help PGN
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

		// Function to display a tab (sub-level)
		function showSubTab($, tabIndex)
		{
			$('#rpbchessboard-admin-fenExamples > div').addClass('rpbchessboard-invisible')
				.eq(tabIndex).removeClass('rpbchessboard-invisible');
			$('#rpbchessboard-admin-fenExamples > ul > li').removeClass('rpbchessboard-admin-selected')
				.eq(tabIndex).addClass('rpbchessboard-admin-selected');
		}

		// Initialization
		jQuery(document).ready(function($)
		{
			// Main tabs
			showTab($, 'rpbchessboard-admin-help-fen');

			// Tabs for FEN examples
			$('#rpbchessboard-admin-fenExamples > ul > li').each(function(index, e)
			{
				$(e).click(function() { showSubTab($, index); });
			});
			showSubTab($, 1);
		});

	</script>

</div>
