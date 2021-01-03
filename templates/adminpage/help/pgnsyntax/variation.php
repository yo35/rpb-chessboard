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

<h3 id="rpbchessboard-pgnVariation"><?php esc_html_e( 'Variations', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			1. e4 e5 (1... c5) (1... e6) 2. Nf3 Nc6 3. Bb5<br/>
			<br/>
			(3. Bc4 Bc5 (3... Be7) 4.d4)<br/>
			<br/>
			(3. d4 exd4 4. Nxd4 Bc5 5. Be3 Qf6)<br/>
			<br/>
			3... a6 4. Ba4 *<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnVariation-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnVariation-anchor').chessgame($.extend(<?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							pgn:
								'1. e4 e5 (1... c5) (1... e6) 2. Nf3 Nc6 3. Bb5\n' +
								'\n' +
								'(3. Bc4 Bc5 (3... Be7) 4.d4)\n' +
								'\n' +
								'(3. d4 exd4 4. Nxd4 Bc5 5. Be3 Qf6)\n' +
								'\n' +
								'3... a6 4. Ba4 *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>

<p>
	<?php
		esc_html_e(
			'As for comments, variations can be rendered either inlined within the move sequence, or as separated paragraphs if they are preceded ' .
			'by a blank line in the PGN string. Variations can be nested. However, inlined variations cannot contain "paragraph-style" variations ' .
			'(or "paragraph-style" comments).',
			'rpb-chessboard'
		);
	?>
</p>
