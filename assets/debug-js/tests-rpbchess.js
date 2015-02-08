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
/* global checkIllegalArgument */
/* global checkInvalidFEN */
/* global wrapCP */
/* global test */
/* global testError */
/* global registerTest */



// -----------------------------------------------------------------------------
// Basic tests
// -----------------------------------------------------------------------------

// Constructor
registerTest('rpbchess.basic.constructor', function() {

	var startFEN   = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
	var emptyFEN   = '8/8/8/8/8/8/8/8 w - - 0 1';
	var customFEN1 = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b Kk e3 10 5';
	var customFEN2 = 'k7/n1PB4/1K6/8/8/8/8/8 w - - 0 60';

	var optsFEN1 = { fiftyMoveClock: 10, fullMoveNumber: 5 };
	var optsFEN2 = { fullMoveNumber: 60 };

	test('Default constructor'    , function() { return (new RPBChess.Position()).fen(); }, startFEN);
	test('Constructor \'start\''  , function() { return (new RPBChess.Position('start')).fen(); }, startFEN);
	test('Constructor \'empty\''  , function() { return (new RPBChess.Position('empty')).fen(); }, emptyFEN);
	test('Constructor FEN-based 1', function() { return (new RPBChess.Position(customFEN1)).fen(optsFEN1); }, customFEN1);
	test('Constructor FEN-based 2', function() { return (new RPBChess.Position(customFEN2)).fen(optsFEN2); }, customFEN2);

	test('Copy constructor', function() {
		var p1 = new RPBChess.Position(customFEN1);
		var p2 = new RPBChess.Position(p1);
		p1.clear();
		return p1.fen() + '|' + p2.fen(optsFEN1);
	}, emptyFEN + '|' + customFEN1);
});


// Strict FEN parsing
registerTest('rpbchess.basic.strictfen', function() {

	var customFEN1 = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b Kk e3 10 5';
	var customFEN2 = 'k7/n1PB4/1K6/8/8/8/8/8 w - - 0 60';

	var optsFEN1 = { fiftyMoveClock: 10, fullMoveNumber: 5 };
	var optsFEN2 = { fullMoveNumber: 60 };

	var customFEN3  = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b Kq e3 0 1';
	var customFEN3a = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b qK e3 0 1';
	var customFEN3b = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b Kq e6 0 1';
	var customFEN3c = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b Kq e3 00 1';

	test('Set FEN (tolerant) A', function() { var p=new RPBChess.Position(); p.fen(customFEN3a); return p.fen(); }, customFEN3);
	test('Set FEN (tolerant) B', function() { var p=new RPBChess.Position(); p.fen(customFEN3b); return p.fen(); }, customFEN3);
	test('Set FEN (tolerant) C', function() { var p=new RPBChess.Position(); p.fen(customFEN3c); return p.fen(); }, customFEN3);
	test('Set FEN (strict) OK 1', function() { var p=new RPBChess.Position(); p.fen(customFEN1, true); return p.fen(optsFEN1); }, customFEN1);
	test('Set FEN (strict) OK 2', function() { var p=new RPBChess.Position(); p.fen(customFEN2, true); return p.fen(optsFEN2); }, customFEN2);
	test('Set FEN (strict) OK 3', function() { var p=new RPBChess.Position(); p.fen(customFEN3, true); return p.fen(); }, customFEN3);
	testError('Set FEN (strict) NOK A', function() { var p=new RPBChess.Position(); p.fen(customFEN3a, true); }, checkInvalidFEN('INVALID_CASTLE_RIGHTS_FIELD'));
	testError('Set FEN (strict) NOK B', function() { var p=new RPBChess.Position(); p.fen(customFEN3b, true); }, checkInvalidFEN('WRONG_ROW_IN_EN_PASSANT_FIELD'));
	testError('Set FEN (strict) NOK C', function() { var p=new RPBChess.Position(); p.fen(customFEN3c, true); }, checkInvalidFEN('INVALID_MOVE_COUNTING_FIELD', '5th'));
});


