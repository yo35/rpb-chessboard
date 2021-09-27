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

<h3 id="rpbchessboard-pgnHorde"><?php esc_html_e( 'Horde chess', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]<br/>
			<br/>
			[Variant "Horde"]<br/>
			<br/>
			{[#]} 1. d5 d6 2. d4 a5 3. d3 axb4 4. axb4 c6 5. b6 Nd7 6. a5 dxc5 7. dxc5 Nxc5<br/>
			8. bxc5 Rxa5 9. b4 Ra6 10. d4 Bxf5 11. gxf5 Qa8 12. dxc6 bxc6 13. d5 cxd5<br/>
			14. exd5 Nf6 15. gxf6 gxf6 16. b5 Ra5 17. b4 Rxa2 18. bxa2 Qxa2 19. b7 Qa7<br/>
			20. c6 e6 21. c5 Bxc5 22. bxc5 O-O 23. fxe6 fxe6 24. b6 Qxa1 25. c7 Qxc3<br/>
			26. b8=Q Qxc5 27. dxe6 Qd6 28. Qb7 Qxe6 29. e4 Qd7 30. Qd5+ Qxd5 31. exd5 1-0<br/>
			<br/>
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

		<p>
			<?php
				printf(
					esc_html__(
						'The %1$s header indicates that the game is a horde chess game.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">[Variant "Horde"]</span>',
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnHorde-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnHorde-anchor').chessgame($.extend(true, <?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'frame',
							diagramOptions: { squareSize: 28 },
							pgn:
								'[Variant "Horde"]\n' +
								'\n' +
								'{[#]} 1. d5 d6 2. d4 a5 3. d3 axb4 4. axb4 c6 5. b6 Nd7 6. a5 dxc5 7. dxc5 Nxc5 8. bxc5 Rxa5 9. b4 Ra6 10. d4 Bxf5 11. gxf5 Qa8 ' +
								'12. dxc6 bxc6 13. d5 cxd5 14. exd5 Nf6 15. gxf6 gxf6 16. b5 Ra5 17. b4 Rxa2 18. bxa2 Qxa2 19. b7 Qa7 20. c6 e6 21. c5 Bxc5 ' +
								'22. bxc5 O-O 23. fxe6 fxe6 24. b6 Qxa1 25. c7 Qxc3 26. b8=Q Qxc5 27. dxe6 Qd6 28. Qb7 Qxe6 29. e4 Qd7 30. Qd5+ Qxd5 31. exd5 1-0'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
