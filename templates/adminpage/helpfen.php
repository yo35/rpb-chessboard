<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div id="rpbchessboard-helpFENPage">

	<h3>
		<?php echo sprintf(
			__('Chess diagrams with the %1$s[%3$s][/%3$s]%2$s tags', 'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getFENShortcode())
		); ?>
	</h3>





	<h4><?php _e('FEN format', 'rpbchessboard'); ?></h4>

	<p>
		<?php echo sprintf(
			__(
				'The string between the %1$s[%3$s][/%3$s]%2$s tags describe the position. '.
				'The used notation follows the %4$sFEN format%5$s (Forsyth-Edwards Notation), '.
				'which is comprehensively described on %4$sWikipedia%5$s. '.
				'The FEN syntax is summarized here through a few representative examples.',
			'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getFENShortcode()),
			sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpbchessboard')),
			'</a>'
		); ?>
	</p>





	<div id="rpbchessboard-fenExamples" class="rpbchessboard-tabs">

		<ul>
			<li><a href="#rpbchessboard-fenExample1"><?php _e('Empty position'                , 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-fenExample2"><?php _e('Initial position'              , 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-fenExample3"><?php _e('Who can castle?'               , 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-fenExample4"><?php _e('<em>En passant</em> available?', 'rpbchessboard'); ?></a></li>
		</ul>


		<div id="rpbchessboard-fenExample1">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s]8/8/8/8/8/8/8/8 w - - 0 1[/%1$s]',
							htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<p><?php _e('An empty position.', 'rpbchessboard'); ?></p>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-fenExample1-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-fenExample1-anchor').chessboard({
										squareSize: 28,
										position: '8/8/8/8/8/8/8/8 w - - 0 1'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="rpbchessboard-fenExample2">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s]rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1[/%1$s]',
							htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<p><?php _e('The initial position.', 'rpbchessboard'); ?></p>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-fenExample2-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-fenExample2-anchor').chessboard({
										squareSize: 28,
										position: 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="rpbchessboard-fenExample3">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s]r3k2r/8/8/8/8/8/8/R3K2R b <strong>K</strong> - 0 1[/%1$s]',
							htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<p><?php _e('Who can castle? And where?', 'rpbchessboard'); ?></p>
					<p><?php echo sprintf(
						__(
							'Here, the 3<sup>rd</sup> field in the FEN string (%1$s) indicates '.
							'that only king-side white castling is available. Other castling availabilities '.
							'might be indicated with characters %2$s (queen-side white castling), '.
							'%3$s (king-side black castling), and %4$s (queen-side black castling). '.
							'If neither side can castle, the 3<sup>rd</sup> FEN field is set to %5$s.',
						'rpbchessboard'),
						'<span class="rpbchessboard-sourceCode">&quot;K&quot;</span>',
						'<span class="rpbchessboard-sourceCode">&quot;Q&quot;</span>',
						'<span class="rpbchessboard-sourceCode">&quot;k&quot;</span>',
						'<span class="rpbchessboard-sourceCode">&quot;q&quot;</span>',
						'<span class="rpbchessboard-sourceCode">&quot;-&quot;</span>'
					); ?></p>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-fenExample3-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-fenExample3-anchor').chessboard({
										squareSize: 28,
										position: 'r3k2r/8/8/8/8/8/8/R3K2R b K - 0 1'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="rpbchessboard-fenExample4">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s]4k3/pppppppp/8/8/4P3/8/PPPP1PPP/4K3 b - <strong>e3</strong> 0 1[/%1$s]',
							htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<p><?php _e('Is <em>en passant</em> possible? On which square?', 'rpbchessboard'); ?></p>
					<p><?php echo sprintf(
						__(
							'In this example, White has just played a pawn from e2 to e4: ' .
							'the 4<sup>th</sup> field in the FEN string is then set to %1$s ' .
							'to account for the fact that <em>en passant</em> would be possible on this square ' .
							'if there were a black pawn either in d4 or f4. ' .
							'Otherwise, this field would be set to %2$s.',
						'rpbchessboard'),
						'<span class="rpbchessboard-sourceCode">&quot;e3&quot;</span>',
						'<span class="rpbchessboard-sourceCode">&quot;-&quot;</span>'
					); ?></p>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-fenExample4-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-fenExample4-anchor').chessboard({
										squareSize: 28,
										position: '4k3/pppppppp/8/8/4P3/8/PPPP1PPP/4K3 b - e3 0 1'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<script type="text/javascript">
			jQuery(document).ready(function($) { $('#rpbchessboard-fenExamples').tabs(); });
		</script>

	</div>





	<h4 id="rpbchessboard-helpOnFENAttributes"><?php _e('Attributes', 'rpbchessboard'); ?></h4>

	<p><?php
		_e('The aspect of the chess diagrams can be customized thanks to the following attributes.',
			'rpbchessboard');
	?></p>





	<div id="rpbchessboard-fenAttributes" class="rpbchessboard-tabs">

		<ul>
			<li><a href="#rpbchessboard-fenAttribute1"><?php _e('Orientation', 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-fenAttribute2"><?php _e('Square size', 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-fenAttribute3"><?php _e('Coordinates', 'rpbchessboard'); ?></a></li>
		</ul>


		<div id="rpbchessboard-fenAttribute1">
			<p><?php echo sprintf(
				__(
					'The %1$s attribute controls whether the chessboard is rotated or not.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">flip</span>'
			); ?></p>
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s <strong>flip=true</strong>] ... [/%1$s]', htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-helpOnFlip-example1"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-helpOnFlip-example1').chessboard({ squareSize: 28, flip: true, position: 'start' });
								});
							</script>
						</div>
					</div>
				</div>
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s <strong>flip=false</strong>] ... [/%1$s]', htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-helpOnFlip-example2"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-helpOnFlip-example2').chessboard({ squareSize: 28, flip: false, position: 'start' });
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="rpbchessboard-fenAttribute2">
			<p><?php echo sprintf(
				__(
					'The %1$s attribute controls the size (in pixels) of the chessboard squares.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">square_size</span>'
			); ?></p>
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s <strong>square_size=20</strong>] ... [/%1$s]', htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-helpOnSquareSize-example1"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-helpOnSquareSize-example1').chessboard({ squareSize: 20, position: 'start' });
								});
							</script>
						</div>
					</div>
				</div>
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s <strong>square_size=50</strong>] ... [/%1$s]', htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-helpOnSquareSize-example2"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-helpOnSquareSize-example2').chessboard({ squareSize: 50, position: 'start' });
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="rpbchessboard-fenAttribute3">
			<p><?php echo sprintf(
				__(
					'The %1$s attribute controls whether the row and column coordinates are displayed or not.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">show_coordinates</span>'
			); ?></p>
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s <strong>show_coordinates=true</strong>] ... [/%1$s]', htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<div class="rpbchessboard-visuBlock">
						<div id="rpbchessboard-helpOnShowCoordinates-example1"></div>
						<script type="text/javascript">
							jQuery(document).ready(function($) {
								$('#rpbchessboard-helpOnShowCoordinates-example1').chessboard({ squareSize: 28, showCoordinates: true, position: 'start' });
							});
						</script>
					</div>
				</div>
				<div>
					<div class="rpbchessboard-sourceCode">
						<?php echo sprintf('[%1$s <strong>show_coordinates=false</strong>] ... [/%1$s]', htmlspecialchars($model->getFENShortcode())); ?>
					</div>
					<div class="rpbchessboard-visuBlock">
						<div id="rpbchessboard-helpOnShowCoordinates-example2"></div>
						<script type="text/javascript">
							jQuery(document).ready(function($) {
								$('#rpbchessboard-helpOnShowCoordinates-example2').chessboard({ squareSize: 28, showCoordinates: false, position: 'start' });
							});
						</script>
					</div>
				</div>
			</div>
		</div>


		<script type="text/javascript">
			jQuery(document).ready(function($) { $('#rpbchessboard-fenAttributes').tabs(); });
		</script>

	</div>

</div>
