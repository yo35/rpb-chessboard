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

<p class="rpbchessboard-spacerBefore"></p>

<div id="<?php echo esc_attr( $model->getUniqueID() ); ?>" class="rpbchessboard-chessdb">
	<noscript>
		<div class="rpbchessboard-javascriptWarning">
			<?php esc_html_e( 'You must activate JavaScript to allow chess database visualization.', 'rpb-chessboard' ); ?>
		</div>
	</noscript>
	<div class="rpbchessboard-chessdbAnchor"></div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			<?php if ( $model->getNoConflictForButton() ) : ?>
			if(typeof $.fn.button.noConflict === 'function') { $.fn.button.noConflict(); }
			<?php endif; ?>
			$.chessdb.selectMenuClass          = 'rpbchessboard-jQuery-enableSmoothness';
			$.chessgame.navigationButtonClass  = 'rpbchessboard-jQuery-enableSmoothness';
			$.chessgame.navigationFrameClass   = 'wp-dialog';
			$.chessgame.navigationFrameOptions = <?php echo wp_json_encode( $model->getDefaultChessboardSettings() ); ?>;
			var selector = '#' + <?php echo wp_json_encode( $model->getUniqueID() ); ?> + ' .rpbchessboard-chessdbAnchor';
			$(selector).removeClass('rpbchessboard-chessdbAnchor').chessdb(<?php echo wp_json_encode( $model->getWidgetArgs() ); ?>);
		});
	</script>
</div>

<p class="rpbchessboard-spacerAfter"></p>
