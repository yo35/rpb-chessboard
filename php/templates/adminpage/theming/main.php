<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div class="rpbchessboard-columns">

	<div>
		<?php
			RPBChessboardHelperLoader::printTemplate( 'admin-page/theming/colorsets', $model );
			RPBChessboardHelperLoader::printTemplate( 'admin-page/theming/piecesets', $model );
		?>
	</div>

	<div>
		<div id="rpbchessboard-themingPreview">
			<div id="rpbchessboard-themingPreviewWidget"></div>
			<p>
				<input id="rpbchessboard-themingPreviewAnnotations" type="checkbox" />
				<label for="rpbchessboard-themingPreviewAnnotations"><?php esc_html_e( 'Show annotations', 'rpb-chessboard' ); ?></label>
			</p>
		</div>
	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		// State variables
		var editingColorset = false;
		var editingPieceset = false;
		var hoveredColorset = false;
		var hoveredPieceset = false;
		var colorset = $('input[name="preview_colorset"]:checked').val();
		var pieceset = $('input[name="preview_pieceset"]:checked').val();
		var showAnnotations = $('#rpbchessboard-themingPreviewAnnotations').prop('checked');
		var mediaFrame = {};


		// Refresh the widget
		function refresh() {
			RPBChessboard.renderAdminChessboard($('#rpbchessboard-themingPreviewWidget'), {
				position: 'start',
				move: showAnnotations ? 'e4' : undefined,
				squareMarkers: showAnnotations ? 'Ga4,Ga5,Rb4,Rb5,Yc4,Yc5' : '',
				arrowMarkers: showAnnotations ? 'Gf3f6,Rg3g6,Yh3h6' : '',
				squareSize: 48,
				coordinateVisible: false,
				colorset: editingColorset ? '_edit_' : hoveredColorset ? hoveredColorset : colorset,
				pieceset: editingPieceset ? '_edit_' : hoveredPieceset ? hoveredPieceset : pieceset,
			});
		}
		refresh();


		// Show/hide the annotations
		$('#rpbchessboard-themingPreviewAnnotations').change(function() {
			showAnnotations = $('#rpbchessboard-themingPreviewAnnotations').prop('checked');
			refresh();
		});


		// Refresh on click on one of the radio button
		$('input[name="preview_colorset"]').change(function() {
			colorset = $(this).val();
			refresh();
		});
		$('input[name="preview_pieceset"]').change(function() {
			pieceset = $(this).val();
			refresh();
		});


		// Trigger preview on mouse over any table row
		$('.rpbchessboard-colorsetRow').mouseleave(function() {
			hoveredColorset = false;
			refresh();
		}).mouseenter(function() {
			hoveredColorset = $(this).data('slug');
			refresh();
		});
		$('.rpbchessboard-piecesetRow').mouseleave(function() {
			hoveredPieceset = false;
			refresh();
		}).mouseenter(function() {
			hoveredPieceset = $(this).data('slug');
			refresh();
		});


		// Initialization function for the colorset edition components.
		function initializeColorsetEditor(target) {

			function irisCallback(colorsetField) {
				return function(event, ui) {
					RPBChessboard.editColorset[colorsetField] = ui.color.toString();
					refresh();
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

			// Disable what needs to be disable in the rest of the page.
			$('input[name="preview_colorset"]').attr('disabled', true);
			$('.rpbchessboard-action-add').addClass('disabled');
			$('.rpbchessboard-rowActions').addClass('disabled');

			// Reconfigure preview chessboard
			RPBChessboard.editColorset.b = $('.rpbchessboard-darkSquareColorField', target).iris('color');
			RPBChessboard.editColorset.w = $('.rpbchessboard-lightSquareColorField', target).iris('color');
			RPBChessboard.editColorset.highlight = $('.rpbchessboard-highlightColorField', target).iris('color');
			RPBChessboard.editColorset.g = $('.rpbchessboard-greenMarkerColorField', target).iris('color');
			RPBChessboard.editColorset.r = $('.rpbchessboard-redMarkerColorField', target).iris('color');
			RPBChessboard.editColorset.y = $('.rpbchessboard-yellowMarkerColorField', target).iris('color');
			editingColorset = true;
			refresh();
		}


		// Display the media frame (the frame is initialized if necessary).
		function displayMediaFrame(form, button) {
			var coloredPiece = button.data('coloredPiece');
			if (!mediaFrame[coloredPiece]) {

				mediaFrame[coloredPiece] = wp.media({
					title: $(button).attr('title'),
					button: {	text: <?php echo wp_json_encode( __( 'Select', 'rpb-chessboard' ) ); ?>	},
					multiple: false,
				});

				mediaFrame[coloredPiece].on('select', function() {
					var attachment = mediaFrame[coloredPiece].state().get('selection').first().toJSON();
					onMediaSelected(form, coloredPiece, attachment.id, attachment.url);
				});
			}
			mediaFrame[coloredPiece].open();
		}


		// Callback invoked when the media frame is validated.
		function onMediaSelected(form, coloredPiece, attachmentId, attachmentURL) {
			$('.rpbchessboard-coloredPieceButton-' + coloredPiece + ' img', form).attr('src', attachmentURL);
			$('input[name="imageId-' + coloredPiece + '"]', form).val(attachmentId);
			RPBChessboard.editPieceset[coloredPiece] = attachmentURL;
			refresh();
		}


		// Initialization function for the pieceset edition components.
		function initializePiecesetEditor(target) {

			// Initialize the buttons showing the image picker frame.
			$('.rpbchessboard-coloredPieceButton', target).click(function(e) {
				e.preventDefault();
				displayMediaFrame(target, $(this));
			});

			// Disable what needs to be disable in the rest of the page.
			$('input[name="preview_pieceset"]').attr('disabled', true);
			$('.rpbchessboard-action-add').addClass('disabled');
			$('.rpbchessboard-rowActions').addClass('disabled');

			// Reconfigure preview chessboard
			['bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx'].forEach(function(coloredPiece) {
				RPBChessboard.editPieceset[coloredPiece] = $('input[name="imageId-' + coloredPiece + '"]', target).data('initialPreviewUrl');
			});
			editingPieceset = true;
			refresh();
		}


		// Handle "add" actions
		$('.rpbchessboard-action-addColorset').click(function(e) {
			e.preventDefault();
			if($(this).hasClass('disabled')) {
				return;
			}
			var target = $('#rpbchessboard-colorsetCreator');
			initializeColorsetEditor(target);
			target.show();
		});
		$('.rpbchessboard-action-addPieceset').click(function(e) {
			e.preventDefault();
			if($(this).hasClass('disabled')) {
				return;
			}
			var target = $('#rpbchessboard-piecesetCreator');
			initializePiecesetEditor(target);
			target.show();
		});


		// Handle "edit" actions
		$('.rpbchessboard-action-editColorset').click(function(e) {
			e.preventDefault();
			var row = $(this).closest('tr');
			$('td', row).not('.rpbchessboard-themingEditor').hide();
			var target = $('td.rpbchessboard-themingEditor', row);
			initializeColorsetEditor(target);
			target.show();
		});
		$('.rpbchessboard-action-editPieceset').click(function(e) {
			e.preventDefault();
			var row = $(this).closest('tr');
			$('td', row).not('.rpbchessboard-themingEditor').hide();
			var target = $('td.rpbchessboard-themingEditor', row);
			initializePiecesetEditor(target);
			target.show();
		});

	});
</script>
