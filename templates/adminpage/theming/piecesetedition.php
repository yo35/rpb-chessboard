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

<td colspan="3" <?php if($isNew): ?>id="rpbchessboard-setCodeCreator"<?php else: ?>class="rpbchessboard-setCodeEditor"<?php endif; ?>>
	<form class="rpbchessboard-inlineForm" action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars($model->getFormAction($isNew)); ?>" />

		<?php foreach(array('bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx') as $coloredPiece): ?>
			<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-<?php echo $coloredPiece; ?>"
				value="<?php echo htmlspecialchars($model->getCustomPiecesetImageId($pieceset, $coloredPiece)); ?>"
				data-sprite-url="<?php echo htmlspecialchars($model->getCustomPiecesetSpriteURL($pieceset, $coloredPiece)); ?>" />
		<?php endforeach; ?>

		<div class="rpbchessboard-inlineFormTitle">
			<?php $isNew ? _e('New pieceset', 'rpb-chessboard') : _e('Edit pieceset', 'rpb-chessboard'); ?>
		</div>

		<div>
			<label>
				<span><?php _e('Name', 'rpb-chessboard'); ?></span>
				<input type="text" name="label"
					value="<?php echo htmlspecialchars($isNew ? $model->getLabelProposalForNewSetCode() : $model->getCustomPiecesetLabel($pieceset)); ?>" />
			</label>
		</div>

		<?php if($isNew): ?>
			<div>
				<label>
					<span><?php _e('Slug', 'rpb-chessboard'); ?></span>
					<input type="text" name="pieceset" value="" />
				</label>
			</div>
		<?php else: ?>
			<input type="hidden" name="pieceset" value="<?php echo htmlspecialchars($pieceset); ?>" />
		<?php endif; ?>

		<div>
			<?php foreach(array('bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx') as $coloredPiece): ?>
				<a class="rpbchessboard-coloredPieceButton rpbchessboard-coloredPieceButton-<?php echo $coloredPiece; ?>" href="#"
					data-colored-piece="<?php echo $coloredPiece; ?>" title="<?php echo htmlspecialchars($model->getPiecesetEditionButtonTitle($coloredPiece)); ?>">
					<img src="<?php echo htmlspecialchars($model->getCustomPiecesetThumbnailURL($pieceset, $coloredPiece)); ?>" width="64px" height="64px" />
				</a>
			<?php endforeach; ?>
		</div>
		<div>
			<?php foreach(array('wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx') as $coloredPiece): ?>
				<a class="rpbchessboard-coloredPieceButton rpbchessboard-coloredPieceButton-<?php echo $coloredPiece; ?>" href="#"
					data-colored-piece="<?php echo $coloredPiece; ?>" title="<?php echo htmlspecialchars($model->getPiecesetEditionButtonTitle($coloredPiece)); ?>">
					<img src="<?php echo htmlspecialchars($model->getCustomPiecesetThumbnailURL($pieceset, $coloredPiece)); ?>" width="64px" height="64px" />
				</a>
			<?php endforeach; ?>
		</div>

		<p class="rpbchessboard-piecesetEditionErrorMessage"></p>

		<p class="submit rpbchessboard-inlineFormButtons">
			<input type="submit" class="button-primary" value="<?php $isNew ? _e('Create pieceset', 'rpb-chessboard') : _e('Save changes', 'rpb-chessboard'); ?>" />
			<a class="button" href="<?php echo htmlspecialchars($model->getFormActionURL()); ?>"><?php _e('Cancel', 'rpb-chessboard'); ?></a>
		</p>

	</form>
</td>
