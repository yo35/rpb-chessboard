/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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


/* jshint unused:false */
/* jshint globalstrict:true */
'use strict';

/* global RPBChess */
/* global registerException */


// Exception registration
registerException(RPBChess.exceptions.IllegalArgument, function(e) { return 'illegal argument in ' + e.fun; });
registerException(RPBChess.exceptions.InvalidFEN     , function(e) { return 'bad FEN >>' + e.fen + '<< => ' + e.message; });
registerException(RPBChess.exceptions.InvalidNotation, function(e) { return 'bad SAN >>' + e.notation + '<< => ' + e.message; });


/**
 * Generate a predicate to check that an exception is an illegal-argument exception issued by the right function.
 *
 * @param {string} fun
 * @returns {function}
 */
function checkIllegalArgument(fun) {
	return function(e) { return (e instanceof RPBChess.exceptions.IllegalArgument) && (e.fun === fun); };
}


/**
 * Generate a predicate to check that an exception is an invalid-FEN exception with the right message.
 *
 * @param {string} code
 * @param ...
 * @returns {function}
 */
function checkInvalidFEN(code) {

	// Build the error message
	var message = '<not an error message>';
	if(typeof code === 'undefined') {
		message = null;
	}
	else if(code in RPBChess.i18n) {
		message = RPBChess.i18n[code];
		for(var i=1; i<arguments.length; ++i) {
			var re = new RegExp('%' + i + '\\$s');
			message = message.replace(re, arguments[i]);
		}
	}

	// Generate the predicate
	return function(e) {
		return (e instanceof RPBChess.exceptions.InvalidFEN) && (message === null || message === e.message);
	};
}


/**
 * Convert a colored-piece object returned by the {@link Position#board()} getter into a string.
 *
 * @param {object} cp
 * @returns {string}
 */
function wrapCP(cp) {
	return (typeof cp === 'object') && ('piece' in cp) && ('color' in cp) ? (cp.color + ':' + cp.piece) : cp;
}


/**
 * Convert a move descriptor into a string.
 *
 * @param {RPBChess.MoveDescriptor} descriptor
 * @returns {string}
 */
function wrapMove(descriptor) {
	return descriptor.from() + descriptor.to() + (descriptor.type()===RPBChess.movetype.PROMOTION ? descriptor.promotion().toUpperCase() : '');
}


/**
 * Concatenate the legal flag and the king squares into a string.
 *
 * @param {RPBChess.Position} position
 * @returns {string}
 */
function legalInfo(position) {
	return position.isLegal() + ':' + position.kingSquare('w') + ':' + position.kingSquare('b');
}


/**
 * Concatenate the FEN string describing the position with its legal flag and king squares.
 *
 * @param {RPBChess.Position} position
 * @returns {string}
 */
function fenInfo(position) {
	return position.fen() + ' ' + position.isLegal() + ' ' + position.kingSquare('w') + ' ' + position.kingSquare('b');
}


/**
 * Concatenate the legal and the null-move-legal flags into a string
 *
 * @param {RPBChess.Position} position
 * @returns {string}
 */
function nullMoveInfo(position) {
	return position.isLegal() + ':' + position.isNullMoveLegal();
}


/**
 * Concatenate the check/checkmate/stalemate flags into a string.
 *
 * @param {RPBChess.Position} position
 * @returns {string}
 */
function ccsInfo(position) {
	if(position.isLegal()) {
		return position.isCheck() + ':' + position.isCheckmate() + ':' + position.isStalemate() + ':' + position.hasMove();
	}
	else {
		return '';
	}
}
