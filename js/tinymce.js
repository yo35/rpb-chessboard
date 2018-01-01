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


/**
 * Register the chess edition function in the TinyMCE framework.
 *
 * @requires backend.js
 */
(function(RPBChessboard)
{
	'use strict';


	// Skip if tinymce is not defined.
	if(/* global tinymce */ typeof tinymce === 'undefined') {
		return;
	}


	/**
	 * Callback for the edit-FEN dialog.
	 */
	function editFENDialogCallback(editor, fen, options) {
		editor.selection.setContent(RPBChessboard.serializeFENShortcodeContent(fen, options));
	}


	/**
	 * Callback for the edit-FEN button.
	 */
	function editFENButtonCallback(editor)
	{
		// Ensure that the selection does not span over several DOM nodes.
		var selectionRange = editor.selection.getRng();
		if(selectionRange.startContainer !== selectionRange.endContainer) {
			editor.selection.collapse();
		}
		var currentNode = selectionRange.startContainer;

		// Identify the FEN shortcode within the selected text.
		var text = currentNode.textContent;
		var beginSelection = selectionRange.startOffset;
		var endSelection   = selectionRange.endOffset;
		var info = (0 <= beginSelection && beginSelection <= endSelection && endSelection <= text.length) ?
			RPBChessboard.identifyFENShortcodeContent(text, beginSelection, endSelection) : null;

		// Open the FEN dialog.
		var callback = function(fen, options) { editFENDialogCallback(editor, fen, options); };
		if(info === null) {
			RPBChessboard.showEditFENDialog(callback);
		}
		else {
			selectionRange.setStart(currentNode, info.beginShortcode);
			selectionRange.setEnd  (currentNode, info.endShortcode  );
			RPBChessboard.showEditFENDialog({
				fen     : info.fen,
				options : info.options,
				callback: callback
			});
		}
	}


	// Register the edit-FEN button.
	tinymce.PluginManager.add('RPBChessboard', function(editor, url) {
		editor.addButton('rpb-chessboard', {
			title: RPBChessboard.i18n.EDIT_CHESS_DIAGRAM_DIALOG_TITLE,
			image: url + '/../images/tinymce.png',
			onclick: function() { editFENButtonCallback(editor); }
    });
	});

})(/* global RPBChessboard */ RPBChessboard);
