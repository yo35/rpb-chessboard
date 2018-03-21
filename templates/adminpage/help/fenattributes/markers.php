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

<h3 id="rpbchessboard-fenAttributeMarkers"><?php esc_html_e( 'Square and arrow markers', 'rpb-chessboard' ); ?></h3>

<div class="rpbchessboard-columns">
	<div>

		<p>
			<?php
				printf(
					esc_html__(
						'The %1$s and %2$s attributes are used to add respectively square and arrow markers. ' .
						'There is no need to manually edit the value of these attributes as ' .
						'square and arrow marker edition is handled by the %3$schess diagram editor%4$s.',
						'rpb-chessboard'
					),
					'<span class="rpbchessboard-sourceCode">csl</span>',
					'<span class="rpbchessboard-sourceCode">cal</span>',
					sprintf( '<a href="%s">', esc_url( $model->getHelpOnFENSyntaxURL() ) ),
					'</a>'
				);
			?>
		</p>

		<p>
			<?php
				printf(
					esc_html__(
						'For information, the syntax used to define square and arrow markers ' .
						'is described in the %1$sPGN game syntax help page%2$s.',
						'rpb-chessboard'
					),
					sprintf( '<a href="%s">', esc_url( $model->getHelpOnPGNSyntaxURL() ) ),
					'</a>'
				);
			?>
		</p>

	</div>
	<div>

		<div class="rpbchessboard-sourceCode">
			<?php
				printf(
					'[%1$s <strong>csl=Ye5</strong> <strong>cal=Gc6e5,Rf3e5</strong>] ... [/%1$s]',
					esc_html( $model->getFENShortcode() )
				);
			?>
		</div>

		<div class="rpbchessboard-visuBlock">
			<div>
				<div id="rpbchessboard-fenAttributeMarkers-anchor"></div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#rpbchessboard-fenAttributeMarkers-anchor').chessboard($.extend(<?php echo wp_json_encode( $model->getDefaultChessboardSettings() ); ?>, {
							position: 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq - 0 1',
							squareSize: 28,
							squareMarkers: 'Ye5',
							arrowMarkers: 'Gc6e5,Rf3e5'
						}));
					});
				</script>
			</div>
		</div>

	</div>
</div>
