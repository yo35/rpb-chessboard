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

<h3><?php _e('Navigation toolbar', 'rpbchessboard'); ?></h3>


<div class="rpbchessboard-columns">
	<div id="rpbchessboard-tuningNavigationToolbarParameterColumn">

		<p>
			<input type="hidden" name="showFlipButton" value="0" />
			<input type="checkbox" id="rpbchessboard-showFlipButtonField" name="showFlipButton" value="1"
				<?php if($model->getDefaultShowFlipButton()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-showFlipButtonField"><?php _e('Show flip button', 'rpbchessboard'); ?></label>
		</p>

		<p>
			<input type="hidden" name="showDownloadButton" value="0" />
			<input type="checkbox" id="rpbchessboard-showDownloadButtonField" name="showDownloadButton" value="1"
				<?php if($model->getDefaultShowDownloadButton()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-showDownloadButtonField"><?php _e('Show download button', 'rpbchessboard'); ?></label>
		</p>

	</div>
	<div>

		TODO: preview

	</div>
</div>


<p class="description">
	<?php _e('These settings allow to customize the toolbar that is displayed below the navigation board.', 'rpbchessboard'); ?>
</p>
