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

<div id="<?php echo htmlspecialchars( $model->getUniqueID() ); ?>" class="rpbchessboard-chessgame">
	<noscript>
		<?php if ( ! $model->isLoadedFromExternalPGNFile() ) : ?>
			<div class="rpbchessboard-noJavascriptBlock"><?php echo htmlspecialchars( $model->getContent() ); ?></div>
		<?php endif; ?>
		<div class="rpbchessboard-javascriptWarning">
			<?php _e( 'You must activate JavaScript to enhance chess game visualization.', 'rpb-chessboard' ); ?>
		</div>
	</noscript>
	<div class="rpbchessboard-chessgameAnchor"></div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			<?php if ( $model->getNoConflictForButton() ) : ?>
				if(typeof $.fn.button.noConflict === 'function') { $.fn.button.noConflict(); }
			<?php endif; ?>
			$.chessgame.navigationButtonClass  = 'rpbchessboard-jQuery-enableSmoothness';
			$.chessgame.navigationFrameClass   = 'wp-dialog';
			$.chessgame.navigationFrameOptions = <?php echo json_encode( $model->getDefaultChessboardSettings() ); ?>;
			var selector = '#' + <?php echo json_encode( $model->getUniqueID() ); ?> + ' .rpbchessboard-chessgameAnchor';
			$(selector).removeClass('rpbchessboard-chessgameAnchor').chessgame(<?php echo json_encode( $model->getWidgetArgs() ); ?>);
		});
	</script>
</div>

<p class="rpbchessboard-spacerAfter"></p>
