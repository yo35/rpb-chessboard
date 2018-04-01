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

'use strict';

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

pgns.push({
	label: '4', gameCount: 1,
	pgn:
		'[Event "Game with comments and NAGs"]\n' +
		'\n' +
		'{A comment with braces: ab\\{cd\\}ef\\\\gh\\ij 3 backslashes: \\\\\\\\\\\\}\n' +
		'1. e4 ! e6 {This opening is called the French.} 2. d4 +-\n' +
		'{   I\'m joking of course!   } *\n',
	dump: '\n' +
		'Event = {Game with comments and NAGs}\n' +
		'-+<LONG {A comment with braces: ab{cd}ef\\gh\\ij 3 backslashes: \\\\\\}<LONG\n' +
		'(1w) e4 $1\n' +
		'(1b) e6 {This opening is called the French.}\n' +
		'(2w) d4 $18 {I\'m joking of course!}\n' +
		'{Line}\n'
});

pgns.push({
	label: '5', gameCount: 1,
	pgn:
		'[Event "Game with variations"]\n' +
		'\n' +
		'1.e4 e5 (1...e6 +-) 2.Nf3 Nc6 3.Bb5 ({Italian game:} 3.Bc4 Bc5)\n' +
		'(3.d4 exd4 4.Nxd4 ($42 4.Bc4 !? Nf6 inf) 4...Bc5) 3...a6 4.Bxc6 dxc6 1/2-1/2\n',
	dump: '\n' +
		'Event = {Game with variations}\n' +
		'-+<LONG\n' +
		'(1w) e4\n' +
		'(1b) e5\n' +
		' |\n' +
		' +---+\n' +
		' |  (1b) e6 $18\n' +
		' |\n' +
		'(2w) Nf3\n' +
		'(2b) Nc6\n' +
		'(3w) Bb5\n' +
		' |\n' +
		' +---+ {Italian game:}\n' +
		' |  (3w) Bc4\n' +
		' |  (3b) Bc5\n' +
		' |\n' +
		' +---+\n' +
		' |  (3w) d4\n' +
		' |  (3b) exd4\n' +
		' |  (4w) Nxd4\n' +
		' |   |\n' +
		' |   +---+ $42\n' +
		' |   |  (4w) Bc4 $5\n' +
		' |   |  (4b) Nf6 $13\n' +
		' |   |\n' +
		' |  (4b) Bc5\n' +
		' |\n' +
		'(3b) a6\n' +
		'(4w) Bxc6\n' +
		'(4b) dxc6\n' +
		'{Draw}\n'
});

