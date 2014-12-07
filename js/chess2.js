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


var Chess2 = {};


/**
 * Basic chess tools. TODO
 *
 * @namespace Chess2
 */
(function(myself)
{
	'use strict';


	// ---------------------------------------------------------------------------
	// Internationalization
	// ---------------------------------------------------------------------------

	myself.i18n = {};

	// Ordinal integers (from 1 to 8).
	myself.i18n.ORDINALS = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

	// FEN parsing error messages
	myself.i18n.WRONG_NUMBER_OF_FEN_FIELDS                = 'A FEN string must contain exactly 6 space-separated fields.';
	myself.i18n.WRONG_NUMBER_OF_SUBFIELDS_IN_BOARD_FIELD  = 'The 1st field of a FEN string must contain exactly 8 `/`-separated subfields.';
	myself.i18n.UNEXPECTED_CHARACTER_IN_BOARD_FIELD       = 'Unexpected character in the 1st field of the FEN string: `{1}`.';
	myself.i18n.UNEXPECTED_END_OF_SUBFIELD_IN_BOARD_FIELD = 'The {1} subfield of the FEN string 1st field is unexpectedly short.';
	myself.i18n.INVALID_TURN_FIELD                        = 'The 2nd field of a FEN string must be either `w` or `b`.';
	myself.i18n.INVALID_CASTLE_RIGHTS_FIELD               = 'The 3rd field of a FEN string must be either `-` or a list of characters among `K`, `Q`, `k` and `q` (in this order).';
	myself.i18n.INVALID_EN_PASSANT_FIELD                  = 'The 4th field of a FEN string must be either `-` or a square from the 3rd or 6th row where en-passant is allowed.';
	myself.i18n.WRONG_ROW_IN_EN_PASSANT_FIELD             = 'The row number indicated in the FEN string 4th field is inconsistent with respect to the 2nd field.';
	myself.i18n.INVALID_MOVE_COUNTING_FIELD               = 'The {1} field of a FEN string must be a number.';



	// ---------------------------------------------------------------------------
	// Exceptions
	// ---------------------------------------------------------------------------

	myself.exceptions = {};


	/**
	 * @constructor
	 * @alias IllegalArgument
	 * @memberof Chess2
	 *
	 * @classdesc
	 * Exception thrown when an invalid argument is passed to a function.
	 *
	 * @param {string} fun
	 */
	myself.exceptions.IllegalArgument = function(fun) {
		this.fun = fun;
	};


	/**
	 * @constructor
	 * @alias InvalidFEN
	 * @memberof Chess2
	 *
	 * @classdesc
	 * Exception thrown by the FEN parsing function.
	 *
	 * @param {string} fen String whose parsing leads to an error.
	 * @param {string} message Human-readable error message.
	 * @param ...
	 */
	myself.exceptions.InvalidFEN = function(fen, message) {
		this.fen     = fen    ;
		this.message = message;
		for(var i=2; i<arguments.length; ++i) {
			var re = new RegExp('\\{' + (i-1) + '\\}');
			this.message = this.message.replace(re, arguments[i]);
		}
	};



	// ---------------------------------------------------------------------------
	// Internal constants and helper methods
	// ---------------------------------------------------------------------------

	// Colors
	var /* const */ WHITE = 0;
	var /* const */ BLACK = 1;

	// Special square values
	var /* const */ EMPTY   = -1;
	var /* const */ INVALID = -2;

	// Pieces
	var /* const */ KING   = 0;
	var /* const */ QUEEN  = 1;
	var /* const */ ROOK   = 2;
	var /* const */ BISHOP = 3;
	var /* const */ KNIGHT = 4;
	var /* const */ PAWN   = 5;

	// Colored pieces
	var /* const */ WK =  0; var /* const */ BK =  1;
	var /* const */ WQ =  2; var /* const */ BQ =  3;
	var /* const */ WR =  4; var /* const */ BR =  5;
	var /* const */ WB =  6; var /* const */ BB =  7;
	var /* const */ WN =  8; var /* const */ BN =  9;
	var /* const */ WP = 10; var /* const */ BP = 11;

	// Whether the given colored piece is sliding or not.
	function isSliding(coloredPiece) {
		return coloredPiece>=2 && coloredPiece<=7;
	}

	// Attack directions per colored piece.
	var /* const */ ATTACK_DIRECTIONS = [
		[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
		[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
		[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
		[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
		[-16, -1, 1, 16], // rook
		[-16, -1, 1, 16], // rook
		[-17, -15, 15, 17], // bishop
		[-17, -15, 15, 17], // bishop
		[-33, -31, -18, -14, 14, 18, 31, 33], // knight
		[-33, -31, -18, -14, 14, 18, 31, 33], // knight
		[15, 17], // white pawn
		[-17, -15] // black pawn
	];

	// Displacement lookup per square index difference.
	var /* const */ DISPLACEMENT_LOOKUP = [
	 204,    0,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,    0,  204,    0,
	   0,  204,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,  204,    0,    0,
	   0,    0,  204,    0,    0,    0,    0,   60,    0,    0,    0,    0,  204,    0,    0,    0,
	   0,    0,    0,  204,    0,    0,    0,   60,    0,    0,    0,  204,    0,    0,    0,    0,
	   0,    0,    0,    0,  204,    0,    0,   60,    0,    0,  204,    0,    0,    0,    0,    0,
	   0,    0,    0,    0,    0,  204,  768,   60,  768,  204,    0,    0,    0,    0,    0,    0,
	   0,    0,    0,    0,    0,  768, 2255, 2111, 2255,  768,    0,    0,    0,    0,    0,    0,
	  60,   60,   60,   60,   60,   60,   63,    0,   63,   60,   60,   60,   60,   60,   60,    0,
	   0,    0,    0,    0,    0,  768, 1231, 1087, 1231,  768,    0,    0,    0,    0,    0,    0,
	   0,    0,    0,    0,    0,  204,  768,   60,  768,  204,    0,    0,    0,    0,    0,    0,
	   0,    0,    0,    0,  204,    0,    0,   60,    0,    0,  204,    0,    0,    0,    0,    0,
	   0,    0,    0,  204,    0,    0,    0,   60,    0,    0,    0,  204,    0,    0,    0,    0,
	   0,    0,  204,    0,    0,    0,    0,   60,    0,    0,    0,    0,  204,    0,    0,    0,
	   0,  204,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,  204,    0,    0,
	 204,    0,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,    0,  204,    0
	];

	// Sliding direction
	var /* const */ SLIDING_DIRECTION = [
		-17,   0,   0,   0,   0,   0,   0, -16,   0,   0,   0,   0,   0,   0, -15,   0,
		  0, -17,   0,   0,   0,   0,   0, -16,   0,   0,   0,   0,   0, -15,   0,   0,
		  0,   0, -17,   0,   0,   0,   0, -16,   0,   0,   0,   0, -15,   0,   0,   0,
		  0,   0,   0, -17,   0,   0,   0, -16,   0,   0,   0, -15,   0,   0,   0,   0,
		  0,   0,   0,   0, -17,   0,   0, -16,   0,   0, -15,   0,   0,   0,   0,   0,
		  0,   0,   0,   0,   0, -17,   0, -16,   0, -15,   0,   0,   0,   0,   0,   0,
		  0,   0,   0,   0,   0,   0, -17, -16, -15,   0,   0,   0,   0,   0,   0,   0,
		 -1,  -1,  -1,  -1,  -1,  -1,  -1,   0,   1,   1,   1,   1,   1,   1,   1,   0,
		  0,   0,   0,   0,   0,   0,  15,  16,  17,   0,   0,   0,   0,   0,   0,   0,
		  0,   0,   0,   0,   0,  15,   0,  16,   0,  17,   0,   0,   0,   0,   0,   0,
		  0,   0,   0,   0,  15,   0,   0,  16,   0,   0,  17,   0,   0,   0,   0,   0,
		  0,   0,   0,  15,   0,   0,   0,  16,   0,   0,   0,  17,   0,   0,   0,   0,
		  0,   0,  15,   0,   0,   0,   0,  16,   0,   0,   0,   0,  17,   0,   0,   0,
		  0,  15,   0,   0,   0,   0,   0,  16,   0,   0,   0,   0,   0,  17,   0,   0,
		 15,   0,   0,   0,   0,   0,   0,  16,   0,   0,   0,   0,   0,   0,  17,   0
	];

	// Move types
	var /* const */ NORMAL_MOVE     = 0;
	var /* const */ EN_PASSANT_MOVE = 1;
	var /* const */ PROMOTION_MOVE  = 2;
	var /* const */ CASTLING_MOVE   = 3;


	/**
	 * Return the color of a square.
	 *
	 * @param {string} square
	 * @returns {string} Either `'w'` or `'b'`.
	 */
	myself.squareColor = function(square) {
		if(typeof square === 'string') {
			if     (/^[aceg][1357]$/.test(square) || /^[bdfh][2468]$/.test(square)) { return 'b'; }
			else if(/^[aceg][2468]$/.test(square) || /^[bdfh][1357]$/.test(square)) { return 'w'; }
		}
		throw new myself.exceptions.IllegalArgument('squareColor()');
	};



	// ---------------------------------------------------------------------------
	// String conversion
	// ---------------------------------------------------------------------------

	var /* const */ COLORED_PIECE_SYMBOL = 'KkQqRrBbNnPp';
	var /* const */ PIECE_SYMBOL         = 'kqrbnp';
	var /* const */ COLOR_SYMBOL         = 'wb';
	var /* const */ ROW_SYMBOL           = '12345678';
	var /* const */ COLUMN_SYMBOL        = 'abcdefgh';


	/**
	 * Parse a square.
	 *
	 * @param {string} square
	 * @returns {number} `-1` if the input is not valid.
	 */
	function parseSquare(square) {
		if(typeof square !== 'string' || !/^[a-h][1-8]$/.test(square)) {
			return -1;
		}
		var column = COLUMN_SYMBOL.indexOf(square[0]);
		var row    = ROW_SYMBOL   .indexOf(square[1]);
		return row*16 + column;
	}


	/**
	 * Parse a color.
	 *
	 * @param {string} color
	 * @returns {number} `-1` if the input is not valid.
	 */
	function parseColor(color) {
		return (typeof color === 'string') ? COLOR_SYMBOL.indexOf(color) : -1;
	}



	// ---------------------------------------------------------------------------
	// Constructor & string conversion methods
	// ---------------------------------------------------------------------------

	/**
	 * @constructor
	 * @alias Position
	 * @memberof Chess2
	 *
	 * @classdesc
	 * Represent a chess position, i.e. the state of a 64-square chessboard with a few additional
	 * information (who is about to play, castling rights, en-passant rights).
	 *
	 * @param {string} [fen = 'start'] Either `'start'`, `'empty'`, or a FEN string representing chess position.
	 * @throws InvalidFEN If the input parameter is neither a correctly formatted FEN string nor `'start'` or `'empty'`.
	 */
	myself.Position = function(fen) {
		if(typeof fen === 'undefined' || fen === null || fen === 'start') {
			this.reset();
		}
		else if(fen === 'empty') {
			this.clear();
		}
		else {
			setFEN(this, fen, false);
		}
	};


	/**
	 * Set the position to the empty state.
	 */
	myself.Position.prototype.clear = function()
	{
		// Board state
		this._board = [
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY
		];

		// Meta-data
		this._turn         = WHITE;
		this._castleRights = [0, 0];
		this._enPassant    = -1;

		// Computed attributes
		this._legal = false;
		this._king  = [-1, -1];
	};


	/**
	 * Set the position to the starting state.
	 */
	myself.Position.prototype.reset = function()
	{
		// Board state
		this._board = [
			WR   , WN   , WB   , WQ   , WK   , WB   , WN   , WR   , INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			WP   , WP   , WP   , WP   , WP   , WP   , WP   , WP   , INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			BP   , BP   , BP   , BP   , BP   , BP   , BP   , BP   , INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			BR   , BN   , BB   , BQ   , BK   , BB   , BN   , BR
		];

		// Meta-data
		this._turn         = WHITE;
		this._castleRights = [129 /* (1 << 'column a') | (1 << 'column h') */, 129];
		this._enPassant    = -1;

		// Computed attributes
		this._legal = true;
		this._king  = [25 /* e1 */, 95 /* e8 */];
	};


	/**
	 * Return a human-readable string representing the position. This string is multi-line,
	 * and is intended to be displayed in a fixed-width font (similarly to an ASCII-art picture).
	 *
	 * @returns {string} Human-readable representation of the position.
	 */
	myself.Position.prototype.ascii = function()
	{
		// Board scanning
		var res = '+---+---+---+---+---+---+---+---+\n';
		for(var r=7; r>=0; --r) {
			for(var c=0; c<8; ++c) {
				var cp = this._board[r*16 + c];
				res += '| ' + (cp < 0 ? ' ' : COLORED_PIECE_SYMBOL[cp]) + ' ';
			}
			res += '|\n';
			res += '+---+---+---+---+---+---+---+---+\n';
		}

		// Meta-data
		res += COLOR_SYMBOL[this._turn] + ' ';
		res += castleRightsToString(this._castleRights) + ' ';
		res += this._enPassant < 0 ? '-' : COLUMN_SYMBOL[this._enPassant];

		// Return the result
		return res;
	};


	/**
	 * `fen()` or `fen({fiftyMoveClock:number, fullMoveNumber:number})`: return the FEN representation of the position (getter behavior).
	 *
	 * `fen(string [, boolean])`: parse the given FEN string and set the position accordingly (setter behavior).
	 */
	myself.Position.prototype.fen = function()
	{
		if(arguments.length === 0) {
			return getFEN(this, 0, 1);
		}
		else if(arguments.length === 1 && typeof arguments[0] === 'object') {
			var fiftyMoveClock = (typeof arguments[0].fiftyMoveClock === 'number') ? arguments[0].fiftyMoveClock : 0;
			var fullMoveNumber = (typeof arguments[0].fullMoveNumber === 'number') ? arguments[0].fullMoveNumber : 1;
			return getFEN(this, fiftyMoveClock, fullMoveNumber);
		}
		else if(arguments.length === 1 && typeof arguments[0] === 'string') {
			return setFEN(this, arguments[0], false);
		}
		else if(arguments.length >= 2 && typeof arguments[0] === 'string' && typeof arguments[1] === 'boolean') {
			return setFEN(this, arguments[0], arguments[1]);
		}
		else {
			throw new myself.exceptions.IllegalArgument('Position#fen()');
		}
	};


	/**
	 * Return the FEN representation of the position.
	 *
	 * @returns {string}
	 */
	function getFEN(position, fiftyMoveClock, fullMoveNumber) {
		var res = '';

		// Board scanning
		for(var r=7; r>=0; --r) {
			if(r !== 7) {
				res += '/';
			}
			var emptySquareCounter = 0;
			for(var c=0; c<8; ++c) {
				var cp = position._board[r*16 + c];
				if(cp < 0) {
					++emptySquareCounter;
				}
				else {
					if(emptySquareCounter > 0) {
						res += emptySquareCounter;
						emptySquareCounter = 0;
					}
					res += COLORED_PIECE_SYMBOL[cp];
				}
			}
			if(emptySquareCounter > 0) {
				res += emptySquareCounter;
			}
		}

		// Meta-data
		res += ' ' + COLOR_SYMBOL[position._turn] + ' ' + castleRightsToString(position._castleRights) + ' ';
		if(position._enPassant < 0) {
			res += '-';
		}
		else {
			res += COLUMN_SYMBOL[position._enPassant] + (position._turn===WHITE ? '6' : '3');
		}

		// Additional move counting flags
		res += ' ' + fiftyMoveClock + ' ' + fullMoveNumber;

		// Return the result
		return res;
	}


	/**
	 * Try to parse a FEN string, and set the position accordingly if parsing is successful.
	 *
	 * @param {string} fen
	 * @param {boolean} strict
	 * @returns {{fiftyMoveClock:number, fullMoveNumber:number}}
	 * @throws InvalidFEN
	 */
	function setFEN(position, fen, strict) {

		// Trim the input string and split it into 6 fields.
		fen = fen.replace(/^\s+|\s+$/g, '');
		var fields = fen.split(/\s+/);
		if(fields.length !== 6) {
			throw new myself.exceptions.InvalidFEN(fen, myself.i18n.WRONG_NUMBER_OF_FEN_FIELDS);
		}

		// The first field (that represents the board) is split in 8 sub-fields.
		var rowFields = fields[0].split('/');
		if(rowFields.length !== 8) {
			throw new myself.exceptions.InvalidFEN(fen, myself.i18n.WRONG_NUMBER_OF_SUBFIELDS_IN_BOARD_FIELD);
		}

		// Initialize the position
		position.clear();
		position._legal = null;

		// Board parsing
		for(var r=7; r>=0; --r) {
			var rowField = rowFields[7-r];
			var i = 0;
			var c = 0;
			while(i<rowField.length && c<8) {
				var s = rowField[i];
				var cp = COLORED_PIECE_SYMBOL.indexOf(s);

				// The current character is in the range [1-8] -> skip the corresponding number of squares.
				if(/^[1-8]$/.test(s)) {
					c += parseInt(s, 10);
				}

				// The current character corresponds to a colored piece symbol -> set the current square accordingly.
				else if(cp >= 0) {
					position._board[r*16 + c] = cp;
					++c;
				}

				// Otherwise -> parsing error.
				else {
					throw new myself.exceptions.InvalidFEN(fen, myself.i18n.UNEXPECTED_CHARACTER_IN_BOARD_FIELD, s);
				}

				// Increment the character counter.
				++i;
			}

			// Ensure that the current sub-field deals with all the squares of the current row.
			if(i !== rowField.length || c !== 8) {
				throw new myself.exceptions.InvalidFEN(fen, myself.i18n.UNEXPECTED_END_OF_SUBFIELD_IN_BOARD_FIELD, myself.i18n.ORDINALS[7-r]);
			}
		}

		// Turn parsing
		position._turn = COLOR_SYMBOL.indexOf(fields[1]);
		if(position._turn < 0) {
			throw new myself.exceptions.InvalidFEN(fen, myself.i18n.INVALID_TURN_FIELD);
		}

		// Castle-rights parsing
		position._castleRights = castleRightsFromString(fields[2], strict);
		if(position._castleRights === null) {
			throw new myself.exceptions.InvalidFEN(fen, myself.i18n.INVALID_CASTLE_RIGHTS_FIELD);
		}

		// En-passant parsing
		var enPassantField = fields[3];
		if(enPassantField !== '-') {
			if(!/^[a-h][36]$/.test(enPassantField)) {
				throw new myself.exceptions.InvalidFEN(fen, myself.i18n.INVALID_EN_PASSANT_FIELD);
			}
			if(strict && ((enPassantField[1]==='3' && position._turn===WHITE) || (enPassantField[1]==='6' && position._turn===BLACK))) {
				throw new myself.exceptions.InvalidFEN(fen, myself.i18n.WRONG_ROW_IN_EN_PASSANT_FIELD);
			}
			position._enPassant = COLUMN_SYMBOL.indexOf(enPassantField[0]);
		}

		// Move counting flags parsing
		var moveCountingRegExp = strict ? /^(?:0|[1-9][0-9]*)$/ : /^[0-9]+$/;
		if(!moveCountingRegExp.test(fields[4])) {
			throw new myself.exceptions.InvalidFEN(fen, myself.i18n.INVALID_MOVE_COUNTING_FIELD, myself.i18n.ORDINALS[4]);
		}
		if(!moveCountingRegExp.test(fields[5])) {
			throw new myself.exceptions.InvalidFEN(fen, myself.i18n.INVALID_MOVE_COUNTING_FIELD, myself.i18n.ORDINALS[5]);
		}
		return { fiftyMoveClock: parseInt(fields[4], 10), fullMoveNumber: parseInt(fields[5], 10) };
	}


	/**
	 * Return a FEN-compatible representation of the castle rights. TODO: make it chess-960 compatible.
	 *
	 * @param {array} castleRights
	 * @returns {string}
	 */
	function castleRightsToString(castleRights) {
		var res = '';
		if(castleRights[WHITE] /* jshint bitwise:false */ & 1<<7 /* jshint bitwise:true */) { res += 'K'; }
		if(castleRights[WHITE] /* jshint bitwise:false */ & 1<<0 /* jshint bitwise:true */) { res += 'Q'; }
		if(castleRights[BLACK] /* jshint bitwise:false */ & 1<<7 /* jshint bitwise:true */) { res += 'k'; }
		if(castleRights[BLACK] /* jshint bitwise:false */ & 1<<0 /* jshint bitwise:true */) { res += 'q'; }
		return res === '' ? '-' : res;
	}


	/**
	 * Parsing function for the FEN-representation of the castle rights. TODO: make it chess-960 compatible.
	 *
	 * @param {string} castleRights
	 * @param {boolean} strict
	 * @return {array} `null` if the parsing fails.
	 */
	function castleRightsFromString(castleRights, strict) {
		var res = [0, 0];
		if(castleRights === '-') {
			return res;
		}
		if(!(strict ? /^K?Q?k?q?$/ : /^[KQkq]*$/).test(castleRights)) {
			return null;
		}
		if(castleRights.indexOf('K') >= 0) { res[WHITE] /* jshint bitwise:false */ |= 1<<7; /* jshint bitwise:true */ }
		if(castleRights.indexOf('Q') >= 0) { res[WHITE] /* jshint bitwise:false */ |= 1<<0; /* jshint bitwise:true */ }
		if(castleRights.indexOf('k') >= 0) { res[BLACK] /* jshint bitwise:false */ |= 1<<7; /* jshint bitwise:true */ }
		if(castleRights.indexOf('q') >= 0) { res[BLACK] /* jshint bitwise:false */ |= 1<<0; /* jshint bitwise:true */ }
		return res;
	}



	// ---------------------------------------------------------------------------
	// Getters/setters
	// ---------------------------------------------------------------------------

	/**
	 * Get/set the content of a square.
	 *
	 * @param {string} square `'e4'` for instance
	 * @param {string|{type:string, color:string}} [value]
	 */
	myself.Position.prototype.square = function(square, value) {
		square = parseSquare(square);
		if(square < 0) {
			throw new myself.exceptions.IllegalArgument('Position#square()');
		}
		if(typeof value === 'undefined' || value === null) {
			return getSquare(this, square);
		}
		else {
			if(!setSquare(this, square, value)) {
				throw new myself.exceptions.IllegalArgument('Position#square()');
			}
		}
	};


	/**
	 * Return the content of the given square.
	 *
	 * @param {number} square
	 * @returns {string|{piece:string, color:string}} `'-'` is returned if the square is empty.
	 */
	function getSquare(position, square) {
		var cp = position._board[square];
		return cp < 0 ? '-' : { piece: PIECE_SYMBOL[Math.floor(cp/2)], color: COLOR_SYMBOL[cp%2] };
	}


	/**
	 * Set the content of the given square.
	 *
	 * @param {number} square
	 * @param {string|{piece:string, color:string}} value
	 */
	function setSquare(position, square, value) {
		if(value === '-') {
			position._board[square] = EMPTY;
			position._legal = null;
			return true;
		}
		else if(typeof value === 'object' && typeof value.piece === 'string' && typeof value.color === 'string') {
			var piece = PIECE_SYMBOL.indexOf(value.piece);
			var color = COLOR_SYMBOL.indexOf(value.color);
			if(piece >= 0 && color >= 0) {
				position._board[square] = piece*2 + color;
				position._legal = null;
				return true;
			}
		}
		return false;
	}


	/**
	 * Get/set the turn flag.
	 *
	 * @param {string} [value]
	 */
	myself.Position.prototype.turn = function(value) {
		if(typeof value === 'undefined' || value === null) {
			return getTurn(this);
		}
		else {
			if(!setTurn(this, value)) {
				throw new myself.exceptions.IllegalArgument('Position#turn()');
			}
		}
	};


	/**
	 * Return the turn flag.
	 *
	 * @returns {string} `'w'` or `'b'`
	 */
	function getTurn(position) {
		return COLOR_SYMBOL[position._turn];
	}


	/**
	 * Set the turn flag.
	 *
	 * @param {string} value `'w'` or `'b'`
	 */
	function setTurn(position, value) {
		var turn = parseColor(value);
		if(turn >= 0) {
			position._turn = turn;
			position._legal = null;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * Get/set the castle rights. TODO: make it chess-960 compatible.
	 *
	 * @param {string} color
	 * @param {string} side
	 * @param {boolean} [value]
	 */
	myself.Position.prototype.castleRights = function(color, side, value) {
		color = parseColor(color);
		if(color < 0 || !(side==='k' || side==='q')) {
			throw new myself.exceptions.IllegalArgument('Position#castleRights()');
		}
		var column = side==='k' ? 7 : 0;
		if(typeof value === 'undefined' || value === null) {
			return getCastleRights(this, color, column);
		}
		else {
			if(!setCastleRights(this, color, column, value)) {
				throw new myself.exceptions.IllegalArgument('Position#castleRights()');
			}
		}
	};


	/**
	 * Return the castle rights for the given color and column.
	 *
	 * @param {number} color
	 * @param {number} column
	 * @returns {boolean}
	 */
	function getCastleRights(position, color, column) {
		return (position._castleRights[color] /* jshint bitwise:false */ & (1 << column) /* jshint bitwise:true */) !== 0;
	}


	/**
	 * Set the castle rights for the given color and column.
	 *
	 * @param {number} color
	 * @param {number} column
	 * @param {boolean} value
	 */
	function setCastleRights(position, color, column, value) {
		if(typeof value === 'boolean') {
			if(value) {
				position._castleRights[color] /* jshint bitwise:false */ |= 1 << column; /* jshint bitwise:true */
			}
			else {
				position._castleRights[color] /* jshint bitwise:false */ &= ~(1 << column); /* jshint bitwise:true */
			}
			position._legal = null;
			return true;
		}
		return false;
	}


	/**
	 * Get/set the en-passant flag.
	 *
	 * @param {string} [value]
	 */
	myself.Position.prototype.enPassant = function(value) {
		if(typeof value === 'undefined' || value === null) {
			return getEnPassant(this);
		}
		else {
			if(!setEnPassant(this, value)) {
				throw new myself.exceptions.IllegalArgument('Position#enPassant()');
			}
		}
	};


	/**
	 * Return the en-passant flag.
	 *
	 * @returns {string} `'-'`, `'a'`, `'b'`, ... or `'h'`
	 */
	function getEnPassant(position) {
		return position._enPassant < 0 ? '-' : COLUMN_SYMBOL[position._enPassant];
	}


	/**
	 * Set the en-passant flag.
	 *
	 * @param {string} value
	 */
	function setEnPassant(position, value) {
		if(value === '-') {
			position._enPassant = -1;
			position._legal = null;
			return true;
		}
		else if(typeof value === 'string') {
			var enPassant = COLUMN_SYMBOL.indexOf(value);
			if(enPassant >= 0) {
				position._enPassant = enPassant;
				position._legal = null;
				return true;
			}
		}
		return false;
	}



	// ---------------------------------------------------------------------------
	// Square control / position legality
	// ---------------------------------------------------------------------------

	/**
	 * Check if any piece of the given color attacks a given square.
	 *
	 * @param {string} square
	 * @param {string} byWho Either `'w'` or `'b'`
	 * @param {string} [byWhat] Filter for the type of attacker (for instance, `'kp'` to consider only king and pawn attacks).
	 *        If `null` or not defined, no filter is applied, and all types of pieces are considered.
	 * @returns {boolean}
	 */
	myself.Position.prototype.isAttacked = function(square, byWho, byWhat) {
		square = parseSquare(square);
		byWho  = parseColor (byWho );
		if(square < 0 || byWho < 0) {
			throw new myself.exceptions.IllegalArgument('Position#isAttacked()');
		}
		if(typeof byWhat === 'undefined' || byWhat === null) {
			return isAttacked(this, square, byWho);
		}
		else if(typeof byWhat === 'string') {
			for(var piece=0; piece<PIECE_SYMBOL.length; ++piece) {
				if(byWhat.indexOf(PIECE_SYMBOL[piece])>=0 && isAttackedBy(this, square, piece*2 + byWho)) {
					return true;
				}
			}
			return false;
		}
		else {
			throw new myself.exceptions.IllegalArgument('Position#isAttacked()');
		}
	};


	/**
	 * Check if a given type of piece attacks a given square.
	 *
	 * This method can be used even if the position is not legal.
	 *
	 * @param {number} square Square index.
	 * @param {number} attacker Colored piece constant.
	 * @returns {boolean}
	 */
	function isAttackedBy(position, square, attacker) {
		var directions = ATTACK_DIRECTIONS[attacker];
		if(isSliding(attacker)) {
			for(var i=0; i<directions.length; ++i) {
				var sq = square;
				while(true) {
					sq -= directions[i];
					if((sq /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0) {
						var cp = position._board[sq];
						if(cp === attacker) { return true; }
						else if(cp === EMPTY) { continue; }
					}
					break;
				}
			}
		}
		else {
			for(var i=0; i<directions.length; ++i) {
				var sq = square - directions[i];
				if((sq /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0 && position._board[sq]===attacker) {
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Check if any piece of the given color attacks a given square.
	 *
	 * This method can be used even if the position is not legal.
	 *
	 * @param {number} square Square index.
	 * @param {number} attackerColor Color constant.
	 * @returns {boolean}
	 */
	function isAttacked(position, square, attackerColor) {
		for(var piece=0; piece<6; ++piece) {
			if(isAttackedBy(position, square, piece*2 + attackerColor)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Check whether the current position is legal or not.
	 *
	 * A position is considered to be legal if all the following conditions are met:
	 *
	 *  1. There is exactly one white king and one black king on the board.
	 *  2. The player that is not about to play is not check.
	 *  3. There are no pawn on rows 1 and 8.
	 *  4. For each colored castle flag set, there is a rook and a king on the
	 *     corresponding initial squares.
	 *  5. The pawn situation is consistent with the en-passant flag if it is set.
	 *     For instance, if it is set to the 'e' column and black is about to play,
	 *     the squares e2 and e3 must be empty, and there must be a white pawn on e4.
	 *
	 * @returns {boolean}
	 */
	myself.Position.prototype.isLegal = function() {
		refreshLegalFlag(this);
		return this._legal;
	};


	/**
	 * Return the square on which is located the king of the given color.
	 *
	 * @param {string} color
	 * @returns {string} Square where is located the searched king. `'-'` is returned
	 *          if there is no king of the given color or if the are 2 such kings or more.
	 */
	myself.Position.prototype.kingSquare = function(color) {
		color = parseColor(color);
		if(color < 0) {
			throw new myself.exceptions.IllegalArgument('Position#kingSquare()');
		}
		refreshLegalFlag(this);
		var square = this._king[color];
		return square < 0 ? '-' : COLUMN_SYMBOL[square % 16] + ROW_SYMBOL[Math.floor(square / 16)];
	};


	/**
	 * Refresh the legal flag of the current position if it is set to null
	 * (which means that the legality state of the position is unknown).
	 *
	 * Together with the legal flag, the reference to the squares where the white and
	 * black kings lie is updated by this function.
	 *
	 * TODO: make it chess-960 compatible.
	 *
	 * @param {Position} position
	 */
	function refreshLegalFlag(position) {
		if(position._legal !== null) {
			return;
		}
		position._legal = false;

		// Condition (1)
		refreshKingSquare(position, WHITE);
		refreshKingSquare(position, BLACK);
		if(position._king[WHITE] < 0 || position._king[BLACK] < 0) {
			return;
		}

		// Condition (2)
		if(isAttacked(position, position._king[1-position._turn], position._turn)) {
			return;
		}

		// Condition (3)
		for(var c=0; c<8; ++c) {
			var cp1 = position._board[c];
			var cp8 = position._board[112 + c];
			if(cp1 === WP || cp8 === WP || cp1 === BP || cp8 === BP) {
				return;
			}
		}

		// Condition (4)
		for(var color=0; color<2; ++color) {
			var skipOO  = !getCastleRights(position, color, 7);
			var skipOOO = !getCastleRights(position, color, 0);
			var rookHOK = skipOO              || position._board[7 + 112*color] === ROOK*2 + color;
			var rookAOK = skipOOO             || position._board[0 + 112*color] === ROOK*2 + color;
			var kingOK  = (skipOO && skipOOO) || position._board[4 + 112*color] === KING*2 + color;
			if(!(kingOK && rookAOK && rookHOK)) {
				return;
			}
		}

		// Condition (5)
		if(position._enPassant >= 0) {
			var square2 = (6-position._turn*5)*16 + position._enPassant;
			var square3 = (5-position._turn*3)*16 + position._enPassant;
			var square4 = (4-position._turn  )*16 + position._enPassant;
			if(!(position._board[square2]===EMPTY && position._board[square3]===EMPTY && position._board[square4]===PAWN*2+1-position._turn)) {
				return;
			}
		}

		// At this point, all the conditions (1) to (5) hold, so the position can be flagged as legal.
		position._legal = true;
	}


	/**
	 * Detect the kings of the given color that are present on the chess board.
	 *
	 * @param {Position} position
	 * @param {number} color
	 */
	function refreshKingSquare(position, color) {
		var target = KING*2 + color;
		position._king[color] = -1;
		for(var sq=0; sq<120; sq += (sq /* jshint bitwise:false */ & 0x7 /* jshint bitwise:true */)===7 ? 9 : 1) {
			if(position._board[sq] === target) {

				// If the targeted king is detected on the square sq, two situations may occur:
				// 1) No king was detected on the previously visited squares: then the current
				//    square is saved, and loop over the next board squares goes on.
				if(position._king[color] < 0) {
					position._king[color] = sq;
				}

				// 2) Another king was detected on the previously visited squares: then the buffer position._king[color]
				//    is set to the invalid state (-1), and the loop is interrupted.
				else {
					position._king[color] = -1;
					return;
				}
			}
		}
	}



	// ---------------------------------------------------------------------------
	// Move generation
	// ---------------------------------------------------------------------------

	/**
	 * Whether a move is legal or not.
	 *
	 * @param {string} move
	 * @returns {boolean}
	 */
	myself.Position.prototype.isLegalMove = function(move) {
		if(typeof move !== 'string') {
			throw new myself.exceptions.IllegalArgument('Position#isLegalMove()');
		}

		// Parsing 'g1f3'-style
		if(/^[a-h][1-8][a-h][1-8][QRBN]?$/.test(move)) {
			var columnFrom = COLUMN_SYMBOL.indexOf(move[0]);
			var rowFrom    = ROW_SYMBOL   .indexOf(move[1]);
			var columnTo   = COLUMN_SYMBOL.indexOf(move[2]);
			var rowTo      = ROW_SYMBOL   .indexOf(move[3]);

			var moveDescriptor = isLegalDisplacement(this, rowFrom*16+columnFrom, rowTo*16+columnTo);
			if(moveDescriptor) {
				var hasPromotion = move.length === 5;
				var needPromotion = moveDescriptor.type === PROMOTION_MOVE;
				return hasPromotion === needPromotion;
			}
			else {
				return false;
			}
		}

		// Unknown move format
		else {
			throw new myself.exceptions.IllegalArgument('Position#isLegalMove()');
		}
	};


	/**
	 * Core algorithm to determine whether a move is legal or not. The verification flow is the following:
	 *
	 *  1. Ensure that the position itself is legal.
	 *  2. Ensure that the origin square contains a piece (denoted as the moving-piece)
	 *     whose color is the same than the color of the player about to play.
	 *  3. Ensure that the displacement is geometrically correct, with respect to the moving piece.
	 *  4. Check the content of the destination square.
	 *  5. For the sliding pieces (and in case of a 2-square pawn move), ensure that there is no piece
	 *     on the trajectory.
	 *
	 * The displacement is almost ensured to be legal at this point. The last condition to check
	 * is whether the king of the current player will be in check after the move or not.
	 *
	 *  6. Execute the displacement from the origin to the destination square, in such a way that
	 *     it can be reversed. Only the state of the board is updated at this point.
	 *  7. Look for king attacks.
	 *  8. Reverse the displacement.
	 *
	 * Castling moves fail at step (3). They are taken out of this flow and processed
	 * by the dedicated method `isLegalCastling()`.
	 *
	 * @param {Position} position
	 * @param {number} from Index of the origin square.
	 * @param {number} to Index of the destination square.
	 * @returns {boolean|object}
	 */
	function isLegalDisplacement(position, from, to) {

		// Step (1)
		if(!position.isLegal()) { return false; }

		// Step (2)
		var fromContent = position._board[from];
		var toContent   = position._board[to  ];
		if(fromContent < 0 || fromContent%2 !== position._turn) { return false; }

		// Miscellaneous variables
		var movingPiece = Math.floor(fromContent / 2);
		var displacement = to - from + 119;
		var moveType = NORMAL_MOVE;
		var enPassantSquare = -1; // square where a pawn is taken if the move is "en-passant"
		var updateEnPassant = -1; // new value for the "en-passant" flag if the move is legal

		// Step (3)
		if((DISPLACEMENT_LOOKUP[displacement] /* jshint bitwise:false */ & 1<<fromContent /* jshint bitwise:true */) === 0) {
			if(movingPiece === PAWN && displacement === 151-position._turn*64) {
				var firstSquareOfRow = (1 + position._turn*5) * 16;
				if(from < firstSquareOfRow || from >= firstSquareOfRow+8) { return false; }
				updateEnPassant = from % 8;
			}
			else if(movingPiece === KING && (displacement === 117 || displacement === 121)) {
				return isLegalCastling(position, from, to);
			}
			else {
				return false;
			}
		}

		// Step (4) -> check the content of the destination square
		if(movingPiece === PAWN) {
			if(displacement === 135-position._turn*32 || updateEnPassant >= 0) { // non-capturing pawn move
				if(toContent !== EMPTY) { return false; }
			}
			else if(toContent === EMPTY) { // en-passant pawn move
				if(position._enPassant < 0 || to !== (5-position._turn*3)*16 + position._enPassant) { return false; }
				moveType = EN_PASSANT_MOVE;
				enPassantSquare = (4-position._turn)*16 + position._enPassant;
			}
			else { // regular capturing pawn move
				if(toContent%2 === position._turn) { return false; }
			}
		}
		else { // piece move
			if(toContent >= 0 && toContent%2 === position._turn) { return false; }
		}

		// Step (5) -> For sliding pieces, ensure that there is nothing between the origin and the destination squares.
		if(isSliding(fromContent)) {
			var direction = SLIDING_DIRECTION[displacement];
			for(var sq=from + direction; sq !== to; sq += direction) {
				if(position._board[sq] !== EMPTY) { return false; }
			}
		}
		else if(updateEnPassant >= 0) { // 2-square pawn moves also require this test.
			if(position._board[(from + to) / 2] !== EMPTY) { return false; }
		}

		// Step (6) -> Execute the displacement (castling moves are processed separately).
		position._board[to  ] = fromContent;
		position._board[from] = EMPTY;
		if(moveType === EN_PASSANT_MOVE) {
			position._board[enPassantSquare] = EMPTY;
		}

		// Step (7) -> Is the king safe after the displacement?
		var kingSquare    = movingPiece===KING ? to : position._king[position._turn];
		var kingIsInCheck = isAttacked(position, kingSquare, 1-position._turn);

		// Step (8) -> Reverse the displacement.
		position._board[from] = fromContent;
		position._board[to  ] = toContent;
		if(moveType === EN_PASSANT_MOVE) {
			position._board[enPassantSquare] = PAWN*2 + 1-position._turn;
		}

		// Final result
		if(kingIsInCheck) {
			return false;
		}
		else {
			var updateCastleRights = [0xff, 0xff];
			if(movingPiece === KING) { updateCastleRights[position._turn] = 0; }
			if(from <    8) { updateCastleRights[WHITE] /* jshint bitwise:false */ &= ~(1 <<  from    ); /* jshint bitwise:true */ }
			if(to   <    8) { updateCastleRights[WHITE] /* jshint bitwise:false */ &= ~(1 <<  to      ); /* jshint bitwise:true */ }
			if(from >= 112) { updateCastleRights[BLACK] /* jshint bitwise:false */ &= ~(1 << (from%16)); /* jshint bitwise:true */ }
			if(to   >= 112) { updateCastleRights[BLACK] /* jshint bitwise:false */ &= ~(1 << (to  %16)); /* jshint bitwise:true */ }
			return {
				type: (movingPiece === PAWN) && (to<8 || to>=112) ? PROMOTION_MOVE : moveType,
				movingPiece: movingPiece,
				enPassantSquare: enPassantSquare,
				updateEnPassant: updateEnPassant,
				updateCastleRights: updateCastleRights
			};
		}
	}


	/**
	 * Delegated method for checking whether a castling move is legal or not.
	 *
	 * TODO: make it chess-960 compatible.
	 *
	 * @param {Position} position
	 * @param {number} from
	 * @param {number} to
	 * @returns {boolean|object}
	 */
	function isLegalCastling(position, from, to) {

		// Ensure that the given underlying castling is allowed.
		var column = from < to ? 7 : 0;
		if((position._castleRights[position._turn] /* jshint bitwise:false */ & 1<<column /* jshint bitwise:true */) === 0) {
			return false;
		}

		// Origin and destination squares of the rook involved in the move.
		var rookFrom = column + position._turn*112;
		var rookTo   = (from + to) / 2;

		// Ensure that each square between the king and the rook is empty.
		var offset = from < rookFrom ? 1 : -1;
		for(var sq=from+offset; sq!==rookFrom; sq+=offset) {
			if(position._board[sq] !== EMPTY) { return false; }
		}

		// The origin and destination squares of the king, and the square between them must not be attacked.
		var byWho = 1-position._turn;
		if(isAttacked(position, from, byWho) || isAttacked(position, to, byWho) || isAttacked(position, rookTo, byWho)) {
			return false;
		}

		// Final result
		var updateCastleRights = [0xff, 0xff];
		updateCastleRights[position._turn] = 0;
		return {
			type: CASTLING_MOVE,
			movingPiece: KING,
			rookFrom: rookFrom,
			rookTo: rookTo,
			updateEnPassant: -1,
			updateCastleRights: updateCastleRights
		};
	}



})(Chess2);
