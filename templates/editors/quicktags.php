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

<div id="rpbchessboard-editFen-dialog" class="rbpchessboard-invisible">
	<div class="rpbchessboard-admin-columns">
		<div style="width: 65%;">
			<div id="rpbchessboard-editFen-chessboard"></div>
		</div>
		<div style="width: 35%;">
			<p>
				<button id="rpbchessboard-editFen-reset" title="<?php _e('Set the initial position', 'rpbchessboard'); ?>">
					<?php _e('Reset', 'rpbchessboard'); ?>
				</button>
				<button id="rpbchessboard-editFen-clear" title="<?php _e('Clear the chessboard', 'rpbchessboard'); ?>">
					<?php _e('Clear', 'rpbchessboard'); ?>
				</button>
			</p>
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
			TODO
		</div>
	</div>
	<div id="rpbchessboard-editFen-fenArea">
		<span><?php _e('FEN:', 'rpbchessboard'); ?></span>
		<input id="rpbchessboard-editFen-fen" type="text" />
	</div>
	<form id="rpbchessboard-editFen-form">
		<div class="submitbox">
			<div id="rpbchessboard-editFen-update">
				<input class="button-primary" type="submit" name="rpbchessboard-editFen-submit" value="<?php
					_e('Add the chess diagram', 'rpbchessboard');
				?>"></input>
			</div>
			<div id="rpbchessboard-editFen-cancel">
				<a class="submitdelete deletion" href="#"><?php _e('Cancel', 'rpbchessboard'); ?></a>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">

	// Build the 'editFen' dialog, if not done yet.
	function rpbchessboard_editFenDialog($)
	{
		// Nothing to do if the dialog has already been initialized.
		if(!$('#rpbchessboard-editFen-dialog').hasClass('rbpchessboard-invisible')) {
			return;
		}

		// FEN widget.
		var fen = $('#rpbchessboard-editFen-fen');
		fen.prop('readonly', true);

		// Create the chessboard widget.
		var cb = $('#rpbchessboard-editFen-chessboard');
		cb.chessboard({
			position   : 'start',
			squareSize : 40,
			allowMoves : 'all',
			sparePieces: true,
			change: function(event, ui) { fen.val(ui); }
		});
		fen.val(cb.chessboard('option', 'position'));

		// Turn widget.
		$('#rpbchessboard-editFen-turn-' + cb.chessboard('turn')).prop('checked', true);
		$('#rpbchessboard-editFen-turn input').each(function(index, e)
		{
			$(e).click(function() { cb.chessboard('turn', $(e).val()); });
		});

		// Buttons 'reset' and 'clear'.
		function resetPosition(fen)
		{
			cb.chessboard('option', 'position', fen);
			$('#rpbchessboard-editFen-turn-' + cb.chessboard('turn')).prop('checked', true);
		}
		$('#rpbchessboard-editFen-reset').button().click(function() { resetPosition('start'); });
		$('#rpbchessboard-editFen-clear').button().click(function() { resetPosition('empty'); });

		// Button 'cancel'.
		$('#rpbchessboard-editFen-cancel a').click(function()
		{
			$('#rpbchessboard-editFen-dialog').dialog('close');
		});

		// Submit button.
		$('#rpbchessboard-editFen-form').submit(function(event)
		{
			event.preventDefault();
			$('#rpbchessboard-editFen-dialog').dialog('close');
			QTags.insertContent(
				'[fen]' + cb.chessboard('option', 'position') + '[/fen]'
			);
		});

		// Create the dialog.
		$('#rpbchessboard-editFen-dialog').removeClass('rbpchessboard-invisible').dialog({
			autoOpen   : false,
			modal      : true,
			dialogClass: 'wp-dialog',
			title      : '<?php _e('Insert/edit chess diagram', 'rpbchessboard'); ?>',
			width      : 650
		});
	}


	// Callback called when the user clicks on the 'editFen' button.
	function rpbchessboard_editFenCallback(button, canvas, editor)
	{
		rpbchessboard_editFenDialog(jQuery);
		jQuery('#rpbchessboard-editFen-dialog').dialog('open');
	}


	// Register the button.
	QTags.addButton(
		'rpbchessboard-editFen-button',
		'<?php _e('chessboard', 'rpbchessboard'); ?>',
		rpbchessboard_editFenCallback
	);

</script>
