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
/* global legalInfo */
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



// -----------------------------------------------------------------------------
// Square color
// -----------------------------------------------------------------------------

registerTest('rpbchess.squarecolor', function() {
	var /* const */ ROW    = '12345678';
	var /* const */ COLUMN = 'abcdefgh';

	function fun(asExpected) {
		var res = '';
		for(var r=0; r<8; ++r) {
			for(var c=0; c<8; ++c) {
				if(res !== '') { res += '/'; }
				res += asExpected ? (c%2 === r%2 ? 'b' : 'w') : RPBChess.squareColor(COLUMN[c] + ROW[r]);
			}
		}
		return res;
	}

	test('Square color', function() { return fun(false); }, fun(true));
	testError('Square color NOK 1', function() { return RPBChess.squareColor('e9'); }, checkIllegalArgument('squareColor()'));
	testError('Square color NOK 2', function() { return RPBChess.squareColor('i5'); }, checkIllegalArgument('squareColor()'));
});



// -----------------------------------------------------------------------------
// Attacks
// -----------------------------------------------------------------------------

registerTest('rpbchess.attacks', function() {
	var /* const */ ROW    = '12345678';
	var /* const */ COLUMN = 'abcdefgh';

	// Return the list of attacked squares in the given position
	function attacked(position, byWho, byWhat) {
		var res = '';
		for(var r=0; r<8; ++r) {
			for(var c=0; c<8; ++c) {
				var square = COLUMN[c] + ROW[r];
				if(position.isAttacked(square, byWho, byWhat)) {
					if(res !== '') { res += '/'; }
					res += square;
				}
			}
		}
		return res;
	}

	test('King attacks 1', function() { var p=new RPBChess.Position('8/8/8/4K3/8/8/8/8 w - - 0 1'); return attacked(p, 'w'); }, 'd4/e4/f4/d5/f5/d6/e6/f6');
	test('King attacks 2', function() { var p=new RPBChess.Position('8/8/8/8/8/8/PPP5/K1P5 w - - 0 1'); return attacked(p, 'w', 'k'); }, 'b1/a2/b2');
	test('Queen attacks 1', function() { var p=new RPBChess.Position('8/8/8/4q3/8/8/8/8 w - - 0 1'); return attacked(p, 'b'); }, 'a1/e1/b2/e2/h2/c3/e3/g3/d4/e4/f4/a5/b5/c5/d5/f5/g5/h5/d6/e6/f6/c7/e7/g7/b8/e8/h8');
	test('Queen attacks 2', function() { var p=new RPBChess.Position('8/8/8/8/8/pppp4/3p4/q2p4 w - - 0 1'); return attacked(p, 'b', 'q'); }, 'b1/c1/d1/a2/b2/a3/c3');
	test('Rook attacks 1', function() { var p=new RPBChess.Position('8/8/8/4R3/8/8/8/8 w - - 0 1'); return attacked(p, 'w'); }, 'e1/e2/e3/e4/a5/b5/c5/d5/f5/g5/h5/e6/e7/e8');
	test('Rook attacks 2', function() { var p=new RPBChess.Position('8/8/8/8/8/PPPP4/3P4/R2P4 w - - 0 1'); return attacked(p, 'w', 'r'); }, 'b1/c1/d1/a2/a3');
	test('Bishop attacks 1', function() { var p=new RPBChess.Position('8/8/8/4b3/8/8/8/8 w - - 0 1'); return attacked(p, 'b'); }, 'a1/b2/h2/c3/g3/d4/f4/d6/f6/c7/g7/b8/h8');
	test('Bishop attacks 2', function() { var p=new RPBChess.Position('8/8/8/8/8/pppp4/3p4/b2p4 w - - 0 1'); return attacked(p, 'b', 'b'); }, 'b2/c3');
	test('Knight attacks 1', function() { var p=new RPBChess.Position('8/8/8/4N3/8/8/8/8 w - - 0 1'); return attacked(p, 'w'); }, 'd3/f3/c4/g4/c6/g6/d7/f7');
	test('Knight attacks 2', function() { var p=new RPBChess.Position('8/8/8/8/8/8/PPP5/NP6 w - - 0 1'); return attacked(p, 'w', 'n'); }, 'c2/b3');
	test('White pawn attacks', function() { var p=new RPBChess.Position('8/8/8/4P3/8/8/8/8 w - - 0 1'); return attacked(p, 'w'); }, 'd6/f6');
	test('Black pawn attacks', function() { var p=new RPBChess.Position('8/8/8/4p3/8/8/8/8 w - - 0 1'); return attacked(p, 'b'); }, 'd4/f4');
});



// -----------------------------------------------------------------------------
// Position legality
// -----------------------------------------------------------------------------