pgns.push({
	label: '6', gameCount: 1,
	pgn:
		'[Event "1<sup>st</sup> American Chess Congress"]\n' +
		'[Site "New York, NY USA"]\n' +
		'[Date "1857.11.03"]\n' +
		'[Round "4.6"]\n' +
		'[White "Paulsen, Louis"]\n' +
		'[Black "Morphy, Paul"]\n' +
		'[Result "0-1"]\n' +
		'\n' +
		'1. e4 e5 2. Nf3 Nc6 3. Nc3 Nf6 4. Bb5 Bc5 5. O-O O-O 6. Nxe5 Re8 7. Nxc6 dxc6\n' +
		'8. Bc4 b5 9. Be2 Nxe4 10. Nxe4 Rxe4 11. Bf3 Re6 12. c3 Qd3 13. b4 Bb6 14. a4 bxa4\n' +
		'15. Qxa4 Bd7 16. Ra2 Rae8 17. Qa6\n' +
		'\n' +
		'{Morphy took twelve minutes over his next move, probably to assure himself ' +
		'that the combination was sound and that he had a forced win in every variation.}\n' +
		'\n' +
		'17... Qxf3 $3 18. gxf3 Rg6+ 19. Kh1 Bh3 20. Rd1' +
		'({Not} 20. Rg1 Rxg1+ 21. Kxg1 Re1+ $19)\n' +
		'20... Bg2+ 21. Kg1 Bxf3+ 22. Kf1 Bg2+\n' +
		'\n' +
		'(22...Rg2 $1 {would have won more quickly. For instance:} 23. Qd3 Rxf2+ 24. Kg1 Rg2+ 25. Kh1 Rg1#)\n' +
		'\n' +
		'23. Kg1 Bh3+ 24. Kh1 Bxf2 25. Qf1 {Absolutely forced.} 25... Bxf1 26. Rxf1 Re2\n' +
		'27. Ra1 Rh6 28. d4 Be3 0-1',
	dump: '\n' +
		'Black = {Morphy, Paul}\n' +
		'Date = {1857.11.03}\n' +
		'Event = {1<sup>st</sup> American Chess Congress}\n' +
		'Result = {0-1}\n' +
		'Round = {4.6}\n' +
		'Site = {New York, NY USA}\n' +
		'White = {Paulsen, Louis}\n' +
		'-+<LONG\n' +
		'(1w) e4\n' +
		'(1b) e5\n' +
		'(2w) Nf3\n' +
		'(2b) Nc6\n' +
		'(3w) Nc3\n' +
		'(3b) Nf6\n' +
		'(4w) Bb5\n' +
		'(4b) Bc5\n' +
		'(5w) O-O\n' +
		'(5b) O-O\n' +
		'(6w) Nxe5\n' +
		'(6b) Re8\n' +
		'(7w) Nxc6\n' +
		'(7b) dxc6\n' +
		'(8w) Bc4\n' +
		'(8b) b5\n' +
		'(9w) Be2\n' +
		'(9b) Nxe4\n' +
		'(10w) Nxe4\n' +
		'(10b) Rxe4\n' +
		'(11w) Bf3\n' +
		'(11b) Re6\n' +
		'(12w) c3\n' +
		'(12b) Qd3\n' +
		'(13w) b4\n' +
		'(13b) Bb6\n' +
		'(14w) a4\n' +
		'(14b) bxa4\n' +
		'(15w) Qxa4\n' +
		'(15b) Bd7\n' +
		'(16w) Ra2\n' +
		'(16b) Rae8\n' +
		'(17w) Qa6 {Morphy took twelve minutes over his next move, probably to assure himself that the combination was sound and that he had a forced win in every variation.}<LONG\n' +
		'(17b) Qxf3 $3\n' +
		'(18w) gxf3\n' +
		'(18b) Rg6+\n' +
		'(19w) Kh1\n' +
		'(19b) Bh3\n' +
		'(20w) Rd1\n' +
		' |\n' +
		' +---+ {Not}\n' +
		' |  (20w) Rg1\n' +
		' |  (20b) Rxg1+\n' +
		' |  (21w) Kxg1\n' +
		' |  (21b) Re1+ $19\n' +
		' |\n' +
		'(20b) Bg2+\n' +
		'(21w) Kg1\n' +
		'(21b) Bxf3+\n' +
		'(22w) Kf1\n' +
		'(22b) Bg2+\n' +
		' |\n' +
		' +---+<LONG\n' +
		' |  (22b) Rg2 $1 {would have won more quickly. For instance:}\n' +
		' |  (23w) Qd3\n' +
		' |  (23b) Rxf2+\n' +
		' |  (24w) Kg1\n' +
		' |  (24b) Rg2+\n' +
		' |  (25w) Kh1\n' +
		' |  (25b) Rg1#\n' +
		' |\n' +
		'(23w) Kg1\n' +
		'(23b) Bh3+\n' +
		'(24w) Kh1\n' +
		'(24b) Bxf2\n' +
		'(25w) Qf1 {Absolutely forced.}\n' +
		'(25b) Bxf1\n' +
		'(26w) Rxf1\n' +
		'(26b) Re2\n' +
		'(27w) Ra1\n' +
		'(27b) Rh6\n' +
		'(28w) d4\n' +
		'(28b) Be3\n' +
		'{Black wins}\n'
});

pgns.push({
	label: '7', gameCount: 1,
	pgn:
		'[Result "*"]\n' +
		'[Annotator "gokhan007"]\n' +
		'[FEN "r2q1b1r/ppn2kpp/2p1b3/3nP3/2BPQ3/P1N5/1PP3PP/R1B1K2R w KQ - 1 14"]\n' +
		'[SetUp "1"]\n' +
		'14. O-O+ Ke8 *',
	dump: '\n' +
		'Annotator = {gokhan007}\n' +
		'FEN = {r2q1b1r/ppn2kpp/2p1b3/3nP3/2BPQ3/P1N5/1PP3PP/R1B1K2R w KQ - 1 14}\n' +
		'Result = {*}\n' +
		'SetUp = {1}\n' +
		'-+<LONG\n' +
		'(14w) O-O+\n' +
		'(14b) Ke8\n' +
		'{Line}\n'
});

