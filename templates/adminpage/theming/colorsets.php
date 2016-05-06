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
		<?php foreach($model->getAvailableColorsets() as $colorset => $info): ?>
			<tr data-colorset="<?php echo htmlspecialchars($colorset); ?>">
				<td class="has-row-actions">
					<strong class="row-title"><?php echo htmlspecialchars($info->label); ?></strong>
					<span class="row-actions rpbchessboard-inlinedRowActions">
						<span><a href="#">Edit</a> |</span>
						<span><a href="#">Copy</a> |</span>
						<span><a href="#">Delete</a></span>
					</span>
				</td>
				<td><?php echo htmlspecialchars($colorset); ?></td>
				<td>
					<?php if($model->isDefaultColorset($colorset)): ?>
						<div class="rpbchessboard-tickIcon"></div>
					<?php endif; ?>
				</td>
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
			$('#rpbchessboard-themingPreviewWidget').chessboard('option', 'colorset', <?php echo json_encode($model->getDefaultColorset()); ?>);
		}

		$('#rpbchessboard-colorsetList tbody tr').mouseleave(previewDefaultColorset).mouseenter(function(e) {
			previewColorset($(e.currentTarget).data('colorset'));
		});

	});
</script>