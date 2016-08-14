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

<td colspan="3" <?php if($isNew): ?>id="rpbchessboard-colorsetCreation"<?php else: ?>class="rpbchessboard-colorsetEdition"<?php endif; ?>>
	<form class="rpbchessboard-inlineForm" action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars($model->getFormAction($isNew)); ?>" />

		<div class="rpbchessboard-inlineFormTitle">
			<?php $isNew ? _e('New colorset', 'rpbchessboard') : _e('Edit colorset', 'rpbchessboard'); ?>
		</div>

		<div>
			<label>
				<span><?php _e('Name', 'rpbchessboard'); ?></span>
				<input type="text" name="label"
					value="<?php echo htmlspecialchars($isNew ? $model->getLabelProposalForNewColorset() : $model->getCustomColorsetLabel($colorset)); ?>" />
			</label>
		</div>

		<?php if($isNew): ?>
			<div>
				<label>
					<span><?php _e('Slug', 'rpbchessboard'); ?></span>
					<input type="text" name="colorset" value="" />
				</label>
			</div>
		<?php else: ?>
			<input type="hidden" name="colorset" value="<?php echo htmlspecialchars($colorset); ?>" />
		<?php endif; ?>

		<div class="rpbchessboard-columns">

			<div class="rpbchessboard-stretchable rpbchessboard-colorFieldAndSelector">
				<label>
					<span><?php _e('Dark squares', 'rpbchessboard'); ?></span>
					<input type="text" size="7" maxlength="7" class="rpbchessboard-darkSquareColorField" name="darkSquareColor"
						value="<?php echo htmlspecialchars($isNew ? $model->getRandomDarkSquareColor() : $model->getDarkSquareColor($colorset)); ?>" />
				</label>
				<div>
					<div class="rpbchessboard-darkSquareColorSelector"></div>
				</div>
			</div>

			<div class="rpbchessboard-stretchable rpbchessboard-colorFieldAndSelector">
				<label>
					<span><?php _e('Light squares', 'rpbchessboard'); ?></span>
					<input type="text" size="7" maxlength="7" class="rpbchessboard-lightSquareColorField" name="lightSquareColor"
						value="<?php echo htmlspecialchars($isNew ? $model->getRandomLightSquareColor() : $model->getLightSquareColor($colorset)); ?>" />
				</label>
				<div>
					<div class="rpbchessboard-lightSquareColorSelector"></div>
				</div>
			</div>

		</div>

		<p class="submit rpbchessboard-inlineFormButtons">
			<input type="submit" class="button-primary" value="<?php $isNew ? _e('Create colorset', 'rpbchessboard') : _e('Save changes', 'rpbchessboard'); ?>" />
			<a class="button" href="<?php echo htmlspecialchars($model->getFormActionURL()); ?>"><?php _e('Cancel', 'rpbchessboard'); ?></a>
		</p>

	</form>
</td>
