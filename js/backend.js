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


var RPBChessboard = {};

/**
 * TODO
 */
(function(RPBChessboard, $)
{
	/**
	 * Build the edit-FEN dialog (if not done yet).
	 */
	function buildEditFENDialog()
	{
		if($('#rpbchessboard-editFENDialog').length !== 0) {
			return;
		}
		
		var dialog = $('<div id="rpbchessboard-editFENDialog" class="rpbchessboard-modalDialog rpbchessboard-hidden">' +
			'<div class="rpbchessboard-modalBackdrop"></div>' +
			'<div class="rpbchessboard-modalDialogBox">' +
			
				'<div class="rpbchessboard-modalTitleBar">' +
					'<span class="rpbchessboard-modalTitle">The title</span>' +
					'<div class="rpbchessboard-modalCloseButton">' +
						'<span class="screen-reader-text">TODO</span>' +
					'</div>' +
				'</div>' +
				
				'<div class="rpbchessboard-modalContent">The content (TODO)</div>' +
				
			'</div>' +
		'</div>');
		
		//<div class="rpbchessboard-columns">
		//	<div style="width: 65%;">
		//		<div id="rpbchessboard-editFen-chessboard"></div>
		//	</div>
		//	<div style="width: 35%;">
		//		<p>
		//			<?php _e('Turn:', 'rpbchessboard'); ?><br/>
		//			<span id="rpbchessboard-editFen-turn">
		//				<label for="rpbchessboard-editFen-turn-w">
		//					<span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -36px;"></span>
		//				</label>
		//				<input id="rpbchessboard-editFen-turn-w" type="radio" name="turn" value="w" />
		//				&nbsp;
		//				<label for="rpbchessboard-editFen-turn-b">
		//					<span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -0px;"></span>
		//				</label>
		//				<input id="rpbchessboard-editFen-turn-b" type="radio" name="turn" value="b" />
		//			</span>
		//		</p>
		//		<p>
		//			<div><?php _e('Castling availability:', 'rpbchessboard'); ?></div>
		//			<table id="rpbchessboard-editFen-castleRights"><tbody>
		//				<tr>
		//					<td></td>
		//					<td>O-O-O</td>
		//					<td>O-O</td>
		//				</tr>
		//				<tr>
		//					<td><span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -0px;"></span></td>
		//					<td><input id="rpbchessboard-editFen-castle-bq" type="checkbox" /></td>
		//					<td><input id="rpbchessboard-editFen-castle-bk" type="checkbox" /></td>
		//				</tr>
		//				<tr>
		//					<td><span class="rpbchessboard-editFen-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -36px;"></span></td>
		//					<td><input id="rpbchessboard-editFen-castle-wq" type="checkbox" /></td>
		//					<td><input id="rpbchessboard-editFen-castle-wk" type="checkbox" /></td>
		//				</tr>
		//			</tbody></table>
		//		</p>
		//		<p>
		//			<label for="rpbchessboard-editFen-enPassant"><?php _e('En passant column:', 'rpbchessboard'); ?></label>
		//			<select id="rpbchessboard-editFen-enPassant">
		//				<option value="">-</option>
		//				<option value="a">a</option>
		//				<option value="b">b</option>
		//				<option value="c">c</option>
		//				<option value="d">d</option>
		//				<option value="e">e</option>
		//				<option value="f">f</option>
		//				<option value="g">g</option>
		//				<option value="h">h</option>
		//			</select>
		//		</p>
		//		<p>
		//			<button id="rpbchessboard-editFen-reset" title="<?php _e('Set the initial position', 'rpbchessboard'); ?>">
		//				<?php _e('Reset', 'rpbchessboard'); ?>
		//			</button>
		//			<button id="rpbchessboard-editFen-clear" title="<?php _e('Clear the chessboard', 'rpbchessboard'); ?>">
		//				<?php _e('Clear', 'rpbchessboard'); ?>
		//			</button>
		//		</p>
		//	</div>
		//</div>
		//<div id="rpbchessboard-editFen-fenArea">
		//	<span><?php _e('FEN:', 'rpbchessboard'); ?></span>
		//	<input id="rpbchessboard-editFen-fen" type="text" />
		//</div>

		$('#rpbchessboard-editFENDialog-anchor').append(dialog);
		
		$('.rpbchessboard-modalBackdrop', dialog).click(function() {
			dialog.addClass('rpbchessboard-hidden');
		});
	}
	
	
	/**
	 * Build the edit-FEN dialog (if not done yet), and make it visible.
	 * 
	 * @param {string} [fen=undefined] FEN string of the position to edit, or `undefined` to create a new position.
	 */
	RPBChessboard.showEditFENDialog = function(fen)
	{
		buildEditFENDialog();
		$('#rpbchessboard-editFENDialog').removeClass('rpbchessboard-hidden');
		
		// If the argument 'fen' is not defined, then the dialog is set-up in the "add-mode",
		// meaning that it is assumed that the user wants to insert a new FEN string in the text.
		//if(fen===undefined) {
		//	$('#rpbchessboard-editFen-dialog').data('isAddMode', true);
		//	fen = 'start';
		//}

		// Otherwise, the dialog is set-up in the "editMode", meaning that it is assumed that
		// the user wants to edit an existing FEN string.
		//else {
		//	$('#rpbchessboard-editFen-dialog').data('isAddMode', false);
		//}
	};
	// TODO
	
})( /* global RPBChessboard */ RPBChessboard, /* global jQuery */ jQuery );
