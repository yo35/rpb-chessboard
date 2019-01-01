<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2019  Yoann Le Montagner <yo35 -at- melix.net>       *
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
		<?php esc_html_e( 'Add a new pieceset', 'rpb-chessboard' ); ?>
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
			<?php
				RPBChessboardHelperLoader::printTemplate(
					'AdminPage/Theming/PiecesetEdition', $model, array(
						'isNew'    => true,
						'pieceset' => '',
					)
				);
			?>
		</tr>

		<?php foreach ( $model->getAvailablePiecesets() as $pieceset ) : ?>
		<tr data-slug="<?php echo esc_attr( $pieceset ); ?>">

			<td class="has-row-actions">
				<strong class="row-title"><?php echo esc_html( $model->getPiecesetLabel( $pieceset ) ); ?></strong>
				<span class="row-actions rpbchessboard-inlinedRowActions">
					<?php if ( $model->isBuiltinPieceset( $pieceset ) ) : ?>
					<span><a href="#" class="rpbchessboard-action-setDefault"><?php esc_html_e( 'Set default', 'rpb-chessboard' ); ?></a></span>
					<?php else : ?>
					<span><a href="#" class="rpbchessboard-action-setDefault"><?php esc_html_e( 'Set default', 'rpb-chessboard' ); ?></a> |</span>
					<span><a href="#" class="rpbchessboard-action-edit"><?php esc_html_e( 'Edit', 'rpb-chessboard' ); ?></a> |</span>
					<span><a href="#" class="rpbchessboard-action-delete"><?php esc_html_e( 'Delete', 'rpb-chessboard' ); ?></a></span>
					<?php endif; ?>
				</span>
			</td>

			<td><?php echo esc_html( $pieceset ); ?></td>

			<td>
				<?php if ( $model->isDefaultPieceset( $pieceset ) ) : ?>
				<div class="rpbchessboard-tickIcon"></div>
				<?php endif; ?>
			</td>

			<?php
			if ( ! $model->isBuiltinPieceset( $pieceset ) ) {
				RPBChessboardHelperLoader::printTemplate(
					'AdminPage/Theming/PiecesetEdition', $model, array(
						'isNew'    => false,
						'pieceset' => $pieceset,
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

		var mediaFrame = {};

		// Display the media frame (the frame is initialized if necessary).
		function displayMediaFrame(form, button) {

			var coloredPiece = button.data('coloredPiece');

			if(typeof mediaFrame[coloredPiece] === 'undefined') {

				mediaFrame[coloredPiece] = wp.media({
					title: $(button).attr('title'),
					button: {	text: <?php echo wp_json_encode( __( 'Select', 'rpb-chessboard' ) ); ?>	},
					multiple: false
				});

				mediaFrame[coloredPiece].on('select', function() {
					var attachment = mediaFrame[coloredPiece].state().get('selection').first().toJSON();
					onMediaSelected(form, coloredPiece, attachment.id, attachment.url);
				});
			}

			mediaFrame[coloredPiece].open();
		}

		function onMediaSelected(form, coloredPiece, attachmentId, attachmentURL) {

			var image = document.createElement('img');
			image.setAttribute('src', attachmentURL);
			image.setAttribute('width', '64px');
			image.setAttribute('height', '64px');

			$('.rpbchessboard-coloredPieceButton-' + coloredPiece, form).empty().append(image);
			$('input[name="imageId-' + coloredPiece + '"]', form).val(attachmentId);
			$(coloredPieceSelector(coloredPiece)).css('background-image', 'url(' + attachmentURL + ')');
		}

		function coloredPieceSelector(coloredPiece) {
			var color = coloredPiece.substr(0, 1);
			var piece = coloredPiece.substr(1, 1);
			return '#rpbchessboard-themingPreviewWidget .rpbui-chessboard-color-' + color + '.rpbui-chessboard-' + (piece === 'x' ? 'turnFlag' : 'piece-' + piece);
		}

		// Whether all the image fields are set or not.
		function isAllImageFieldsDefined(form) {
			var allDefined = true;
			$('.rpbchessboard-imageIdField', form).each(function() {
				allDefined &= $(this).val() >= 0;
			});
			return allDefined;
		}

		RPBChessboard.initializeSetCodeEditor = function(target) {

			// Hide the error message box.
			$('.rpbchessboard-piecesetEditionErrorMessage', target).hide();

			// Initialize the buttons showing the image picker frame.
			$('.rpbchessboard-coloredPieceButton', target).click(function(e) {
				e.preventDefault();
				displayMediaFrame(target, $(this));
			});

			// Initialize the preview widget
			['bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx'].forEach(function(coloredPiece) {
				$(coloredPieceSelector(coloredPiece)).css('background-image', 'url(' + $('input[name="imageId-' + coloredPiece + '"]', target).data('url') + ')');
			});

			// Validate submit.
			$('input[type="submit"]', target).click(function(e) {
				if($('input[name="pieceset"]', target).val() === '' && !isAllImageFieldsDefined(target)) {
					e.preventDefault();
					var message = <?php echo wp_json_encode( __( 'All the images must be defined to create a pieceset.', 'rpb-chessboard' ) ); ?>;
					$('.rpbchessboard-piecesetEditionErrorMessage', target).text(message).slideDown();
				}
			});
		}

	});
</script>
