<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<?php if(false): ?>

<div id="rpbchessboard-editFen-dialog" class="rpbchessboard-editFen-invisible">
	<div class="rpbchessboard-columns">
		<div style="width: 65%;">
			<div id="rpbchessboard-editFen-chessboard"></div>
		</div>
		<div style="width: 35%;">
			<p>
				<?php _e('Turn:', 'rpbchessboard'); ?><br/>
				<span id="rpbchessboard-editFen-turn">
					<label for="rpbchessboard-editFen-turn-w">
						<span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -36px;"></span>
					</label>
					<input id="rpbchessboard-editFen-turn-w" type="radio" name="turn" value="w" />
					&nbsp;
					<label for="rpbchessboard-editFen-turn-b">
						<span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -0px;"></span>
					</label>
					<input id="rpbchessboard-editFen-turn-b" type="radio" name="turn" value="b" />
				</span>
			</p>
			<p>
				<div><?php _e('Castling availability:', 'rpbchessboard'); ?></div>
				<table id="rpbchessboard-editFen-castleRights"><tbody>
					<tr>
						<td></td>
						<td>O-O-O</td>
						<td>O-O</td>
					</tr>
					<tr>
						<td><span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -0px;"></span></td>
						<td><input id="rpbchessboard-editFen-castle-bq" type="checkbox" /></td>
						<td><input id="rpbchessboard-editFen-castle-bk" type="checkbox" /></td>
					</tr>
					<tr>
						<td><span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -36px;"></span></td>
						<td><input id="rpbchessboard-editFen-castle-wq" type="checkbox" /></td>
						<td><input id="rpbchessboard-editFen-castle-wk" type="checkbox" /></td>
					</tr>
				</tbody></table>
			</p>
			<p>
				<label for="rpbchessboard-editFen-enPassant"><?php _e('En passant column:', 'rpbchessboard'); ?></label>
				<select id="rpbchessboard-editFen-enPassant">
					<option value="">-</option>
					<option value="a">a</option>
					<option value="b">b</option>
					<option value="c">c</option>
					<option value="d">d</option>
					<option value="e">e</option>
					<option value="f">f</option>
					<option value="g">g</option>
					<option value="h">h</option>
				</select>
			</p>
			<p>
				<button id="rpbchessboard-editFen-reset" title="<?php _e('Set the initial position', 'rpbchessboard'); ?>">
					<?php _e('Reset', 'rpbchessboard'); ?>
				</button>
				<button id="rpbchessboard-editFen-clear" title="<?php _e('Clear the chessboard', 'rpbchessboard'); ?>">
					<?php _e('Clear', 'rpbchessboard'); ?>
				</button>
			</p>
		</div>
	</div>
	<div id="rpbchessboard-editFen-fenArea">
		<span><?php _e('FEN:', 'rpbchessboard'); ?></span>
		<input id="rpbchessboard-editFen-fen" type="text" />
	</div>
</div>

<?php else: ?>

	<div id="rpbchessboard-editFENDialog-anchor"></div>

<?php endif; ?>


