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
	<form>
		<div id="rpbchessboard-editFen-chessboard"></div>
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

	var rpbchessboard_editFenDialogBuilt = false;


	// Build the 'editFen' dialog, if not done yet.
	function rpbchessboard_editFenDialog($)
	{
		if(rpbchessboard_editFenDialogBuilt) {
			return;
		}

		// Create the chessboard widget.
		$('#rpbchessboard-editFen-chessboard').chessboard({
			position   : 'start',
			squareSize : 40,
			allowMoves : 'all',
			sparePieces: true
		});

		// Create the dialog.
		$('#rpbchessboard-editFen-dialog').removeClass('rbpchessboard-invisible').dialog({
			autoOpen   : false,
			dialogClass: 'wp-dialog',
			title      : '<?php _e('Insert a chess diagram', 'rpbchessboard'); ?>',
			width      : 500
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
		'<?php _e('Chessboard', 'rpbchessboard'); ?>',
		rpbchessboard_editFenCallback
	);

</script>
