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

<h3><?php esc_html_e( 'Navigation toolbar', 'rpb-chessboard' ); ?></h3>


<div class="rpbchessboard-columns">
	<div id="rpbchessboard-tuningNavigationToolbarParameterColumn">

		<p>
			<input type="hidden" name="showFlipButton" value="0" />
			<input type="checkbox" id="rpbchessboard-showFlipButtonField" name="showFlipButton" value="1"
				<?php echo $model->getDefaultShowFlipButton() ? 'checked="yes"' : ''; ?>
			/>
			<label for="rpbchessboard-showFlipButtonField"><?php esc_html_e( 'Show flip button', 'rpb-chessboard' ); ?></label>
		</p>

		<p>
			<input type="hidden" name="showDownloadButton" value="0" />
			<input type="checkbox" id="rpbchessboard-showDownloadButtonField" name="showDownloadButton" value="1"
				<?php echo $model->getDefaultShowDownloadButton() ? 'checked="yes"' : ''; ?>
			/>
			<label for="rpbchessboard-showDownloadButtonField"><?php esc_html_e( 'Show download button', 'rpb-chessboard' ); ?></label>
		</p>

	</div>
	<div>

		<div id="rpbchessboard-navigationToolbarPreview">
			<img id="rpbchessboard-navigationToolbar-n"   src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/navigation-toolbar-n.png' ); ?>" />
			<img id="rpbchessboard-navigationToolbar-nf"  src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/navigation-toolbar-nf.png' ); ?>" />
			<img id="rpbchessboard-navigationToolbar-nd"  src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/navigation-toolbar-nd.png' ); ?>" />
			<img id="rpbchessboard-navigationToolbar-nfd" src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/navigation-toolbar-nfd.png' ); ?>" />
		</div>

	</div>
</div>


<p class="description">
	<?php esc_html_e( 'These settings allow to customize the toolbar that is displayed below the navigation board.', 'rpb-chessboard' ); ?>
</p>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		function refreshPreview() {

			// ID of the preview image
			var id = '#rpbchessboard-navigationToolbar-n';
			if($('#rpbchessboard-showFlipButtonField').prop('checked')) id += 'f';
			if($('#rpbchessboard-showDownloadButtonField').prop('checked')) id += 'd';

			$('#rpbchessboard-navigationToolbarPreview img').hide();
			$('#rpbchessboard-navigationToolbarPreview ' + id).show();
		}

		$('#rpbchessboard-showFlipButtonField').change(refreshPreview);
		$('#rpbchessboard-showDownloadButtonField').change(refreshPreview);
		refreshPreview();
	});
</script>