<script type="text/javascript">

	// Build the 'editFen' dialog, if not done yet.
	function rpbchessboard_editFenDialog($, fen)
	{
		// If the argument 'fen' is not defined, then the dialog is set-up in the "add-mode",
		// meaning that it is assumed that the user wants to insert a new FEN string in the text.
		if(fen===undefined) {
			$('#rpbchessboard-editFen-dialog').data('isAddMode', true);
			fen = 'start';
		}

		// Otherwise, the dialog is set-up in the "editMode", meaning that it is assumed that
		// the user wants to edit an existing FEN string.
		else {
			$('#rpbchessboard-editFen-dialog').data('isAddMode', false);
		}


		// Method to call to initialize the dialog with a given FEN string.
		function resetPosition(fen)
		{
			var cb = $('#rpbchessboard-editFen-chessboard');
			cb.chessboard('option', 'position', fen);
			$('#rpbchessboard-editFen-turn-' + cb.chessboard('turn')).prop('checked', true);
			var castleRights = cb.chessboard('castleRights');
			$('#rpbchessboard-editFen-castle-wk').prop('checked', castleRights.indexOf('K')>=0);
			$('#rpbchessboard-editFen-castle-wq').prop('checked', castleRights.indexOf('Q')>=0);
			$('#rpbchessboard-editFen-castle-bk').prop('checked', castleRights.indexOf('k')>=0);
			$('#rpbchessboard-editFen-castle-bq').prop('checked', castleRights.indexOf('q')>=0);
			$('#rpbchessboard-editFen-enPassant').val(cb.chessboard('enPassant'));
		}


		// Method to call to set the title of the text of the submit button.
		function resetSubmitButtonText()
		{
			$('#rpbchessboard-editFen-submit').button('option', 'label',
				$('#rpbchessboard-editFen-dialog').data('isAddMode') ?
				<?php echo json_encode(__('Add a new chess diagram', 'rpbchessboard')); ?> :
				<?php echo json_encode(__('Update the chess diagram', 'rpbchessboard')); ?>
			);
		}


		// If the dialog has already been initialized, just reset the position, and exit.
		if(!$('#rpbchessboard-editFen-dialog').hasClass('rpbchessboard-editFen-invisible')) {
			resetPosition(fen);
			resetSubmitButtonText();
			return;
		}


		// FEN widget.
		var textField = $('#rpbchessboard-editFen-fen');
		textField.prop('readonly', true);


		// Create the chessboard widget.
		var cb = $('#rpbchessboard-editFen-chessboard');
		cb.chessboard({
			position   : fen,
			squareSize : 40,
			allowMoves : 'all',
			sparePieces: true,
			change: function(event, ui) { textField.val(ui); }
		});
		textField.val(cb.chessboard('option', 'position'));


		// Turn widget.
		$('#rpbchessboard-editFen-turn-' + cb.chessboard('turn')).prop('checked', true);
		$('#rpbchessboard-editFen-turn input').each(function(index, e)
		{
			$(e).click(function() { cb.chessboard('turn', $(e).val()); });
		});


		// Castle rights widget.
		var castleRights = cb.chessboard('castleRights');
		$('#rpbchessboard-editFen-castle-wk').prop('checked', castleRights.indexOf('K')>=0);
		$('#rpbchessboard-editFen-castle-wq').prop('checked', castleRights.indexOf('Q')>=0);
		$('#rpbchessboard-editFen-castle-bk').prop('checked', castleRights.indexOf('k')>=0);
		$('#rpbchessboard-editFen-castle-bq').prop('checked', castleRights.indexOf('q')>=0);
		$('#rpbchessboard-editFen-castleRights input').each(function(index, e)
		{
			$(e).click(function()
			{
				var castleRights = '';
				if($('#rpbchessboard-editFen-castle-wk').prop('checked')) castleRights += 'K';
				if($('#rpbchessboard-editFen-castle-wq').prop('checked')) castleRights += 'Q';
				if($('#rpbchessboard-editFen-castle-bk').prop('checked')) castleRights += 'k';
				if($('#rpbchessboard-editFen-castle-bq').prop('checked')) castleRights += 'q';
				cb.chessboard('castleRights', castleRights);
			});
		});


		// En-passant widget.
		$('#rpbchessboard-editFen-enPassant').val(cb.chessboard('enPassant')).change(function()
		{
			cb.chessboard('enPassant', $('#rpbchessboard-editFen-enPassant').val());
		});


		// Buttons 'reset' and 'clear'.
		$('#rpbchessboard-editFen-reset').button().click(function() { resetPosition('start'); });
		$('#rpbchessboard-editFen-clear').button().click(function() { resetPosition('empty'); });


		// Create the dialog.
		$('#rpbchessboard-editFen-dialog').removeClass('rpbchessboard-editFen-invisible').dialog({
			autoOpen   : false,
			modal      : true,
			dialogClass: 'wp-dialog',
			title      : '<?php _e('Insert/edit a chess diagram', 'rpbchessboard'); ?>',
			width      : 650,
			buttons    : [
				{
					'text' : <?php echo json_encode(__('Cancel', 'rpbchessboard')); ?>,
					'click': function() { $(this).dialog('close'); }
				},
				{
					'class': 'button-primary',
					'id'   : 'rpbchessboard-editFen-submit',
					'text' : '',
					'click': function()
					{
						$(this).dialog('close');
						var newContent = cb.chessboard('option', 'position');
						if($(this).data('isAddMode')) {
							var fenShortcode = <?php echo json_encode($model->getFENShortcode()); ?>;
							newContent = '[' + fenShortcode + ']' + newContent + '[/' + fenShortcode + ']';
						}
						QTags.insertContent(newContent);
					}
				}
			]
		});


		// Initialize the label of the submit button.
		resetSubmitButtonText();
	}


	// Callback called when the user clicks on the 'editFen' button.
	function rpbchessboard_editFenCallback(button, canvas, editor)
	{
		var posBegin     = canvas.selectionStart;
		var posEnd       = canvas.selectionEnd;
		var text         = canvas.value;
		var fenShortcode = <?php echo json_encode($model->getFENShortcode()); ?>;

		// Search for the first occurence of the closing tag '[/fen]' after the begin of the selection.
		function searchFrom(str, pos, re) {
			if(pos>str.length) {
				return -1;
			}
			else {
				var retVal = str.substr(pos).search(re);
				return retVal<0 ? -1 : retVal+pos;
			}
		}
		var lgClose  = 3 + fenShortcode.length;
		var reClose  = new RegExp('\\[\\/' + fenShortcode + '\\]', 'g');
		var posClose = searchFrom(text, Math.max(0, posBegin-lgClose+1), reClose);

		// Search for the last occurence of the opening tag '[fen ... ]' before the detected closing tag.
		function searchFromBackward(str, pos, re)
		{
			str = str.substr(0, pos);
			var retVal = -1;
			while(true) {
				var newOccurence = searchFrom(str, retVal+1, re);
				if(newOccurence<0) {
					break;
				}
				retVal = newOccurence;
			}
			return retVal;
		}
		var reOpen  = new RegExp('\\[' + fenShortcode + '[^\\[\\]]*\\]', 'g');
		var posOpen = posClose<0 ? -1 : searchFromBackward(text, posClose, reOpen);

		// If both the open and the close tag were found, and if:
		// posOpen <= posBegin <= posEnd <= posClose + (length of the close tag),
		// then set-up the dialog to edit the string enclosed by the tags...
		if(posOpen>=0 && posClose>=0 && posOpen<=posBegin && posEnd<=posClose+lgClose) {
			var lgOpen = text.substr(posOpen).match(reOpen)[0].length;
			var fen    = text.substr(posOpen + lgOpen, posClose - posOpen - lgOpen);
			canvas.selectionStart = posOpen + lgOpen;
			canvas.selectionEnd   = posClose;
			rpbchessboard_editFenDialog(jQuery, fen);
		}

		// Otherwise, set-up the dialog to add a new FEN string.
		else {
			rpbchessboard_editFenDialog(jQuery);
		}

		// Show the dialog.
		jQuery('#rpbchessboard-editFen-dialog').dialog('open');
	}


	// Register the button.
	QTags.addButton(
		'rpbchessboard-editFen-button',
		'<?php _e('chessboard', 'rpbchessboard'); ?>',
		//rpbchessboard_editFenCallback
		RPBChessboard.showEditFENDialog // TODO
	);

</script>
