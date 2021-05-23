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

<div id="rpbchessboard-helpFENSyntaxPage" class="rpbchessboard-helpPage">

	<p>
		<?php
			printf(
				esc_html__(
					'The string between the %1$s[%3$s][/%3$s]%2$s tags describe the position. ' .
					'The used notation follows the standard %4$sFEN format%5$s (Forsyth-Edwards Notation), ' .
					'which is comprehensively described on %4$sWikipedia%5$s.',
					'rpb-chessboard'
				),
				'<span class="rpbchessboard-sourceCode">',
				'</span>',
				esc_html( $model->getFENShortcode() ),
				sprintf( '<a href="%s" target="_blank">', esc_url( __( 'https://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpb-chessboard' ) ) ),
				'</a>'
			);
		?>
	</p>

	<p>
		<?php
			printf(
				esc_html__(
					'A custom block editor for chess diagrams is provided in the WordPress post/page editor by RPB Chessboard (see the screenshot below). ' .
					'As a consequence, there is no need to manually edit the content of the %1$s[%3$s][/%3$s]%2$s tags ' .
					'to create a chessboard diagram.',
					'rpb-chessboard'
				),
				'<span class="rpbchessboard-sourceCode">',
				'</span>',
				esc_html( $model->getFENShortcode() )
			);
		?>
	</p>

	<p><img class="rpbchessboard-screenshot" src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/screenshot-fen-block-editor.png' ); ?>" /></p>

</div>
