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

<?php
	if(!$model->isGDLibraryAvailable()) {
		echo '<p>' . __('Custom pieceset edition is not available, because the GD library is not available on this server. ' .
			'Please contact your web host to fix this.', 'rpbchessboard') . '</p>';
		return;
	}
?>

<p>
	<button id="rpbchessboard-addSetCodeButton" class="button">
		<?php _e('Add a new pieceset', 'rpbchessboard'); ?>
	</button>
</p>

<table id="rpbchessboard-setCodeList" class="wp-list-table widefat striped">

	<thead>
		<tr>
			<th scope="col"><?php _e('Name', 'rpbchessboard'); ?></th>
			<th scope="col"><?php _e('Slug', 'rpbchessboard'); ?></th>
			<th scope="col"><?php _e('Default', 'rpbchessboard'); ?></th>
		</tr>
	</thead>

	<tbody>

		<tr>
			<?php RPBChessboardHelperLoader::printTemplate('AdminPage/Theming/PiecesetEdition', $model, array('isNew' => true)); ?>
		</tr>

		<?php foreach($model->getAvailablePiecesets() as $pieceset): ?>
			<tr data-slug="<?php echo htmlspecialchars($pieceset); ?>">

				<td class="has-row-actions">
					<strong class="row-title"><?php echo htmlspecialchars($model->getPiecesetLabel($pieceset)); ?></strong>
					<span class="row-actions rpbchessboard-inlinedRowActions">
						<?php if($model->isBuiltinPieceset($pieceset)): ?>
							<span><a href="#" class="rpbchessboard-action-setDefault"><?php _e('Set default', 'rpbchessboard'); ?></a></span>
						<?php else: ?>
							<span><a href="#" class="rpbchessboard-action-setDefault"><?php _e('Set default', 'rpbchessboard'); ?></a> |</span>
							<span><a href="#" class="rpbchessboard-action-edit"><?php _e('Edit', 'rpbchessboard'); ?></a> |</span>
							<span><a href="#" class="rpbchessboard-action-delete"><?php _e('Delete', 'rpbchessboard'); ?></a></span>
						<?php endif; ?>
					</span>
				</td>

				<td><?php echo htmlspecialchars($pieceset); ?></td>

				<td>
					<?php if($model->isDefaultPieceset($pieceset)): ?>
						<div class="rpbchessboard-tickIcon"></div>
					<?php endif; ?>
				</td>

				<?php if(!$model->isBuiltinPieceset($pieceset)): ?>
					<?php RPBChessboardHelperLoader::printTemplate('AdminPage/Theming/PiecesetEdition', $model, array('isNew' => false, 'pieceset' => $pieceset)); ?>
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

		var mediaFrame = {};

		// Display the media frame (the frame is initialized if necessary).
		function displayMediaFrame(form, button) {

			var coloredPiece = button.data('coloredPiece');

			if(typeof mediaFrame[coloredPiece] === 'undefined') {

				mediaFrame[coloredPiece] = wp.media({
					title: $(button).attr('title'),
					button: {	text: <?php echo json_encode(__('Select', 'rpbchessboard')); ?>	},
					multiple: false
				});

				mediaFrame[coloredPiece].on('select', function() {
					var attachment = mediaFrame[coloredPiece].state().get('selection').first().toJSON();
					launchFormatPiecesetSprite(form, coloredPiece, attachment);
				});
			}

			mediaFrame[coloredPiece].open();
		}

		// Initiate the AJAX that is in charge of formating the uploaded image into a sprite.
		function launchFormatPiecesetSprite(form, coloredPiece, attachment) {

			$('.rpbchessboard-piecesetEditionErrorMessage', form).hide();

			var ajaxUrl = <?php echo json_encode(admin_url('admin-ajax.php')); ?>;
			var nonce = <?php echo json_encode(wp_create_nonce('rpbchessboard_format_pieceset_sprite')); ?>;

			$.post(ajaxUrl, {
				action: 'rpbchessboard_format_pieceset_sprite',
				_ajax_nonce: nonce,
				coloredPiece: coloredPiece,
				attachmentId: attachment.id
			}, function(data) {
				if(data.success) {
					onFormatPiecesetSpriteSuccess(form, coloredPiece, data.data.attachmentId, data.data.rawDataURL, data.data.formattedDataURL);
				}
				else {
					onFormatPiecesetSpriteFailure(form, coloredPiece, data.data.message);
				}
			});
		}

		// Process the AJAX response in case of success.
		function onFormatPiecesetSpriteSuccess(form, coloredPiece, attachmentId, rawDataURL, formattedDataURL) {
			$('.rpbchessboard-coloredPieceButton-' + coloredPiece, form).empty().append('<img src="' + rawDataURL + '" width="64px" height="64px" />');
			$('input[name="imageId-' + coloredPiece + '"]', form).val(attachmentId);
			$(coloredPieceSelector(coloredPiece)).css('background-image', 'url(' + formattedDataURL + ')');
		}

		// Process the AJAX response in case of success.
		function onFormatPiecesetSpriteFailure(form, coloredPiece, message) {
			$('.rpbchessboard-piecesetEditionErrorMessage', form).text(message).slideDown();
		}

		function coloredPieceSelector(coloredPiece) {
			var color = coloredPiece.substr(0, 1);
			var piece = coloredPiece.substr(1, 1);
			return '#rpbchessboard-themingPreviewWidget .uichess-chessboard-color-' + color + '.uichess-chessboard-' + (piece === 'x' ? 'turnFlag' : 'piece-' + piece);
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

			// Initialize the color picker widgets
			$('.rpbchessboard-coloredPieceButton', target).click(function(e) {
				e.preventDefault();
				displayMediaFrame(target, $(this));
			});

			// Validate submit.
			$('input[type="submit"]', target).click(function(e) {
				if(!isAllImageFieldsDefined(target)) {
					e.preventDefault();
					var message = <?php echo json_encode(__('All the images must be defined to create a pieceset.', 'rpbchessboard')); ?>;
					$('.rpbchessboard-piecesetEditionErrorMessage', target).text(message).slideDown();
				}
			});
		}

	});
</script>
