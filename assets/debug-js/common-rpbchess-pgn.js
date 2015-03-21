/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
registerException(RPBChess.exceptions.InvalidPGN, function(e) { return 'bad PGN >>' + e.pgn + '<< => ' + e.message; });


/**
 * Convert a game result code into a human-readable string.
 *
 * @param {number} gameResult
 * @returns {string}
 */
function wrapGameResult(gameResult) {
	switch(gameResult) {
		case RPBChess.pgn.gameresult.WHITE_WINS: return '1-0';
		case RPBChess.pgn.gameresult.DRAW      : return '1/2-1/2';
		case RPBChess.pgn.gameresult.BLACK_WINS: return '0-1';
		case RPBChess.pgn.gameresult.LINE      : return '*';
		default: return '&lt;unknown-result&gt;';
	}
}


/**
 * Dump the content of an item read from a `.pgn` file.
 *
 * @param {RPBChess.pgn.Item} pgnItem
 * @returns {string}
 */
function dumpPGNItem(pgnItem) {
	var res = '\n';

	// Dump the headers.
	var headers = pgnItem.headers();
	headers.sort();
	for(var k=0; k<headers.length; ++k) {
		var key  = headers[k];
		res += key + ' = {' + pgnItem.header(key) + '}\n';
	}

	// TODO: dump the moves

	// Dump the result.
	res += '{' + wrapGameResult(pgnItem.result()) + '}\n';

	return res;
}
