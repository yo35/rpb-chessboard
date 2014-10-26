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
 * Miscellaneous functions used by the plugin in the backend.
 *
 * @requires uichess-chessboard.js
 * @requires jQuery
 * @requires jQuery UI Dialog
 * @requires jQuery UI Accordion
 * @requires jQuery UI Draggable
 * @requires jQuery UI Droppable
 */
(function(RPBChessboard, $)
{
	'use strict';


	/**
	 * Localization constants.
	 */
	RPBChessboard.i18n =
	{
		EDITOR_BUTTON_LABEL: 'chessboard',
		EDIT_CHESS_DIAGRAM_DIALOG_TITLE: 'Insert/edit a chess diagram',
		CANCEL_BUTTON_LABEL: 'Cancel',
		SUBMIT_BUTTON_ADD_LABEL: 'Add a new chess diagram',
		SUBMIT_BUTTON_EDIT_LABEL: 'Update the chess diagram',
		BASIC_TAB_LABEL: 'Basic',
		TURN_SECTION_TITLE: 'Turn',
		ORIENTATION_SECTION_TITLE: 'Orientation',
		FLIP_CHECKBOX_LABEL: 'Flip board',
		SPECIAL_POSITIONS_SECTION_TITLE: 'Special positions',
		START_POSITION_TOOLTIP: 'Set the initial position',
		EMPTY_POSITION_TOOLTIP: 'Clear the chessboard',
		ADVANCED_TAB_LABEL: 'Advanced',
		CASTLING_SECTION_TITLE: 'Castling rights',
		EN_PASSANT_SECTION_TITLE: 'En passant',
		EN_PASSANT_DISABLED_BUTTON_LABEL: 'Not possible',
		EN_PASSANT_ENABLED_BUTTON_LABEL: 'Possible on column %1$s'
	};


	/**
	 * General settings.
	 */
	RPBChessboard.config =
	{
		/**
		 * FEN shortcode as registered in the WordPress framework.
		 * @type {string}
		 */
		FEN_SHORTCODE: 'fen'
	};


	/**
	 * Validate a string as a boolean.
	 *
	 * @param {mixed} s
	 * @returns {?boolean} Null if `s` does not represent a valid boolean.
	 */
	function validateBoolean(s) {

		// Trivial case
		if(typeof s === 'boolean') {
			return s;
		}

		// Parsing from string
		else if(typeof s === 'string') {
			switch(s.toLowerCase()) {
				case 'true' : case 'yes': case '1': return true ;
				case 'false': case 'no' : case '0': return false;
				default: return null;
			}
		}

		// Default case
		else {
			return null;
		}
	}


	/**
	 * Reset the edit-FEN dialog and initialize it with the given parameters.
	 *
	 * @param {string} [fen]
	 * @param {boolean} [isAddMode]
	 * @param {object} [options]
	 */
	function resetEditFENDialog(fen, isAddMode, options)
	{
		var cb = $('#rpbchessboard-editFENDialog-chessboard');

		if(typeof fen === 'string') {
			cb.chessboard('option', 'position', fen);
			$('#rpbchessboard-editFENDialog-turn-' + cb.chessboard('turn')).prop('checked', true);
			var castleRights = cb.chessboard('castleRights');
			$('#rpbchessboard-editFENDialog-castle-wk').prop('checked', castleRights.indexOf('K')>=0);
			$('#rpbchessboard-editFENDialog-castle-wq').prop('checked', castleRights.indexOf('Q')>=0);
			$('#rpbchessboard-editFENDialog-castle-bk').prop('checked', castleRights.indexOf('k')>=0);
			$('#rpbchessboard-editFENDialog-castle-bq').prop('checked', castleRights.indexOf('q')>=0);
			var enPassant = cb.chessboard('enPassant');
			$('#rpbchessboard-editFENDialog-enPassant-' + (enPassant === '' ? 'disabled' : 'enabled')).prop('checked', true);
			$('#rpbchessboard-editFENDialog-enPassant-column').prop('disabled', enPassant === '');
			if(enPassant !== '') {
				$('#rpbchessboard-editFENDialog-enPassant-column').val(enPassant);
			}
		}

		if(typeof isAddMode === 'boolean') {
			$('#rpbchessboard-editFENDialog-submitButton').button('option', 'label', isAddMode ?
				RPBChessboard.i18n.SUBMIT_BUTTON_ADD_LABEL : RPBChessboard.i18n.SUBMIT_BUTTON_EDIT_LABEL
			);
		}

		if(typeof options === 'object' && options !== null) {

			// Flip parameter
			var flip = ('flip' in options) ? validateBoolean(options.flip) : null;
			if(flip === null) { flip = false; }
			cb.chessboard('option', 'flip', flip);
			$('#rpbchessboard-editFENDialog-flip').prop('checked', flip);
		}
	}


	/**
	 * Build the edit-FEN dialog (if not done yet).
	 */
	function buildEditFENDialog()
	{
		if($('#rpbchessboard-editFENDialog').length !== 0) {
			return;
		}

		$('body').append('<div id="rpbchessboard-editFENDialog">' +
			'<div class="rpbchessboard-columns">' +
				'<div style="width: 450px;">' +
					'<div id="rpbchessboard-editFENDialog-chessboard"></div>' +
				'</div>' +
				'<div class="rpbchessboard-stretchable">' +
					'<div class="rpbchessboard-jQuery-enableSmoothness">' +
						'<div id="rpbchessboard-editFENDialog-accordion" class="rpbchessboard-accordion">' +

							// Basic tab
							'<h3>' + RPBChessboard.i18n.BASIC_TAB_LABEL + '</h3>' +
							'<div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.TURN_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<span class="rpbchessboard-editFENDialog-turnField">' +
										'<label for="rpbchessboard-editFENDialog-turn-w">' +
											'<span class="rpbchessboard-editFENDialog-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -36px;"></span>' +
										'</label>' +
										'<input id="rpbchessboard-editFENDialog-turn-w" type="radio" name="turn" value="w" />' +
									'</span>' +
									'<span class="rpbchessboard-editFENDialog-turnField">' +
										'<label for="rpbchessboard-editFENDialog-turn-b">' +
											'<span class="rpbchessboard-editFENDialog-turnFlag uichess-chessboard-sprite36" style="background-position: -216px -0px;"></span>' +
										'</label>' +
										'<input id="rpbchessboard-editFENDialog-turn-b" type="radio" name="turn" value="b" />' +
									'</span>' +
								'</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.ORIENTATION_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<input id="rpbchessboard-editFENDialog-flip" type="checkbox" />' +
									'<label for="rpbchessboard-editFENDialog-flip">' + RPBChessboard.i18n.FLIP_CHECKBOX_LABEL + '</label>' +
								'</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.SPECIAL_POSITIONS_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<button id="rpbchessboard-editFENDialog-startPosition" class="button rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.START_POSITION_TOOLTIP + '">' +
										'<div class="rpbchessboard-startPositionIcon"></div>' +
									'</button>' +
									'<button id="rpbchessboard-editFENDialog-emptyPosition" class="button rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.EMPTY_POSITION_TOOLTIP + '">' +
										'<div class="rpbchessboard-emptyPositionIcon"></div>' +
									'</button>' +
								'</div>' +
							'</div>' +

							// Advanced tab
							'<h3>' + RPBChessboard.i18n.ADVANCED_TAB_LABEL + '</h3>' +
							'<div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.CASTLING_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<table id="rpbchessboard-editFENDialog-castleRights"><tbody>' +
										'<tr>' +
											'<td></td>' +
											'<td>O-O-O</td>' +
											'<td>O-O</td>' +
										'</tr>' +
										'<tr>' +
											'<td><span class="rpbchessboard-editFENDialog-turnFlag uichess-chessboard-sprite28" style="background-position: -168px -0px;"></span></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-bq" type="checkbox" /></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-bk" type="checkbox" /></td>' +
										'</tr>' +
										'<tr>' +
											'<td><span class="rpbchessboard-editFENDialog-turnFlag uichess-chessboard-sprite28" style="background-position: -168px -28px;"></span></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-wq" type="checkbox" /></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-wk" type="checkbox" /></td>' +
										'</tr>' +
									'</tbody></table>' +
								'</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.EN_PASSANT_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<ul id="rpbchessboard-editFENDialog-enPassantRights">' +
										'<li>' +
											'<input id="rpbchessboard-editFENDialog-enPassant-disabled" type="radio" name="enPassant" />' +
											'<label for="rpbchessboard-editFENDialog-enPassant-disabled">' +
												RPBChessboard.i18n.EN_PASSANT_DISABLED_BUTTON_LABEL +
											'</label>' +
										'</li>' +
										'<li>' +
											'<input id="rpbchessboard-editFENDialog-enPassant-enabled" type="radio" name="enPassant" />' +
											'<label for="rpbchessboard-editFENDialog-enPassant-enabled">' +
												RPBChessboard.i18n.EN_PASSANT_ENABLED_BUTTON_LABEL.replace('%1$s',
													'</label><select id="rpbchessboard-editFENDialog-enPassant-column">' +
														'<option value="a">a</option>' +
														'<option value="b">b</option>' +
														'<option value="c">c</option>' +
														'<option value="d">d</option>' +
														'<option value="e">e</option>' +
														'<option value="f">f</option>' +
														'<option value="g">g</option>' +
														'<option value="h">h</option>' +
													'</select><label for="rpbchessboard-editFENDialog-enPassant-enabled">'
												) +
											'</label>' +
										'</li>' +
									'</ul>' +
								'</div>' +
							'</div>' +

						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>');

		// Chessboard widget
		var cb = $('#rpbchessboard-editFENDialog-chessboard');
		cb.chessboard({
			squareSize : 40,
			allowMoves : 'all',
			sparePieces: true
		});

		// Turn widget
		$('#rpbchessboard-editFENDialog-turn-' + cb.chessboard('turn')).prop('checked', true);
		$('.rpbchessboard-editFENDialog-turnField input').each(function(index, elem) {
			$(elem).click(function() { cb.chessboard('turn', $(elem).val()); });
		});

		// Flip board widget
		$('#rpbchessboard-editFENDialog-flip').click(function() {
			cb.chessboard('option', 'flip', $(this).prop('checked'));
		});

		// Buttons 'reset' and 'clear'
		$('#rpbchessboard-editFENDialog-startPosition').click(function(e) { e.preventDefault(); resetEditFENDialog('start'); });
		$('#rpbchessboard-editFENDialog-emptyPosition').click(function(e) { e.preventDefault(); resetEditFENDialog('empty'); });

		// Castle rights widgets
		$('#rpbchessboard-editFENDialog-castleRights input').each(function(index, elem) {
			$(elem).click(function() {
				var castleRights = '';
				if($('#rpbchessboard-editFENDialog-castle-wk').prop('checked')) { castleRights += 'K'; }
				if($('#rpbchessboard-editFENDialog-castle-wq').prop('checked')) { castleRights += 'Q'; }
				if($('#rpbchessboard-editFENDialog-castle-bk').prop('checked')) { castleRights += 'k'; }
				if($('#rpbchessboard-editFENDialog-castle-bq').prop('checked')) { castleRights += 'q'; }
				cb.chessboard('castleRights', castleRights);
			});
		});

		// En passant widgets
		var enPassantColumnChooser = $('#rpbchessboard-editFENDialog-enPassant-column');
		$('#rpbchessboard-editFENDialog-enPassant-disabled').click(function() {
			enPassantColumnChooser.prop('disabled', true);
			cb.chessboard('enPassant', '');
		});
		$('#rpbchessboard-editFENDialog-enPassant-enabled').click(function() {
			enPassantColumnChooser.prop('disabled', false);
			enPassantColumnChooser.change();
		});
		enPassantColumnChooser.change(function() {
			cb.chessboard('enPassant', enPassantColumnChooser.val());
		});

		// Accordion
		$('#rpbchessboard-editFENDialog-accordion').accordion();

		// Dialog
		$('#rpbchessboard-editFENDialog').dialog({
			autoOpen   : false,
			modal      : true,
			dialogClass: 'wp-dialog',
			title      : RPBChessboard.i18n.EDIT_CHESS_DIAGRAM_DIALOG_TITLE,
			width      : 750,
			buttons    : [
				{
					'text' : RPBChessboard.i18n.CANCEL_BUTTON_LABEL,
					'click': function() { $(this).dialog('close'); }
				},
				{
					'class': 'button-primary',
					'id'   : 'rpbchessboard-editFENDialog-submitButton',
					'text' : '',
					'click': function() {
						$(this).dialog('close');
						var callback = $(this).data('callback');
						if(typeof callback === 'function') {
							var fen = cb.chessboard('option', 'position');
							var options = $(this).data('options');
							options.flip = $('#rpbchessboard-editFENDialog-flip').prop('checked');
							callback(fen, options);
						}
					}
				}
			]
		});
	}


	/**
	 * Build the edit-FEN dialog (if not done yet), and make it visible.
	 *
	 * @param {function|{fen:string, options:object, callback:function}} args
	 *
	 * The callback function is expected to take two arguments: the first is the FEN string
	 * defined by the user in the dialog, the second an associative array of options.
	 */
	RPBChessboard.showEditFENDialog = function(args)
	{
		// Build the dialog
		buildEditFENDialog();
		var dialog = $('#rpbchessboard-editFENDialog');
		dialog.data('callback', null);
		dialog.data('options' , null);

		// 'args' is a callback -> initialize the dialog in "add" mode
		if(typeof args === 'function') {
			dialog.data('callback', args);
			dialog.data('options' , {});
			resetEditFENDialog('start', true);
		}

		// 'args' is a struct
		else if(typeof args === 'object' && args !== null) {

			// Initialize the callback
			if('callback' in args && typeof args.callback === 'function') {
				dialog.data('callback', args.callback);
			}

			// Retrieve the options
			var options = ('options' in args) ? args.options : {};
			dialog.data('options', options);

			// Initialize the dialog
			if('fen' in args && typeof args.fen === 'string') {
				resetEditFENDialog(args.fen, false, options);
			}
			else {
				resetEditFENDialog('start', true, options);
			}
		}

		// Show the dialog
		$('#rpbchessboard-editFENDialog').dialog('open');
	};


	/**
	 * Parse the attributes attached to an opening shortcode as defined in the WordPress API.
	 *
	 * @param {string} openingTag For instance: `'[shortcode attribute1=value1 attribute2=value2]'`
	 * @returns {object}
	 */
	RPBChessboard.parseWordPressShortcodeAttributes = function(openingTag)
	{
		var retVal = {};
		var re = /([a-zA-Z_0-9]+)=([^ \]]+)(?: |\])/g;
		var m = null;
		var fun = function(m, p1) { return p1.toUpperCase(); };
		while((m = re.exec(openingTag)) !== null) {
			var attribute = m[1].toLowerCase().replace(/_([a-z]?)/g, fun);
			retVal[attribute] = m[2];
		}
		return retVal;
	};


	/**
	 * Return the text to add to a WordPress post/page to insert a FEN diagram.
	 *
	 * @param {string} fen FEN string encoding the chess diagram itself.
	 * @param {object} options Options to pass to the shortcode.
	 * @retuns {string}
	 */
	RPBChessboard.serializeFENShortcodeContent = function(fen, options)
	{
		var fenShortcode = RPBChessboard.config.FEN_SHORTCODE;
		var res = '[' + fenShortcode;

		if('flip' in options && options.flip) {
			res += ' flip=true';
		}
		if('squareSize'      in options) { res += ' square_size='      + options.squareSize     ; }
		if('showCoordinates' in options) { res += ' show_coordinates=' + options.showCoordinates; }

		res += ']' + fen + '[/' + fenShortcode + ']';
		return res;
	};


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
	 * Identity a FEN shortcode within the text `text` around a selected range of characters.
	 *
	 * @param {string} text
	 * @param {number} beginSelection Position where the selection starts within `text`.
	 * @param {number} endSelection Position where the selection ends within `text`.
	 * @returns {{beginShortcode:number, endShortcode:number, fen:string, options:object}} Null if no FEN shortcode can be find.
	 */
	RPBChessboard.identifyFENShortcodeContent = function(text, beginSelection, endSelection)
	{
		var fenShortcode = RPBChessboard.config.FEN_SHORTCODE;

		// Search for the first occurrence of the closing tag '[/fen]' after the begin of the selection.
		var lgClose  = 3 + fenShortcode.length;
		var reClose  = new RegExp('\\[\\/' + fenShortcode + '\\]', 'g');
		var posClose = searchFrom(text, Math.max(0, beginSelection-lgClose+1), reClose);

		// Search for the last occurrence of the opening tag '[fen ... ]' before the detected closing tag.
		var reOpen  = new RegExp('\\[' + fenShortcode + '[^\\[\\]]*\\]', 'g');
		var posOpen = posClose<0 ? -1 : searchFromBackward(text, posClose, reOpen);

		// If both the open and the close tag were found, and if:
		// posOpen <= beginSelection <= endSelection <= posClose + (length of the close tag),
		// then set-up the dialog to edit the string enclosed by the tags...
		if(posOpen>=0 && posClose>=0 && posOpen<=beginSelection && endSelection<=posClose+lgClose) {
			var tagOpen = text.substr(posOpen).match(reOpen)[0];
			var lgOpen  = tagOpen.length;
			var fen     = text.substr(posOpen + lgOpen, posClose - posOpen - lgOpen);
			return {
				beginShortcode: posOpen,
				endShortcode: posClose + lgClose,
				fen: fen,
				options: RPBChessboard.parseWordPressShortcodeAttributes(tagOpen)
			};
		}

		// Otherwise, the input text do not contain a FEN shortcode => return null.
		else {
			return null;
		}
	};

})( /* global RPBChessboard */ RPBChessboard, /* global jQuery */ jQuery );
