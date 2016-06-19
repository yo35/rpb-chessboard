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

<table id="rpbchessboard-colorsetList" class="wp-list-table widefat striped">

	<thead>
		<tr>
			<th scope="col"><?php _e('Name', 'rpbchessboard'); ?></th>
			<th scope="col"><?php _e('Slug', 'rpbchessboard'); ?></th>
			<th scope="col"><?php _e('Default', 'rpbchessboard'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach($model->getAvailableColorsets() as $colorset): ?>
			<tr data-colorset="<?php echo htmlspecialchars($colorset); ?>">

				<td class="has-row-actions">
					<strong class="row-title"><?php echo htmlspecialchars($model->getColorsetLabel($colorset)); ?></strong>
					<span class="row-actions rpbchessboard-inlinedRowActions">
						<?php if($model->isBuiltinColorset($colorset)): ?>
							<span><a href="#">Copy</a></span>
						<?php else: ?>
							<span><a href="#" class="rpbchessboard-action-editColorset">Edit</a> |</span>
							<span><a href="#">Copy</a> |</span>
							<span><a href="#">Delete</a></span>
						<?php endif; ?>
					</span>
				</td>

				<td><?php echo htmlspecialchars($colorset); ?></td>

				<td>
					<?php if($model->isDefaultColorset($colorset)): ?>
						<div class="rpbchessboard-tickIcon"></div>
					<?php endif; ?>
				</td>

				<?php if(!$model->isBuiltinColorset($colorset)): ?>
					<td colspan="3" class="rpbchessboard-colorsetEdition">
						<form class="rpbchessboard-inlineForm" action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">
							<div class="rpbchessboard-inlineFormTitle"><?php _e('Edit colorset', 'rpbchessboard'); ?></div>
							<div>
								<label>
									<span><?php _e('Name', 'rpbchessboard'); ?></span>
									<input type="text" name="label" value="<?php echo htmlspecialchars($model->getColorsetLabel($colorset)); ?>" />
								</label>
							</div>
							<div class="rpbchessboard-columns">
								<div class="rpbchessboard-stretchable rpbchessboard-colorFieldAndSelector">
									<label>
										<span><?php _e('Dark squares', 'rpbchessboard'); ?></span>
										<input type="text" class="rpbchessboard-darkColorField" name="darkSquareColor" value="" />
									</label>
									<div>
										<div class="rpbchessboard-darkColorSelector"></div>
									</div>
								</div>
								<div class="rpbchessboard-stretchable rpbchessboard-colorFieldAndSelector">
									<label>
										<span><?php _e('Light squares', 'rpbchessboard'); ?></span>
										<input type="text" class="rpbchessboard-lightColorField" name="lightSquareColor" value=""/>
									</label>
									<div>
										<div class="rpbchessboard-lightColorSelector"></div>
									</div>
								</div>
							</div>
							<p class="submit rpbchessboard-inlineFormButtons">
								<input type="submit" class="button-primary" value="<?php _e('Save changes', 'rpbchessboard'); ?>" />
								<a class="button" href="<?php echo htmlspecialchars($model->getFormActionURL()); ?>"><?php _e('Cancel', 'rpbchessboard'); ?></a>
							</p>
						</form>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<th scope="col"><?php _e('Name', 'rpbchessboard'); ?></th>
			<th scope="col"><?php _e('Slug', 'rpbchessboard'); ?></th>
			<th scope="col"><?php _e('Default', 'rpbchessboard'); ?></th>
		</tr>
	</tfoot>

</table>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		function previewColorset($colorset) {
			$('#rpbchessboard-themingPreviewWidget').chessboard('option', 'colorset', $colorset);
		}

		function previewDefaultColorset() {
			previewColorset(<?php echo json_encode($model->getDefaultColorset()); ?>);
		}

		$('#rpbchessboard-colorsetList tbody tr').mouseleave(previewDefaultColorset).mouseenter(function(e) {
			previewColorset($(e.currentTarget).data('colorset'));
		});

		$('#rpbchessboard-colorsetList tr .rpbchessboard-action-editColorset').click(function(e) {
			e.preventDefault();

			// Display the edition form
			var row = $(e.currentTarget).closest('tr');
			$('td', row).not('.rpbchessboard-colorsetEdition').hide();
			$('td.rpbchessboard-colorsetEdition', row).show();

			// Initialize the color picker widgets
			$('.rpbchessboard-darkColorField', row).iris({
				color: '#ff0000',
				hide: false,
				target: $('.rpbchessboard-darkColorSelector', row)
			});
			$('.rpbchessboard-lightColorField', row).iris({
				color: '#ffff00',
				hide: false,
				target: $('.rpbchessboard-lightColorSelector', row)
			});
		});

	});
</script>
