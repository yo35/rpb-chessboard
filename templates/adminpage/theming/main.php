<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div id="rpbchessboard-themingPage" class="rpbchessboard-columns">

	<div class="rpbchessboard-stretchable">
		<?php RPBChessboardHelperLoader::printTemplate($model->getSubPageTemplateName(), $model); ?>
	</div>

	<div>
		<div id="rpbchessboard-themingPreviewWidget"></div>
	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#rpbchessboard-themingPreviewWidget').chessboard({
			position       : 'start',
			squareSize     : 48     ,
			showCoordinates: false  ,
			colorset       : <?php echo json_encode($model->getDefaultColorset()); ?>,
			pieceset       : <?php echo json_encode($model->getDefaultPieceset()); ?>
		});
	});
</script>
