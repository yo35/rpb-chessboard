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

<div id="rpbchessboard-themingPage" class="rpbchessboard-columns">

	<div class="rpbchessboard-stretchable">
		<?php RPBChessboardHelperLoader::printTemplate( $model->getSubPageTemplateName(), $model ); ?>
	</div>

	<div>
		<div id="rpbchessboard-themingPreviewWidget"></div>
	</div>

</div>

<form id="rpbchessboard-deleteForm" action="<?php echo htmlspecialchars( $model->getFormActionURL() ); ?>" method="post">
	<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars( $model->getDeleteAction() ); ?>" />
	<input type="hidden" name="<?php echo htmlspecialchars( $model->getManagedSetCode() ); ?>" value="" />
</form>

<form id="rpbchessboard-setDefaultForm" action="<?php echo htmlspecialchars( $model->getFormActionURL() ); ?>" method="post">
	<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars( $model->getSetDefaultAction() ); ?>" />
	<input type="hidden" name="<?php echo htmlspecialchars( $model->getManagedSetCode() ); ?>" value="" />
</form>

<script type="text/javascript">

	jQuery(document).ready(function($) {


		// Initialize preview widget.
		$('#rpbchessboard-themingPreviewWidget').chessboard({
			position       : 'start',
			squareSize     : 48     ,
			showCoordinates: false  ,
			colorset       : <?php echo json_encode( $model->getDefaultColorset() ); ?>,
			pieceset       : <?php echo json_encode( $model->getDefaultPieceset() ); ?>
		});

		var disableAutoPreview = false;

		function preview(slug) {
			if(disableAutoPreview) { return; }
			$('#rpbchessboard-themingPreviewWidget').chessboard('option', <?php echo json_encode( $model->getManagedSetCode() ); ?>, slug);
		}

		$('#rpbchessboard-setCodeList tbody tr').mouseleave(function() {
			preview(<?php echo json_encode( $model->getDefaultSetCodeValue() ); ?>);
		}).mouseenter(function(e) {
			preview($(e.currentTarget).data('slug'));
		});

		function disableAllActions() {
			disableAutoPreview = true;
			$('#rpbchessboard-addSetCodeButton').addClass('disabled');
			$('#rpbchessboard-setCodeList').addClass('rpbchessboard-rowActionsDisabled');
		}


		// Handle "add" actions
		$('#rpbchessboard-addSetCodeButton').click(function(e) {
			e.preventDefault();

			// Prevent other actions when the edition form is displayed.
			if($(this).hasClass('disabled')) { return; }
			disableAllActions();

			// Initialize and display the form.
			$('#rpbchessboard-setCodeCreator').show();
			if(typeof RPBChessboard.initializeSetCodeEditor === 'function') {
				RPBChessboard.initializeSetCodeEditor($('#rpbchessboard-setCodeCreator'));
			}
		});


		// Handle "edit" actions
		$('#rpbchessboard-setCodeList tr .rpbchessboard-action-edit').click(function(e) {
			e.preventDefault();

			// Prevent other actions when the edition form is displayed.
			disableAllActions();

			// Initialize and display the form.
			var row = $(e.currentTarget).closest('tr');
			$('td', row).not('.rpbchessboard-setCodeEditor').hide();
			$('td.rpbchessboard-setCodeEditor', row).show();
			if(typeof RPBChessboard.initializeSetCodeEditor === 'function') {
				RPBChessboard.initializeSetCodeEditor($('td.rpbchessboard-setCodeEditor', row));
			}
		});


		// Handle "delete" actions.
		$('#rpbchessboard-setCodeList tr .rpbchessboard-action-delete').click(function(e) {
			e.preventDefault();

			var row = $(this).closest('tr');

			// Ask for confirmation from the user.
			var message = <?php echo json_encode( $model->getDeleteConfirmMessage() ); ?>;
			message = message.replace('{1}', $('.row-title', row).text());
			if(!confirm(message)) { return; }

			// Process the request.
			var form = $('#rpbchessboard-deleteForm');
			$('input[name="' + <?php echo json_encode( $model->getManagedSetCode() ); ?> + '"]', form).val(row.data('slug'));
			form.submit();
		});


		// Handle "set-default" actions.
		$('#rpbchessboard-setCodeList tr .rpbchessboard-action-setDefault').click(function(e) {
			e.preventDefault();

			var row = $(this).closest('tr');

			var form = $('#rpbchessboard-setDefaultForm');
			$('input[name="' + <?php echo json_encode( $model->getManagedSetCode() ); ?> + '"]', form).val(row.data('slug'));
			form.submit();
		});

	});

</script>
