<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>            *
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

<?php
	require_once(RPBCHESSBOARD_ABSPATH.'helpers/json.php');
?>

<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-in" class="rpbchessboard-in"><?php
	echo htmlspecialchars($model->getContent());
?></div>

<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-out" class="rpbchessboard-out rpbchessboard-invisible">
	<div class="rpbchessboard-game-head">
		<div class="PgnWidget-field-fullNameWhite">
			<span class="rpbchessboard-white-square"></span>
			<span class="PgnWidget-anchor-fullNameWhite"></span>
		</div>
		<div class="PgnWidget-field-fullNameBlack">
			<span class="rpbchessboard-black-square"></span>
			<span class="PgnWidget-anchor-fullNameBlack"></span>
		</div>
		<div class="PgnWidget-field-Event">
			<span class="PgnWidget-anchor-Event"></span>
			<span class="PgnWidget-field-Round">(<span class="PgnWidget-anchor-Round"></span>)</span>
			<span class="PgnWidget-field-Date">- <span class="PgnWidget-anchor-Date"></span></span>
		</div>
		<div class="PgnWidget-field-Annotator">
			<?php echo sprintf(__('Commented by %1$s', 'rpbchessboard'), '<span class="PgnWidget-anchor-Annotator"></span>'); ?>
		</div>
	</div>
	<div class="rpbchessboard-game-body PgnWidget-field-moves">
		<div class="PgnWidget-anchor-moves"></div>
		<div class="PgnWidget-field-Result">
			<span class="PgnWidget-anchor-Result"></span>
		</div>
	</div>
</div>

<script type="text/javascript">
	processPGN(
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-in',
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-out',
		<?php
			echo RPBChessboardHelperJSON::formatChessWidgetAttributes(
				$model->getCustomSquareSize     (),
				$model->getCustomShowCoordinates()
			);
		?>
	);
</script>
