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


/**
 * Register the chess edition function in the QuickTags framework.
 *
 * @requires backend.js
 */
(function(RPBChessboard)
{
	'use strict';


	// Skip if QuickTags is not defined.
	if(/* global QTags */ typeof QTags === 'undefined') {
		return;
	}


	/**
	 * Callback for the edit-FEN dialog.
	 */
	function editFENDialogCallback(fen, options) {
		QTags.insertContent(RPBChessboard.serializeFENShortcodeContent(fen, options));
	}


	/**
	 * Callback for the edit-FEN button.
	 */
	function editFENButtonCallback(button, canvas)
	{
		var info = RPBChessboard.identifyFENShortcodeContent(canvas.value, canvas.selectionStart, canvas.selectionEnd);
		if(info === null) {
			RPBChessboard.showEditFENDialog(editFENDialogCallback);
		}
		else {
			canvas.selectionStart = info.beginShortcode;
			canvas.selectionEnd   = info.endShortcode;
			RPBChessboard.showEditFENDialog({
				fen     : info.fen,
				options : info.options,
				callback: editFENDialogCallback
			});
		}
	}


	// Register the edit-FEN button.
	QTags.addButton(
		'rpbchessboard-editFENButton',
		RPBChessboard.i18n.EDITOR_BUTTON_LABEL,
		editFENButtonCallback
	);

})( /* global RPBChessboard */ RPBChessboard );
