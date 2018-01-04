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

<?php RPBChessboardHelperLoader::printTemplate( $model->getSubPageTemplateName(), $model ); ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		$.chessgame.navigationFrameOptions = <?php echo json_encode( $model->getDefaultChessboardSettings() ); ?>;

		$('.rpbchessboard-outline a').click(function(e) {
			e.preventDefault();
			var target = $(this).attr('href');
			$('html').animate({ scrollTop: $(target).offset().top - 50 }, 500);
		});
	});
</script>
