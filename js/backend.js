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


var RPBChessboard = {};

/**
 * Miscellaneous functions used by the plugin in the backend.
 *
 * @requires rpbchess-ui-chessboard.js
 * @requires jQuery
 * @requires jQuery UI Dialog
 * @requires jQuery UI Accordion
 * @requires jQuery UI Draggable
 * @requires jQuery UI Droppable
 */
(function(RPBChessboard, $)
{
	'use strict';



	// ---------------------------------------------------------------------------
	// Global settings.
	// ---------------------------------------------------------------------------

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
		UNDO_TOOLTIP: 'Undo the last modification',
		REDO_TOOLTIP: 'Re-do the last modification',
		FLIP_CHECKBOX_LABEL: 'Flip the board',
		STANDARD_POSITIONS_TAB_LABEL: 'Standard positions',
		START_POSITION_TOOLTIP: 'Set the initial position',
		EMPTY_POSITION_TOOLTIP: 'Clear the chessboard',
		ANNOTATIONS_TAB_LABEL: 'Annotations',
		SQUARE_MARKERS_SECTION_TITLE: 'Square markers',
		DELETE_SQUARE_MARKERS_TOOLTIP: 'Remove the square markers',
		ARROW_MARKERS_SECTION_TITLE: 'Arrow markers',
		DELETE_ARROW_MARKERS_TOOLTIP: 'Remove the arrow markers',
		ADVANCED_TAB_LABEL: 'Advanced settings',
		CASTLING_SECTION_TITLE: 'Castling rights',
		EN_PASSANT_SECTION_TITLE: 'En passant',
		EN_PASSANT_DISABLED_BUTTON_LABEL: 'Not possible',
		EN_PASSANT_ENABLED_BUTTON_LABEL: 'Possible on column %1$s',
		KEYBOARD_SHORTCUTS_TAB_LABEL: 'Keyboard shortcuts',
		UNDO_SHORTCUT_LABEL: 'undo',
		REDO_SHORTCUT_LABEL: 'redo',
		ADD_PAWNS_SHORTCUT_LABEL: 'add pawns',
		ADD_KNIGHTS_SHORTCUT_LABEL: 'add knights',
		ADD_BISHOPS_SHORTCUT_LABEL: 'add bishops',
		ADD_ROOKS_SHORTCUT_LABEL: 'add rooks',
		ADD_QUEENS_SHORTCUT_LABEL: 'add queens',
		ADD_KINGS_SHORTCUT_LABEL: 'add kings'
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



	// ---------------------------------------------------------------------------
	// Miscellaneous methods.
	// ---------------------------------------------------------------------------

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



	// ---------------------------------------------------------------------------
	// Edit-FEN dialog.
	// ---------------------------------------------------------------------------

	/**
	 * History of actions done in the edit-FEN dialog.
	 */
	var undoHistory = [];


	/**
	 * Index of the next action in the undo-history stack.
	 */
	var nextActionIndex = 0;


	/**
	 * Set to `true` to stop propagation of undo-recording events.
	 */
	var shuntUndoHistoryListeners = false;


	/**
	 * Callback to execute when the user validates the dialog.
	 */
	var dialogCallback = null;


	/**
	 * Options passed to the dialog.
	 */
	var dialogOptions = {};


	/**
	 * Add a new action on the top of the undo-history stack.
	 *
	 * @param {callback} callback
	 * @param {mixed} oldValue
	 * @param {mixed} newValue
	 */
	function pushActionOnUndoHistory(callback, oldValue, newValue) {

		// Remove the actions that have been canceled from the stack.
		if(nextActionIndex < undoHistory.length) {
			undoHistory = undoHistory.slice(0, nextActionIndex);
		}

		// Push the new action and increment the "next-action-index".
		undoHistory.push({ callback:callback, undoValue:oldValue, redoValue:newValue });
		++nextActionIndex;

		// Update the state of the undo/redo buttons.
		$('#rpbchessboard-editFENDialog-undo').button('enable');
		$('#rpbchessboard-editFENDialog-redo').button('disable');
	}


	/**
	 * Clear the undo-history stack.
	 */
	function clearUndoHistory() {
		undoHistory = [];
		nextActionIndex = 0;
		$('#rpbchessboard-editFENDialog-undo').button('disable');
		$('#rpbchessboard-editFENDialog-redo').button('disable');
	}


	/**
	 * Undo the last action.
	 */
	function undo() {
		if(nextActionIndex===0) {
			return;
		}

		// Undo the last action.
		--nextActionIndex;
		executeUndoRedoCallback(undoHistory[nextActionIndex].callback, undoHistory[nextActionIndex].undoValue);

		// Update the state of the undo/redo buttons.
		$('#rpbchessboard-editFENDialog-undo').button(nextActionIndex===0 ? 'disable' : 'enable');
		$('#rpbchessboard-editFENDialog-redo').button('enable');
	}


	/**
	 * Re-do the last action.
	 */
	function redo() {
		if(nextActionIndex===undoHistory.length) {
			return;
		}

		// Re-do the last action.
		executeUndoRedoCallback(undoHistory[nextActionIndex].callback, undoHistory[nextActionIndex].redoValue);
		++nextActionIndex;

		// Update the state of the undo/redo buttons.
		$('#rpbchessboard-editFENDialog-undo').button('enable');
		$('#rpbchessboard-editFENDialog-redo').button(nextActionIndex===undoHistory.length ? 'disable' : 'enable');
	}


	/**
	 * Execute an undo or a redo-callback.
	 *
	 * @param {callback} callback
	 * @param {mixed} value
	 */
	function executeUndoRedoCallback(callback, value) {
		shuntUndoHistoryListeners = true;
		callback(value);
		shuntUndoHistoryListeners = false;
	}


	/**
	 * Change the active chessboard interaction mode in the edit-FEN dialog.
	 *
	 * @param {string} [newMode]
	 */
	function switchInteractionMode(newMode) {
		var cb = $('#rpbchessboard-editFENDialog-chessboard');
		var oldMode = cb.chessboard('option', 'interactionMode');
		$('#rpbchessboard-editFENDialog-' + oldMode).prop('checked', false).button('refresh');
		if(typeof newMode === 'undefined' || newMode === oldMode) {
			cb.chessboard('option', 'interactionMode', 'movePieces');
		}
		else {
			cb.chessboard('option', 'interactionMode', newMode);
			$('#rpbchessboard-editFENDialog-' + newMode).prop('checked', true).button('refresh');
		}
	}


	/**
	 * Reset the chessboard position.
	 *
	 * @param {string} fen
	 */
	function resetPosition(fen) {
		var cb = $('#rpbchessboard-editFENDialog-chessboard');
		cb.chessboard('option', 'position', fen);

		// Turn selector
		$('#rpbchessboard-editFENDialog-turn-' + cb.chessboard('turn')).prop('checked', true);
		$('#rpbchessboard-editFENDialog-turnSelector input').button('refresh');

		// Castle-right widgets
		$('#rpbchessboard-editFENDialog-castle-wk').prop('checked', cb.chessboard('castling', 'wk'));
		$('#rpbchessboard-editFENDialog-castle-wq').prop('checked', cb.chessboard('castling', 'wq'));
		$('#rpbchessboard-editFENDialog-castle-bk').prop('checked', cb.chessboard('castling', 'bk'));
		$('#rpbchessboard-editFENDialog-castle-bq').prop('checked', cb.chessboard('castling', 'bq'));

		// En-passant widgets
		var enPassant = cb.chessboard('enPassant');
		$('#rpbchessboard-editFENDialog-enPassant-' + (enPassant === '-' ? 'disabled' : 'enabled')).prop('checked', true);
		$('#rpbchessboard-editFENDialog-enPassant-column').prop('disabled', enPassant === '-');
		if(enPassant !== '-') {
			$('#rpbchessboard-editFENDialog-enPassant-column').val(enPassant);
		}
	}


	/**
	 * Reset the edit-FEN dialog and initialize it with the given parameters.
	 *
	 * @param {string} fen
	 * @param {boolean} isAddMode
	 * @param {object} [options]
	 */
	function resetEditFENDialog(fen, isAddMode, options) {
		shuntUndoHistoryListeners = true;
		var cb = $('#rpbchessboard-editFENDialog-chessboard');

		// Chessboard position
		resetPosition(fen);

		if(typeof options === 'object' && options !== null) {

			// Flip parameter
			var flip = ('flip' in options) ? validateBoolean(options.flip) : null;
			if(flip === null) { flip = false; }
			cb.chessboard('option', 'flip', flip);
			$('#rpbchessboard-editFENDialog-flip').prop('checked', flip);

			// Square & arrow markers
			cb.chessboard('option', 'squareMarkers', ('csl' in options) ? options.csl : '');
			cb.chessboard('option', 'arrowMarkers' , ('cal' in options) ? options.cal : '');
		}
		else {
			cb.chessboard('option', 'squareMarkers', '');
			cb.chessboard('option', 'arrowMarkers' , '');
		}

		// Reset the dialog
		switchInteractionMode();
		clearUndoHistory();
		$('#rpbchessboard-editFENDialog-submitButton').button('option', 'label', isAddMode ?
			RPBChessboard.i18n.SUBMIT_BUTTON_ADD_LABEL : RPBChessboard.i18n.SUBMIT_BUTTON_EDIT_LABEL
		);
		shuntUndoHistoryListeners = false;
	}


	/**
	 * Build the edit-FEN dialog (if not done yet).
	 */
	function buildEditFENDialog() {
		if($('#rpbchessboard-editFENDialog').length !== 0) {
			return;
		}

		$('body').append('<div id="rpbchessboard-editFENDialog">' +
			'<div class="rpbchessboard-columns">' +
				'<div style="min-width:425px;">' +
					'<div class="rpbchessboard-jQuery-enableSmoothness">' +
						'<div class="rpbchessboard-toolbar rpbui-chessboard-size30">' +

							// Piece selection for the "add-piece" mode.
							'<div id="rpbchessboard-editFENDialog-addPiecesSelector" class="rpbchessboard-buttonSet">' +
								'<div class="rpbchessboard-buttonRow">' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-wp" name="interactionMode" value="addPieces-wp" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-wn" name="interactionMode" value="addPieces-wn" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-wb" name="interactionMode" value="addPieces-wb" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-wr" name="interactionMode" value="addPieces-wr" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-wq" name="interactionMode" value="addPieces-wq" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-wk" name="interactionMode" value="addPieces-wk" />' +
									'<label for="rpbchessboard-editFENDialog-addPieces-wp" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-wp"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-wn" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-wn"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-wb" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-wb"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-wr" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-wr"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-wq" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-wq"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-wk" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-wk"></div></label>' +
								'</div>' +
								'<div class="rpbchessboard-buttonRow">' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-bp" name="interactionMode" value="addPieces-bp" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-bn" name="interactionMode" value="addPieces-bn" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-bb" name="interactionMode" value="addPieces-bb" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-br" name="interactionMode" value="addPieces-br" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-bq" name="interactionMode" value="addPieces-bq" />' +
									'<input type="checkbox" id="rpbchessboard-editFENDialog-addPieces-bk" name="interactionMode" value="addPieces-bk" />' +
									'<label for="rpbchessboard-editFENDialog-addPieces-bp" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-bp"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-bn" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-bn"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-bb" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-bb"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-br" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-br"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-bq" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-bq"></div></label>' +
									'<label for="rpbchessboard-editFENDialog-addPieces-bk" class="rpbchessboard-graphicButton"><div class="rpbui-chessboard-sized rpbui-chessboard-piece-bk"></div></label>' +
								'</div>' +
							'</div>' +

							// Turn selection
							'<div id="rpbchessboard-editFENDialog-turnSelector" class="rpbchessboard-buttonSet">' +
								'<div class="rpbchessboard-buttonRow">' +
									'<input type="radio" id="rpbchessboard-editFENDialog-turn-w" name="turn" value="w" />' +
									'<label for="rpbchessboard-editFENDialog-turn-w" class="rpbchessboard-graphicButton">' +
										'<div class="rpbui-chessboard-sized rpbui-chessboard-color-w rpbui-chessboard-turnFlag"></div>' +
									'</label>' +
								'</div>' +
								'<div class="rpbchessboard-buttonRow">' +
									'<input type="radio" id="rpbchessboard-editFENDialog-turn-b" name="turn" value="b" />' +
									'<label for="rpbchessboard-editFENDialog-turn-b" class="rpbchessboard-graphicButton">' +
										'<div class="rpbui-chessboard-sized rpbui-chessboard-color-b rpbui-chessboard-turnFlag"></div>' +
									'</label>' +
								'</div>' +
							'</div>' +

							// Undo/redo + flip
							'<div class="rpbchessboard-toolbarGroup">' +
								'<div>' +
									'<button id="rpbchessboard-editFENDialog-undo" class="rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.UNDO_TOOLTIP + '">' +
										'<div class="rpbchessboard-undoIcon"></div>' +
									'</button>' +
									'<button id="rpbchessboard-editFENDialog-redo" class="rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.REDO_TOOLTIP + '">' +
										'<div class="rpbchessboard-redoIcon"></div>' +
									'</button>' +
								'</div>' +
								'<div id="rpbchessboard-editFENDialog-flipSelector">' +
									'<input id="rpbchessboard-editFENDialog-flip" type="checkbox" />' +
									'<label for="rpbchessboard-editFENDialog-flip">' + RPBChessboard.i18n.FLIP_CHECKBOX_LABEL + '</label>' +
								'</div>' +
							'</div>' +

						'</div>' +
					'</div>' +
					'<div id="rpbchessboard-editFENDialog-chessboard"></div>' +
				'</div>' +
				'<div class="rpbchessboard-stretchable">' +
					'<div class="rpbchessboard-jQuery-enableSmoothness">' +
						'<div id="rpbchessboard-editFENDialog-accordion">' +

							// Standard positions tab
							'<h3>' + RPBChessboard.i18n.STANDARD_POSITIONS_TAB_LABEL + '</h3>' +
							'<div>' +
								'<div class="rpbchessboard-editFENDialog-buttonColumn">' +
									'<div>' +
										'<button id="rpbchessboard-editFENDialog-startPosition" class="rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.START_POSITION_TOOLTIP + '">' +
											'<div class="rpbchessboard-startPositionIcon"></div>' +
										'</button>' +
									'</div><div>' +
										'<button id="rpbchessboard-editFENDialog-emptyPosition" class="rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.EMPTY_POSITION_TOOLTIP + '">' +
											'<div class="rpbchessboard-emptyPositionIcon"></div>' +
										'</button>' +
									'</div>' +
								'</div>' +
							'</div>' +

							// Annotations tab
							'<h3>' + RPBChessboard.i18n.ANNOTATIONS_TAB_LABEL + '</h3>' +
							'<div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.SQUARE_MARKERS_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<div class="rpbchessboard-toolbar">' +
										'<div id="rpbchessboard-editFENDialog-addSquareMarkersSelector" class="rpbchessboard-buttonSet">' +
											'<div class="rpbchessboard-buttonRow">' +
												'<input type="checkbox" id="rpbchessboard-editFENDialog-addSquareMarkers-G" name="interactionMode" value="addSquareMarkers-G" />' +
												'<input type="checkbox" id="rpbchessboard-editFENDialog-addSquareMarkers-R" name="interactionMode" value="addSquareMarkers-R" />' +
												'<input type="checkbox" id="rpbchessboard-editFENDialog-addSquareMarkers-Y" name="interactionMode" value="addSquareMarkers-Y" />' +
												'<label for="rpbchessboard-editFENDialog-addSquareMarkers-G" class="rpbchessboard-graphicButton"><div class="rpbchessboard-squareMarkerGreenIcon"></div></label>' +
												'<label for="rpbchessboard-editFENDialog-addSquareMarkers-R" class="rpbchessboard-graphicButton"><div class="rpbchessboard-squareMarkerRedIcon"></div></label>' +
												'<label for="rpbchessboard-editFENDialog-addSquareMarkers-Y" class="rpbchessboard-graphicButton"><div class="rpbchessboard-squareMarkerYellowIcon"></div></label>' +
											'</div>' +
										'</div>' +
										'<button id="rpbchessboard-editFENDialog-deleteSquareMarkers" class="rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.DELETE_SQUARE_MARKERS_TOOLTIP + '">' +
											'<div class="rpbchessboard-deleteIcon"></div>' +
										'</button>' +
									'</div>' +
								'</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.ARROW_MARKERS_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<div class="rpbchessboard-toolbar">' +
										'<div id="rpbchessboard-editFENDialog-addArrowMarkersSelector" class="rpbchessboard-buttonSet">' +
											'<div class="rpbchessboard-buttonRow">' +
												'<input type="checkbox" id="rpbchessboard-editFENDialog-addArrowMarkers-G" name="interactionMode" value="addArrowMarkers-G" />' +
												'<input type="checkbox" id="rpbchessboard-editFENDialog-addArrowMarkers-R" name="interactionMode" value="addArrowMarkers-R" />' +
												'<input type="checkbox" id="rpbchessboard-editFENDialog-addArrowMarkers-Y" name="interactionMode" value="addArrowMarkers-Y" />' +
												'<label for="rpbchessboard-editFENDialog-addArrowMarkers-G" class="rpbchessboard-graphicButton"><div class="rpbchessboard-arrowMarkerGreenIcon"></div></label>' +
												'<label for="rpbchessboard-editFENDialog-addArrowMarkers-R" class="rpbchessboard-graphicButton"><div class="rpbchessboard-arrowMarkerRedIcon"></div></label>' +
												'<label for="rpbchessboard-editFENDialog-addArrowMarkers-Y" class="rpbchessboard-graphicButton"><div class="rpbchessboard-arrowMarkerYellowIcon"></div></label>' +
											'</div>' +
										'</div>' +
										'<button id="rpbchessboard-editFENDialog-deleteArrowMarkers" class="rpbchessboard-graphicButton" title="' + RPBChessboard.i18n.DELETE_ARROW_MARKERS_TOOLTIP + '">' +
											'<div class="rpbchessboard-deleteIcon"></div>' +
										'</button>' +
									'</div>' +
								'</div>' +
							'</div>' +

							// Advanced tab
							'<h3>' + RPBChessboard.i18n.ADVANCED_TAB_LABEL + '</h3>' +
							'<div>' +
								'<div class="rpbchessboard-editFENDialog-sectionTitle">' + RPBChessboard.i18n.CASTLING_SECTION_TITLE + '</div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<table id="rpbchessboard-editFENDialog-castling" class="rpbui-chessboard-size28"><tbody>' +
										'<tr>' +
											'<td></td>' +
											'<td>O-O-O</td>' +
											'<td>O-O</td>' +
										'</tr>' +
										'<tr>' +
											'<td><span class="rpbchessboard-editFENDialog-turnFlag rpbui-chessboard-turnFlag rpbui-chessboard-color-b rpbui-chessboard-sized"></span></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-bq" type="checkbox" data-castle="bq" /></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-bk" type="checkbox" data-castle="bk" /></td>' +
										'</tr>' +
										'<tr>' +
											'<td><span class="rpbchessboard-editFENDialog-turnFlag rpbui-chessboard-turnFlag rpbui-chessboard-color-w rpbui-chessboard-sized"></span></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-wq" type="checkbox" data-castle="wq" /></td>' +
											'<td><input id="rpbchessboard-editFENDialog-castle-wk" type="checkbox" data-castle="wk" /></td>' +
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

							// Keyboard shortcuts tab
							'<h3>' + RPBChessboard.i18n.KEYBOARD_SHORTCUTS_TAB_LABEL + '</h3>' +
							'<div>' +
								'<div class="rpbchessboard-editFENDialog-sectionContent">' +
									'<ul>' +
										'<li><span class="rpbchessboard-keyboardShortcut">Ctrl+Z</span>' + RPBChessboard.i18n.UNDO_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">Ctrl+Y</span>' + RPBChessboard.i18n.REDO_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">P</span>' + RPBChessboard.i18n.ADD_PAWNS_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">N</span>' + RPBChessboard.i18n.ADD_KNIGHTS_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">B</span>' + RPBChessboard.i18n.ADD_BISHOPS_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">R</span>' + RPBChessboard.i18n.ADD_ROOKS_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">Q</span>' + RPBChessboard.i18n.ADD_QUEENS_SHORTCUT_LABEL + '</li>' +
										'<li><span class="rpbchessboard-keyboardShortcut">K</span>' + RPBChessboard.i18n.ADD_KINGS_SHORTCUT_LABEL + '</li>' +
									'</ul>' +
								'</div>' +
							'</div>'+

						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>');

		// Chessboard widget
		var cb = $('#rpbchessboard-editFENDialog-chessboard');
		cb.chessboard({
			squareSize: 40,
			interactionMode: 'movePieces',

			positionChange: function(event, ui) {
				if(!shuntUndoHistoryListeners) {
					pushActionOnUndoHistory(function(value) { resetPosition(value); }, ui.oldValue, ui.newValue);
				}
			},

			flipChange: function(event, ui) {
				if(!shuntUndoHistoryListeners) {
					pushActionOnUndoHistory(function(value) {
						cb.chessboard('option', 'flip', value);
						$('#rpbchessboard-editFENDialog-flip').prop('checked', value);
					}, ui.oldValue, ui.newValue);
				}
			},

			squareMarkersChange: function(event, ui) {
				if(!shuntUndoHistoryListeners) {
					pushActionOnUndoHistory(function(value) { cb.chessboard('option', 'squareMarkers', value); }, ui.oldValue, ui.newValue);
				}
			},

			arrowMarkersChange: function(event, ui) {
				if(!shuntUndoHistoryListeners) {
					pushActionOnUndoHistory(function(value) { cb.chessboard('option', 'arrowMarkers', value); }, ui.oldValue, ui.newValue);
				}
			}
		});


		// Add-pieces buttons
		$('#rpbchessboard-editFENDialog-addPiecesSelector input').button().each(function(index, elem) {
			$(elem).click(function() { switchInteractionMode($(elem).val()); });
		});

		// Add-markers buttons
		$('#rpbchessboard-editFENDialog-addSquareMarkersSelector input').button().each(function(index, elem) {
			$(elem).click(function() { switchInteractionMode($(elem).val()); });
		});
		$('#rpbchessboard-editFENDialog-addArrowMarkersSelector input').button().each(function(index, elem) {
			$(elem).click(function() { switchInteractionMode($(elem).val()); });
		});

		// Delete-markers buttons
		$('#rpbchessboard-editFENDialog-deleteSquareMarkers').button().click(function() {
			cb.chessboard('option', 'squareMarkers', '');
		});
		$('#rpbchessboard-editFENDialog-deleteArrowMarkers').button().click(function() {
			cb.chessboard('option', 'arrowMarkers', '');
		});

		// Turn buttons
		$('#rpbchessboard-editFENDialog-turnSelector input').button().each(function(index, elem) {
			$(elem).click(function() { cb.chessboard('turn', $(elem).val()); });
		});

		// Flip board checkbox
		$('#rpbchessboard-editFENDialog-flip').click(function() {
			cb.chessboard('option', 'flip', $(this).prop('checked'));
		});

		// Undo/redo
		$('#rpbchessboard-editFENDialog-undo').button().click(function() { undo(); });
		$('#rpbchessboard-editFENDialog-redo').button().click(function() { redo(); });

		// Buttons 'reset' and 'clear'
		$('#rpbchessboard-editFENDialog-startPosition').button().click(function() { resetPosition('start'); });
		$('#rpbchessboard-editFENDialog-emptyPosition').button().click(function() { resetPosition('empty'); });

		// Castle rights widgets
		$('#rpbchessboard-editFENDialog-castling input').each(function(index, elem) {
			$(elem).click(function() {
				cb.chessboard('castling', $(elem).data('castle'), $(elem).prop('checked'));
			});
		});

		// En passant widgets
		var enPassantColumnChooser = $('#rpbchessboard-editFENDialog-enPassant-column');
		$('#rpbchessboard-editFENDialog-enPassant-disabled').click(function() {
			enPassantColumnChooser.prop('disabled', true);
			cb.chessboard('enPassant', '-');
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
						if(dialogCallback !== null) {
							dialogOptions.flip = cb.chessboard('option', 'flip');
							dialogOptions.csl = cb.chessboard('option', 'squareMarkers');
							dialogOptions.cal = cb.chessboard('option', 'arrowMarkers');
							dialogCallback(cb.chessboard('option', 'position'), dialogOptions);
						}
					}
				}
			]
		});

		// Handler for keyboard shortcuts
		$(document).keypress(function(event) {

			if(!$('#rpbchessboard-editFENDialog').dialog('isOpen')) {
				return;
			}

			// Undo (resp. redo) on CTRL+Z (resp. CTRL+Y).
			if(!event.altKey && event.ctrlKey && !event.metaKey && !event.shiftKey) {
				if(event.key === 'z') { undo(); }
				else if(event.key === 'y') { redo(); }
			}

			// Switch between "addPieces" modes when clicking on "p/n/b/r/q/k" without modifiers.
			else if(!event.altKey && !event.ctrlKey && !event.metaKey && !event.shiftKey && 'pnbrqk'.indexOf(event.key) >= 0) {
				var mode = $('#rpbchessboard-editFENDialog-chessboard').chessboard('option', 'interactionMode');
				var targetedModeW = 'addPieces-w' + event.key;
				var targetedModeB = 'addPieces-b' + event.key;
				switchInteractionMode(mode === targetedModeW ? targetedModeB : mode === targetedModeB ? 'movePieces' : targetedModeW);
			}

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
	RPBChessboard.showEditFENDialog = function(args) {

		// Build the dialog
		buildEditFENDialog();

		// 'args' is a callback -> initialize the dialog in "add" mode
		if(typeof args === 'function') {
			dialogCallback = args;
			dialogOptions = {};
			resetEditFENDialog('start', true);
		}

		// 'args' is a struct
		else if(typeof args === 'object' && args !== null) {
			dialogCallback = ('callback' in args && typeof args.callback === 'function') ? args.callback : null;
			dialogOptions = ('options' in args && typeof args.options === 'object') ? args.options : {};
			resetEditFENDialog(args.fen, false, dialogOptions);
		}

		// Other cases
		else {
			dialogCallback = null;
			dialogOptions = {};
		}

		// Show the dialog
		$('#rpbchessboard-editFENDialog').dialog('open');
	};



	// ---------------------------------------------------------------------------
	// Text processing methods.
	// ---------------------------------------------------------------------------

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
		if('csl' in options && options.csl !== '') {
			res += ' csl=' + options.csl;
		}
		if('cal' in options && options.cal !== '') {
			res += ' cal=' + options.cal;
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

/* exported RPBChessboard */
