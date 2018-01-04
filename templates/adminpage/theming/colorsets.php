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

<p>
	<button id="rpbchessboard-addSetCodeButton" class="button">
		<?php _e( 'Add a new colorset', 'rpb-chessboard' ); ?>
	</button>
</p>

<table id="rpbchessboard-setCodeList" class="wp-list-table widefat striped">

	<thead>
		<tr>
			<th scope="col"><?php _e( 'Name', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php _e( 'Slug', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php _e( 'Default', 'rpb-chessboard' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<tr>
			<?php RPBChessboardHelperLoader::printTemplate( 'AdminPage/Theming/ColorsetEdition', $model, array( 'isNew' => true ) ); ?>
		</tr>

		<?php foreach ( $model->getAvailableColorsets() as $colorset ) : ?>
			<tr data-slug="<?php echo htmlspecialchars( $colorset ); ?>">

				<td class="has-row-actions">
					<strong class="row-title"><?php echo htmlspecialchars( $model->getColorsetLabel( $colorset ) ); ?></strong>
					<span class="row-actions rpbchessboard-inlinedRowActions">
						<?php if ( $model->isBuiltinColorset( $colorset ) ) : ?>
							<span><a href="#" class="rpbchessboard-action-setDefault"><?php _e( 'Set default', 'rpb-chessboard' ); ?></a></span>
						<?php else : ?>
							<span><a href="#" class="rpbchessboard-action-setDefault"><?php _e( 'Set default', 'rpb-chessboard' ); ?></a> |</span>
							<span><a href="#" class="rpbchessboard-action-edit"><?php _e( 'Edit', 'rpb-chessboard' ); ?></a> |</span>
							<span><a href="#" class="rpbchessboard-action-delete"><?php _e( 'Delete', 'rpb-chessboard' ); ?></a></span>
						<?php endif; ?>
					</span>
				</td>

				<td><?php echo htmlspecialchars( $colorset ); ?></td>

				<td>
					<?php if ( $model->isDefaultColorset( $colorset ) ) : ?>
						<div class="rpbchessboard-tickIcon"></div>
					<?php endif; ?>
				</td>

				<?php if ( ! $model->isBuiltinColorset( $colorset ) ) : ?>
					<?php
					RPBChessboardHelperLoader::printTemplate(
						'AdminPage/Theming/ColorsetEdition', $model, array(
							'isNew'    => false,
							'colorset' => $colorset,
						)
					);
?>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<th scope="col"><?php _e( 'Name', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php _e( 'Slug', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php _e( 'Default', 'rpb-chessboard' ); ?></th>
		</tr>
	</tfoot>

</table>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		RPBChessboard.initializeSetCodeEditor = function(target) {

			// Initialize the color picker widgets
			$('.rpbchessboard-darkSquareColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-darkSquareColorSelector', target),
				change: function(event, ui) {
					$('#rpbchessboard-themingPreviewWidget .rpbui-chessboard-darkSquare').css('background-color', ui.color.toString());
				}
			});
			$('.rpbchessboard-lightSquareColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-lightSquareColorSelector', target),
				change: function(event, ui) {
					$('#rpbchessboard-themingPreviewWidget .rpbui-chessboard-lightSquare').css('background-color', ui.color.toString());
				}
			});

			// Initialize the colors of the preview chessboard
			var darkSquareColor = $('.rpbchessboard-darkSquareColorField', target).iris('color');
			var lightSquareColor = $('.rpbchessboard-lightSquareColorField', target).iris('color');
			$('#rpbchessboard-themingPreviewWidget .rpbui-chessboard-darkSquare').css('background-color', darkSquareColor);
			$('#rpbchessboard-themingPreviewWidget .rpbui-chessboard-lightSquare').css('background-color', lightSquareColor);
		}

	});
</script>
