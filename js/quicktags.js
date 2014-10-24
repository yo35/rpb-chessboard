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
	 * Search the string `str` starting from position `pos`, for a match with regular expression `re`.
	 *
	 * @param {string} str
	 * @param {number} pos
	 * @param {RegExp} re
	 * @return {number}
	 */
	function searchFrom(str, pos, re) {
		if(pos>str.length) {
			return -1;
		}
		else {
			var retVal = str.substr(pos).search(re);
			return retVal<0 ? -1 : retVal+pos;
		}
	}


	/**
	 * Search the string `str` backward from position `pos`, for a match with regular expression `re`.
	 *
	 * @param {string} str
	 * @param {number} pos
	 * @param {RegExp} re
	 * @return {number}
	 */
	function searchFromBackward(str, pos, re) {
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


	/**
	 * Callback for the edit-FEN dialog.
	 */
	function editFENDialogCallback(fen, options)
	{
		var fenShortcode = RPBChessboard.config.FEN_SHORTCODE;
		var res = '[' + fenShortcode;

		if('flip' in options && options.flip) {
			res += ' flip=true';
		}

		if('squareSize'      in options) { res += ' square_size='      + options.squareSize     ; }
		if('showCoordinates' in options) { res += ' show_coordinates=' + options.showCoordinates; }

		res += ']' + fen + '[/' + fenShortcode + ']';

		QTags.insertContent(res);
	}


	/**
	 * Callback for the edit-FEN button.
	 */
	function editFENButtonCallback(button, canvas)
	{
		var posBegin     = canvas.selectionStart;
		var posEnd       = canvas.selectionEnd;
		var text         = canvas.value;
		var fenShortcode = RPBChessboard.config.FEN_SHORTCODE;

		// Search for the first occurrence of the closing tag '[/fen]' after the begin of the selection.
		var lgClose  = 3 + fenShortcode.length;
		var reClose  = new RegExp('\\[\\/' + fenShortcode + '\\]', 'g');
		var posClose = searchFrom(text, Math.max(0, posBegin-lgClose+1), reClose);

		// Search for the last occurrence of the opening tag '[fen ... ]' before the detected closing tag.
		var reOpen  = new RegExp('\\[' + fenShortcode + '[^\\[\\]]*\\]', 'g');
		var posOpen = posClose<0 ? -1 : searchFromBackward(text, posClose, reOpen);

		// If both the open and the close tag were found, and if:
		// posOpen <= posBegin <= posEnd <= posClose + (length of the close tag),
		// then set-up the dialog to edit the string enclosed by the tags...
		if(posOpen>=0 && posClose>=0 && posOpen<=posBegin && posEnd<=posClose+lgClose) {
			var tagOpen = text.substr(posOpen).match(reOpen)[0];
			var lgOpen  = tagOpen.length;
			var fen     = text.substr(posOpen + lgOpen, posClose - posOpen - lgOpen);
			canvas.selectionStart = posOpen;
			canvas.selectionEnd   = posClose + lgClose;
			RPBChessboard.showEditFENDialog({
				callback: editFENDialogCallback,
				fen: fen,
				options: RPBChessboard.parseWordPressShortcodeAttributes(tagOpen)
			});
		}

		// Otherwise, set-up the dialog to add a new FEN string.
		else {
			RPBChessboard.showEditFENDialog(editFENDialogCallback);
		}
	}


	// Register the edit-FEN button.
	QTags.addButton(
		'rpbchessboard-editFENButton',
		RPBChessboard.i18n.EDITOR_BUTTON_LABEL,
		editFENButtonCallback
	);

})( /* global RPBChessboard */ RPBChessboard);
