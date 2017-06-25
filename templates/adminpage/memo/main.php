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

<div id="rpbchessboard-memoPage">

	<p>
		<?php
			_e(
				'This short reminder presents through examples the features provided by the RPB Chessboard plugin, '.
				'namely the insertion of chess diagrams and games in WordPress websites. '.
				'On the left is the code written in posts and pages, while the right column shows the corresponding rendering.',
			'rpbchessboard');
		?>
	</p>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$.chessgame.navigationFrameOptions = <?php echo json_encode($model->getDefaultChessboardSettings()); ?>;
		});
	</script>

	<?php
		RPBChessboardHelperLoader::printTemplate('AdminPage/Memo/FEN', $model);
		RPBChessboardHelperLoader::printTemplate('AdminPage/Memo/PGN', $model);
	?>

</div>
