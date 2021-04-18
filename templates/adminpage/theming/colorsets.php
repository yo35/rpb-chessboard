<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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
		<?php esc_html_e( 'Add a new colorset', 'rpb-chessboard' ); ?>
	</button>
</p>

<table id="rpbchessboard-setCodeList" class="wp-list-table widefat striped">

	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'Name', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Slug', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Default', 'rpb-chessboard' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<tr>
			<?php RPBChessboardHelperLoader::printTemplate( 'AdminPage/Theming/ColorsetEdition', $model, array( 'isNew' => true ) ); ?>
		</tr>

		<?php foreach ( $model->getAvailableColorsets() as $colorset ) : ?>
		<tr data-slug="<?php echo esc_attr( $colorset ); ?>">

			<td class="has-row-actions">
				<strong class="row-title"><?php echo esc_html( $model->getColorsetLabel( $colorset ) ); ?></strong>
				<span class="row-actions rpbchessboard-inlinedRowActions">
					<?php if ( $model->isBuiltinColorset( $colorset ) ) : ?>
					<span><a href="#" class="rpbchessboard-action-setDefault"><?php esc_html_e( 'Set default', 'rpb-chessboard' ); ?></a></span>
					<?php else : ?>
					<span><a href="#" class="rpbchessboard-action-setDefault"><?php esc_html_e( 'Set default', 'rpb-chessboard' ); ?></a> |</span>
					<span><a href="#" class="rpbchessboard-action-edit"><?php esc_html_e( 'Edit', 'rpb-chessboard' ); ?></a> |</span>
					<span><a href="#" class="rpbchessboard-action-delete"><?php esc_html_e( 'Delete', 'rpb-chessboard' ); ?></a></span>
					<?php endif; ?>
				</span>
			</td>

			<td><?php echo esc_html( $colorset ); ?></td>

			<td>
				<?php if ( $model->isDefaultColorset( $colorset ) ) : ?>
				<div class="rpbchessboard-tickIcon"></div>
				<?php endif; ?>
			</td>

			<?php
			if ( ! $model->isBuiltinColorset( $colorset ) ) {
				RPBChessboardHelperLoader::printTemplate(
					'AdminPage/Theming/ColorsetEdition',
					$model,
					array(
						'isNew'    => false,
						'colorset' => $colorset,
					)
				);
			}
			?>

		</tr>
		<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<th scope="col"><?php esc_html_e( 'Name', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Slug', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Default', 'rpb-chessboard' ); ?></th>
		</tr>
	</tfoot>

</table>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		RPBChessboard.initializeSetCodeEditor = function(target, refreshCallback) {

			function irisCallback(colorsetField) {
				return function(event, ui) {
					RPBChessboard.editColorset[colorsetField] = ui.color.toString();
					refreshCallback();
				};
			}

			// Initialize the color picker widgets
			$('.rpbchessboard-darkSquareColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-darkSquareColorSelector', target),
				change: irisCallback('b')
			});
			$('.rpbchessboard-lightSquareColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-lightSquareColorSelector', target),
				change: irisCallback('w')
			});
			$('.rpbchessboard-highlightColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-highlightColorSelector', target),
				change: irisCallback('highlight')
			});
			$('.rpbchessboard-greenMarkerColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-greenMarkerColorSelector', target),
				change: irisCallback('g')
			});
			$('.rpbchessboard-redMarkerColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-redMarkerColorSelector', target),
				change: irisCallback('r')
			});
			$('.rpbchessboard-yellowMarkerColorField', target).iris({
				hide: false,
				target: $('.rpbchessboard-yellowMarkerColorSelector', target),
				change: irisCallback('y')
			});

			// Initialize the preview chessboard
			RPBChessboard.editColorset.b = $('.rpbchessboard-darkSquareColorField', target).iris('color');
			RPBChessboard.editColorset.w = $('.rpbchessboard-lightSquareColorField', target).iris('color');
			RPBChessboard.editColorset.highlight = $('.rpbchessboard-highlightColorField', target).iris('color');
			RPBChessboard.editColorset.g = $('.rpbchessboard-greenMarkerColorField', target).iris('color');
			RPBChessboard.editColorset.r = $('.rpbchessboard-redMarkerColorField', target).iris('color');
			RPBChessboard.editColorset.y = $('.rpbchessboard-yellowMarkerColorField', target).iris('color');
			refreshCallback();
		}

	});
</script>
