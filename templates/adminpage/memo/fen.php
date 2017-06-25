<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2017  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php _e('FEN diagram', 'rpbchessboard'); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php _e('White to move and mate in two:', 'rpbchessboard'); ?>
			<br/><br/>
			<?php echo sprintf(
				'[%1$s]r2qkbnr/ppp2ppp/2np4/4N3/2B1P3/2N5/PPPP1PPP/R1BbK2R w KQkq - 0 6[/%1$s]',
				htmlspecialchars($model->getFENShortcode())
			); ?>
			<br/><br/>
			<?php _e(
				'This position is known as the Légal Trap. '.
				'It is named after the French player François Antoine de Legall de Kermeur (1702&ndash;1792).'
			, 'rpbchessboard'); ?>
		</div>

		<p>
			<?php echo sprintf(
				__(
					'The string between the %1$s[%3$s][/%3$s]%2$s tags describe the position. '.
					'The used notation follows the %4$sFEN format%5$s (Forsyth-Edwards Notation). '.
					'A comprehensive description of this FEN notation is available on %4$sWikipedia%5$s.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">',
				'</span>',
				htmlspecialchars($model->getFENShortcode()),
				sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpbchessboard')),
				'</a>'
			); ?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<p><?php _e('White to move and mate in two:', 'rpbchessboard'); ?></p>
			<div>
				<div id="rpbchessboard-example1"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-example1').chessboard($.extend(<?php echo json_encode($model->getDefaultChessboardSettings()); ?>, {
							squareSize: 28,
							position: 'r2qkbnr/ppp2ppp/2np4/4N3/2B1P3/2N5/PPPP1PPP/R1BbK2R w KQkq - 0 6'
						}));
					});
				</script>
			</div>
			<p>
				<?php _e(
					'This position is known as the Légal Trap. '.
					'It is named after the French player François Antoine de Legall de Kermeur (1702&ndash;1792).'
				, 'rpbchessboard'); ?>
			</p>
		</div>

	</div>
</div>
