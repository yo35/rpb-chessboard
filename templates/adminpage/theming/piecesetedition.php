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

<td colspan="3" <?php if($isNew): ?>id="rpbchessboard-setCodeCreator"<?php else: ?>class="rpbchessboard-setCodeEditor"<?php endif; ?>>
	<form class="rpbchessboard-inlineForm" action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars($model->getFormAction($isNew)); ?>" />

		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-bp" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bp')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-bn" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bn')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-bb" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bb')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-br" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'br')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-bq" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bq')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-bk" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bk')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-bx" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bx')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wp" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wp')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wn" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wn')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wb" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wb')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wr" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wr')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wq" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wq')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wk" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wk')); ?>" />
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-wx" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wx')); ?>" />

		<div class="rpbchessboard-inlineFormTitle">
			<?php $isNew ? _e('New pieceset', 'rpbchessboard') : _e('Edit pieceset', 'rpbchessboard'); ?>
		</div>

		<div>
			<label>
				<span><?php _e('Name', 'rpbchessboard'); ?></span>
				<input type="text" name="label"
					value="<?php echo htmlspecialchars($isNew ? $model->getLabelProposalForNewSetCode() : $model->getCustomPiecesetLabel($pieceset)); ?>" />
			</label>
		</div>

		<?php if($isNew): ?>
			<div>
				<label>
					<span><?php _e('Slug', 'rpbchessboard'); ?></span>
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
					<?php if($isNew || !$model->isCustomPiecesetImageDefined($pieceset, $coloredPiece)): ?>
						<img src="<?php echo RPBCHESSBOARD_URL . 'images/undefined-' . $coloredPiece . '.png'; ?>" />
					<?php else: ?>
						<img src="<?php echo htmlspecialchars($model->getCustomPiecesetThumbnailURL($pieceset, $coloredPiece)); ?>" width="64px" height="64px" />
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<div>
			<?php foreach(array('wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx') as $coloredPiece): ?>
				<a class="rpbchessboard-coloredPieceButton rpbchessboard-coloredPieceButton-<?php echo $coloredPiece; ?>" href="#"
					data-colored-piece="<?php echo $coloredPiece; ?>" title="<?php echo htmlspecialchars($model->getPiecesetEditionButtonTitle($coloredPiece)); ?>">
					<?php if($isNew || !$model->isCustomPiecesetImageDefined($pieceset, $coloredPiece)): ?>
						<img src="<?php echo RPBCHESSBOARD_URL . 'images/undefined-' . $coloredPiece . '.png'; ?>" />
					<?php else: ?>
						<img src="<?php echo htmlspecialchars($model->getCustomPiecesetThumbnailURL($pieceset, $coloredPiece)); ?>" width="64px" height="64px" />
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>

		<p class="rpbchessboard-piecesetEditionErrorMessage"></p>

		<p class="submit rpbchessboard-inlineFormButtons">
			<input type="submit" class="button-primary" value="<?php $isNew ? _e('Create pieceset', 'rpbchessboard') : _e('Save changes', 'rpbchessboard'); ?>" />
			<a class="button" href="<?php echo htmlspecialchars($model->getFormActionURL()); ?>"><?php _e('Cancel', 'rpbchessboard'); ?></a>
		</p>

	</form>
</td>