pgns.push({
	label: '8', gameCount: 1,
	pgn:
		'[Event "Game with long/short comment descriptors"]\n' +
		'{No empty line => SHORT.}\n' +
		'1.e4 e5 (\n' +
		'\n' +
		'{Within short variation, always SHORT.}\n' +
		'\n' +
		'1...e6 +-\n' +
		'\n' +
		'{Within short variation, always SHORT.}\n' +
		'\n' +
		') 2.Nf3 Nc6 3.Bb5\n' +
		'\n' +
		'({No empty line => SHORT.} 3.Bc4 Bc5 {No empty line => SHORT.})\n' +
		'\n' +
		'3...a6 4.Bxc6\n' +
		'\n' +
		'(\n' +
		'\n' +
		'{Empty line => LONG.}\n' +
		'\n' +
		'4. Ba4 b5\n' +
		'\n' +
		'{Empty line => LONG.}\n' +
		'\n' +
		')\n' +
		'\n' +
		'4... dxc6 1/2-1/2',
	dump: '\n' +
		'Event = {Game with long/short comment descriptors}\n' +
		'-+<LONG {No empty line => SHORT.}\n' +
		'(1w) e4\n' +
		'(1b) e5\n' +
		' |\n' +
		' +---+ {Within short variation, always SHORT.}\n' +
		' |  (1b) e6 $18 {Within short variation, always SHORT.}\n' +
		' |\n' +
		'(2w) Nf3\n' +
		'(2b) Nc6\n' +
		'(3w) Bb5\n' +
		' |\n' +
		' +---+<LONG {No empty line => SHORT.}\n' +
		' |  (3w) Bc4\n' +
		' |  (3b) Bc5 {No empty line => SHORT.}\n' +
		' |\n' +
		'(3b) a6\n' +
		'(4w) Bxc6\n' +
		' |\n' +
		' +---+<LONG {Empty line => LONG.}<LONG\n' +
		' |  (4w) Ba4\n' +
		' |  (4b) b5 {Empty line => LONG.}<LONG\n' +
		' |\n' +
		'(4b) dxc6\n' +
		'{Draw}\n'
});

pgns.push({
	label: '9', gameCount: 1,
	pgn:
		'[Event "Game with null-moves"]\n' +
		'[Result "*"]\n' +
		'1.e4 -- 2.d4 d5 3.-- e5 *',
	dump: '\n' +
		'Event = {Game with null-moves}\n' +
		'Result = {*}\n' +
		'-+<LONG\n' +
		'(1w) e4\n' +
		'(1b) --\n' +
		'(2w) d4\n' +
		'(2b) d5\n' +
		'(3w) --\n' +
		'(3b) e5\n' +
		'{Line}\n'
});

pgns.push({
	label: '10', gameCount: 1,
	pgn:
		'[Event "Game with tags"]\n' +
		'1.e4 {[%cal Ge2e4]} 1...h5 {[%cal Gh7h5] Odd move:    the pawn in h5 [%csl Rh5] is weak.}\n' +
		'({[%key value] More usual is} 1...c5 { })\n' +
		'2.d4 {[%key value1] Cannot have several tags with the same key here! [%key value2]}\n' +
		'2...a5 {No tag here.} *',
	dump: '\n' +
		'Event = {Game with tags}\n' +
		'-+<LONG\n' +
		'(1w) e4 [cal = {Ge2e4}]\n' +
		'(1b) h5 [cal = {Gh7h5}] [csl = {Rh5}] {Odd move: the pawn in h5 is weak.}\n' +
		' |\n' +
		' +---+ [key = {value}] {More usual is}\n' +
		' |  (1b) c5\n' +
		' |\n' +
		'(2w) d4 [key = {value2}] {Cannot have several tags with the same key here!}\n' +
		'(2b) a5 {No tag here.}\n' +
		'{Line}\n'
});


// -----------------------------------------------------------------------------
// Parsing
// -----------------------------------------------------------------------------

registerTests( 'rpbchess.pgn.parse', pgns, function( scenario ) {
	test( 'Parse PGN ' + scenario.label, function() {
		var pgnItems = RPBChess.pgn.parse( scenario.pgn );
		return '\n#' + pgnItems.length + '\n' + dumpPGNItems( pgnItems );
	}, '\n#' + scenario.gameCount + '\n' + scenario.dump );
});

registerTests( 'rpbchess.pgn.parseOne', pgns, function( scenario ) {
	if ( 1 === scenario.gameCount ) {
		test( 'Parse-one PGN ' + scenario.label, function() {
			var pgnItem = RPBChess.pgn.parseOne( scenario.pgn );
			return dumpPGNItem( pgnItem );
		}, scenario.dump );
	} else {
		testError( 'Parse-one PGN ' + scenario.label + ' (error)', function() {
			var pgnItem = RPBChess.pgn.parseOne( scenario.pgn );
			return dumpPGNItem( pgnItem );
		}, checkInvalidPGN( 0 === scenario.gameCount ? 'PGN_TEXT_IS_EMPTY' : 'PGN_TEXT_CONTAINS_SEVERAL_GAMES' ) );
	}
});
