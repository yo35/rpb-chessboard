<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3 id="rpbchessboard-pgnNAG"><?php esc_html_e( 'NAGs (aka. Numeric Annotation Glyphs)', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<div class="rpbchessboard-sourceCode">
			[<?php echo esc_html( $model->getPGNShortcode() ); ?>]
			1.e4 !! ! !? ?! ? ?? +- +/- +/= = ~ =/+ -/+ -+ *
			[/<?php echo esc_html( $model->getPGNShortcode() ); ?>]
		</div>

	</div>
	<div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-pgnNAG-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-pgnNAG-anchor').chessgame($.extend(<?php echo wp_json_encode( $model->getDefaultChessgameSettings() ); ?>, {
							navigationBoard: 'none',
							pgn: '1.e4 !! ! !? ?! ? ?? +- +/- +/= = ~ =/+ -/+ -+ *'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>

<p>
	<?php
		printf(
			esc_html__(
				'Notice that the chess database softwares may introduce annotations such as %1$s"$x"%2$s where %1$s"x"%2$s is replaced with one or ' .
				'more digits (for instance, %1$s"1.e4 $1"%2$s). This is what is advocated by the PGN norm, which defines equivalences between this ' .
				'syntax and the human-readable one (for instance, %1$s"$1"%2$s is equivalent to %1$s"!"%2$s). Both syntaxes are understood by the ' .
				'RPB Chessboard plugin. See the %3$slist of NAGs%4$s.',
				'rpb-chessboard'
			),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			sprintf( '<a href="%s" target="_blank">', esc_url( __( 'https://en.wikipedia.org/wiki/Numeric_Annotation_Glyphs', 'rpb-chessboard' ) ) ),
			'</a>'
		);
	?>
</p>
