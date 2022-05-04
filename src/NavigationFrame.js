/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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


import React from 'react';
import ReactDOM from 'react-dom';
import { Chessboard } from 'kokopu-react';


/**
 * DOM node on which the board is rendered.
 */
let boardAnchor = false;


/**
 * Chessgame instance that is currently displaying something in the popup.
 */
let currentOwner = false;


/**
 * Lazy frame builder.
 */
function buildFrame() {
	if (boardAnchor) {
		return;
	}

	// Build the dialog skeleton
	let navigationFrame = document.createElement('div');
	navigationFrame.id = 'rpbchessboard-navigationFrame';
	boardAnchor = document.createElement('div');
	boardAnchor.appendChild(document.createTextNode('TODO')); // TODO
	navigationFrame.appendChild(boardAnchor);

	// Create the dialog widget.
	let $ = window.jQuery;
	$('body').append(navigationFrame);
	$('#rpbchessboard-navigationFrame').dialog({

		/* Hack to keep the dialog draggable after the page has being scrolled. */
		create     : evt => $(evt.target).parent().css('position', 'fixed'),
		resizeStart: evt => $(evt.target).parent().css('position', 'fixed'),
		resizeStop : evt => $(evt.target).parent().css('position', 'fixed'),
		/* End of hack */

		autoOpen: false,
		dialogClass: 'wp-dialog',
		width: 'auto',
		close: () => {
			if (currentOwner) {
				currentOwner.handleMoveSelected(undefined, 'external');
			}
		},
	});
}


/**
 * Make the popup frame visible (assuming it has been built beforehand).
 */
function openFrame() {
	let $ = window.jQuery;
	let navigationFrame = $('#rpbchessboard-navigationFrame');
	if (!navigationFrame.dialog('isOpen')) {
		navigationFrame.dialog('option', 'position', { my: 'center', at: 'center', of: window });
		navigationFrame.dialog('open');
	}
}


/**
 * Hide the popup frame (if it exists).
 */
function closeFrame() {
	if (!boardAnchor) {
		return;
	}
	let $ = window.jQuery;
	$('#rpbchessboard-navigationFrame').dialog('close');
}


export function showPopupBoard(owner) {
	buildFrame();
	if (currentOwner && currentOwner !== owner) {
		currentOwner.handleMoveSelected(undefined, 'external');
	}
	currentOwner = owner;
	ReactDOM.render(<Chessboard />, boardAnchor); // TODO set-up board properly
	openFrame();
}


export function hidePopupBoard(owner) {
	if (currentOwner === owner) {
		currentOwner = false;
		closeFrame();
	}
}