// Getters
registerTest('rpbchess.basic.getters', function() {

	var customFEN = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b Kk e3 0 1';

	test('Getter board 1', function() { var p=new RPBChess.Position(); return wrapCP(p.square('e1')); }, 'w:k');
	test('Getter board 2', function() { var p=new RPBChess.Position(); return wrapCP(p.square('b4')); }, '-');
	test('Getter turn 1', function() { var p=new RPBChess.Position(); return p.turn(); }, 'w');
	test('Getter turn 2', function() { var p=new RPBChess.Position(customFEN); return p.turn(); }, 'b');
	test('Getter castling 1', function() { var p=new RPBChess.Position(); return p.castleRights('w', 'q'); }, true);
	test('Getter castling 2', function() { var p=new RPBChess.Position(customFEN); return p.castleRights('b', 'q'); }, false);
	test('Getter castling 3', function() { var p=new RPBChess.Position(customFEN); return p.castleRights('b', 'k'); }, true);
	test('Getter en-passant 1', function() { var p=new RPBChess.Position(); return p.enPassant(); }, '-');
	test('Getter en-passant 2', function() { var p=new RPBChess.Position(customFEN); return p.enPassant(); }, 'e');
	testError('Getter board NOK'   , function() { var p=new RPBChess.Position(); return wrapCP(p.square('j1')); }, checkIllegalArgument('Position#square()'));
	testError('Getter castling NOK', function() { var p=new RPBChess.Position(); return p.castleRights('b', 'K'); }, checkIllegalArgument('Position#castleRights()'));
});


// Setters
registerTest('rpbchess.basic.setters', function() {

	var pos1 = new RPBChess.Position('start');
	var pos2 = new RPBChess.Position('empty');

	// Setters
	test('Setter board 1a', function() { pos1.square('a8', '-'); return pos1.fen(); }, '1nbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
	test('Setter board 1b', function() { pos1.square('f6', {color:'w', piece:'b'}); return pos1.fen(); }, '1nbqkbnr/pppppppp/5B2/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
	test('Setter board 2a', function() { pos2.square('c3', {color:'b', piece:'k'}); return pos2.fen(); }, '8/8/8/8/8/2k5/8/8 w - - 0 1');
	test('Setter board 2b', function() { pos2.square('g5', {color:'w', piece:'k'}); return pos2.fen(); }, '8/8/8/6K1/8/2k5/8/8 w - - 0 1');
	test('Setter board 2c', function() { pos2.square('c3', '-'); return pos2.fen(); }, '8/8/8/6K1/8/8/8/8 w - - 0 1');
	test('Setter turn 1', function() { pos1.turn('w'); return pos1.fen(); }, '1nbqkbnr/pppppppp/5B2/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
	test('Setter turn 2', function() { pos2.turn('b'); return pos2.fen(); }, '8/8/8/6K1/8/8/8/8 b - - 0 1');
	test('Setter castling 1a', function() { pos1.castleRights('w', 'k', false); return pos1.fen(); }, '1nbqkbnr/pppppppp/5B2/8/8/8/PPPPPPPP/RNBQKBNR w Qkq - 0 1');
	test('Setter castling 1b', function() { pos1.castleRights('b', 'k', true); return pos1.fen(); }, '1nbqkbnr/pppppppp/5B2/8/8/8/PPPPPPPP/RNBQKBNR w Qkq - 0 1');
	test('Setter castling 2a', function() { pos2.castleRights('w', 'q', false); return pos2.fen(); }, '8/8/8/6K1/8/8/8/8 b - - 0 1');
	test('Setter castling 2b', function() { pos2.castleRights('b', 'q', true); return pos2.fen(); }, '8/8/8/6K1/8/8/8/8 b q - 0 1');
	test('Setter en-passant 1a', function() { pos1.enPassant('e'); return pos1.fen(); }, '1nbqkbnr/pppppppp/5B2/8/8/8/PPPPPPPP/RNBQKBNR w Qkq e6 0 1');
	test('Setter en-passant 1b', function() { pos1.enPassant('-'); return pos1.fen(); }, '1nbqkbnr/pppppppp/5B2/8/8/8/PPPPPPPP/RNBQKBNR w Qkq - 0 1');
	test('Setter en-passant 2a', function() { pos2.enPassant('a'); return pos2.fen(); }, '8/8/8/6K1/8/8/8/8 b q a3 0 1');
	test('Setter en-passant 2b', function() { pos2.enPassant('h'); return pos2.fen(); }, '8/8/8/6K1/8/8/8/8 b q h3 0 1');
});
