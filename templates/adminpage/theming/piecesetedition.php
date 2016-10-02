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

		<input type="hidden" name="imageId-bp" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bp')); ?>" />
		<input type="hidden" name="imageId-bn" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bn')); ?>" />
		<input type="hidden" name="imageId-bb" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bb')); ?>" />
		<input type="hidden" name="imageId-br" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'br')); ?>" />
		<input type="hidden" name="imageId-bq" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bq')); ?>" />
		<input type="hidden" name="imageId-bk" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bk')); ?>" />
		<input type="hidden" name="imageId-bx" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'bx')); ?>" />
		<input type="hidden" name="imageId-wp" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wp')); ?>" />
		<input type="hidden" name="imageId-wn" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wn')); ?>" />
		<input type="hidden" name="imageId-wb" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wb')); ?>" />
		<input type="hidden" name="imageId-wr" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wr')); ?>" />
		<input type="hidden" name="imageId-wq" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wq')); ?>" />
		<input type="hidden" name="imageId-wk" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wk')); ?>" />
		<input type="hidden" name="imageId-wx" value="<?php echo $isNew ? -1 : htmlspecialchars($model->getCustomPiecesetImageId($pieceset, 'wx')); ?>" />

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

		<?php
			$TITLES = array(
				'bp' =>  __('Select the image to use for black pawns'     , 'rpbchessboard'),
				'bn' =>  __('Select the image to use for black knights'   , 'rpbchessboard'),
				'bb' =>  __('Select the image to use for black bishops'   , 'rpbchessboard'),
				'br' =>  __('Select the image to use for black rooks'     , 'rpbchessboard'),
				'bq' =>  __('Select the image to use for black queens'    , 'rpbchessboard'),
				'bk' =>  __('Select the image to use for black kings'     , 'rpbchessboard'),
				'bx' =>  __('Select the image to use for black turn flags', 'rpbchessboard'),
				'wp' =>  __('Select the image to use for white pawns'     , 'rpbchessboard'),
				'wn' =>  __('Select the image to use for white knights'   , 'rpbchessboard'),
				'wb' =>  __('Select the image to use for white bishops'   , 'rpbchessboard'),
				'wr' =>  __('Select the image to use for white rooks'     , 'rpbchessboard'),
				'wq' =>  __('Select the image to use for white queens'    , 'rpbchessboard'),
				'wk' =>  __('Select the image to use for white kings'     , 'rpbchessboard'),
				'wx' =>  __('Select the image to use for white turn flags', 'rpbchessboard')
			);
		?>

		<div>
			<?php foreach(array('bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx') as $coloredPiece): ?>
				<a class="rpbchessboard-coloredPieceButton rpbchessboard-coloredPieceButton-<?php echo $coloredPiece; ?>" href="#"
					data-colored-piece="<?php echo $coloredPiece; ?>" title="<?php echo $TITLES[$coloredPiece]; ?>">
					<?php if($isNew || !$model->isCustomPiecesetImageDefined($pieceset, $coloredPiece)): ?>
						<img src="<?php echo RPBCHESSBOARD_URL . 'images/undefined-' . $coloredPiece . '.png'; ?>" />
					<?php else: ?>
						<img src="<?php echo htmlspecialchars($model->getCustomPiecesetRawDataURL($pieceset, $coloredPiece)); ?>" />
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<div>
			<?php foreach(array('wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx') as $coloredPiece): ?>
				<a class="rpbchessboard-coloredPieceButton rpbchessboard-coloredPieceButton-<?php echo $coloredPiece; ?>" href="#"
					data-colored-piece="<?php echo $coloredPiece; ?>" title="<?php echo $TITLES[$coloredPiece]; ?>">
					<?php if($isNew || !$model->isCustomPiecesetImageDefined($pieceset, $coloredPiece)): ?>
						<img src="<?php echo RPBCHESSBOARD_URL . 'images/undefined-' . $coloredPiece . '.png'; ?>" />
					<?php else: ?>
						<img src="<?php echo htmlspecialchars($model->getCustomPiecesetRawDataURL($pieceset, $coloredPiece)); ?>" />
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>

		<p class="submit rpbchessboard-inlineFormButtons">
			<input type="submit" class="button-primary" value="<?php $isNew ? _e('Create pieceset', 'rpbchessboard') : _e('Save changes', 'rpbchessboard'); ?>" />
			<a class="button" href="<?php echo htmlspecialchars($model->getFormActionURL()); ?>"><?php _e('Cancel', 'rpbchessboard'); ?></a>
		</p>

	</form>
</td>