registerTest('rpbchess.islegal', function() {

	function fun(label, fen, expected) {
		test(label, function() { var p=new RPBChess.Position(fen); return legalInfo(p); }, expected);
	}

	fun('Legality starting position'       , 'start'                                         , 'true:e1:e8' );
	fun('Legality kings OK'                , 'k7/8/8/8/8/8/8/7K w - - 0 1'                   , 'true:h1:a8' );
	fun('Legality missing WK'              , '7k/8/8/8/8/8/8/8 w - - 0 1'                    , 'false:-:h8' );
	fun('Legality missing BK'              , '8/8/8/8/8/8/8/K7 w - - 0 1'                    , 'false:a1:-' );
	fun('Legality too many WK'             , '4k3/8/8/8/8/8/8/K6K w - - 0 1'                 , 'false:-:e8' );
	fun('Legality too many BK'             , 'k6k/8/8/8/8/8/8/4K3 w - - 0 1'                 , 'false:e1:-' );
	fun('Legality white is check 1'        , '4k3/8/8/8/8/2b5/8/4K3 w - - 0 1'               , 'true:e1:e8' );
	fun('Legality white is check 2'        , '4k3/8/8/8/8/2b5/8/4K3 b - - 0 1'               , 'false:e1:e8');
	fun('Legality black is check 1'        , '4k3/8/5N2/8/8/8/8/4K3 w - - 0 1'               , 'false:e1:e8');
	fun('Legality black is check 2'        , '4k3/8/5N2/8/8/8/8/4K3 b - - 0 1'               , 'true:e1:e8' );
	fun('Legality pawn on first/last row 1', '6p1/8/2k5/8/8/5K2/8/8 w - - 0 1'               , 'false:f3:c6');
	fun('Legality pawn on first/last row 2', '3P4/8/5k2/8/8/2K5/8/8 w - - 0 1'               , 'false:c3:f6');
	fun('Legality pawn on first/last row 3', '8/8/8/2k5/5K2/8/8/4p3 w - - 0 1'               , 'false:f4:c5');
	fun('Legality pawn on first/last row 4', '8/8/8/5k2/2K5/8/8/1P6 w - - 0 1'               , 'false:c4:f5');
	fun('Legality castling white 1'        , '8/4k3/8/8/8/8/8/R3K2R w KQ - 0 1'              , 'true:e1:e7' );
	fun('Legality castling white 2'        , '8/4k3/8/8/8/8/8/R3K3 w Q - 0 1'                , 'true:e1:e7' );
	fun('Legality castling white 3'        , '8/4k3/8/8/8/8/8/4K2R w K - 0 1'                , 'true:e1:e7' );
	fun('Legality castling white 4'        , '8/4k3/8/8/8/8/8/R3K3 w K - 0 1'                , 'false:e1:e7');
	fun('Legality castling white 5'        , '8/4k3/8/8/8/8/8/4K2R w Q - 0 1'                , 'false:e1:e7');
	fun('Legality castling white 6'        , '8/4k3/8/8/8/8/4K3/R7 w Q - 0 1'                , 'false:e2:e7');
	fun('Legality castling white 7'        , '8/4k3/8/8/8/8/4K3/7R w K - 0 1'                , 'false:e2:e7');
	fun('Legality castling black 1'        , 'r3k2r/8/8/8/8/8/4K3/8 w kq - 0 1'              , 'true:e2:e8' );
	fun('Legality castling black 2'        , 'r3k3/8/8/8/8/8/4K3/8 w q - 0 1'                , 'true:e2:e8' );
	fun('Legality castling black 3'        , '4k2r/8/8/8/8/8/4K3/8 w k - 0 1'                , 'true:e2:e8' );
	fun('Legality castling black 4'        , 'r3k3/8/8/8/8/8/4K3/8 w k - 0 1'                , 'false:e2:e8');
	fun('Legality castling black 5'        , '4k2r/8/8/8/8/8/4K3/8 w q - 0 1'                , 'false:e2:e8');
	fun('Legality castling black 6'        , 'r7/4k3/8/8/8/8/4K3/8 w q - 0 1'                , 'false:e2:e7');
	fun('Legality castling black 7'        , '7r/4k3/8/8/8/8/4K3/8 w k - 0 1'                , 'false:e2:e7');
	fun('Legality en-passant white 1'      , '4k3/pppppp1p/8/6p1/8/8/PPPPPPPP/4K3 w - g6 0 1', 'true:e1:e8' );
	fun('Legality en-passant white 2'      , '4k3/pppppp1p/8/6r1/8/8/PPPPPPPP/4K3 w - g6 0 1', 'false:e1:e8');
	fun('Legality en-passant white 3'      , '4k3/pppppp1p/6P1/6p1/8/8/8/4K3 w - g6 0 1'     , 'false:e1:e8');
	fun('Legality en-passant white 4'      , '4k3/ppppppPp/8/6p1/8/8/8/4K3 w - g6 0 1'       , 'false:e1:e8');
	fun('Legality en-passant black 1'      , '4k3/pppppppp/8/8/2P5/8/PP1PPPPP/4K3 b - c3 0 1', 'true:e1:e8' );
	fun('Legality en-passant black 2'      , '4k3/pppppppp/8/8/2B5/8/PP1PPPPP/4K3 b - c3 0 1', 'false:e1:e8');
	fun('Legality en-passant black 3'      , '4k3/8/8/8/2P5/2p5/8/4K3 b - c3 0 1'            , 'false:e1:e8');
	fun('Legality en-passant black 4'      , '4k3/8/8/8/2P5/8/2p5/4K3 b - c3 0 1'            , 'false:e1:e8');
});
