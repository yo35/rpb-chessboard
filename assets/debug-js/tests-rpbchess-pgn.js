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
/* global dumpPGNItem */
/* global test */
/* global testError */
/* global registerTest */
/* global registerTests */



// -----------------------------------------------------------------------------
// PGN samples
// -----------------------------------------------------------------------------

var pgns = [];

pgns.push({
	label: 'empty', gameCount: 0,
	pgn: '', dump: ''
});

pgns.push({
	label: '1', gameCount: 1,
	pgn:
		'[White "Player 1"]\n' +
		'[Black "Player 2"]\n' +
		'[Result "*"]\n' +
		'*',
	dump: '\n' +
		'Black = {Player 2}\n' +
		'Result = {*}\n' +
		'White = {Player 1}\n' +
		'-+<LONG\n' +
		'{Line}\n'
});

pgns.push({
	label: '2', gameCount: 1,
	pgn:
		'[White "Bill Gates"]\n' +
		'[Black "Magnus Carlsen"]\n' +
		'[Result "0-1"]\n' +
		'1. e4 Nc6 2. Nf3 d5 3. Bd3 Nf6 4. exd5 Qxd5 5. Nc3 Qh5 6. O-O Bg4 ' +
		'7. h3 Ne5 8. hxg4 Nfxg4 9. Nxe5 Qh2# 0-1\n',
	dump: '\n' +
		'Black = {Magnus Carlsen}\n' +
		'Result = {0-1}\n' +
		'White = {Bill Gates}\n' +
		'-+<LONG\n' +
		'(1w) e4\n' +
		'(1b) Nc6\n' +
		'(2w) Nf3\n' +
		'(2b) d5\n' +
		'(3w) Bd3\n' +
		'(3b) Nf6\n' +
		'(4w) exd5\n' +
		'(4b) Qxd5\n' +
		'(5w) Nc3\n' +
		'(5b) Qh5\n' +
		'(6w) O-O\n' +
		'(6b) Bg4\n' +
		'(7w) h3\n' +
		'(7b) Ne5\n' +
		'(8w) hxg4\n' +
		'(8b) Nfxg4\n' +
		'(9w) Nxe5\n' +
		'(9b) Qh2#\n' +
		'{Black wins}\n'
});

pgns.push({
	label: '3', gameCount: 2,
	pgn:
		'[Event "Scholar\'s mate"]\n' +
		'[Site "*"]\n' +
		'[Date "2013.??.??"]\n' +
		'[Round "?"]\n' +
		'[White "Player 1"]\n' +
		'[Black "Player 2"]\n' +
		'[Result "1-0"]\n' +
		'\n' +
		'1. e4 e5 2. Bc4 Nc6 3. Qh5 Nf6 4. Qxf7# 1-0\n' +
		'\n' +
		'[Event "Quickest checkmate"]\n' +
		'\n' +
		'1. f4 e6 2. g4 Qh4# 0-1\n',
	dump: '\n' +
		'Black = {Player 2}\n' +
		'Date = {2013.??.??}\n' +
		'Event = {Scholar\'s mate}\n' +
		'Result = {1-0}\n' +
		'Round = {?}\n' +
		'Site = {*}\n' +
		'White = {Player 1}\n' +
		'-+<LONG\n' +
		'(1w) e4\n' +
		'(1b) e5\n' +
		'(2w) Bc4\n' +
		'(2b) Nc6\n' +
		'(3w) Qh5\n' +
		'(3b) Nf6\n' +
		'(4w) Qxf7#\n' +
		'{White wins}\n' +
		'\n' +
		'Event = {Quickest checkmate}\n' +
		'-+<LONG\n' +
		'(1w) f4\n' +
		'(1b) e6\n' +
		'(2w) g4\n' +
		'(2b) Qh4#\n' +
		'{Black wins}\n'
});



// -----------------------------------------------------------------------------
// Parsing
// -----------------------------------------------------------------------------

registerTests('rpbchess.pgn.parse', pgns, function(scenario) {
	test('Parse PGN ' + scenario.label, function() {
		var pgnItems = RPBChess.pgn.parse(scenario.pgn);
		return '\n#' + pgnItems.length + '\n' + dumpPGNItems(pgnItems);
	}, '\n#' + scenario.gameCount + '\n' + scenario.dump);
});

registerTests('rpbchess.pgn.parseOne', pgns, function(scenario) {
	if(scenario.gameCount === 1) {
		test('Parse-one PGN ' + scenario.label, function() {
			var pgnItem = RPBChess.pgn.parseOne(scenario.pgn);
			return dumpPGNItem(pgnItem);
		}, scenario.dump);
	}
	else {
		testError('Parse-one PGN ' + scenario.label + ' (error)', function() {
			var pgnItem = RPBChess.pgn.parseOne(scenario.pgn);
			return dumpPGNItem(pgnItem);
		}, checkInvalidPGN(scenario.gameCount===0 ? 'PGN_TEXT_IS_EMPTY' : 'PGN_TEXT_CONTAINS_SEVERAL_GAMES'));
	}
});
