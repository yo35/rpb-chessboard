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

<h3 id="rpbchessboard-pgnComment"><?php esc_html_e( 'Comments', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			1. d4
			{<?php printf( esc_html__( 'I\'m a %1$sshort%2$s comment.', 'rpb-chessboard' ), '&lt;em&gt;', '&lt;/em&gt;' ); ?>}
			1... Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5
			8. a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4<br/>
			<br/>
			{<?php printf( esc_html__( 'I\'m a %1$slong%2$s comment.', 'rpb-chessboard' ), '&lt;em&gt;', '&lt;/em&gt;' ); ?>}<br/>
			<br/>
			13... a5 14. Rac1 O-O 15. Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6
			19. Rcc1 Rfd8 20. Rfd1 Rac8 *<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnComment-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnComment-anchor').chessgame($.extend(<?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							pgn:
								'1. d4 {' +
								<?php echo wp_json_encode( sprintf( __( 'I\'m a %1$sshort%2$s comment.', 'rpb-chessboard' ), '<em>', '</em>' ) ); ?> +
								'} 1... Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 ' +
								'8. a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4\n' +
								'\n' +
								'{' +
								<?php echo wp_json_encode( sprintf( __( 'I\'m a %1$slong%2$s comment.', 'rpb-chessboard' ), '<em>', '</em>' ) ); ?> +
								'}\n' +
								'\n' +
								'13... a5 14. Rac1 O-O 15. Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 *'
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
			'Text comments can be inserted, surrounded with braces. They can be rendered either inlined within the move sequence ("short comment" ' .
			'style), or as separated paragraphs ("long comment" style). To insert a comment as a separated paragraph, let a blank line before it ' .
			'in the PGN string. Also, notice that HTML tags are allowed within comments.',
			'rpb-chessboard'
		);
	?>
</p>
